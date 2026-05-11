<?php
require 'db.php';

// Add user_points column if it doesn't exist
$check_column = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='users' AND COLUMN_NAME='user_points'";
$result = $conn->query($check_column);

if ($result->num_rows == 0) {
    $alter_table = "ALTER TABLE users ADD COLUMN user_points INT DEFAULT 0";
    if ($conn->query($alter_table)) {
        echo "✓ user_points column added successfully!";
    } else {
        echo "✗ Error adding column: " . $conn->error;
    }
} else {
    echo "✓ user_points column already exists!";
}

$conn->close();
?>
