# 🚀 PHP Query Builder (PDO)

Dự án được xây dựng nhằm mục đích học tập, giúp hiểu rõ cách hoạt động của Query Builder và PDO trong PHP.

---

## 📌 Giới thiệu

Một thư viện Query Builder đơn giản, không phụ thuộc thư viện ngoài, được viết bằng PHP sử dụng PDO.
Hỗ trợ các thao tác CRUD cơ bản với cú pháp method chaining.

---

## ✨ Tính năng

- ✔ Method chaining (cú pháp chuỗi lệnh)
- ✔ Sử dụng PDO prepared statements (an toàn với SQL Injection ở phần value)
- ✔ Bạn có thể tạo một database để test bằng cách chạy file `DATABASE.sql`
- ✔ Hỗ trợ CRUD cơ bản:
  - SELECT
  - INSERT
  - UPDATE
  - DELETE

---

## ⚙️ Cài đặt

Clone repository:

```bash
git clone https://github.com/lenhatlinhgit/QueryBuilder
```

---

## 📖 Cách sử dụng

### 🔍 SELECT – Lấy dữ liệu

```php
$qb = new QueryBuilder($pdo);

$result = $qb->select('*')
             ->from('users')
             ->where('age', '>', 18)
             ->where('name', '=', 'Linh')
             ->get();

print_r($result);
```

### ➕ INSERT – Thêm dữ liệu

```php
$qb = new QueryBuilderInsert($pdo);

$qb->insert('users')
   ->values(NULL, 'Linh', 2003, 20, '0123456789', 'HN')
   ->execute();
```

### ✏️ UPDATE – Cập nhật dữ liệu

```php
$qb = new QueryBuilderUpdate($pdo);

$qb->update('users')
   ->set('name', '=', 'Linh')
   ->set('age', '=', 21)
   ->where('id', '=', 1)
   ->execute();
```

### ❌ DELETE – Xóa dữ liệu

```php
$qb = new QueryBuilderDelete($pdo);

$qb->delete('users')
   ->where('id', '=', 1)
   ->execute();
```

---

## 🧠 Nguyên lý hoạt động

- Sử dụng PDO với prepared statement (`?`)
- Dữ liệu được bind thông qua mảng `$bindings`
- Query được build động từ các mảng:
  - `where`
  - `set`
  - `values`

---

## 📊 Ví dụ SQL được tạo

```sql
SELECT * FROM users WHERE age > ? AND name = ?

INSERT INTO users VALUES (?, ?, ?, ?, ?, ?)

UPDATE users SET name = ?, age = ? WHERE id = ?

DELETE FROM users WHERE id = ?
```

---

## ⚠️ Lưu ý quan trọng

- ❗ Thay đổi các biến `$host`, `$dbname`, `$username`, `$password`, `$charset` tương ứng với database của bạn trước khi sử dụng
- ❗ Không kiểm tra `table`, `column`, `operator` → cần truyền đúng
- ❗ INSERT phụ thuộc vào thứ tự cột trong database
- ❗ UPDATE/DELETE không có `WHERE` sẽ ảnh hưởng toàn bộ bảng
- ❗ Không reset state sau khi chạy → nên tạo object mới cho mỗi query

---

## 🧪 Hướng dẫn chạy test

### Yêu cầu môi trường

Kiểm tra PHP đã cài PDO SQLite chưa:

```bash
php -m | grep sqlite
```

- Nếu thấy `pdo_sqlite` và `sqlite3` → ✅ OK
- Nếu chưa → bật extension `pdo_sqlite` trong `php.ini` rồi restart server PHP

Cài PHPUnit qua Composer:

```bash
composer require --dev phpunit/phpunit ^9
```

### Cấu trúc thư mục test

```
project/
├── vendor/
├── tests/
│   └── QueryBuilderSQLiteTest.php
├── ConnectionSQLite.php
└── composer.json
```

> ⚠️ Tên folder không nên có khoảng trắng để tránh lỗi đường dẫn SQLite.

### Chạy test

```bash
# Di chuyển vào folder project
cd "link project"

# Chạy toàn bộ test
php vendor/bin/phpunit tests

# Chạy một file cụ thể
php vendor/bin/phpunit tests/QueryBuilderSQLiteTest.php

# Chạy riêng một method
php vendor/bin/phpunit --filter testInsertUser tests/QueryBuilderSQLiteTest.php
```

### Đọc kết quả

```
PHPUnit 9.6.34 by Sebastian Bergmann and contributors.

....                                                                4 / 4 (100%)

Time: 00:00.023, Memory: 6.00 MB

OK (4 tests, 4 assertions)
```

| Ký hiệu | Ý nghĩa |
|---------|---------|
| `.` | 1 test PASS |
| `F` | 1 test FAIL (assertion sai) |
| `E` | 1 test ERROR (có exception) |
| `S` | 1 test SKIPPED (bị bỏ qua) |

### Lưu ý khi dùng SQLite

**Dùng file thật (`test.db`):**
- SQLite tự tạo file nếu chưa tồn tại
- Mỗi lần test, `setUp()` xóa bảng và tạo lại → database luôn sạch

**Dùng `:memory:` (khuyến nghị):**
- Chạy trong RAM, nhanh hơn, không để lại file rác

```php
$pdo = new PDO('sqlite::memory:');
```

### Các lỗi hay gặp

| Lỗi | Nguyên nhân | Cách fix |
|-----|------------|---------|
| `could not find driver` | `pdo_sqlite` chưa bật | Bỏ dấu `;` trước `extension=pdo_sqlite` trong `php.ini` |
| `No such file or directory` | PHPUnit chưa cài | Chạy `composer install` |
| Lỗi đường dẫn | Folder có khoảng trắng | Bọc đường dẫn trong dấu `" "` |

---

## 💡 Tác giả

Lê Nhật Linh
