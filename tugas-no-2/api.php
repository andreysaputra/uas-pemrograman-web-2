<?php

header('Content-Type: application/json');
$pdo = new PDO('mysql:host=localhost;dbname=todo_app', 'username', 'password');

// GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query("SELECT * FROM todo_list");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

// POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $stmt = $pdo->prepare("INSERT INTO todo_list (task) VALUES (?)");
    $stmt->execute([$data['task']]);
    echo json_encode(['status' => 'Task added']);
}

// PUT
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);
    $stmt = $pdo->prepare("UPDATE todo_list SET task = ?, status = ? WHERE id = ?");
    $stmt->execute([$data['task'], $data['status'], $data['id']]);
    echo json_encode(['status' => 'Task updated']);
}

// DELETE
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);
    $stmt = $pdo->prepare("DELETE FROM todo_list WHERE id = ?");
    $stmt->execute([$data['id']]);
    echo json_encode(['status' => 'Task deleted']);
}
?>