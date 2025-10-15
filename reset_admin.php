<?php
require_once __DIR__ . '/config.php';
$conn = get_db_connection();

$username = 'admin';
$password = 'admin123';
$hash = password_hash($password, PASSWORD_BCRYPT);

// Upsert admin user
$stmt = $conn->prepare('SELECT id FROM users WHERE username = ?');
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->bind_result($existingId);
if ($stmt->fetch()) {
    $stmt->close();
    $stmt = $conn->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
    $stmt->bind_param('si', $hash, $existingId);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    echo 'Updated existing admin user. Username: admin, Password: admin123';
} else {
    $stmt->close();
    $stmt = $conn->prepare('INSERT INTO users (username, password_hash) VALUES (?, ?)');
    $stmt->bind_param('ss', $username, $hash);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    echo 'Created admin user. Username: admin, Password: admin123';
}
?>


