<?php
// =========================
// CONEXÃO COM BANCO DE DADOS
// Lumora Café
// =========================

// Configurações do banco via variáveis de ambiente
$host = getenv('DB_HOST') ?: 'db';
$user = getenv('DB_USER') ?: 'lumora';
$pass = getenv('DB_PASS') ?: 'lumora';
$db   = getenv('DB_NAME') ?: 'lumora_cafe_v2';
$port = (int)(getenv('DB_PORT') ?: 3306);

// Cria conexão
$conn = new mysqli($host, $user, $pass, $db, $port);


// Verifica erro de conexão
if ($conn->connect_error) {
    die("❌ Erro de conexão com o banco de dados: " . $conn->connect_error);
}

// Define charset (evita problema com acentos)
$conn->set_charset("utf8mb4");
?>




