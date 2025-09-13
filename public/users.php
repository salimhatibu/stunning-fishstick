<?php
// public/users.php
require_once __DIR__ . '/../includes/config.php';

$sql = "SELECT username, email FROM users ORDER BY username ASC";
$result = $conn->query($sql);

echo "<h1>Registered users</h1>";
if ($result && $result->num_rows > 0) {
    echo "<ol>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>" . htmlspecialchars($row['username']) . " &lt;" . htmlspecialchars($row['email']) . "&gt;</li>";
    }
    echo "</ol>";
} else {
    echo "<p>No users found.</p>";
}
