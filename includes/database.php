<?php
$db = mysqli_connect(
    $_ENV['DB_HOST'] ?? getenv('DB_HOST'),
    $_ENV['DB_USER'] ?? getenv('DB_USER'),
    $_ENV['DB_PASS'] ?? getenv('DB_PASS'),
    $_ENV['DB_NAME'] ?? getenv('DB_NAME'),
    $_ENV['DB_PORT'] ?? getenv('DB_PORT')
);

if (!$db) {
    echo "Error: No se pudo conectar a MySQL.";
    echo "errno de depuración: " . mysqli_connect_errno();
    echo "error de depuración: " . mysqli_connect_error();
    exit;
}
