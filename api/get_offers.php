<?php
// /api/get_offers.php

header('Content-Type: application/json');
require_once '../includes/db_connect.php';

try {
    // --- Query para buscar ofertas ativas e com quantidade > 0 ---
    // Juntamos com 'businesses' para pegar o nome do estabelecimento
    $sql = "SELECT
                o.offer_id,
                o.title,
                o.description,
                o.price,
                o.quantity_available,
                o.pickup_start_time,
                o.pickup_end_time,
                b.business_name,
                b.address,
                b.city
            FROM offers AS o
            JOIN businesses AS b ON o.business_id = b.business_id
            WHERE o.status = 'active'
              AND o.quantity_available > 0
              AND o.pickup_end_time > NOW() -- Opcional: Não mostra ofertas que já expiraram
            ORDER BY o.created_at DESC"; // Mais recentes primeiro

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $offers = $stmt->fetchAll();

    // --- Formata as datas/horas para exibição (Opcional, mas útil) ---
    foreach ($offers as &$offer) {
        $start = new DateTime($offer['pickup_start_time']);
        $end = new DateTime($offer['pickup_end_time']);
        $offer['pickup_window'] = $start->format('H:i') . ' - ' . $end->format('H:i \d\e ') . $end->format('d/m');
    }

    echo json_encode(['status' => 'success', 'data' => $offers]);
    http_response_code(200);

} catch (\PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao buscar ofertas: ' . $e->getMessage()]);
    http_response_code(500);
}
?>