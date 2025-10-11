<?php
/**
 * Reset Admin Password Script
 * Run this once then DELETE the file for security
 */

define('APP_ACCESS', true);
require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/config/database.php';

// Reset admin password to 'admin123'
$newPassword = 'admin123';
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

$sql = "UPDATE admin_users SET password = ? WHERE username = 'admin'";
$result = $db->query($sql, [$hashedPassword]);

if ($result) {
    echo "Password reset successfully!<br>";
    echo "New password: " . $newPassword . "<br>";
    echo "Please delete this file immediately for security reasons!";
} else {
    echo "Failed to reset password. Check if admin user exists.";
}
