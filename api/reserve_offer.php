<?php
// /api/reserve_offer.php

session_start();
header('Content-Type: application/json');
require_once '../includes/db_connect.php';

$response = ['status' => 'error', 'message' => 'Não foi possível fazer a reserva.'];
http_response_code(400); // Bad Request (padrão)

// --- 1. Verifica se o usuário está logado ---
if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'Você precisa estar logado para fazer uma reserva.';
    http_response_code(401); // Unauthorized
    echo json_encode($response);
    exit;
}

// --- 2. Verifica se a oferta foi enviada via POST ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $offer_id = $_POST['offer_id'] ?? null;
    $user_id = $_SESSION['user_id'];
    $quantity_to_reserve = 1; // Simplificando para 1 por reserva

    if (empty($offer_id)) {
        $response['message'] = 'ID da oferta não fornecido.';
        echo json_encode($response);
        exit;
    }

    // --- 3. Inicia a Transação ---
    try {
        $pdo->beginTransaction();

        // --- 4. Busca a oferta e bloqueia a linha (FOR UPDATE) ---
        // Isso previne que duas pessoas reservem a última unidade ao mesmo tempo.
        $stmt_check = $pdo->prepare("SELECT quantity_available, status FROM offers WHERE offer_id = ? FOR UPDATE");
        $stmt_check->execute([$offer_id]);
        $offer = $stmt_check->fetch();

        // --- 5. Verifica a disponibilidade ---
        if (!$offer || $offer['status'] !== 'active' || $offer['quantity_available'] < $quantity_to_reserve) {
            $pdo->rollBack(); // Desfaz a transação
            $response['message'] = 'Oferta indisponível ou não encontrada.';
            http_response_code(409); // Conflict
        } else {
            // --- 6. Diminui a quantidade ---
            $new_quantity = $offer['quantity_available'] - $quantity_to_reserve;
            $new_status = ($new_quantity == 0) ? 'sold_out' : 'active'; // Atualiza status se esgotar

            $stmt_update = $pdo->prepare("UPDATE offers SET quantity_available = ?, status = ? WHERE offer_id = ?");
            $stmt_update->execute([$new_quantity, $new_status, $offer_id]);

            // --- 7. Gera um código de reserva simples ---
            $reservation_code = strtoupper(substr(uniqid(), -6));

            // --- 8. Cria o pedido (Order) ---
            $stmt_insert = $pdo->prepare("INSERT INTO orders (user_id, offer_id, quantity_reserved, reservation_code, status) VALUES (?, ?, ?, ?, 'reserved')");
            $stmt_insert->execute([$user_id, $offer_id, $quantity_to_reserve, $reservation_code]);

            // --- 9. Confirma a Transação ---
            $pdo->commit();
            $response = [
                'status' => 'success',
                'message' => 'Reserva realizada com sucesso!',
                'reservation_code' => $reservation_code
            ];
            http_response_code(201); // Created
        }

    } catch (\PDOException $e) {
        $pdo->rollBack(); // Desfaz a transação em caso de erro
        $response['message'] = 'Erro no processamento da reserva: ' . $e->getMessage();
        http_response_code(500);
    }
} else {
    $response['message'] = 'Método de requisição inválido. Use POST.';
    http_response_code(405);
}

echo json_encode($response);
?>