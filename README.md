# 🚀 PHP Query Builder (PDO)

Dự án được xây dựng nhằm mục đích học tập, giúp hiểu rõ cách hoạt động của Query Builder và PDO trong PHP.


## Công nghệ sử dụng

Một thư viện Query Builder đơn giản, không phụ thuộc thư viện ngoài, được viết bằng PHP sử dụng PDO.
Hỗ trợ các thao tác CRUD cơ bản với cú pháp method chaining.

---

## 📌 Tính năng

* ✔ Method chaining (cú pháp chuỗi lệnh)
* ✔ Sử dụng PDO prepared statements (an toàn với SQL Injection ở phần value)
* ✔ Bạn có thể tạo một database để test bằng cách chạy file DATABASE.sql
* ✔ Hỗ trợ CRUD cơ bản:

  * SELECT
  * INSERT
  * UPDATE
  * DELETE

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

---

### ➕ INSERT – Thêm dữ liệu

```php
$qb = new QueryBuilderInsert($pdo);

$qb->insert('users')
   ->values(NULL, 'Linh', 2003, 20, '0123456789', 'HN')
   ->execute();
```

---

### ✏️ UPDATE – Cập nhật dữ liệu

```php
$qb = new QueryBuilderUpdate($pdo);

$qb->update('users')
   ->set('name', '=', 'Linh')
   ->set('age', '=', 21)
   ->where('id', '=', 1)
   ->execute();
```

---

### ❌ DELETE – Xóa dữ liệu

```php
$qb = new QueryBuilderDelete($pdo);

$qb->delete('users')
   ->where('id', '=', 1)
   ->execute();
```

---

## 🧠 Nguyên lý hoạt động

* Sử dụng PDO với prepared statement (`?`)
* Dữ liệu được bind thông qua mảng `$bindings`
* Query được build động từ các mảng:

  * `where`
  * `set`
  * `values`

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

* ❗ Thay đổi các biến $host, $dbname, $username, $password, $charset tương ứng với database của bạn trước khi sử dụng
* ❗ Không kiểm tra `table`, `column`, `operator` → cần truyền đúng
* ❗ INSERT phụ thuộc vào thứ tự cột trong database
* ❗ UPDATE/DELETE không có `WHERE` sẽ ảnh hưởng toàn bộ bảng
* ❗ Không reset state sau khi chạy → nên tạo object mới cho mỗi query

---

## 💡 Tác giả

Lê Nhật Linh
