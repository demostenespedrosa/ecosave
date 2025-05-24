<?php
// /api/user_login.php (ATUALIZADO)

session_start();
header('Content-Type: application/json');
require_once '../includes/db_connect.php';

$response = ['status' => 'error', 'message' => 'Email ou senha inválidos.'];
http_response_code(401);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;

    if (!empty($email) && !empty($password)) {
        try {
            $stmt = $pdo->prepare("SELECT user_id, name, email, password_hash, user_type FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_type'] = $user['user_type'];
                $user_data_for_response = [ // Dados para enviar de volta
                    'id' => $user['user_id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'type' => $user['user_type']
                ];

                // *** NOVO: Se for 'business', busca e salva o business_id ***
                if ($user['user_type'] === 'business') {
                    $stmt_b = $pdo->prepare("SELECT business_id FROM businesses WHERE user_id = ?");
                    $stmt_b->execute([$user['user_id']]);
                    $business = $stmt_b->fetch();
                    if ($business) {
                        $_SESSION['business_id'] = $business['business_id'];
                        $user_data_for_response['business_id'] = $business['business_id'];
                    } else {
                        // Isso não deveria acontecer se o cadastro foi feito corretamente
                        // Mas é bom ter um fallback ou logar um erro.
                        $_SESSION['business_id'] = null;
                    }
                }
                // *** FIM DO NOVO ***

                $response = [
                    'status' => 'success',
                    'message' => 'Login realizado com sucesso!',
                    'user' => $user_data_for_response
                ];
                http_response_code(200);
            } else {
                $response['message'] = 'Email ou senha inválidos.';
            }

        } catch (\PDOException $e) {
            $response['message'] = 'Erro no banco de dados: ' . $e->getMessage();
            http_response_code(500);
        }
    } else {
        $response['message'] = 'Email e senha são obrigatórios.';
        http_response_code(400);
    }
} else {
    $response['message'] = 'Método de requisição inválido. Use POST.';
    http_response_code(405);
}

echo json_encode($response);
?>