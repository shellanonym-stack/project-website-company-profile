<?php
// includes/functions.php

/**
 * Redirect to another page
 */
function redirect($url) {
    header("Location: " . $url);
    exit;
}

/**
 * Sanitize input data
 */
function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Check if user is logged in as admin
 */
function is_admin_logged_in() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

/**
 * Require admin authentication
 */
function require_admin_auth() {
    if (!is_admin_logged_in()) {
        redirect('login.php');
    }
}

/**
 * Format price with currency
 */
function format_price($price) {
    return '$' . number_format($price, 2);
}

/**
 * Get featured products
 */
function get_featured_products($conn, $limit = 6) {
    $products = [];
    $sql = "SELECT * FROM products WHERE featured = 1 AND status = 'active' LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    
    return $products;
}

/**
 * Get all active products
 */
function get_all_products($conn) {
    $products = [];
    $sql = "SELECT * FROM products WHERE status = 'active' ORDER BY created_at DESC";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
    
    return $products;
}

/**
 * Upload image and return filename
 */
function upload_image($file, $upload_dir = '../uploads/') {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $max_size = 5 * 1024 * 1024; // 5MB
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'File upload error'];
    }
    
    if (!in_array($file['type'], $allowed_types)) {
        return ['success' => false, 'error' => 'Invalid file type. Only JPG, PNG, GIF, and WEBP are allowed.'];
    }
    
    if ($file['size'] > $max_size) {
        return ['success' => false, 'error' => 'File too large. Maximum size is 5MB.'];
    }
    
    // Generate unique filename
    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $file_extension;
    $filepath = $upload_dir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => true, 'filename' => $filename];
    } else {
        return ['success' => false, 'error' => 'Failed to move uploaded file.'];
    }
}

/**
 * Delete image file
 */
function delete_image($filename, $upload_dir = '../uploads/') {
    if (!empty($filename) && file_exists($upload_dir . $filename)) {
        return unlink($upload_dir . $filename);
    }
    return false;
}

/**
 * Display success message
 */
function display_success($message) {
    if (!empty($message)) {
        echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">' . htmlspecialchars($message) . '</div>';
    }
}

/**
 * Display error message
 */
function display_error($message) {
    if (!empty($message)) {
        echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">' . htmlspecialchars($message) . '</div>';
    }
}

/**
 * Get contact form submissions
 */
function get_contact_submissions($conn, $limit = 50) {
    $contacts = [];
    $sql = "SELECT * FROM contacts ORDER BY created_at DESC LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while($row = $result->fetch_assoc()) {
        $contacts[] = $row;
    }
    
    return $contacts;
}

/**
 * Get dashboard statistics
 */
function get_dashboard_stats($conn) {
    $stats = [];
    
    // Total products
    $sql = "SELECT COUNT(*) as total FROM products WHERE status = 'active'";
    $result = $conn->query($sql);
    $stats['total_products'] = $result ? $result->fetch_assoc()['total'] : 0;
    
    // Total contacts
    $sql = "SELECT COUNT(*) as total FROM contacts";
    $result = $conn->query($sql);
    $stats['total_contacts'] = $result ? $result->fetch_assoc()['total'] : 0;
    
    // Featured products
    $sql = "SELECT COUNT(*) as total FROM products WHERE featured = 1 AND status = 'active'";
    $result = $conn->query($sql);
    $stats['featured_products'] = $result ? $result->fetch_assoc()['total'] : 0;
    
    // Recent contacts (last 7 days)
    $sql = "SELECT COUNT(*) as total FROM contacts WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
    $result = $conn->query($sql);
    $stats['recent_contacts'] = $result ? $result->fetch_assoc()['total'] : 0;
    
    return $stats;
}
?>