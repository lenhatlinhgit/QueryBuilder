<?php
$host = '127.0.0.1';
$dbname = 'datademo'; // dbname của bạn
$username = 'root'; // username của bạn
$password = '123456789'; // password của bạn
$charset = 'utf8mb4';
try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=$charset",
        $username,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Lỗi kết nối: " . $e->getMessage());
}