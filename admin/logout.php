<?php
/**
 * Admin Logout
 * PT Komodo Industrial Indonesia
 */

define('APP_ACCESS', true);

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

// Clear remember me token
clearRememberMe();

// Logout
logout();

// Redirect to login
setFlash('success', 'You have been logged out successfully');
redirect(ADMIN_URL . '/login.php');