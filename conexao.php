<?php
// =========================
// CONEXÃO COM BANCO DE DADOS
// Lumora Café
// =========================

// Configurações do banco via variáveis de ambiente
$host = "mainline.proxy.rlwy.net";
$user = "root";
$pass = "IjZdaYXxTxKjazlQGCJjuuffGvSFZcxa";
$db   = "railway";
$port = "13275";

// Cria conexão
$conn = new mysqli($host, $user, $pass, $db, $port);


// Verifica erro de conexão
if ($conn->connect_error) {
    die("❌ Erro de conexão com o banco de dados: " . $conn->connect_error);
}

// Define charset (evita problema com acentos)
$conn->set_charset("utf8mb4");





