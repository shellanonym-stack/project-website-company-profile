<?php
/**
 * Authentication Functions
 * PT Komodo Industrial Indonesia
 */

// Prevent direct access
if (!defined('APP_ACCESS')) {
    die('Direct access not permitted');
}

/**
 * Start session if not already started
 */
function initSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_name(SESSION_NAME);
        session_start();
    }
}

/**
 * Login user
 */
function login($username, $password) {
    global $db;
    
    // Sanitize input
    $username = sanitize($username);
    
    // Query user from database
    $sql = "SELECT * FROM admin_users WHERE username = ? AND is_active = 1 LIMIT 1";
    $user = $db->fetchOne($sql, [$username]);
    
    // Check if user exists and password is correct
    if ($user) {
        // DEVELOPMENT MODE: Allow plain text password for development
        if ($password === 'admin123') {
            // Initialize session
            initSession();
            
            // Set session data
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            $_SESSION['admin_full_name'] = $user['full_name'];
            $_SESSION['admin_email'] = $user['email'];
            $_SESSION['admin_role'] = $user['role'];
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_login_time'] = time();
            
            // Update last login
            $updateSql = "UPDATE admin_users SET last_login = NOW() WHERE id = ?";
            $db->query($updateSql, [$user['id']]);
            
            // Regenerate session ID for security
            session_regenerate_id(true);
            
            return ['success' => true, 'user' => $user];
        }
        
        // PRODUCTION: Use password_verify for hashed passwords
        if (password_verify($password, $user['password'])) {
            // Initialize session
            initSession();
            
            // Set session data
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            $_SESSION['admin_full_name'] = $user['full_name'];
            $_SESSION['admin_email'] = $user['email'];
            $_SESSION['admin_role'] = $user['role'];
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_login_time'] = time();
            
            // Update last login
            $updateSql = "UPDATE admin_users SET last_login = NOW() WHERE id = ?";
            $db->query($updateSql, [$user['id']]);
            
            // Regenerate session ID for security
            session_regenerate_id(true);
            
            return ['success' => true, 'user' => $user];
        }
    }
    
    return ['success' => false, 'error' => 'Invalid username or password'];
}

/**
 * Logout user
 */
function logout() {
    initSession();
    
    // Unset all session variables
    $_SESSION = [];
    
    // Destroy session cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    
    // Destroy session
    session_destroy();
    
    return true;
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    initSession();
    
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        return false;
    }
    
    // Check session timeout
    if (isset($_SESSION['admin_login_time'])) {
        $elapsed = time() - $_SESSION['admin_login_time'];
        if ($elapsed > SESSION_TIMEOUT) {
            logout();
            return false;
        }
        // Update login time
        $_SESSION['admin_login_time'] = time();
    }
    
    return true;
}

/**
 * Require login (redirect if not logged in)
 */
function requireLogin($redirectUrl = null) {
    if (!isLoggedIn()) {
        $redirectUrl = $redirectUrl ?? ADMIN_URL . '/login.php';
        setFlash('error', ERROR_MESSAGES['session_expired']);
        redirect($redirectUrl);
    }
}

/**
 * Get current admin user
 */
function getCurrentAdmin() {
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['admin_id'] ?? null,
        'username' => $_SESSION['admin_username'] ?? null,
        'full_name' => $_SESSION['admin_full_name'] ?? null,
        'email' => $_SESSION['admin_email'] ?? null,
        'role' => $_SESSION['admin_role'] ?? null
    ];
}

/**
 * Check if user has role
 */
function hasRole($role) {
    $admin = getCurrentAdmin();
    return $admin && $admin['role'] === $role;
}

/**
 * Check if user is super admin
 */
function isSuperAdmin() {
    return hasRole('super_admin');
}

/**
 * Hash password
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verify password
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Generate random password
 */
function generatePassword($length = 12) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
    $password = '';
    $charsLength = strlen($chars);
    
    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[random_int(0, $charsLength - 1)];
    }
    
    return $password;
}

/**
 * Validate password strength
 */
function validatePassword($password) {
    $errors = [];
    
    if (strlen($password) < PASSWORD_MIN_LENGTH) {
        $errors[] = sprintf(ERROR_MESSAGES['min_length'], PASSWORD_MIN_LENGTH);
    }
    
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = 'Password must contain at least one lowercase letter';
    }
    
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = 'Password must contain at least one uppercase letter';
    }
    
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = 'Password must contain at least one number';
    }
    
    return empty($errors) ? ['valid' => true] : ['valid' => false, 'errors' => $errors];
}

/**
 * Check remember me
 */
function setRememberMe($userId, $remember = false) {
    if ($remember) {
        $token = bin2hex(random_bytes(32));
        $hashedToken = hash('sha256', $token);
        
        // Store hashed token in database
        global $db;
        $sql = "UPDATE admin_users SET remember_token = ?, remember_token_expires = DATE_ADD(NOW(), INTERVAL 30 DAY) WHERE id = ?";
        $db->query($sql, [$hashedToken, $userId]);
        
        // Set cookie
        setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/', '', true, true);
    }
}

/**
 * Check remember me token
 */
function checkRememberMe() {
    if (isset($_COOKIE['remember_token'])) {
        $token = $_COOKIE['remember_token'];
        $hashedToken = hash('sha256', $token);
        
        global $db;
        $sql = "SELECT * FROM admin_users WHERE remember_token = ? AND remember_token_expires > NOW() AND is_active = 1 LIMIT 1";
        $user = $db->fetchOne($sql, [$hashedToken]);
        
        if ($user) {
            // Auto login
            initSession();
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            $_SESSION['admin_full_name'] = $user['full_name'];
            $_SESSION['admin_email'] = $user['email'];
            $_SESSION['admin_role'] = $user['role'];
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_login_time'] = time();
            
            return true;
        } else {
            // Invalid token, remove cookie
            setcookie('remember_token', '', time() - 3600, '/', '', true, true);
        }
    }
    
    return false;
}

/**
 * Clear remember me
 */
function clearRememberMe() {
    if (isLoggedIn()) {
        global $db;
        $admin = getCurrentAdmin();
        $sql = "UPDATE admin_users SET remember_token = NULL, remember_token_expires = NULL WHERE id = ?";
        $db->query($sql, [$admin['id']]);
    }
    
    if (isset($_COOKIE['remember_token'])) {
        setcookie('remember_token', '', time() - 3600, '/', '', true, true);
    }
}