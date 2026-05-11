<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first = $_POST['first_name'];
    $last = $_POST['last_name'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
        $stmt->execute([$first, $last, $email, $pass]);

        echo json_encode([
        'status' => 'success',
        'message' => 'Account created successfully'
        ]);
        exit;
        
        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['user_name'] = $first;
        
        echo json_encode(['status' => 'success', 'message' => 'Account created!']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Email already exists.']);
    }
}
?>