<?php
// /logout.php

session_start(); // Inicia a sessão para poder manipulá-la

// Remove todas as variáveis da sessão
$_SESSION = array();

// Destrói o cookie de sessão, se existir
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destrói a sessão.
session_destroy();

// Redireciona para a página de login
header("Location: login.php");
exit;
?>