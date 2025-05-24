<?php
// /includes/db_connect.php

// --- Configurações do Banco de Dados ---
// ATENÇÃO: Em produção, guarde estas credenciais de forma mais segura!
define('DB_HOST', 'localhost');    // Geralmente 'localhost'
define('DB_NAME', 'ecosave');      // O nome do banco que criamos
define('DB_USER', 'root');         // Seu usuário do MySQL (padrão 'root' no XAMPP)
define('DB_PASS', '');             // Sua senha do MySQL (padrão '' no XAMPP)
define('DB_CHARSET', 'utf8mb4');

// --- Tentativa de Conexão PDO ---
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lança exceções em erros
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Retorna arrays associativos
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Usa prepared statements nativos
    ];

    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

} catch (\PDOException $e) {
    // Em caso de erro na conexão, exibe uma mensagem e termina o script.
    // Em um app real, você faria um log do erro e mostraria uma msg amigável.
    header('Content-Type: application/json');
    http_response_code(500); // Internal Server Error
    echo json_encode([
        'status' => 'error',
        'message' => 'Falha na conexão com o banco de dados: ' . $e->getMessage()
    ]);
    exit; // Impede a execução do resto do script
}

// O objeto $pdo está agora disponível para ser usado nos arquivos que incluírem este.

?>