<?php
// tests/create_users_table.php

require __DIR__ . '/../vendor/autoload.php'; // autoload PSR-4
require 'ConnectionSQLite.php';
// --- Kết nối SQLite test DB ---
$dbPath = __DIR__ . '/test.db';
$conn = new ConnectionSQLite($dbPath);
$pdo = $conn->getPDO();

// --- Tạo bảng users ---
$createTableSQL = <<<SQL
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    birth_year INTEGER,
    age INTEGER,
    address TEXT
);
SQL;

try {
    $pdo->exec($createTableSQL);
    echo "Bảng 'users' đã được tạo thành công trong $dbPath.\n";
} catch (PDOException $e) {
    echo "Lỗi khi tạo bảng: " . $e->getMessage() . "\n";
}