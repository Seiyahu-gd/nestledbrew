<?php
ob_start();
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'Not logged in.']);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

require_once 'db.php';

$name = trim($_POST['name'] ?? '');
$bio  = trim($_POST['bio']  ?? '');

if ($name === '') {
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'Display name cannot be empty.']);
    exit;
}
if (mb_strlen($name) > 40) {
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'Name must be 40 characters or fewer.']);
    exit;
}
if (mb_strlen($bio) > 160) {
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'Bio must be 160 characters or fewer.']);
    exit;
}

$stmt = $conn->prepare('UPDATE users SET first_name = ?, bio = ? WHERE id = ?');
if (!$stmt) {
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'Server error. Please try again.']);
    exit;
}

$stmt->bind_param('ssi', $name, $bio, $_SESSION['user_id']);

if ($stmt->execute()) {
    $_SESSION['user_name'] = $name;
    $_SESSION['user_bio']  = $bio;
    ob_clean();
    echo json_encode(['success' => true, 'message' => 'Profile updated successfully.']);
} else {
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'Could not save changes. Please try again.']);
}

$stmt->close();
$conn->close();