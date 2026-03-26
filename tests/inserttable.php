<?php
// tests/insert_users.php

require __DIR__ . '/../vendor/autoload.php'; // autoload PSR-4
require 'ConnectionSQLite.php';
// --- Kết nối SQLite test DB ---
$dbPath = __DIR__ . '/test.db';
$conn = new ConnectionSQLite($dbPath);
$pdo = $conn->getPDO();

// --- Tạo bảng users ---
$createTableSQL = <<<SQL
INSERT INTO users (name, birth_year, age, address) VALUES
('Nguyễn Minh Anh', 2000, 25, 'Hà Nội'),
('Trần Quang Huy', 1999, 26, 'Hải Phòng'),
('Lê Bảo Ngọc', 2001, 24, 'Đà Nẵng'),
('Phạm Hoàng Long', 1998, 27, 'TP.HCM'),
('Hoàng Thu Trang', 2002, 23, 'Hà Nội'),
('Vũ Tuấn Kiệt', 2000, 25, 'Nam Định'),
('Đặng Gia Hân', 2003, 22, 'Huế'),
('Bùi Đức Minh', 1997, 28, 'Bắc Ninh'),
('Đỗ Phương Anh', 2001, 24, 'Hà Nội'),
('Ngô Ngọc Linh', 2002, 23, 'Hải Dương'),

('Phan Trọng Nhân', 1999, 26, 'Cần Thơ'),
('Lý Lệ Quyên', 2000, 25, 'TP.HCM'),
('Dương Anh Khoa', 1998, 27, 'Đà Nẵng'),
('Tạ Khánh Huyền', 2001, 24, 'Hà Nội'),
('Cao Thành Đạt', 1997, 28, 'Bình Dương'),
('Đinh Mai Phương', 2002, 23, 'Hà Nam'),
('Hồ Huy Hoàng', 1999, 26, 'Huế'),
('Trịnh Lan Chi', 2003, 22, 'Nghệ An'),
('Phùng Quốc Bảo', 2000, 25, 'Quảng Ninh'),
('Mai Yến Nhi', 2002, 23, 'Hà Nội'),

('Nguyễn Đức Thịnh', 1998, 27, 'Hải Phòng'),
('Trần Nguyên Khôi', 2001, 24, 'Đà Nẵng'),
('Lê Bảo Trâm', 2003, 22, 'Huế'),
('Phạm Phúc An', 2000, 25, 'TP.HCM'),
('Hoàng Thiên Phúc', 1999, 26, 'Hà Nội'),
('Vũ Mỹ Linh', 2002, 23, 'Hải Dương'),
('Đặng Khánh Sơn', 1997, 28, 'Nam Định'),
('Bùi Quỳnh Anh', 2001, 24, 'Hà Nội'),
('Đỗ Hoài Nam', 1998, 27, 'Bắc Giang'),
('Ngô Trúc Linh', 2003, 22, 'Huế'),

('Phan Đình Khánh', 1999, 26, 'Đà Nẵng'),
('Lý Ngọc Trâm', 2002, 23, 'TP.HCM'),
('Dương Đức Tài', 2000, 25, 'Hà Nội'),
('Tạ Anh Thư', 2003, 22, 'Hải Phòng'),
('Cao Hiếu Nhân', 1998, 27, 'Quảng Bình'),
('Đinh Thu Hà', 2001, 24, 'Hà Nội'),
('Hồ Trung Hiếu', 1997, 28, 'Huế'),
('Trịnh Linh Chi', 2002, 23, 'Hà Nội'),
('Phùng Quang Đạt', 2000, 25, 'Bắc Ninh'),
('Mai Ngọc Anh', 2001, 24, 'Hà Nội'),

('Nguyễn Phước Lộc', 1999, 26, 'Cần Thơ'),
('Trần Thanh Trúc', 2002, 23, 'TP.HCM'),
('Lê Tuấn Phát', 2000, 25, 'Đà Nẵng'),
('Phạm Ngọc Hân', 2003, 22, 'Hà Nội'),
('Hoàng Phúc Hưng', 1998, 27, 'Hải Phòng'),
('Vũ Thảo Nguyên', 2001, 24, 'Huế'),
('Đặng Minh Trí', 1997, 28, 'Nam Định'),
('Bùi Bảo Hân', 2002, 23, 'Hà Nội'),
('Đỗ Quốc Thịnh', 1999, 26, 'Bình Dương'),
('Ngô Phương Thảo', 2003, 22, 'Hà Nội'),

('Phan Huy Khang', 2000, 25, 'Đà Nẵng'),
('Lý Lan Ngọc', 2002, 23, 'TP.HCM'),
('Dương Đức Anh', 1998, 27, 'Hà Nội'),
('Tạ Yến Trang', 2001, 24, 'Hải Dương'),
('Cao Trung Đức', 1997, 28, 'Huế'),
('Đinh Ngọc Bích', 2003, 22, 'Hà Nội'),
('Hồ Anh Tuấn', 1999, 26, 'TP.HCM'),
('Trịnh Thanh Nhân', 2000, 25, 'Nghệ An'),
('Phùng Phúc Thịnh', 2001, 24, 'Quảng Ninh'),
('Mai Kim Ngân', 2002, 23, 'Hà Nội'),

('Nguyễn Hoàng Nam', 1998, 27, 'Hải Phòng'),
('Trần Mỹ Duyên', 2003, 22, 'TP.HCM'),
('Lê Bảo Long', 1999, 26, 'Đà Nẵng'),
('Phạm Thu Hiền', 2001, 24, 'Hà Nội'),
('Hoàng Quang Khôi', 2000, 25, 'Huế'),
('Vũ Trúc Quỳnh', 2002, 23, 'Hà Nội'),
('Đặng Ngọc Phúc', 1997, 28, 'Bắc Ninh'),
('Bùi Thiên An', 2003, 22, 'TP.HCM'),
('Đỗ Anh Đào', 2001, 24, 'Hà Nội'),
('Ngô Đức Phát', 1998, 27, 'Hải Dương'),

('Phan Phương Linh', 2002, 23, 'Hà Nội'),
('Lý Khánh Toàn', 1999, 26, 'Đà Nẵng'),
('Dương Ngọc Diễm', 2003, 22, 'Huế'),
('Tạ Huy Phong', 2000, 25, 'TP.HCM'),
('Cao Trang Anh', 2001, 24, 'Hà Nội'),
('Đinh Minh Phúc', 1998, 27, 'Nam Định'),
('Hồ Bảo Châu', 2002, 23, 'Hà Nội'),
('Trịnh Quốc Huy', 1999, 26, 'Hải Phòng'),
('Phùng Thanh Hằng', 2003, 22, 'Hà Nội'),
('Mai Đức Khoa', 2000, 25, 'Đà Nẵng'),

('Nguyễn Yến Phương', 2001, 24, 'Hà Nội'),
('Trần Trung Kiên', 1998, 27, 'TP.HCM'),
('Lê Ngọc Trinh', 2003, 22, 'Huế'),
('Phạm Hoàng Khang', 1999, 26, 'Bình Dương'),
('Hoàng Thu Quỳnh', 2002, 23, 'Hà Nội'),
('Vũ Phúc Long', 2000, 25, 'Đà Nẵng'),
('Đặng Mỹ Hạnh', 2003, 22, 'TP.HCM'),
('Bùi Quang Hải', 1998, 27, 'Hải Phòng'),
('Đỗ Lan Hương', 2001, 24, 'Hà Nội'),
('Ngô Anh Kiệt', 1999, 26, 'Huế');
SQL;

try {
    $pdo->exec($createTableSQL);
    echo "Bảng 'users' đã được insertt thành công trong $dbPath.\n";
} catch (PDOException $e) {
    echo "Lỗi khi insertt: " . $e->getMessage() . "\n";
}
