<?php
// /api/get_my_orders.php

session_start();
header('Content-Type: application/json');
require_once '../includes/db_connect.php';

$response = ['status' => 'error', 'message' => 'Não foi possível buscar os pedidos.'];
http_response_code(400);

// --- Verifica se o usuário está logado ---
if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'Você precisa estar logado para ver seus pedidos.';
    http_response_code(401);
    echo json_encode($response);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $sql = "SELECT
                ord.order_id, ord.reservation_code, ord.status AS order_status,
                ord.created_at AS order_date,
                off.title, off.price, off.pickup_start_time, off.pickup_end_time,
                b.business_name, b.address
            FROM orders AS ord
            JOIN offers AS off ON ord.offer_id = off.offer_id
            JOIN businesses AS b ON off.business_id = b.business_id
            WHERE ord.user_id = ?
            ORDER BY ord.created_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    $orders = $stmt->fetchAll();

    // Formata datas para cada pedido
    foreach ($orders as &$order) {
        $start = new DateTime($order['pickup_start_time']);
        $end = new DateTime($order['pickup_end_time']);
        $order['pickup_window'] = $start->format('d/m H:i') . ' - ' . $end->format('H:i');
        $order_date = new DateTime($order['order_date']);
        $order['order_date_formatted'] = $order_date->format('d/m/Y H:i');
    }


    $response = ['status' => 'success', 'data' => $orders];
    http_response_code(200);

} catch (\PDOException $e) {
    $response['message'] = 'Erro no banco de dados: ' . $e->getMessage();
    http_response_code(500);
}

echo json_encode($response);
?>