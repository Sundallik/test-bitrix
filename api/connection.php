<?php

$connection = mysqli_connect("localhost","root","","api");

$driver = "mysql";
$host = "localhost";
$port = 3306;
$db_name = "api";
$db_user = "root";
$db_pass = "";
$charset = "utf8";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];

try {
    $pdo = new PDO(
        "$driver:host=$host;dbname=$db_name;port=$port;$charset=$charset",
        $db_user, $db_pass, $options
    );
} catch (Exception $e) {
    die("Error DB connection: {$exception->getMessage()}");
}