<?php
/**
 * Frontend Homepage
 * PT Komodo Industrial Indonesia
 */

define('APP_ACCESS', true);

// Load configurations
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// Get featured products from database
$featuredProducts = $db->fetchAll(
    "SELECT * FROM products WHERE is_active = 1 AND is_featured = 1 ORDER BY display_order ASC LIMIT 4"
);

// Page title
$pageTitle = "Premium Stainless Cookware";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> | <?php echo $pageTitle; ?></title>
    <meta name="description" content="Focusing on the production of stainless steel tableware and cooking utensils.">
    <meta name="keywords" content="stainless steel, cookware, tableware, kitchen utensils">
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
    </style>
</head>
<body class="antialiased">
    <!-- Navigation -->
    <nav class="fixed w-full z-50 bg-black bg-opacity-90 backdrop-blur-sm">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="index.php" class="text-4xl font-bold text-white">KII</a>
            <div class="hidden md:flex space-x-8 items-center">
                <a href="index.php" class="menu-item text-white hover:text-green-500" data-en="Home" data-id="Beranda">Home</a>
                <a href="products.php" class="menu-item text-white hover:text-green-500" data-en="Products" data-id="Produk">Products</a>
                <a href="about.php" class="menu-item text-white hover:text-green-500" data-en="About" data-id="Tentang Kami">About</a>
                <a href="contact.php" class="menu-item text-white hover:text-green-500" data-en="Contact" data-id="Hubungi Kami">Contact</a>
                
                <!-- Language Switcher -->
                <div class="flex border border-gray-700 rounded">
                    <button class="lang-switch px-3 py-1 bg-green-600 text-white rounded-l" data-lang="en">EN</button>
                    <button class="lang-switch px-3 py-1 hover:bg-gray-800 rounded-r" data-lang="id">ID</button>
                </div>
            </div>
            <button class="md:hidden text-white focus:outline-none" id="mobileMenuBtn">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>
        
        <!-- Mobile Menu -->
        <div class="md:hidden hidden" id="mobileMenu">
            <div class="px-6 py-4 space-y-4 bg-gray-900">
                <a href="index.php" class="block text-white hover:text-green-500">Home</a>
                <a href="products.php" class="block text-white hover:text-green-500">Products</a>
                <a href="about.php" class="block text-white hover:text-green-500">About</a>
                <a href="contact.php" class="block text-white hover:text-green-500">Contact</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="min-h-screen flex items-center pt-20">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 mb-12 md:mb-0">
                    <h2 class="text-4xl md:text-8xl font-bold mb-4">
                        <span class="text-green-500">KOMODO</span><br>
                        <span class="text-white">INDUSTRIAL</span><br>
                        <span class="text-white">INDONESIA</span>
                    </h2>
                    <p class="mt-8 text-lg text-gray-300 max-w-lg" data-en="Focusing on the production of stainless steel tableware and cooking utensils." data-id="Berfokus pada produksi peralatan makan dan perlengkapan masak berbahan stainless steel.">
                        Focusing on the production of stainless steel tableware and cooking utensils.
                    </p>
                    <div class="mt-12 flex space-x-4">
                        <a href="products.php" class="px-8 py-3 bg-green-600 text-black font-medium rounded-full hover:bg-green-500 transition duration-300" data-en="Explore Products" data-id="Jelajahi Produk">
                            Explore Products
                        </a>
                        <a href="contact.php" class="px-8 py-3 border-2 border-green-600 text-white font-medium rounded-full hover:bg-green-600 hover:text-black transition duration-300" data-en="Contact Us" data-id="Hubungi Kami">
                            Contact Us
                        </a>
                    </div>
                </div>
                <div class="md:w-1/2 flex justify-center">
                    <div class="relative">
                        <div class="absolute -inset-4 bg-green-500 rounded-2xl opacity-20 blur"></div>
                        <img src="https://lh3.googleusercontent.com/geougc-cs/AB3l90DZuGhDzulGx3KbREbEp678k5PxJS9UEvidpparridlDxzNfae3D5_HeS9A1E3V6J_6v-ic9wNz0MAyshb2boAH8GN_y16SyR1giNTd1fUV7sd2VA18_LWXzSsQ5_BBC9D3HJunQDvA0FVx=w600-h450-p" 
                             alt="Komodo Stainless Cookware" 
                             class="relative rounded-xl w-full max-w-md shadow-2xl">
                    </div>
                </div>
            </div>
            
            <div class="mt-24 text-center scroll-indicator">
                <a href="#products" class="text-green-500 inline-block">
                    <i class="fas fa-chevron-down text-2xl"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section id="products" class="py-20 bg-gray-900">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4" data-en="Our Products" data-id="Produk Kami">Our <span class="text-green-500">Products</span></h2>
                <p class="text-gray-400 max-w-2xl mx-auto" data-en="Designed for home kitchens, cafes, and restaurants, made with the finest stainless steel." data-id="Dirancang untuk dapur rumahan, kafe & restoran yang dibuat dengan bahan stainless steel terbaik.">
                    Designed for home kitchens, cafes, and restaurants, made with the finest stainless steel.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php if (!empty($featuredProducts)): ?>
                    <?php foreach ($featuredProducts as $product): ?>
                        <div class="project-card bg-gray-800 rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition duration-300">
                            <div class="relative overflow-hidden h-64">
                                <img src="<?php echo htmlspecialchars($product['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($product['name_en']); ?>" 
                                     class="w-full h-full object-cover transition duration-500 hover:scale-105">
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold mb-2" data-en="<?php echo htmlspecialchars($product['name_en']); ?>" data-id="<?php echo htmlspecialchars($product['name_id']); ?>">
                                    <?php echo htmlspecialchars($product['name_en']); ?>
                                </h3>
                                <p class="text-gray-400 mb-4 line-clamp-3" data-en="<?php echo htmlspecialchars($product['description_en']); ?>" data-id="<?php echo htmlspecialchars($product['description_id']); ?>">
                                    <?php echo truncate($product['description_en'], 100); ?>
                                </p>
                                <a href="products.php#product-<?php echo $product['id']; ?>" class="text-green-500 font-medium inline-flex items-center">
                                    View Details <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-4 text-center text-gray-400 py-12">
                        <p>No featured products available at the moment.</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="text-center mt-12">
                <a href="products.php" class="inline-flex items-center px-6 py-3 border-2 border-green-600 text-white font-medium rounded-full hover:bg-green-600 hover:text-black transition duration-300">
                    View All Products <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- About Preview -->
    <section class="py-20 bg-black">
        <div class="container mx-auto px-6">
            <div class="flex flex-col lg:flex-row items-center gap-12">
                <div class="lg:w-1/2">
                    <h2 class="text-4xl md:text-5xl font-bold mb-8">
                        <span class="text-green-500">About</span> Komodo Industrial Indonesia
                    </h2>
                    <p class="text-gray-300 mb-6 text-lg leading-relaxed">
                        Active since 2023, focusing on the production of stainless steel tableware and cookware. Located in Serang, Banten, equipped with various types of production machines.
                    </p>
                    <div class="flex flex-wrap gap-4 mt-8">
                        <div class="bg-gray-900 px-6 py-4 rounded-lg">
                            <div class="text-green-500 text-3xl font-bold">300+</div>
                            <div class="text-gray-400">Professional Clients</div>
                        </div>
                        <div class="bg-gray-900 px-6 py-4 rounded-lg">
                            <div class="text-green-500 text-3xl font-bold">100+</div>
                            <div class="text-gray-400">Regional Coverage</div>
                        </div>
                        <div class="bg-gray-900 px-6 py-4 rounded-lg">
                            <div class="text-green-500 text-3xl font-bold">02</div>
                            <div class="text-gray-400">Years of Experience</div>
                        </div>
                    </div>
                    <div class="mt-8">
                        <a href="about.php" class="inline-flex items-center px-6 py-3 bg-green-600 text-black font-medium rounded-full hover:bg-green-500 transition duration-300">
                            Learn More About Us <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                <div class="lg:w-1/2">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative h-64 rounded-xl overflow-hidden">
                            <img src="https://lh3.googleusercontent.com/geougc-cs/AB3l90D14OOH49P0eIFI8RCpQDCPRZLfz987Gjb5KhZcBoNS9OoKQWLZdBdKanszkBrhJh6YIQ5eVh0RcapLaDNmh_1xc8tRRqebugUILkY0tZ7rL-t6pKTMNrLS4FZyPFmDkVflpyuoj90VoeDn=w600-h450-p" 
                                 alt="Manufacturing Warehouse" 
                                 class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent opacity-70"></div>
                            <div class="absolute bottom-4 left-4 text-white font-medium">Our Manufacturing</div>
                        </div>
                        <div class="relative h-64 rounded-xl overflow-hidden">
                            <img src="https://indonesian.stainlessmetalsheet.com/photo/ps112807814-sus_4x8ft_stainless_steel_plate_316l_321_310s_for_roofing_materials.jpg" 
                                 alt="Quality Control" 
                                 class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent opacity-70"></div>
                            <div class="absolute bottom-4 left-4 text-white font-medium">Precision Quality</div>
                        </div>
                        <div class="relative h-64 rounded-xl overflow-hidden">
                            <img src="https://www.nagakomodo.co.id/uploads/2025/07/ac9dc3e809a20365fc2c93df3129a26b_ce7163e015450ca0c14e0701f821147a.jpg" 
                                 alt="Innovation" 
                                 class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent opacity-70"></div>
                            <div class="absolute bottom-4 left-4 text-white font-medium">Innovation</div>
                        </div>
                        <div class="relative h-64 rounded-xl overflow-hidden">
                            <img src="https://www.nagakomodo.co.id/uploads/2025/07/daf07a3b9491fe20ad93cbf80fd08ab0_617bfcf7ae0b41e3c8898738d131dabc.jpg" 
                                 alt="Product Variants" 
                                 class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent opacity-70"></div>
                            <div class="absolute bottom-4 left-4 text-white font-medium">Variant Products</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <script src="<?php echo ASSETS_URL; ?>/js/frontend/language.js"></script>
    <script src="<?php echo ASSETS_URL; ?>/js/frontend/main.js"></script>
</body>
</html>