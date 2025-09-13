<?php
// includes/config.php
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';        // set your MySQL password
$DB_NAME = 'taskapp';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    die('DB connection failed: ' . $conn->connect_error);
}

// Create users table if it doesn't exist
$createTableSQL = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($createTableSQL)) {
    die('Error creating table: ' . $conn->error);
}

// Add created_at column if it doesn't exist (for existing tables)
$checkColumnSQL = "SHOW COLUMNS FROM users LIKE 'created_at'";
$result = $conn->query($checkColumnSQL);
if ($result->num_rows == 0) {
    $addColumnSQL = "ALTER TABLE users ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
    if (!$conn->query($addColumnSQL)) {
        // Column might already exist, ignore error
    }
}