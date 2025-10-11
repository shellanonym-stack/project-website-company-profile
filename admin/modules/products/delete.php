<?php
/**
 * Products Management - Delete
 * PT Komodo Industrial Indonesia
 */

define('APP_ACCESS', true);

require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../includes/auth.php';

requireLogin();

$productId = (int)get('id', 0);

// Get product
$product = $db->fetchOne("SELECT * FROM products WHERE id = ?", [$productId]);

if (!$product) {
    setFlash('error', 'Product not found');
    redirect(ADMIN_URL . '/modules/products/index.php');
}

// Delete product
$sql = "DELETE FROM products WHERE id = ?";
if ($db->query($sql, [$productId])) {
    // Delete image file if it's a local upload
    if (strpos($product['image'], PRODUCTS_IMAGES_URL) !== false) {
        $imageFile = UPLOADS_PATH . '/products/' . basename($product['image']);
        deleteFile($imageFile);
    }
    
    setFlash('success', 'Product deleted successfully!');
} else {
    setFlash('error', 'Failed to delete product');
}

redirect(ADMIN_URL . '/modules/products/index.php');