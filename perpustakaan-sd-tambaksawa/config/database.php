<?php
$host = 'localhost';$dbname = 'db_perpus_sd_tambaksawa';
$username = 'root';$password = '';

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Buat database jika belum ada
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    $pdo->exec("USE `$dbname`;");
    
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>