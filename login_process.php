<?php
session_start();
require 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

$identifier = trim($_POST['identifier'] ?? '');
$pass       = $_POST['password'] ?? '';

if (!$identifier || !$pass) {
    echo json_encode(['status' => 'error', 'message' => 'Please fill in all fields.']);
    exit;
}

// Determine if identifier is email or first name
if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
    $stmt = $conn->prepare("SELECT id, first_name, password, user_points FROM users WHERE email = ?");
} else {
    $stmt = $conn->prepare("SELECT id, first_name, password, user_points FROM users WHERE first_name = ?");
}

if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Server error. Please try again.']);
    exit;
}

$stmt->bind_param("s", $identifier
$stmt->execute();
$result = $stmt->get_result();
$user   = $result->fetch_assoc();
$stmt->close();
$conn->close();

if ($user && password_verify($pass, $user['password'])) {
    $_SESSION['user_id']     = $user['id'];
    $_SESSION['user_name']   = $user['first_name'];
    $_SESSION['user_points'] = $user['user_points'] ?? 0;

    echo json_encode([
        'status'  => 'success',
        'message' => 'Login successful!',
        'points'  => $_SESSION['user_points'],
        'name'    => $user['first_name'],
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email or password.']);
}
exit;