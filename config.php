<?php
$conn = new mysqli('localhost', 'root', '', 'userdb');
if ($conn->connect_error) {
    die('Koneksi MySQLi gagal: ' . $conn->connect_error);
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=userdb', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Koneksi PDO gagal: ' . $e->getMessage());
}
?>
