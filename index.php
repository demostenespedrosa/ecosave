<?php
// /index.php

session_start(); // Sempre inicie a sessão

// Verifica se o usuário está logado
if (isset($_SESSION['user_id'])) {
    // Verifica o tipo de usuário e redireciona
    if ($_SESSION['user_type'] === 'business') {
        header("Location: dashboard_estabelecimento.php");
        exit; // Garante que o script pare após o redirecionamento
    } else {
        header("Location: dashboard_consumidor.php");
        exit;
    }
} else {
    // Se não estiver logado, redireciona para a página de login
    header("Location: login.php");
    exit;
}

?>