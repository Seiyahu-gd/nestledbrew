<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $pass = $_POST['password'];


    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();


    if ($user && password_verify($pass, $user['password'])) {
  
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['first_name'];
        
        echo json_encode(['status' => 'success']);
    } else {
        
        echo json_encode(['status' => 'error', 'message' => 'Invalid email or password.']);
    }
    exit;
}
?>