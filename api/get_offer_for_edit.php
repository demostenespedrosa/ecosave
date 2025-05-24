<?php
// /api/get_offer_for_edit.php

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
$response = ['status' => 'error', 'message' => 'Oferta não encontrada.'];
http_response_code(404);

$offer_id = $_GET['id'] ?? null;

if ($offer_id) {
    try {
        // Busca a oferta E garante que ela pertence ao business logado
        $sql = "SELECT offer_id, title, description, price, quantity_available, 
                       DATE_FORMAT(pickup_start_time, '%Y-%m-%dT%H:%i') as pickup_start_time, 
                       DATE_FORMAT(pickup_end_time, '%Y-%m-%dT%H:%i') as pickup_end_time, 
                       status
                FROM offers 
                WHERE offer_id = ? AND business_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$offer_id, $business_id]);
        $offer = $stmt->fetch();

        if ($offer) {
            $response = ['status' => 'success', 'data' => $offer];
            http_response_code(200);
        } else {
            $response['message'] = 'Oferta não encontrada ou não pertence a você.';
        }
    } catch (\PDOException $e) {
        $response['message'] = 'Erro no banco: ' . $e->getMessage();
        http_response_code(500);
    }
} else {
    $response['message'] = 'ID da oferta não fornecido.';
    http_response_code(400);
}

echo json_encode($response);
?>