<?php

namespace Tools\Querybuilder\Query;

use PDO;

class QueryBuilderInsert {

    protected $pdo;
    protected $table;
    protected $values = [];
    protected $bindings = [];

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function insert($table){
        $this->table = $table;
        return $this;
    }

    // ✅ SỬA: dùng ...$values để nhận nhiều tham số
    public function values(...$values){

        // giờ $values sẽ là mảng tự động
        // ví dụ: [NULL, 'Linh', 2003, 20, ...]
        $this->values = $values;

        // ✅ binding luôn
        $this->bindings = $values;

        return $this;
    }

    public function execute(){

        if (!$this->table) {
            throw new Exception("No table yet.");
        }

        if (empty($this->values)) {
            throw new Exception("No data to insert yet.");
        }

        // tạo ?, ?, ?, ...
        $placeholders = implode(',', array_fill(0, count($this->values), '?'));

        $sql = "INSERT INTO {$this->table} VALUES ($placeholders)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute($this->bindings);

        echo "done!";
    }
}