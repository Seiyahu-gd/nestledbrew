<?php
/* =========================================
   NestledBrew — update_profile.php
   Handles display name + bio updates
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

$name = trim($_POST['name'] ?? '');
$bio  = trim($_POST['bio']  ?? '');

if ($name === '') {
    echo json_encode(['success' => false, 'message' => 'Display name cannot be empty.']);
    exit;
}
if (mb_strlen($name) > 40) {
    echo json_encode(['success' => false, 'message' => 'Name must be 40 characters or fewer.']);
    exit;
}
if (mb_strlen($bio) > 160) {
    echo json_encode(['success' => false, 'message' => 'Bio must be 160 characters or fewer.']);
    exit;
}

$stmt = $conn->prepare('UPDATE users SET first_name = ?, bio = ? WHERE id = ?');
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Server error. Please try again.']);
    exit;
}

$stmt->bind_param('ssi', $name, $bio, $_SESSION['user_id']);

if ($stmt->execute()) {
    $_SESSION['user_name'] = $name;
    $_SESSION['user_bio']  = $bio;
    echo json_encode(['success' => true, 'message' => 'Profile updated successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Could not save changes. Please try again.']);
}

$stmt->close();
$conn->close();
