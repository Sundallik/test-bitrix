<?php

function getPosts($pdo)
{
    $sql = "SELECT * FROM posts";
    $stmt = $pdo->query($sql);

    $postsList = [];
    while($post = $stmt->fetchAll()) {
        $postsList[] = $post;
    }
    echo json_encode($postsList);
}

function getPost($pdo, $id)
{
    $sql = "SELECT * FROM posts WHERE id = ?";
    $stmt = $pdo->prepare($sql);

    if (!$stmt->execute([$id])) {
        http_response_code(404);
        echo json_encode([
            'status' => 'error',
            'message' => 'Post not found'
        ]);
    } else {
        $stmt->execute([$id]);
        $post = $stmt->fetch();
        echo json_encode($post);
    }
}

function addPost($pdo, $data)
{
    $title = $data['title'];
    $content = $data['content'];

    $sql = "INSERT INTO `posts` (`title`, `content`) VALUES (:title, :content)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['title' => $title, 'content' => $content]);

    http_response_code(201);
    echo json_encode([
        'status' => 'success',
        'post_id' => $pdo->lastInsertId()
    ]);
}

function updatePost($pdo, $id, $data)
{
    $title = $data['title'];
    $content = $data['content'];

    $sql = "UPDATE `posts` SET `title` = :title, `content` = :content WHERE `posts`.`id` = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id, 'title' => $title, 'content' => $content]);

    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'post_id' => $id
    ]);
}

function deletePost($pdo, $id)
{
    $sql = "DELETE FROM `posts` WHERE `posts`.`id` = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'post_id' => $id
    ]);
}