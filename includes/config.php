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
