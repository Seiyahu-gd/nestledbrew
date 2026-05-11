<?php
/* =========================================
   NestledBrew — delete_account.php
   Permanently deletes the user account
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

$userId = (int) $_SESSION['user_id'];

// Delete related rows first (skip any table you don't have yet — no error if rows don't exist)
$tables = ['rewards', 'orders', 'points_log'];
foreach ($tables as $table) {
    $stmt = $conn->prepare("DELETE FROM `$table` WHERE user_id = ?");
    if ($stmt) {
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $stmt->close();
    }
}

// Delete the user
$stmt = $conn->prepare('DELETE FROM users WHERE id = ?');
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Server error. Please try again.']);
    exit;
}
$stmt->bind_param('i', $userId);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();

    // Destroy the session
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();

    echo json_encode(['success' => true, 'message' => 'Account deleted. Goodbye!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Could not delete account. Please try again.']);
    $stmt->close();
    $conn->close();
}
