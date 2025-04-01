<?php

require_once 'setting.php';

$connection = new mysqli($host, $user, $pass, $data);

if ($connection->connect_error) die('Error connection');

$query = "SELECT * FROM test_table";
$result = $connection->query($query);

if (!$result) die ('Error select');

$rows = $result->num_rows;
for ($i = 0; $i < $rows; $i++) {
    $result->data_seek($i);
    echo $result->fetch_assoc()['name'] . '<br>';
}

//$query = "INSERT INTO test_table (name, quantity, price_retail, price_opt) VALUES ('wood', 10, 100, 50);";
//
//for ($i = 0; $i < 3; $i++) {
//    $connection->query($query);
//}
//

echo '<pre>';
print_r($result);
echo '</pre>';

$result->close();
$connection->close();