<?php
// includes/db.php
// Konfiguracija baze podataka
// Za Railway: koristiti environment varijable

$host     = getenv('DB_HOST')     ?: 'localhost';
$user     = getenv('DB_USER')     ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
$database = getenv('DB_NAME')     ?: 'lv4';
$port     = getenv('DB_PORT')     ?: 3306;

$conn = new mysqli($host, $user, $password, $database, (int)$port);

if ($conn->connect_error) {
    die("Greška pri spajanju na bazu: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");