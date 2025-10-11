<?php
// includes/header.php
$current_page = basename($_SERVER['PHP_SELF']);

// Handle session start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PT KOMODO INDUSTRIAL INDONESIA - Premium Stainless Cookware Manufacturer">
    <title>
        <?php 
        $page_titles = [
            'index.php' => 'PT KOMODO INDUSTRIAL INDONESIA | Premium Stainless Cookware',
            'about.php' => 'About Us - PT KOMODO INDUSTRIAL INDONESIA',
            'products.php' => 'Our Products - PT KOMODO INDUSTRIAL INDONESIA',
            'contact.php' => 'Contact Us - PT KOMODO INDUSTRIAL INDONESIA'
        ];
        echo $page_titles[$current_page] ?? 'PT KOMODO INDUSTRIAL INDONESIA';
        ?>
    </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #000000;
            color: #ffffff;
            overflow-x: hidden;
        }
        
        .hero-text {
            font-size: clamp(2rem, 8vw, 8rem);
            line-height: 0.9;
        }
        
        .menu-item {
            position: relative;
            font-size: 0.95rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }
        
        .menu-item::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: #22c55e;
            transition: width 0.3s ease;
        }
        
        .menu-item:hover::after {
            width: 100%;
        }
        
        .project-card {
            transition: transform 0.3s ease;
        }
        
        .project-card:hover {
            transform: translateY(-10px);
        }
        
        .scroll-indicator {
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }

        /* Custom utilities untuk layout yang lebih baik */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Pastikan images responsive */
        img {
            max-width: 100%;
            height: auto;
        }

        /* Product card consistency */
        .product-card {
            transition: all 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        /* Improved navbar styles */
        .navbar-logo {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .nav-container {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }

        .mobile-menu-btn {
            font-size: 1.25rem;
        }

        .lang-switcher {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }
    </style>
</head>
<body class="antialiased bg-black">
    <!-- Fixed Navigation -->
    <nav class="fixed-header w-full z-50 bg-black bg-opacity-95 backdrop-blur-sm border-b border-gray-800">
        <div class="container mx-auto px-6 nav-container">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <a href="index.php" class="navbar-logo text-white flex items-center">
                    <span class="text-green-500">KOMODO</span>
                    <span class="ml-1">INDUSTRIAL</span>
                </a>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="index.php" class="menu-item <?php echo $current_page == 'index.php' ? 'text-green-500 font-semibold' : 'text-white hover:text-green-500'; ?>">Home</a>
                    <a href="products.php" class="menu-item <?php echo $current_page == 'products.php' ? 'text-green-500 font-semibold' : 'text-white hover:text-green-500'; ?>">Products</a>
                    <a href="about.php" class="menu-item <?php echo $current_page == 'about.php' ? 'text-green-500 font-semibold' : 'text-white hover:text-green-500'; ?>">About</a>
                    <a href="contact.php" class="menu-item <?php echo $current_page == 'contact.php' ? 'text-green-500 font-semibold' : 'text-white hover:text-green-500'; ?>">Contact</a>
                    
                    <!-- Language Switch -->
                    <div class="flex border border-gray-700 rounded overflow-hidden lang-switcher">
                        <button class="lang-switch px-3 py-1 bg-green-600 text-white" data-lang="en">EN</button>
                        <button class="lang-switch px-3 py-1 bg-gray-800 text-white hover:bg-gray-700" data-lang="id">ID</button>
                    </div>
                </div>
                
                <!-- Mobile Menu Button -->
                <div class="flex items-center space-x-4 md:hidden">
                    <div class="flex border border-gray-700 rounded overflow-hidden lang-switcher">
                        <button class="lang-switch px-2 py-1 bg-green-600 text-white text-xs" data-lang="en">EN</button>
                        <button class="lang-switch px-2 py-1 bg-gray-800 text-white text-xs hover:bg-gray-700" data-lang="id">ID</button>
                    </div>
                    <button id="mobile-menu-button" class="mobile-menu-btn text-white focus:outline-none">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="mobile-menu md:hidden bg-black bg-opacity-95 backdrop-blur-sm border-t border-gray-800 px-6 py-4 hidden">
            <div class="flex flex-col space-y-3">
                <a href="index.php" class="menu-item <?php echo $current_page == 'index.php' ? 'text-green-500 font-semibold' : 'text-white hover:text-green-500'; ?> py-2 text-center">Home</a>
                <a href="products.php" class="menu-item <?php echo $current_page == 'products.php' ? 'text-green-500 font-semibold' : 'text-white hover:text-green-500'; ?> py-2 text-center">Products</a>
                <a href="about.php" class="menu-item <?php echo $current_page == 'about.php' ? 'text-green-500 font-semibold' : 'text-white hover:text-green-500'; ?> py-2 text-center">About</a>
                <a href="contact.php" class="menu-item <?php echo $current_page == 'contact.php' ? 'text-green-500 font-semibold' : 'text-white hover:text-green-500'; ?> py-2 text-center">Contact</a>
            </div>
        </div>
    </nav>

    <!-- Main Content Wrapper -->
    <div class="content-wrapper min-h-screen pt-16">