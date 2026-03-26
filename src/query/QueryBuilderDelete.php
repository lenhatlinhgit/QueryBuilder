<?php
namespace Tools\Querybuilder\Query;

use PDO;

class QueryBuilderDelete {

    protected $pdo;

    protected $table;

    protected $where = [];

    protected $bindings = [];

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function delete($table){
        $this->table = $table;
        return $this;
    }

    public function where($column, $operator, $value) {

        $this->where[] = "$column $operator ?";

        $this->bindings[] = $value;

        return $this;
    }

    public function execute() {
        if (!$this->table) {
            throw new Exception("No table yet.");
        }

        $sql = "DELETE FROM {$this->table}";

        if (!empty($this->where)) {

            $sql .= " WHERE " . implode(" AND ", $this->where);

        }

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute($this->bindings);

        echo "done!";
    }
}