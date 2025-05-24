<?php
// /api/update_offer.php

session_start();
header('Content-Type: application/json');
require_once '../includes/db_connect.php';

// --- Funções Auxiliares ---
function check_business_auth($pdo) { /* ... código ... */ }
function handle_offer_image_upload($file_info, $old_image_path = null) { /* ... código ... */ }

$business_id = check_business_auth($pdo);
$response = ['status' => 'error', 'message' => 'Erro ao atualizar oferta.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $offer_id = $_POST['offer_id'] ?? null;
    // ... (pegar todos os outros campos: title, price, etc.) ...
    $category_id = $_POST['category_id'] ?? null;
    $offer_image_info = $_FILES['offer_image'] ?? null;

    if (empty($offer_id) || /* ... outras validações ... */) {
        $response['message'] = 'Dados incompletos ou inválidos.';
        http_response_code(400);
    } else {
        $pdo->beginTransaction();
        try {
            // Pega o caminho da imagem antiga para poder deletar
            $stmt_old = $pdo->prepare("SELECT image_path FROM offers WHERE offer_id = ? AND business_id = ?");
            $stmt_old->execute([$offer_id, $business_id]);
            $old_offer = $stmt_old->fetch();

            if (!$old_offer) {
                throw new Exception("Oferta não encontrada ou não pertence a você.");
            }
            $old_image_path = $old_offer['image_path'];

            // Tenta o upload da nova imagem (passando a antiga para ser deletada se houver nova)
            $new_image_path = handle_offer_image_upload($offer_image_info, $old_image_path);

            if (strpos($new_image_path, 'error_') === 0) {
                 throw new Exception("Erro no upload da imagem: " . $new_image_path);
            }

            // Define qual caminho usar: o novo (se houver) ou o antigo
            $final_image_path = ($new_image_path !== null) ? $new_image_path : $old_image_path;

            $sql = "UPDATE offers SET 
                        title = ?, description = ?, price = ?, quantity_available = ?, 
                        pickup_start_time = ?, pickup_end_time = ?, status = ?,
                        category_id = ?, image_path = ? 
                    WHERE offer_id = ? AND business_id = ?";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $title, $description, $price, $quantity, 
                $pickup_start, $pickup_end, $status, 
                $category_id, $final_image_path, 
                $offer_id, $business_id
            ]);

            $pdo->commit();
            $response = ['status' => 'success', 'message' => 'Oferta atualizada com sucesso!'];
            http_response_code(200);

        } catch (\Exception $e) {
            $pdo->rollBack();
            $response['message'] = 'Erro no processamento: ' . $e->getMessage();
            http_response_code(500);
        }
    }
} else { /* ... */ }

echo json_encode($response);
?>