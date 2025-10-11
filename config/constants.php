<?php
/**
 * Application Constants
 * PT Komodo Industrial Indonesia
 */

// Prevent direct access
if (!defined('APP_ACCESS')) {
    die('Direct access not permitted');
}

// Application Info
define('APP_NAME', 'PT Komodo Industrial Indonesia');
define('APP_SHORT_NAME', 'KII');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost/komodo-industrial');

// Path Constants
define('ROOT_PATH', dirname(__DIR__));
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('ADMIN_PATH', ROOT_PATH . '/admin');
define('INCLUDES_PATH', ROOT_PATH . '/includes');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('ASSETS_PATH', ROOT_PATH . '/assets');
define('UPLOADS_PATH', ASSETS_PATH . '/uploads');
define('PRODUCTS_IMAGES_PATH', UPLOADS_PATH . '/products');
define('IMAGES_PATH', ASSETS_PATH . '/images');
define('SRC_PATH', ROOT_PATH . '/src');

// URL Constants
define('BASE_URL', APP_URL);
define('PUBLIC_URL', BASE_URL . '/public');
define('ADMIN_URL', BASE_URL . '/admin');
define('ASSETS_URL', BASE_URL . '/assets');
define('UPLOADS_URL', ASSETS_URL . '/uploads');
define('PRODUCTS_IMAGES_URL', UPLOADS_URL . '/products');
define('IMAGES_URL', ASSETS_URL . '/images');

// Upload Settings
define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp']);
define('ALLOWED_IMAGE_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'webp']);

// Session Settings
define('SESSION_NAME', 'KII_ADMIN_SESSION');
define('SESSION_TIMEOUT', 3600); // 1 hour

// Pagination
define('ITEMS_PER_PAGE', 10);
define('PRODUCTS_PER_PAGE', 12);

// Date Format
define('DATE_FORMAT', 'Y-m-d H:i:s');
define('DISPLAY_DATE_FORMAT', 'd F Y');
define('DISPLAY_DATETIME_FORMAT', 'd F Y, H:i');

// Security
define('CSRF_TOKEN_NAME', '_csrf_token');
define('CSRF_TOKEN_LENGTH', 32);
define('PASSWORD_MIN_LENGTH', 6);

// Default Language
define('DEFAULT_LANG', 'en');
define('AVAILABLE_LANGUAGES', ['en', 'id']);

// Email Settings (untuk future development)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASS', 'your-password');
define('SMTP_FROM', 'noreply@komodoindustrial.com');
define('SMTP_FROM_NAME', 'PT Komodo Industrial Indonesia');

// Product Categories
define('PRODUCT_CATEGORIES', [
    'basin' => ['en' => 'Basin Series', 'id' => 'Seri Baskom'],
    'spoon' => ['en' => 'Spoon Series', 'id' => 'Seri Sendok'],
    'knife' => ['en' => 'Knife Series', 'id' => 'Seri Pisau'],
    'wok' => ['en' => 'Wok Series', 'id' => 'Seri Kuali'],
    'pot' => ['en' => 'Pot Series', 'id' => 'Seri Panci'],
    'pan' => ['en' => 'Pan Series', 'id' => 'Seri Penggorengan'],
    'other' => ['en' => 'Other', 'id' => 'Lainnya']
]);

// Contact Subject Types
define('CONTACT_SUBJECTS', [
    'product' => ['en' => 'Product Inquiry', 'id' => 'Pertanyaan Produk'],
    'wholesale' => ['en' => 'Wholesale Inquiry', 'id' => 'Pertanyaan Grosir'],
    'custom' => ['en' => 'Custom Order', 'id' => 'Pesanan Khusus'],
    'other' => ['en' => 'Other', 'id' => 'Lainnya']
]);

// Error Messages
define('ERROR_MESSAGES', [
    'required' => 'This field is required',
    'email' => 'Please enter a valid email address',
    'min_length' => 'Minimum length is %d characters',
    'max_length' => 'Maximum length is %d characters',
    'invalid_file' => 'Invalid file type',
    'file_too_large' => 'File size exceeds maximum allowed size',
    'upload_failed' => 'File upload failed',
    'database_error' => 'Database error occurred',
    'unauthorized' => 'Unauthorized access',
    'not_found' => 'Resource not found',
    'invalid_credentials' => 'Invalid username or password',
    'session_expired' => 'Your session has expired. Please login again.'
]);

// Success Messages
define('SUCCESS_MESSAGES', [
    'login' => 'Login successful',
    'logout' => 'Logout successful',
    'created' => 'Record created successfully',
    'updated' => 'Record updated successfully',
    'deleted' => 'Record deleted successfully',
    'message_sent' => 'Your message has been sent successfully'
]);

// Timezone
date_default_timezone_set('Asia/Jakarta');

// Error Reporting (Change to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', ROOT_PATH . '/logs/error.log');