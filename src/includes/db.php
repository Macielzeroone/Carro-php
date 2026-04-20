<?php
$host = 'mysql';
$user = 'meu_usuario';
$pass = 'minha_senha';
$db   = 'carros_db';

$conn = new mysqli($host, $user, $pass, $db);
$conn->set_charset('utf8mb4');

if ($conn->connect_error) {
    die(json_encode(['erro' => 'Falha na conexão: ' . $conn->connect_error]));
}
