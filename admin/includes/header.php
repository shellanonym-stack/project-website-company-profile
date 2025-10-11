<?php
/**
 * Admin Header
 * PT Komodo Industrial Indonesia
 */

if (!defined('APP_ACCESS')) {
    die('Direct access not permitted');
}

$currentAdmin = getCurrentAdmin();
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' | ' : ''; ?>Admin Panel | <?php echo APP_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .sidebar-link {
            transition: all 0.2s;
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }
        
        .sidebar-link:hover {
            background-color: rgba(34, 197, 94, 0.1);
            border-left-color: #22c55e;
        }
        
        .sidebar-link.active {
            background-color: rgba(34, 197, 94, 0.1);
            border-left-color: #22c55e;
            color: #22c55e;
        }

        /* Improved admin navbar */
        .admin-navbar {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }

        .admin-logo {
            font-size: 1.25rem;
        }

        .user-menu {
            font-size: 0.9rem;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Top Navigation -->
    <nav class="bg-white shadow-sm fixed w-full z-40 admin-navbar">
        <div class="px-6">
            <div class="flex items-center justify-between">
                <!-- Logo & Menu Toggle -->
                <div class="flex items-center space-x-4">
                    <button id="sidebarToggle" class="lg:hidden text-gray-600 hover:text-gray-900">
                        <i class="fas fa-bars"></i>
                    </button>
                    <a href="<?php echo ADMIN_URL; ?>/index.php" class="admin-logo font-bold">
                        <span class="text-green-600">KII</span> <span class="text-gray-800">Admin</span>
                    </a>
                </div>

                <!-- Right Menu -->
                <div class="flex items-center space-x-4 user-menu">
                    <!-- Website Link -->
                    <a href="<?php echo PUBLIC_URL; ?>/index.php" 
                       target="_blank"
                       class="text-gray-600 hover:text-gray-900 p-2"
                       title="View Website">
                        <i class="fas fa-external-link-alt"></i>
                    </a>

                    <!-- Notifications -->
                    <div class="relative">
                        <button class="text-gray-600 hover:text-gray-900 relative p-2">
                            <i class="fas fa-bell"></i>
                            <?php if ($stats['unread_contacts'] ?? 0 > 0): ?>
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center text-[10px]">
                                    <?php echo min($stats['unread_contacts'], 9); ?>
                                </span>
                            <?php endif; ?>
                        </button>
                    </div>

                    <!-- User Menu -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 p-2">
                            <div class="w-7 h-7 bg-green-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                <?php echo strtoupper(substr($currentAdmin['full_name'], 0, 1)); ?>
                            </div>
                            <span class="hidden md:block font-medium text-sm"><?php echo htmlspecialchars($currentAdmin['full_name']); ?></span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 border border-gray-200 z-50">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user mr-2 text-xs"></i> Profile
                            </a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-cog mr-2 text-xs"></i> Settings
                            </a>
                            <hr class="my-2">
                            <a href="<?php echo ADMIN_URL; ?>/logout.php" 
                               class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50"
                               onclick="return confirm('Are you sure you want to logout?')">
                                <i class="fas fa-sign-out-alt mr-2 text-xs"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="flex pt-14">
        <!-- Sidebar -->
        <aside id="sidebar" 
               class="fixed lg:static inset-y-0 left-0 w-64 bg-white shadow-lg transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-30 mt-14 lg:mt-0">
            <div class="h-full overflow-y-auto">
                <!-- Navigation -->
                <nav class="p-4">
                    <div class="space-y-1">
                        <!-- Dashboard -->
                        <a href="<?php echo ADMIN_URL; ?>/index.php" 
                           class="sidebar-link flex items-center space-x-3 px-4 py-3 text-gray-700 rounded-lg border-l-4 border-transparent <?php echo $currentPage === 'index' ? 'active' : ''; ?>">
                            <i class="fas fa-home w-4"></i>
                            <span class="text-sm">Dashboard</span>
                        </a>

                        <!-- Products -->
                        <div class="mt-4">
                            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Products</p>
                            <a href="<?php echo ADMIN_URL; ?>/modules/products/index.php" 
                               class="sidebar-link flex items-center space-x-3 px-4 py-3 text-gray-700 rounded-lg border-l-4 border-transparent <?php echo strpos($_SERVER['PHP_SELF'], 'products') !== false ? 'active' : ''; ?>">
                                <i class="fas fa-box w-4"></i>
                                <span class="text-sm">All Products</span>
                            </a>
                            <a href="<?php echo ADMIN_URL; ?>/modules/products/create.php" 
                               class="sidebar-link flex items-center space-x-3 px-4 py-3 text-gray-700 rounded-lg border-l-4 border-transparent">
                                <i class="fas fa-plus w-4"></i>
                                <span class="text-sm">Add Product</span>
                            </a>
                        </div>

                        <!-- Messages -->
                        <div class="mt-4">
                            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Messages</p>
                            <a href="<?php echo ADMIN_URL; ?>/modules/contacts/index.php" 
                               class="sidebar-link flex items-center space-x-3 px-4 py-3 text-gray-700 rounded-lg border-l-4 border-transparent <?php echo strpos($_SERVER['PHP_SELF'], 'contacts') !== false ? 'active' : ''; ?>">
                                <i class="fas fa-envelope w-4"></i>
                                <span class="text-sm">All Messages</span>
                                <?php if (isset($stats['unread_contacts']) && $stats['unread_contacts'] > 0): ?>
                                    <span class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-1 text-[10px]">
                                        <?php echo $stats['unread_contacts']; ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        </div>
                    </div>
                </nav>

                <!-- Sidebar Footer -->
                <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200 bg-gray-50">
                    <div class="text-xs text-gray-500 text-center">
                        <p class="font-semibold"><?php echo APP_SHORT_NAME; ?> v<?php echo APP_VERSION; ?></p>
                        <p>&copy; <?php echo date('Y'); ?> All rights reserved</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Sidebar Overlay for Mobile -->
        <div id="sidebarOverlay" 
             class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden hidden"
             onclick="toggleSidebar()"></div>

        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden">
            <?php displayFlash(); ?>