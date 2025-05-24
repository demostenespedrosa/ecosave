<?php
// /api/get_categories.php

header('Content-Type: application/json');
require_once '../includes/db_connect.php'; // Conecta ao BD

try {
    $stmt = $pdo->query("SELECT category_id, name FROM categories ORDER BY name");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC); // Usar FETCH_ASSOC é bom
    echo json_encode(['status' => 'success', 'data' => $categories]);

} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Erro ao buscar categorias: ' . $e->getMessage()]);
}
?>