<?php
// /api/get_categorized_offers.php
// Gerado em: 24/05/2025, 16:15:00 (Horário de Brasília)
// Localização: Cabo de Santo Agostinho, Pernambuco, Brasil

// ATIVE ESTAS LINHAS PARA DEPURAR ERROS PHP DIRETAMENTE NA SAÍDA DA API
error_reporting(E_ALL);
ini_set('display_errors', 1);
// LEMBRE-SE DE REMOVER OU COMENTAR ESTAS LINHAS EM PRODUÇÃO

header('Content-Type: application/json');
require_once '../includes/db_connect.php';

try {
    $stmt_cat = $pdo->query("SELECT category_id, name FROM categories ORDER BY name");
    $categories = $stmt_cat->fetchAll(PDO::FETCH_ASSOC);

    if ($categories === false) {
        // Isso não deveria acontecer se a tabela existir e a conexão estiver OK
        throw new Exception("Falha ao buscar categorias do banco de dados.");
    }

    $categorized_data = [];

    foreach ($categories as $category) {
        $sql = "SELECT
                    o.offer_id, o.title, o.price,
                    o.quantity_available, 
                    o.pickup_start_time,
                    o.pickup_end_time,
                    o.image_path,
                    b.business_name, b.city,
                    b.avg_rating,
                    b.rating_count
                FROM offers AS o
                JOIN businesses AS b ON o.business_id = b.business_id
                WHERE o.status = 'active' 
                  AND o.quantity_available > 0 
                  AND o.pickup_end_time > NOW() 
                  AND o.category_id = :category_id -- Usar placeholder nomeado
                ORDER BY o.created_at DESC 
                LIMIT 10";

        $stmt_offers = $pdo->prepare($sql);
        $stmt_offers->bindParam(':category_id', $category['category_id'], PDO::PARAM_INT);
        $stmt_offers->execute();
        $offers_for_category = $stmt_offers->fetchAll(PDO::FETCH_ASSOC);

        if ($offers_for_category === false) {
            error_log("Falha ao buscar ofertas para a categoria ID: " . $category['category_id'] . " - Erro PDO: " . implode(":", $stmt_offers->errorInfo()));
            continue; 
        }

        if (count($offers_for_category) > 0) {
            foreach ($offers_for_category as &$offer) { // Passa por referência para modificar
                if (!empty($offer['pickup_start_time']) && !empty($offer['pickup_end_time'])) {
                    try {
                        $start = new DateTime($offer['pickup_start_time']);
                        $end = new DateTime($offer['pickup_end_time']);
                        $offer['pickup_window'] = $start->format('H:i') . ' - ' . $end->format('H:i');
                    } catch (Exception $e) {
                        $offer['pickup_window'] = 'Data Inválida';
                         error_log("Erro ao formatar data para oferta ID " . $offer['offer_id'] . ": " . $e->getMessage());
                    }
                } else {
                    $offer['pickup_window'] = 'N/D';
                }
                $offer['image_url'] = $offer['image_path'] ? $offer['image_path'] : 'https://via.placeholder.com/300x150.png/FF4500/FFFFFF?Text=EcoSave';
                unset($offer['pickup_start_time']); // Remove para limpar a resposta JSON
                unset($offer['pickup_end_time']);   // Remove para limpar a resposta JSON
            }
            unset($offer); // Quebra a referência da última iteração

            $categorized_data[] = [
                'category_id' => $category['category_id'],
                'category_name' => $category['name'],
                'offers' => $offers_for_category
            ];
        }
    }

    echo json_encode(['status' => 'success', 'data' => $categorized_data]);
    http_response_code(200);

} catch (\PDOException $e) {
    http_response_code(500);
    // Em produção, logue o erro em vez de expor detalhes
    echo json_encode(['status' => 'error', 'message' => 'Erro de Banco de Dados. Tente novamente mais tarde.', 'detail' => $e->getMessage(), 'code' => $e->getCode()]);
} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Ocorreu um erro inesperado. Tente novamente mais tarde.', 'detail' => $e->getMessage()]);
}
?>
