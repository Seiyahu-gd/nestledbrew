<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in.']);
    exit;
}

if (!isset($_FILES['profile_picture']) || $_FILES['profile_picture']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded.']);
    exit;
}

$file     = $_FILES['profile_picture'];
$allowed  = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
$maxSize  = 2 * 1024 * 1024; // 2MB

if (!in_array($file['type'], $allowed)) {
    echo json_encode(['success' => false, 'message' => 'Only JPG, PNG, WEBP, or GIF allowed.']);
    exit;
}
if ($file['size'] > $maxSize) {
    echo json_encode(['success' => false, 'message' => 'Image must be under 2MB.']);
    exit;
}

$uploadDir = 'uploads/avatars/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

// Delete old picture if exists
$stmt = $conn->prepare("SELECT profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
if ($row['profile_picture'] && file_exists($row['profile_picture'])) {
    unlink($row['profile_picture']);
}

$ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = 'avatar_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
$path     = $uploadDir . $filename;

if (!move_uploaded_file($file['tmp_name'], $path)) {
    echo json_encode(['success' => false, 'message' => 'Failed to save image.']);
    exit;
}

$stmt = $conn->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
$stmt->bind_param("si", $path, $_SESSION['user_id']);
$stmt->execute();

$_SESSION['user_picture'] = $path;

echo json_encode(['success' => true, 'path' => $path]);