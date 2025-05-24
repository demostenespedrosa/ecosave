<?php
// /api/get_offer_details.php

header('Content-Type: application/json');
require_once '../includes/db_connect.php';

$response = ['status' => 'error', 'message' => 'Oferta não encontrada.'];
http_response_code(404);

$offer_id = $_GET['id'] ?? null;

if (!empty($offer_id)) {
    try {
        $sql = "SELECT
                    o.*, -- Pega todos os campos da oferta
                    DATE_FORMAT(o.pickup_start_time, '%d/%m %H:%i') as pickup_start_formatted,
                    DATE_FORMAT(o.pickup_end_time, '%H:%i') as pickup_end_formatted,
                    b.business_name, b.address, b.city, b.phone
                FROM offers AS o
                JOIN businesses AS b ON o.business_id = b.business_id
                WHERE o.offer_id = ?"; // Não precisa checar status aqui, pode querer ver detalhes

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$offer_id]);
        $offer = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($offer) {
            $offer['pickup_window'] = $offer['pickup_start_formatted'] . ' - ' . $offer['pickup_end_formatted'];
            $offer['image_url'] = $offer['image_path'] ? $offer['image_path'] : 'https://via.placeholder.com/400x200.png/FFC107/333333?Text=EcoSave';
            $response = ['status' => 'success', 'data' => $offer];
            http_response_code(200);
        } else {
             $response['message'] = 'Oferta não encontrada.';
        }

    } catch (\PDOException $e) { /* ... */ }
} else { /* ... */ }

echo json_encode($response);
?>