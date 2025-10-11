<?php
/**
 * Common Functions
 * PT Komodo Industrial Indonesia
 */

// Prevent direct access
if (!defined('APP_ACCESS')) {
    die('Direct access not permitted');
}

/**
 * Sanitize input data
 */
function sanitize($data) {
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = sanitize($value);
        }
        return $data;
    }
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Redirect to a URL
 */
function redirect($url, $statusCode = 302) {
    header('Location: ' . $url, true, $statusCode);
    exit;
}

/**
 * Check if request is POST
 */
function isPost() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

/**
 * Check if request is GET
 */
function isGet() {
    return $_SERVER['REQUEST_METHOD'] === 'GET';
}

/**
 * Get POST data
 */
function post($key, $default = null) {
    return isset($_POST[$key]) ? sanitize($_POST[$key]) : $default;
}

/**
 * Get GET data
 */
function get($key, $default = null) {
    return isset($_GET[$key]) ? sanitize($_GET[$key]) : $default;
}

/**
 * Set flash message
 */
function setFlash($type, $message) {
    if (!isset($_SESSION)) {
        session_start();
    }
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Get and clear flash message
 */
function getFlash() {
    if (!isset($_SESSION)) {
        session_start();
    }
    
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Display flash message HTML
 */
function displayFlash() {
    $flash = getFlash();
    if ($flash) {
        $alertClass = $flash['type'] === 'success' ? 'bg-green-100 border-green-500 text-green-700' : 'bg-red-100 border-red-500 text-red-700';
        echo '<div class="' . $alertClass . ' border-l-4 p-4 mb-4 rounded" role="alert">';
        echo '<p class="font-medium">' . htmlspecialchars($flash['message']) . '</p>';
        echo '</div>';
    }
}

/**
 * Format date
 */
function formatDate($date, $format = DISPLAY_DATE_FORMAT) {
    if (empty($date)) return '-';
    return date($format, strtotime($date));
}

/**
 * Format datetime
 */
function formatDateTime($datetime, $format = DISPLAY_DATETIME_FORMAT) {
    if (empty($datetime)) return '-';
    return date($format, strtotime($datetime));
}

/**
 * Get time ago
 */
function timeAgo($datetime) {
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;
    
    if ($diff < 60) {
        return $diff . ' seconds ago';
    } elseif ($diff < 3600) {
        return floor($diff / 60) . ' minutes ago';
    } elseif ($diff < 86400) {
        return floor($diff / 3600) . ' hours ago';
    } elseif ($diff < 604800) {
        return floor($diff / 86400) . ' days ago';
    } else {
        return formatDateTime($datetime);
    }
}

/**
 * Generate CSRF token
 */
function generateCsrfToken() {
    if (!isset($_SESSION)) {
        session_start();
    }
    if (empty($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(CSRF_TOKEN_LENGTH));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

/**
 * Verify CSRF token
 */
function verifyCsrfToken($token) {
    if (!isset($_SESSION)) {
        session_start();
    }
    return isset($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

/**
 * Upload file dengan perbaikan path
 */
function uploadFile($file, $destination, $allowedTypes = ALLOWED_IMAGE_TYPES, $maxSize = UPLOAD_MAX_SIZE) {
    // Check if file was uploaded
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'No file uploaded or upload error'];
    }
    
    // Check file size
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'error' => 'File size exceeds maximum allowed size'];
    }
    
    // Check file type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mimeType, $allowedTypes)) {
        return ['success' => false, 'error' => 'Invalid file type'];
    }
    
    // Check file extension
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, ALLOWED_IMAGE_EXTENSIONS)) {
        return ['success' => false, 'error' => 'Invalid file extension'];
    }
    
    // Generate unique filename
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $filepath = $destination . '/' . $filename;
    
    // Create directory if not exists
    if (!file_exists($destination)) {
        mkdir($destination, 0755, true);
    }
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return [
            'success' => true, 
            'filename' => $filename, 
            'filepath' => $filepath,
            'url' => getImageUrlFromPath($filepath)
        ];
    }
    
    return ['success' => false, 'error' => 'Failed to move uploaded file'];
}

/**
 * Get image URL from file path
 */
function getImageUrlFromPath($filepath) {
    // Convert absolute path to URL
    $relativePath = str_replace(ROOT_PATH, '', $filepath);
    $relativePath = str_replace('\\', '/', $relativePath);
    return BASE_URL . $relativePath;
}

/**
 * Delete file
 */
function deleteFile($filepath) {
    if (file_exists($filepath)) {
        return unlink($filepath);
    }
    return false;
}

/**
 * Get client IP address
 */
function getClientIp() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

/**
 * Get user agent
 */
function getUserAgent() {
    return $_SERVER['HTTP_USER_AGENT'] ?? '';
}

/**
 * Validate email
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate phone number (Indonesian format)
 */
function isValidPhone($phone) {
    $phone = preg_replace('/[^0-9]/', '', $phone);
    return preg_match('/^(08|628|\+628)[0-9]{8,11}$/', $phone);
}

/**
 * Truncate text
 */
function truncate($text, $length = 100, $suffix = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . $suffix;
}

/**
 * Slugify string
 */
function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return empty($text) ? 'n-a' : $text;
}

/**
 * Debug print
 */
function debug($data, $die = false) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    if ($die) die();
}

/**
 * Get pagination data
 */
function getPaginationData($totalItems, $currentPage = 1, $itemsPerPage = ITEMS_PER_PAGE) {
    $totalPages = ceil($totalItems / $itemsPerPage);
    $currentPage = max(1, min($currentPage, $totalPages));
    $offset = ($currentPage - 1) * $itemsPerPage;
    
    return [
        'total_items' => $totalItems,
        'total_pages' => $totalPages,
        'current_page' => $currentPage,
        'items_per_page' => $itemsPerPage,
        'offset' => $offset,
        'has_prev' => $currentPage > 1,
        'has_next' => $currentPage < $totalPages
    ];
}

/**
 * Render pagination HTML
 */
function renderPagination($pagination, $baseUrl) {
    if ($pagination['total_pages'] <= 1) return '';
    
    $html = '<div class="flex justify-center mt-8">';
    $html .= '<nav class="inline-flex rounded-md shadow">';
    
    // Previous button
    if ($pagination['has_prev']) {
        $html .= '<a href="' . $baseUrl . '?page=' . ($pagination['current_page'] - 1) . '" class="px-3 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">Previous</a>';
    }
    
    // Page numbers
    for ($i = 1; $i <= $pagination['total_pages']; $i++) {
        $activeClass = $i === $pagination['current_page'] ? 'bg-green-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50';
        $html .= '<a href="' . $baseUrl . '?page=' . $i . '" class="px-4 py-2 border border-gray-300 text-sm font-medium ' . $activeClass . '">' . $i . '</a>';
    }
    
    // Next button
    if ($pagination['has_next']) {
        $html .= '<a href="' . $baseUrl . '?page=' . ($pagination['current_page'] + 1) . '" class="px-3 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">Next</a>';
    }
    
    $html .= '</nav>';
    $html .= '</div>';
    
    return $html;
}

/**
 * Get current URL
 */
function currentUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    return $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

/**
 * Check if current page is active
 */
function isActivePage($page) {
    $currentUrl = $_SERVER['REQUEST_URI'];
    return strpos($currentUrl, $page) !== false;
}

/**
 * Get default product image
 */
function getDefaultProductImage() {
    return IMAGES_URL . '/default-product.jpg';
}

/**
 * Check if image exists
 */
function imageExists($imageUrl) {
    if (empty($imageUrl)) return false;
    
    // Check if it's a local file
    if (strpos($imageUrl, BASE_URL) === 0) {
        $localPath = str_replace(BASE_URL, ROOT_PATH, $imageUrl);
        return file_exists($localPath);
    }
    
    // For external URLs, we assume they exist
    return true;
}
