<?php
// admin/index.php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Authentication check
function checkAuth() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: login.php');
        exit;
    }
}

// Handle login
if ($_POST['action'] ?? '' === 'login') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        $stmt = $db->prepare("SELECT * FROM admin_users WHERE username = ? AND is_active = 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            $_SESSION['admin_fullname'] = $user['full_name'];
            
            // Update last login
            $stmt = $db->prepare("UPDATE admin_users SET last_login = NOW() WHERE id = ?");
            $stmt->execute([$user['id']]);
            
            header('Location: index.php');
            exit;
        } else {
            $login_error = 'Invalid username or password';
        }
    } catch (Exception $e) {
        $login_error = 'Database error: ' . $e->getMessage();
    }
}

// Check if user is logged in
$is_logged_in = $_SESSION['admin_logged_in'] ?? false;

if (!$is_logged_in && ($_POST['action'] ?? '') !== 'login') {
    header('Location: login.php');
    exit;
}

// Get statistics for dashboard
if ($is_logged_in) {
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        // Total products
        $stmt = $db->query("SELECT COUNT(*) as total FROM products WHERE is_active = 1");
        $total_products = $stmt->fetch()['total'];
        
        // Total contacts
        $stmt = $db->query("SELECT COUNT(*) as total FROM contacts");
        $total_contacts = $stmt->fetch()['total'];
        
        // Unread messages
        $stmt = $db->query("SELECT COUNT(*) as total FROM contacts WHERE is_read = 0");
        $unread_messages = $stmt->fetch()['total'];
        
        // Featured products
        $stmt = $db->query("SELECT COUNT(*) as total FROM products WHERE is_featured = 1 AND is_active = 1");
        $featured_products = $stmt->fetch()['total'];
        
    } catch (Exception $e) {
        error_log("Dashboard error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - PT KOMODO INDUSTRIAL INDONESIA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-gray-800 text-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <h1 class="text-xl font-bold">KOMODO INDUSTRIAL - Admin</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-300">Welcome, <?php echo htmlspecialchars($_SESSION['admin_fullname']); ?></span>
                    <a href="logout.php" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i class="fas fa-box text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Total Products</h3>
                        <p class="text-2xl font-semibold text-gray-900"><?php echo $total_products; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <i class="fas fa-envelope text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Contact Messages</h3>
                        <p class="text-2xl font-semibold text-gray-900"><?php echo $total_contacts; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <i class="fas fa-star text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Featured Products</h3>
                        <p class="text-2xl font-semibold text-gray-900"><?php echo $featured_products; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-red-100 rounded-lg">
                        <i class="fas fa-bell text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Unread Messages</h3>
                        <p class="text-2xl font-semibold text-gray-900"><?php echo $unread_messages; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <a href="products.php" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i class="fas fa-plus text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Manage Products</h3>
                        <p class="text-gray-500">Add, edit, or remove products</p>
                    </div>
                </div>
            </a>
            
            <a href="contacts.php" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <i class="fas fa-envelope-open-text text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">View Messages</h3>
                        <p class="text-gray-500">Check contact form submissions</p>
                    </div>
                </div>
            </a>
            
            <a href="content.php" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <i class="fas fa-edit text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Manage Content</h3>
                        <p class="text-gray-500">Edit website content</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Recent Contact Messages</h2>
            </div>
            <div class="p-6">
                <?php
                try {
                    $stmt = $db->prepare("
                        SELECT name, email, subject, message, created_at, is_read 
                        FROM contacts 
                        ORDER BY created_at DESC 
                        LIMIT 5
                    ");
                    $stmt->execute();
                    $recent_contacts = $stmt->fetchAll();
                    
                    if (empty($recent_contacts)) {
                        echo '<p class="text-gray-500 text-center py-4">No recent messages</p>';
                    } else {
                        foreach ($recent_contacts as $contact) {
                            $message_preview = strlen($contact['message']) > 100 
                                ? substr($contact['message'], 0, 100) . '...' 
                                : $contact['message'];
                            ?>
                            <div class="border-b border-gray-200 py-4 last:border-b-0">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900">
                                            <?php echo htmlspecialchars($contact['name']); ?>
                                            <?php if (!$contact['is_read']): ?>
                                                <span class="inline-block bg-red-100 text-red-800 text-xs px-2 py-1 rounded ml-2">New</span>
                                            <?php endif; ?>
                                        </h4>
                                        <p class="text-sm text-gray-600"><?php echo htmlspecialchars($contact['email']); ?></p>
                                        <p class="text-sm text-gray-800 mt-1"><?php echo htmlspecialchars($message_preview); ?></p>
                                    </div>
                                    <div class="text-right text-sm text-gray-500">
                                        <?php echo date('M j, Y', strtotime($contact['created_at'])); ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                } catch (Exception $e) {
                    echo '<p class="text-red-500 text-center py-4">Error loading recent messages</p>';
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>