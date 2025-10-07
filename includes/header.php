<?php
// includes/header.php
$current_page = basename($_SERVER['PHP_SELF']);
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
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="antialiased">
    <!-- Navigation -->
    <nav class="fixed w-full z-50 bg-black bg-opacity-90 backdrop-blur-sm">
        <div class="container mx-auto px-6 py-2 flex justify-between items-center">
            <a href="index.php" class="text-4xl font-bold text-white">KII</a>
            <div class="hidden md:flex space-x-8">
                <a href="index.php" class="menu-item <?php echo $current_page == 'index.php' ? 'text-green-500 font-bold' : 'text-white hover:text-green-500'; ?>">Home</a>
                <a href="products.php" class="menu-item <?php echo $current_page == 'products.php' ? 'text-green-500 font-bold' : 'text-white hover:text-green-500'; ?>">Products</a>
                <a href="about.php" class="menu-item <?php echo $current_page == 'about.php' ? 'text-green-500 font-bold' : 'text-white hover:text-green-500'; ?>">About</a>
                <a href="contact.php" class="menu-item <?php echo $current_page == 'contact.php' ? 'text-green-500 font-bold' : 'text-white hover:text-green-500'; ?>">Contact</a>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex border border-gray-700 rounded">
                    <a href="#" class="lang-switch px-2 py-1 bg-green-600 text-white" data-lang="en">EN</a>
                    <a href="#" class="lang-switch px-2 py-1 hover:bg-gray-800 text-white" data-lang="id">ID</a>
                </div>
                <button id="mobile-menu-button" class="md:hidden text-white focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="mobile-menu md:hidden absolute top-full left-0 w-full bg-black bg-opacity-95 backdrop-blur-sm p-4 flex flex-col space-y-4">
            <a href="index.php" class="menu-item <?php echo $current_page == 'index.php' ? 'text-green-500 font-bold' : 'text-white hover:text-green-500'; ?> py-2">Home</a>
            <a href="products.php" class="menu-item <?php echo $current_page == 'products.php' ? 'text-green-500 font-bold' : 'text-white hover:text-green-500'; ?> py-2">Products</a>
            <a href="about.php" class="menu-item <?php echo $current_page == 'about.php' ? 'text-green-500 font-bold' : 'text-white hover:text-green-500'; ?> py-2">About</a>
            <a href="contact.php" class="menu-item <?php echo $current_page == 'contact.php' ? 'text-green-500 font-bold' : 'text-white hover:text-green-500'; ?> py-2">Contact</a>
        </div>
    </nav>