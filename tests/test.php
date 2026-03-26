<?php
require __DIR__ . '/../vendor/autoload.php'; // đúng đường dẫn
require 'ConnectionSQLite.php';

use Tools\Querybuilder\connect\Connection;
use Tools\Querybuilder\query\QueryBuilderSelect;
use Tools\Querybuilder\query\QueryBuilderDelete;
use Tools\Querybuilder\query\QueryBuilderInsert;
use Tools\Querybuilder\query\QueryBuilderUpdate;

// kết nối DB SQLite test
$conn = new ConnectionSQLite(__DIR__ . '/test.db'); // test.db nằm trong cùng thư mục tests/
$pdo = $conn->getPDO();

// Insert
$qbInsert = new QueryBuilderInsert($pdo);
$qbInsert->insert('users')
         ->values(NULL, 'Linh', 2003, 20, 'HN')
         ->execute();

// Select
$qbSelect = new QueryBuilderSelect($pdo);
$result = $qbSelect->select('*')
                   ->from('users')
                   ->where('age', '>', 18)
                   ->where('name', '=', 'Linh')
                   ->get();
print_r($result);

// Update
$qbUpdate = new QueryBuilderUpdate($pdo);
$qbUpdate->update('users')
         ->set('name', '=', 'Linh Updated')
         ->set('age', '=', 23)
         ->where('id', '=', 1)
         ->execute();

// Delete
$qbDelete = new QueryBuilderDelete($pdo);
$qbDelete->delete('users')
         ->where('id', '=', 1)
         ->execute();




