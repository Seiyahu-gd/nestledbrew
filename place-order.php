<?php
session_start();
header('Content-Type: application/json');

// Auth check
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

require 'db.php';

/* ============================================================
   STEP 1 — Ensure orders + order_items tables exist
   ============================================================ */
$conn->query("
    CREATE TABLE IF NOT EXISTS orders (
        id              INT AUTO_INCREMENT PRIMARY KEY,
        order_number    VARCHAR(20) NOT NULL UNIQUE,
        user_id         INT NOT NULL,
        order_type      ENUM('takeout','dinein','delivery') NOT NULL DEFAULT 'takeout',
        status          ENUM('pending','preparing','ready','completed','cancelled') NOT NULL DEFAULT 'pending',
        first_name      VARCHAR(60) NOT NULL,
        last_name       VARCHAR(60) DEFAULT '',
        email           VARCHAR(100) NOT NULL,
        phone           VARCHAR(30) NOT NULL,
        order_note      TEXT DEFAULT '',
        payment_method  ENUM('cash','gcash','maya','card') NOT NULL DEFAULT 'cash',
        receipt_ref     VARCHAR(100) DEFAULT NULL,
        table_number    VARCHAR(20) DEFAULT NULL,
        delivery_address   VARCHAR(255) DEFAULT NULL,
        delivery_barangay  VARCHAR(100) DEFAULT NULL,
        delivery_city      VARCHAR(100) DEFAULT NULL,
        delivery_notes     TEXT DEFAULT NULL,
        subtotal        DECIMAL(10,2) NOT NULL DEFAULT 0,
        tax             DECIMAL(10,2) NOT NULL DEFAULT 0,
        delivery_fee    DECIMAL(10,2) NOT NULL DEFAULT 0,
        discount_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
        total           DECIMAL(10,2) NOT NULL DEFAULT 0,
        points_used     INT NOT NULL DEFAULT 0,
        points_earned   INT NOT NULL DEFAULT 0,
        created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )
");

$conn->query("
    CREATE TABLE IF NOT EXISTS order_items (
        id           INT AUTO_INCREMENT PRIMARY KEY,
        order_id     INT NOT NULL,
        menu_item_id INT NOT NULL,
        name         VARCHAR(100) NOT NULL,
        price        DECIMAL(10,2) NOT NULL,
        quantity     INT NOT NULL DEFAULT 1,
        subtotal     DECIMAL(10,2) NOT NULL,
        FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
    )
");

/* ============================================================
   STEP 2 — Collect & sanitise inputs
   ============================================================ */
$user_id        = (int) $_SESSION['user_id'];
$order_type     = in_array($_POST['order_type'] ?? '', ['takeout','dinein','delivery'])
                    ? $_POST['order_type'] : 'takeout';
$payment_method = in_array($_POST['payment_method'] ?? '', ['cash','gcash','maya','card'])
                    ? $_POST['payment_method'] : 'cash';

$first_name  = trim($_POST['first_name']  ?? '');
$last_name   = trim($_POST['last_name']   ?? '');
$email       = trim($_POST['email']       ?? '');
$phone       = trim($_POST['phone']       ?? '');
$order_note  = trim($_POST['order_note']  ?? '');
$receipt_ref = trim($_POST['receipt_ref'] ?? '') ?: null;
$table_number = ($order_type === 'dinein')
                    ? trim($_POST['table_number'] ?? '') : null;

$delivery_address  = ($order_type === 'delivery') ? trim($_POST['delivery_address']  ?? '') : null;
$delivery_barangay = ($order_type === 'delivery') ? trim($_POST['delivery_barangay'] ?? '') : null;
$delivery_city     = ($order_type === 'delivery') ? trim($_POST['delivery_city']     ?? '') : null;
$delivery_notes    = ($order_type === 'delivery') ? trim($_POST['delivery_notes']    ?? '') : null;

$points_used     = max(0, (int)   ($_POST['points_used']     ?? 0));
$discount_amount = max(0, (float) ($_POST['discount_amount'] ?? 0));

// Basic validation
if (!$first_name || !$email || !$phone) {
    echo json_encode(['success' => false, 'message' => 'Missing required contact details.']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
    exit;
}

/* ============================================================
   STEP 3 — Load cart items
   ============================================================ */
$stmt = $conn->prepare("
    SELECT ci.menu_item_id, ci.quantity,
           mi.name, mi.price,
           (mi.price * ci.quantity) AS subtotal
    FROM cart_items ci
    JOIN menu_items mi ON mi.id = ci.menu_item_id
    WHERE ci.user_id = ?
");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$cart_result = $stmt->get_result();
$cart_items  = $cart_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if (empty($cart_items)) {
    echo json_encode(['success' => false, 'message' => 'Your cart is empty.']);
    exit;
}

/* ============================================================
   STEP 4 — Calculate totals
   ============================================================ */
$subtotal     = array_sum(array_column($cart_items, 'subtotal'));
$tax          = round($subtotal * 0.10, 2);
$base_total   = $subtotal + $tax;
$delivery_fee = ($order_type === 'delivery') ? 50.00 : 0.00;

// Cap discount so it can't exceed base_total
$discount_amount = min($discount_amount, $base_total);
$total = max($base_total + $delivery_fee - $discount_amount, 0);

/* ============================================================
   STEP 5 — Validate points against user's actual balance
   ============================================================ */
$user_stmt = $conn->prepare("SELECT points FROM users WHERE id = ?");
$user_stmt->bind_param('i', $user_id);
$user_stmt->execute();
$user_row = $user_stmt->get_result()->fetch_assoc();
$user_stmt->close();

$user_points = (int) ($user_row['points'] ?? 0);

// Revalidate: 100 pts = ₱10 off
$max_redeemable = min($user_points, (int) ceil($base_total / 10 * 100));
if ($points_used > $max_redeemable) {
    $points_used     = $max_redeemable;
    $discount_amount = round($points_used / 100 * 10, 2);
    $total           = max($base_total + $delivery_fee - $discount_amount, 0);
}

// Points earned: 1 pt per ₱10 spent (on the final total paid)
$points_earned = (int) floor($total / 10);

/* ============================================================
   STEP 6 — Generate unique order number
   ============================================================ */
$order_number = 'NB-' . strtoupper(substr(md5(uniqid($user_id, true)), 0, 8));

/* ============================================================
   STEP 7 — Begin transaction
   ============================================================ */
$conn->begin_transaction();

try {
    // Insert order
    $ins = $conn->prepare("
        INSERT INTO orders (
            order_number, user_id, order_type, status,
            first_name, last_name, email, phone, order_note,
            payment_method, receipt_ref, table_number,
            delivery_address, delivery_barangay, delivery_city, delivery_notes,
            subtotal, tax, delivery_fee, discount_amount, total,
            points_used, points_earned
        ) VALUES (
            ?, ?, ?, 'pending',
            ?, ?, ?, ?, ?,
            ?, ?, ?,
            ?, ?, ?, ?,
            ?, ?, ?, ?, ?,
            ?, ?
        )
    ");

    $ins->bind_param(
        'sisssssssssssssdddddii',
        $order_number, $user_id, $order_type,
        $first_name, $last_name, $email, $phone, $order_note,
        $payment_method, $receipt_ref, $table_number,
        $delivery_address, $delivery_barangay, $delivery_city, $delivery_notes,
        $subtotal, $tax, $delivery_fee, $discount_amount, $total,
        $points_used, $points_earned
    );
    $ins->execute();
    $order_id = $conn->insert_id;
    $ins->close();

    // Insert order items
    $item_stmt = $conn->prepare("
        INSERT INTO order_items (order_id, menu_item_id, name, price, quantity, subtotal)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    foreach ($cart_items as $item) {
        $item_stmt->bind_param(
            'iisdid',
            $order_id,
            $item['menu_item_id'],
            $item['name'],
            $item['price'],
            $item['quantity'],
            $item['subtotal']
        );
        $item_stmt->execute();
    }
    $item_stmt->close();

    // Update user points: subtract used, add earned
    $net_points = $points_earned - $points_used;
    $pts_stmt   = $conn->prepare("
        UPDATE users
        SET points = GREATEST(0, points + ?)
        WHERE id = ?
    ");
    $pts_stmt->bind_param('ii', $net_points, $user_id);
    $pts_stmt->execute();
    $pts_stmt->close();

    // Update tier based on new points total
    $new_points_row = $conn->query("SELECT points FROM users WHERE id = $user_id")->fetch_assoc();
    $new_points     = (int) $new_points_row['points'];

    if ($new_points >= 2000)     $new_tier = 'Gold Reserve';
    elseif ($new_points >= 500)  $new_tier = 'Brew Member';
    else                         $new_tier = 'Bean Starter';

    $tier_stmt = $conn->prepare("UPDATE users SET tier = ? WHERE id = ?");
    $tier_stmt->bind_param('si', $new_tier, $user_id);
    $tier_stmt->execute();
    $tier_stmt->close();

    // Clear the cart
    $clear = $conn->prepare("DELETE FROM cart_items WHERE user_id = ?");
    $clear->bind_param('i', $user_id);
    $clear->execute();
    $clear->close();

    $conn->commit();

    // Refresh session points
    $_SESSION['user_points'] = $new_points;

    echo json_encode([
        'success'        => true,
        'order_number'   => $order_number,
        'points_earned'  => $points_earned,
        'points_used'    => $points_used,
        'new_points_total' => $new_points,
        'new_tier'       => $new_tier,
        'total'          => number_format($total, 2),
    ]);

} catch (Exception $e) {
    $conn->rollback();
    error_log('place-order.php error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Order could not be placed. Please try again.']);
}

$conn->close();
?>
