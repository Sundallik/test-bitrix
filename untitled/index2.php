<?php

$connection = new PDO("mysql:host=localhost;dbname=test_db;charset=utf8", "root", "");

$query = "INSERT INTO test_table (name, quantity, price_retail, price_opt) VALUES ('wood', 10, 100, 50);";
$count = $connection->exec($query);

echo $count;

$count = null;
$connection = null;