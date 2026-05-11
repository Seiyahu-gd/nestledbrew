<?php
/* =========================================
   NestledBrew — change_password.php
   Verifies current password then sets new
   ========================================= */

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in.']);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

require_once 'db.php';

$currentPw = $_POST['current_password'] ?? '';
$newPw     = $_POST['new_password']     ?? '';
$confirmPw = $_POST['confirm_password'] ?? '';

if ($currentPw === '' || $newPw === '' || $confirmPw === '') {
    echo json_encode(['success' => false, 'message' => 'All password fields are required.']);
    exit;
}
if ($newPw !== $confirmPw) {
    echo json_encode(['success' => false, 'message' => 'New passwords do not match.']);
    exit;
}
if (strlen($newPw) < 6) {
    echo json_encode(['success' => false, 'message' => 'New password must be at least 6 characters.']);
    exit;
}

// Fetch stored hash
$stmt = $conn->prepare('SELECT password FROM users WHERE id = ?');
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Server error. Please try again.']);
    exit;
}
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($storedHash);
$stmt->fetch();
$stmt->close();

if (!$storedHash) {
    echo json_encode(['success' => false, 'message' => 'User not found.']);
    exit;
}

// Verify current password
if (!password_verify($currentPw, $storedHash)) {
    echo json_encode(['success' => false, 'message' => 'Current password is incorrect.']);
    exit;
}

// Save new hashed password
$newHash = password_hash($newPw, PASSWORD_BCRYPT);
$update  = $conn->prepare('UPDATE users SET password = ? WHERE id = ?');
if (!$update) {
    echo json_encode(['success' => false, 'message' => 'Server error. Please try again.']);
    exit;
}
$update->bind_param('si', $newHash, $_SESSION['user_id']);

if ($update->execute()) {
    echo json_encode(['success' => true, 'message' => 'Password updated successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Could not update password. Please try again.']);
}

$update->close();
$conn->close();
