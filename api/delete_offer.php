<?php
// /api/delete_offer.php

session_start();
header('Content-Type: application/json');
require_once '../includes/db_connect.php';

function check_business_auth($pdo) { /* ... mesma função de antes ... */ 
    if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'business' || !isset($_SESSION['business_id'])) {
        http_response_code(403); echo json_encode(['status' => 'error', 'message' => 'Acesso negado.']); exit;
    }
    return $_SESSION['business_id'];
}
$business_id = check_business_auth($pdo);
$response = ['status' => 'error', 'message' => 'Erro ao desativar oferta.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $offer_id = $_POST['offer_id'] ?? null;

    if (empty($offer_id)) {
        $response['message'] = 'ID da oferta não fornecido.';
        http_response_code(400);
    } else {
        try {
            // Apenas muda o status para 'inactive' E verifica se pertence ao business
            $sql = "UPDATE offers SET status = 'inactive' 
                    WHERE offer_id = ? AND business_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$offer_id, $business_id]);

            if ($stmt->rowCount() > 0) {
                $response = ['status' => 'success', 'message' => 'Oferta desativada com sucesso!'];
                http_response_code(200);
            } else {
                $response['message'] = 'Oferta não encontrada ou já estava inativa.';
                http_response_code(404);
            }
        } catch (\PDOException $e) {
            $response['message'] = 'Erro no banco: ' . $e->getMessage();
            http_response_code(500);
        }
    }
} else {
    $response['message'] = 'Use POST.';
    http_response_code(405);
}

echo json_encode($response);
?>