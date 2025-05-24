<?php
// /api/get_reservations.php

session_start();
header('Content-Type: application/json');
require_once '../includes/db_connect.php';

function check_business_auth($pdo) { /* ... mesma função de antes ... */
    if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'business' || !isset($_SESSION['business_id'])) {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'Acesso negado.']);
        exit;
    }
    return $_SESSION['business_id'];
}
$business_id = check_business_auth($pdo);

try {
    $sql = "SELECT
                ord.order_id, ord.reservation_code, ord.status AS order_status,
                ord.created_at, u.name AS user_name, off.title AS offer_title
            FROM orders AS ord
            JOIN offers AS off ON ord.offer_id = off.offer_id
            JOIN users AS u ON ord.user_id = u.user_id
            WHERE off.business_id = ?
            ORDER BY ord.created_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$business_id]);
    $reservations = $stmt->fetchAll();

    echo json_encode(['status' => 'success', 'data' => $reservations]);
    http_response_code(200);

} catch (\PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao buscar reservas: ' . $e->getMessage()]);
    http_response_code(500);
}
?>