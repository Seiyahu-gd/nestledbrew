<?php
session_start();
require 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit;
}

$action  = $_POST['action'] ?? '';
$user_id = $_SESSION['user_id'];

switch ($action) {
    case 'add':            addToCart($conn, $user_id);      break;
    case 'update':         updateQuantity($conn, $user_id); break;
    case 'remove':         removeFromCart($conn, $user_id); break;
    case 'get':            getCartItems($conn, $user_id);   break;
    case 'validate_points': validatePoints($user_id);       break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
}

function addToCart($conn, $user_id) {
    $menu_item_id = intval($_POST['menu_item_id'] ?? 0);
    if (!$menu_item_id) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid item']);
        return;
    }

    $check = $conn->prepare("SELECT id FROM menu_items WHERE id = ?");
    $check->bind_param("i", $menu_item_id);
    $check->execute();
    if ($check->get_result()->num_rows == 0) {
        echo json_encode(['status' => 'error', 'message' => 'Item not found']);
        return;
    }
    $check->close();

    $stmt = $conn->prepare("INSERT INTO cart_items (user_id, menu_item_id, quantity) VALUES (?, ?, 1)
                            ON DUPLICATE KEY UPDATE quantity = quantity + 1");
    $stmt->bind_param("ii", $user_id, $menu_item_id);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Item added to cart']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
    }
    $stmt->close();
}

function updateQuantity($conn, $user_id) {
    $menu_item_id = intval($_POST['menu_item_id'] ?? 0);
    $quantity     = intval($_POST['quantity']     ?? 0);

    if (!$menu_item_id || $quantity < 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        return;
    }
    if ($quantity == 0) {
        // Delegate cleanly
        $_POST['menu_item_id'] = $menu_item_id;
        removeFromCart($conn, $user_id);
        return;
    }

    $stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE user_id = ? AND menu_item_id = ?");
    $stmt->bind_param("iii", $quantity, $user_id, $menu_item_id);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Quantity updated']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Update failed']);
    }
    $stmt->close();
}

function removeFromCart($conn, $user_id) {
    $menu_item_id = intval($_POST['menu_item_id'] ?? 0);
    if (!$menu_item_id) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid item']);
        return;
    }

    $stmt = $conn->prepare("DELETE FROM cart_items WHERE user_id = ? AND menu_item_id = ?");
    $stmt->bind_param("ii", $user_id, $menu_item_id);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Item removed']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Deletion failed']);
    }
    $stmt->close();
}

function getCartItems($conn, $user_id) {
    // Also return the user's current points so JS always has a fresh value
    $pts_stmt = $conn->prepare("SELECT user_points FROM users WHERE id = ?");
    $pts_stmt->bind_param("i", $user_id);
    $pts_stmt->execute();
    $pts_row      = $pts_stmt->get_result()->fetch_assoc();
    $user_points  = $pts_row['user_points'] ?? 0;
    $pts_stmt->close();

    $stmt = $conn->prepare("
        SELECT
            ci.id,
            ci.menu_item_id,
            ci.quantity,
            mi.name,
            mi.price,
            mi.image_path,
            (mi.price * ci.quantity) AS subtotal
        FROM cart_items ci
        JOIN menu_items mi ON ci.menu_item_id = mi.id
        WHERE ci.user_id = ?
        ORDER BY ci.id DESC
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $items = [];
    $total = 0;
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
        $total  += floatval($row['subtotal']);
    }
    $stmt->close();

    echo json_encode([
        'status'       => 'success',
        'items'        => $items,
        'subtotal'     => $total,
        'tax'          => round($total * 0.10, 2),
        'total'        => round($total * 1.10, 2),
        'user_points'  => $user_points,       // fresh from DB every load
    ]);
}

function validatePoints($user_id) {
    global $conn;

    $points_to_use = intval($_POST['points'] ?? 0);

    if ($points_to_use <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Enter a points amount greater than 0.']);
        return;
    }

    // Server-side truth: recompute cart totals from DB (do not trust client cart_total)
    $cartTotalsStmt = $conn->prepare(
        "
        SELECT COALESCE(SUM(mi.price * ci.quantity), 0) AS subtotal
        FROM cart_items ci
        JOIN menu_items mi ON ci.menu_item_id = mi.id
        WHERE ci.user_id = ?
        "
    );

    if (!$cartTotalsStmt) {
        echo json_encode(['status' => 'error', 'message' => 'Server error. Please try again.']);
        return;
    }

    $cartTotalsStmt->bind_param('i', $user_id);
    $cartTotalsStmt->execute();
    $cartTotalsRes = $cartTotalsStmt->get_result();
    $cartRow = $cartTotalsRes ? $cartTotalsRes->fetch_assoc() : null;
    $cartTotalsStmt->close();

    $subtotal = (float) ($cartRow['subtotal'] ?? 0);
    $tax = round($subtotal * 0.10, 2);
    $cart_total = round($subtotal + $tax, 2);

    // Load user points (server-side)
    $stmt = $conn->prepare("SELECT user_points FROM users WHERE id = ?");
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Server error. Please try again.']);
        return;
    }

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$user) {
        echo json_encode(['status' => 'error', 'message' => 'User not found.']);
        return;
    }

    $available = (int) $user['user_points'];

    if ($points_to_use > $available) {
        echo json_encode([
            'status'    => 'error',
            'message'   => "You only have {$available} pts available.",
            'available' => $available,
        ]);
        return;
    }

    // Fixed rate: 100 pts = ₱10 discount
    $discount = round(($points_to_use / 100) * 10, 2);

    // Never discount more than the cart total
    if ($cart_total > 0 && $discount > $cart_total) {
        $discount = round($cart_total, 2);
        // How many points actually needed for that cap
        $points_to_use = (int) ceil(($discount / 10) * 100);
    }

    echo json_encode([
        'status'          => 'success',
        'available'       => $available,
        'points_used'     => $points_to_use,
        'discount_amount' => $discount,
        'message'         => "✓ {$points_to_use} pts = ₱{$discount} off",
    ]);
}
?>
