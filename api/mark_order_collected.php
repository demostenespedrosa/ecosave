<?php
// /api/mark_order_collected.php

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
$response = ['status' => 'error', 'message' => 'Erro ao atualizar pedido.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'] ?? null;

    if (empty($order_id)) {
        $response['message'] = 'ID do pedido não fornecido.';
    } else {
        try {
            // --- Verifica se o pedido pertence a este estabelecimento antes de atualizar ---
            $sql_check = "SELECT o.order_id FROM orders o JOIN offers f ON o.offer_id = f.offer_id WHERE o.order_id = ? AND f.business_id = ?";
            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->execute([$order_id, $business_id]);

            if ($stmt_check->fetch()) {
                // Pertence, então pode atualizar
                $sql_update = "UPDATE orders SET status = 'collected' WHERE order_id = ? AND status = 'reserved'";
                $stmt_update = $pdo->prepare($sql_update);
                $count = $stmt_update->execute([$order_id]);

                if ($stmt_update->rowCount() > 0) {
                    $response = ['status' => 'success', 'message' => 'Pedido marcado como coletado!'];
                    http_response_code(200);
                } else {
                     $response['message'] = 'Pedido não encontrado ou já processado.';
                     http_response_code(404);
                }
            } else {
                $response['message'] = 'Este pedido não pertence ao seu estabelecimento.';
                http_response_code(403);
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