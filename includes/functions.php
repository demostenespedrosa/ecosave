/**
 * Lida com o upload de uma imagem para uma oferta.
 *
 * @param array $file_info A informação do arquivo vinda de $_FILES['nome_do_campo'].
 * @param int $offer_id (Opcional) ID da oferta para nomear o arquivo (útil na edição).
 * @return string|null O caminho relativo do arquivo salvo ou null em caso de erro.
 */
function handle_offer_image_upload($file_info, $offer_id = 0) {
    if (!isset($file_info) || $file_info['error'] !== UPLOAD_ERR_OK) {
        // Sem arquivo ou erro no upload inicial, não é um erro fatal aqui.
        return null;
    }

    $upload_dir = '../uploads/'; // Caminho relativo da API para a pasta
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $max_size = 5 * 1024 * 1024; // 5 MB

    // Validações
    if (!in_array($file_info['type'], $allowed_types)) {
        error_log("Tipo de arquivo não permitido: " . $file_info['type']);
        return null; // Ou pode retornar uma mensagem de erro específica
    }
    if ($file_info['size'] > $max_size) {
        error_log("Arquivo muito grande: " . $file_info['size']);
        return null;
    }

    // Gera nome único
    $extension = pathinfo($file_info['name'], PATHINFO_EXTENSION);
    $safe_name = "offer_" . ($offer_id > 0 ? $offer_id . "_" : '') . time() . uniqid() . '.' . strtolower($extension);
    $destination = $upload_dir . $safe_name;

    // Move o arquivo
    if (move_uploaded_file($file_info['tmp_name'], $destination)) {
        return 'uploads/' . $safe_name; // Retorna o caminho RELATIVO à RAIZ do site
    } else {
        error_log("Falha ao mover arquivo para: " . $destination);
        return null;
    }
}