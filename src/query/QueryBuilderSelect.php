<?php
namespace Tools\Querybuilder\Query;

use PDO;

class QueryBuilderSelect {

    // Lưu đối tượng PDO (dùng để chạy query)
    protected $pdo;

    // Lưu cột cần select (mặc định là *)
    protected $select = '*';

    // Lưu tên bảng
    protected $table;

    // Lưu các điều kiện WHERE (dạng mảng)
    protected $where = [];

    // Lưu giá trị để bind vào dấu ?
    protected $bindings = [];

    // Constructor nhận PDO từ bên ngoài
    public function __construct($pdo) {
        // Gán PDO vào thuộc tính để dùng sau
        $this->pdo = $pdo;
    }

    // Hàm chọn cột
    public function select($columns = '*') {
        // Lưu lại danh sách cột
        $this->select = $columns;

        // return $this để cho phép chaining
        return $this;
    }

    // Hàm chọn bảng
    public function from($table) {
        // Lưu tên bảng
        $this->table = $table;

        // chaining tiếp
        return $this;
    }

    // Hàm thêm điều kiện WHERE
    public function where($column, $operator, $value) {

        // Thêm điều kiện vào mảng WHERE
        // Ví dụ: "age > ?"
        $this->where[] = "$column $operator ?";

        // Lưu giá trị thật để bind sau
        // Ví dụ: 18
        $this->bindings[] = $value;

        // chaining tiếp
        return $this;
    }

    // Hàm thực thi query
    public function get() {
        if (!$this->table) {
            throw new Exception("No table yet.");
        }
        // Tạo câu SQL cơ bản
        // Ví dụ: SELECT * FROM users
        $sql = "SELECT {$this->select} FROM {$this->table}";

        // Nếu có điều kiện WHERE
        if (!empty($this->where)) {

            // Ghép các điều kiện lại bằng AND
            // Ví dụ: age > ? AND name = ?
            $sql .= " WHERE " . implode(" AND ", $this->where);
        }

        // Chuẩn bị câu SQL (chưa chạy)
        $stmt = $this->pdo->prepare($sql);

        // Thực thi và bind dữ liệu vào dấu ?
        // Ví dụ: [18, 'Linh']
        $stmt->execute($this->bindings);

        // Lấy toàn bộ kết quả dưới dạng mảng associative
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}