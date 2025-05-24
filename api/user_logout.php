<?php
// /api/user_logout.php

session_start(); // Inicia a sessão para poder destruí-la

// Remove todas as variáveis da sessão
$_SESSION = array();

// Se desejar destruir a sessão completamente, apague também o cookie de sessão.
// Nota: Isto destruirá a sessão, e não apenas os dados da sessão!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destrói a sessão.
session_destroy();

// Retorna uma resposta de sucesso
header('Content-Type: application/json');
echo json_encode(['status' => 'success', 'message' => 'Logout realizado com sucesso.']);
http_response_code(200);
?>