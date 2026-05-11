<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first = trim($_POST['first_name'] ?? '');
    $last = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['password'] ?? '';

    // Validate input
    if (!$first || !$last || !$email || !$pass) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email format.']);
        exit;
    }

    if (strlen($pass) < 6) {
        echo json_encode(['status' => 'error', 'message' => 'Password must be at least 6 characters.']);
        exit;
    }

    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

    // Check if email already exists
    $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Email already exists.']);
        exit;
    }

    // Insert user
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, user_points) VALUES (?, ?, ?, ?, 0)");
    $stmt->bind_param("ssss", $first, $last, $email, $hashed_pass);

    if ($stmt->execute()) {
        $_SESSION['user_id'] = $conn->insert_id;
        $_SESSION['user_name'] = $first;
        $_SESSION['user_points'] = 0;

        echo json_encode(['status' => 'success', 'message' => 'Account created successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error. Please try again.']);
    }
}
?>