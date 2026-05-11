<?php
/* =========================================
   NestledBrew — rewards_action.php
   Minimal rewards backend for current UI

   Supported actions:
   - GET (via POST with action=get_points): returns points + tier
   - POST action=claim: deduct points (cost) after validation
   ========================================= */

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit;
}

require_once 'db.php';

$userId = (int) $_SESSION['user_id'];
$action = $_POST['action'] ?? 'get_points';

function getTierFromPoints(int $pts): array {
    if ($pts >= 2000) {
        return ['name' => 'Gold Reserve', 'icon' => '🔥'];
    }
    if ($pts >= 500) {
        return ['name' => 'Brew Member', 'icon' => '✨'];
    }
    return ['name' => 'Bean Starter', 'icon' => '☕'];
}

function getUserPoints(mysqli $conn, int $userId): int {
    $stmt = $conn->prepare('SELECT user_points FROM users WHERE id = ?');
    if (!$stmt) {
        return 0;
    }
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res ? $res->fetch_assoc() : null;
    $stmt->close();
    return (int) (($row['user_points'] ?? 0));
}

if ($action === 'get_points') {
    $points = getUserPoints($conn, $userId);
    $tier = getTierFromPoints($points);

    echo json_encode([
        'status' => 'success',
        'points' => $points,
        'tier'   => $tier['name'],
    ]);
    exit;
}

if ($action === 'claim') {
    $cost = (int) ($_POST['cost'] ?? 0);

    if ($cost <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid reward cost']);
        exit;
    }

    // Re-read available points from DB (server-side trust)
    $available = getUserPoints($conn, $userId);

    if ($cost > $available) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Not enough points available',
            'available' => $available
        ]);
        exit;
    }

    $newPoints = $available - $cost;

    $stmt = $conn->prepare('UPDATE users SET user_points = ? WHERE id = ?');
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Server error']);
        exit;
    }
    $stmt->bind_param('ii', $newPoints, $userId);

    if ($stmt->execute()) {
        $stmt->close();
        $tier = getTierFromPoints($newPoints);

        echo json_encode([
            'status' => 'success',
            'message' => 'Reward claimed',
            'new_points' => $newPoints,
            'tier' => $tier['name'],
        ]);
    } else {
        $stmt->close();
        echo json_encode(['status' => 'error', 'message' => 'Could not claim reward']);
    }
    exit;
}

// Unknown action
echo json_encode(['status' => 'error', 'message' => 'Invalid action']);

