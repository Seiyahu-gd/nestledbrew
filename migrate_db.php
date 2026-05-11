<?php
require 'db.php';

echo "<h2>NestledBrew Database Migration</h2>";
echo "<p>Running schema updates...</p><br>";

$errors = [];
$success = [];

// 1. Add bio column to users table
$check_bio = "SHOW COLUMNS FROM users LIKE 'bio'";
$result = $conn->query($check_bio);

if ($result->num_rows == 0) {
    $add_bio = "ALTER TABLE users ADD COLUMN bio TEXT DEFAULT ''";
    if ($conn->query($add_bio)) {
        $success[] = "✓ Added 'bio' column to users table";
    } else {
        $errors[] = "✗ Failed to add bio column: " . $conn->error;
    }
} else {
    $success[] = "✓ 'bio' column already exists";
}

// 2. Add profile_picture column to users table
$check_picture = "SHOW COLUMNS FROM users LIKE 'profile_picture'";
$result = $conn->query($check_picture);

if ($result->num_rows == 0) {
    $add_picture = "ALTER TABLE users ADD COLUMN profile_picture VARCHAR(255) DEFAULT NULL";
    if ($conn->query($add_picture)) {
        $success[] = "✓ Added 'profile_picture' column to users table";
    } else {
        $errors[] = "✗ Failed to add profile_picture column: " . $conn->error;
    }
} else {
    $success[] = "✓ 'profile_picture' column already exists";
}

// 3. Create cart_items table
$check_table = "SHOW TABLES LIKE 'cart_items'";
$result = $conn->query($check_table);

if ($result->num_rows == 0) {
    $create_table = "CREATE TABLE cart_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        menu_item_id INT NOT NULL,
        quantity INT DEFAULT 1,
        added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (menu_item_id) REFERENCES menu_items(id) ON DELETE CASCADE,
        UNIQUE KEY unique_user_item (user_id, menu_item_id)
    )";

    if ($conn->query($create_table)) {
        $success[] = "✓ Created 'cart_items' table";
    } else {
        $errors[] = "✗ Failed to create cart_items table: " . $conn->error;
    }
} else {
    $success[] = "✓ 'cart_items' table already exists";
}

// Display results
echo "<div style='font-family: Arial; margin: 20px;'>";
echo "<h3 style='color: green;'>Success:</h3>";
foreach ($success as $msg) {
    echo "<p style='color: green;'>$msg</p>";
}

if (!empty($errors)) {
    echo "<h3 style='color: red;'>Errors:</h3>";
    foreach ($errors as $msg) {
        echo "<p style='color: red;'>$msg</p>";
    }
} else {
    echo "<p style='color: green; font-weight: bold;'><br>✓ All migrations completed successfully!</p>";
}

echo "<p><a href='homepage.php' style='color: #8B6B4F; text-decoration: none; font-weight: bold;'>← Back to Homepage</a></p>";
echo "</div>";

$conn->close();
?>
