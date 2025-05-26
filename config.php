<?php
// Koneksi MySQLi
$conn = new mysqli('localhost', 'root', 'arry', 'userdb');
if ($conn->connect_error) {
    die('Koneksi MySQLi gagal: ' . $conn->connect_error);
}

// Koneksi PDO (untuk profile.php, bisa diabaikan di sini jika belum dipakai)
try {
    $pdo = new PDO('mysql:host=localhost;dbname=userdb', 'root', 'arry');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Koneksi PDO gagal: ' . $e->getMessage());
}
?>
