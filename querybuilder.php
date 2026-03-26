<?php

require './vendor/autoload.php';
require 'src/connect/Connection.php';

use Tools\Querybuilder\connect\Connection;
use Tools\Querybuilder\query\QueryBuilderSelect;
use Tools\Querybuilder\query\QueryBuilderDelete;
use Tools\Querybuilder\query\QueryBuilderInsert;
use Tools\Querybuilder\query\QueryBuilderUpdate;

// ===== Select =====
$qbSelect = new QueryBuilderSelect($pdo);

$result = $qbSelect->select('*')
             ->from('users')
             ->where('age', '>', 18)
             ->where('name', '=', 'Linh')
             ->get();
print_r($result);
// ===== Insert =====
$qbInsert = new QueryBuilderInsert($pdo);

$qbInsert->insert('users')
   ->values(NULL, 'Linh', 2003, 20, 'HN')
   ->execute();

// ===== Delete =====
$qbDelete = new QueryBuilderDelete($pdo);

$qbDelete->delete('users')
   ->where('id', '=', 1)
   ->execute();

// ===== Update =====
$qbUpdate = new QueryBuilderUpdate($pdo);

$qbUpdate->update('users')
   ->set('name', '=', 'Linh')
   ->set('age', '=', 23)
   ->where('id', '=', 1)
   ->execute();