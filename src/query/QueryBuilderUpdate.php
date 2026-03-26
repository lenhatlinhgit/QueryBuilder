<?php
namespace Tools\Querybuilder\Query;

use PDO;

class QueryBuilderUpdate {

    protected $pdo;

    protected $table;

    protected $set = [];

    protected $where = [];

    protected $bindings = [];

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function update($table){
        $this->table = $table;
        return $this;
    }

    public function where($column, $operator, $value) {

        $this->where[] = "$column $operator ?";

        $this->bindings[] = $value;

        return $this;
    }

    public function set($column, $operator, $value) {

        $this->set[] = "$column $operator ?";

        $this->bindings[] = $value;

        return $this;
    }

    public function execute() {
        if (!$this->table) {
            throw new Exception("No table yet.");
        }
        
        $sql = "UPDATE {$this->table}";


        if (!empty($this->set)){
            $sql .= " SET " . implode(" , ", $this->set);
        }

        if (!empty($this->where)) {

            $sql .= " WHERE " . implode(" AND ", $this->where);

        }
        if ((empty($this->set)) && (!empty($this->where))){
            throw new Exception("syntax error.");
        }

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute($this->bindings);

        echo "done!";
    }
}