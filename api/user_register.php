<?php
// /api/user_register.php

header('Content-Type: application/json'); // Sempre retornar JSON
require_once '../includes/db_connect.php'; // Conecta ao BD

// --- Resposta Padrão ---
$response = ['status' => 'error', 'message' => 'Ocorreu um erro.'];

// --- Verifica se os dados vieram via POST ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pega os dados enviados (idealmente, viriam como JSON, mas vamos usar POST simples)
    $name = $_POST['name'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;
    $user_type = $_POST['user_type'] ?? 'consumer'; // Padrão 'consumer'

    // --- Validação Simples ---
    if (empty($name) || empty($email) || empty($password)) {
        $response['message'] = 'Nome, email e senha são obrigatórios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Formato de email inválido.';
    } elseif (strlen($password) < 6) {
        $response['message'] = 'A senha deve ter pelo menos 6 caracteres.';
    } elseif (!in_array($user_type, ['consumer', 'business'])) {
        $response['message'] = 'Tipo de usuário inválido.';
    } else {
        try {
            // --- Verifica se o email já existe ---
            $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $response['message'] = 'Este email já está cadastrado.';
            } else {
                // --- Criptografa a senha ---
                $password_hash = password_hash($password, PASSWORD_DEFAULT);

                // --- Insere o usuário no banco ---
                $sql = "INSERT INTO users (name, email, password_hash, user_type) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$name, $email, $password_hash, $user_type]);
                $user_id = $pdo->lastInsertId();

                // --- Se for 'business', adiciona na tabela businesses (bem básico) ---
                if ($user_type === 'business') {
                    // Aqui precisaríamos de mais dados, mas vamos criar um básico
                    $business_name = $_POST['business_name'] ?? $name; // Usa o nome ou um específico
                    $address = $_POST['address'] ?? 'Endereço não informado';
                    $stmt_b = $pdo->prepare("INSERT INTO businesses (user_id, business_name, address) VALUES (?, ?, ?)");
                    $stmt_b->execute([$user_id, $business_name, $address]);
                }

                $response = ['status' => 'success', 'message' => 'Usuário cadastrado com sucesso!'];
                http_response_code(201); // Created
            }
        } catch (\PDOException $e) {
            $response['message'] = 'Erro no banco de dados: ' . $e->getMessage();
            http_response_code(500);
        }
    }
} else {
    $response['message'] = 'Método de requisição inválido. Use POST.';
    http_response_code(405); // Method Not Allowed
}

echo json_encode($response); // Envia a resposta JSON
?>