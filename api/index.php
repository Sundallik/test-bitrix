<?php
//die($_GET['q']);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Credentials: true');
header('content-type: application/json');

require_once('connection.php');
require_once('functions.php');

$method = $_SERVER['REQUEST_METHOD'];
$type = $_GET['q'];
$params = explode("/", $type);

$type = $params[0];
$id = $params[1] ?? null;

switch ($method) {
    case 'GET':
        if ($type === 'posts') {
            if (isset($id)) {
                getPost($pdo, $id);
            } else {
                getPosts($pdo);
            }
        }
        break;
    case 'POST':
        if ($type === 'posts') {
            addPost($pdo, $_POST);
        }
        break;
    case 'PATCH':
        if (isset($id)) {
            $data = file_get_contents('php://input');
            $data = json_decode($data, true);
            updatePost($pdo, $id, $data);
        }
        break;
    case 'DELETE':
        if (isset($id)) {
            deletePost($pdo, $id);
        }
        break;
}









