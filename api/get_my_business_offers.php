<?php
// /api/get_my_business_offers.php

session_start();
header('Content-Type: application/json');
require_once '../includes/db_connect.php';

// --- Função auxiliar para checar se é business ---
function check_business_auth($pdo) {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'business' || !isset($_SESSION['business_id'])) {
        http_response_code(403); // Forbidden
        echo json_encode(['status' => 'error', 'message' => 'Acesso negado. Apenas para estabelecimentos.']);
        exit;
    }
    return $_SESSION['business_id'];
}

$business_id = check_business_auth($pdo); // Verifica e pega o ID

try {
    $sql = "SELECT offer_id, title, price, quantity_available, pickup_start_time, pickup_end_time, status
            FROM offers
            WHERE business_id = ?
            ORDER BY created_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$business_id]);
    $offers = $stmt->fetchAll();

    echo json_encode(['status' => 'success', 'data' => $offers]);
    http_response_code(200);

} catch (\PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao buscar suas ofertas: ' . $e->getMessage()]);
    http_response_code(500);
}
?>