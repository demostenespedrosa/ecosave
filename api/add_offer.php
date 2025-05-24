<?php
// /api/add_offer.php

session_start();
header('Content-Type: application/json');
require_once '../includes/db_connect.php';

// --- Funções Auxiliares ---
function check_business_auth($pdo) {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'business' || !isset($_SESSION['business_id'])) {
        http_response_code(403); echo json_encode(['status' => 'error', 'message' => 'Acesso negado.']); exit;
    }
    return $_SESSION['business_id'];
}
function handle_offer_image_upload($file_info, $old_image_path = null) { /* Cole o código da função aqui */ 
    if (!isset($file_info) || $file_info['error'] !== UPLOAD_ERR_OK) { return null; }
    $upload_dir = '../uploads/'; $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp']; $max_size = 5 * 1024 * 1024;
    if (!in_array($file_info['type'], $allowed_types)) { return 'error_type'; }
    if ($file_info['size'] > $max_size) { return 'error_size'; }
    $extension = pathinfo($file_info['name'], PATHINFO_EXTENSION); $safe_name = "offer_" . time() . '_' . bin2hex(random_bytes(8)) . '.' . strtolower($extension); $destination = $upload_dir . $safe_name;
    if (move_uploaded_file($file_info['tmp_name'], $destination)) { if ($old_image_path && file_exists('../' . $old_image_path)) { unlink('../' . $old_image_path); } return 'uploads/' . $safe_name; } else { return 'error_move'; }
}

$business_id = check_business_auth($pdo);
$response = ['status' => 'error', 'message' => 'Erro ao adicionar oferta.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? null;
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? null;
    $quantity = $_POST['quantity'] ?? null;
    $pickup_start = $_POST['pickup_start'] ?? null;
    $pickup_end = $_POST['pickup_end'] ?? null;
    $category_id = $_POST['category_id'] ?? null;
    $offer_image_info = $_FILES['offer_image'] ?? null;

    if (empty($title) || $price === null || $quantity === null || empty($pickup_start) || empty($pickup_end) || empty($category_id)) {
        $response['message'] = 'Todos os campos, exceto imagem e descrição, são obrigatórios.';
        http_response_code(400);
    } else {
        $pdo->beginTransaction();
        try {
            $image_path = handle_offer_image_upload($offer_image_info); // Tenta upload
            
            // Verifica se o upload deu erro (e não foi apenas 'sem arquivo')
            if (strpos($image_path, 'error_') === 0) {
                 throw new Exception("Erro no upload da imagem: " . $image_path);
            }

            $sql = "INSERT INTO offers (business_id, title, description, image_path, price, quantity_available, 
                                      pickup_start_time, pickup_end_time, status, category_id)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'active', ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $business_id, $title, $description, $image_path, $price, $quantity, 
                $pickup_start, $pickup_end, $category_id
            ]);
            
            $pdo->commit();
            $response = ['status' => 'success', 'message' => 'Oferta adicionada com sucesso!'];
            http_response_code(201);

        } catch (\Exception $e) {
            $pdo->rollBack();
            $response['message'] = 'Erro no processamento: ' . $e->getMessage();
            http_response_code(500);
        }
    }
} else {
    $response['message'] = 'Use POST.';
    http_response_code(405);
}

echo json_encode($response);
?>