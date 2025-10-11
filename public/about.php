<?php
/**
 * Frontend About Page
 * PT Komodo Industrial Indonesia
 */

define('APP_ACCESS', true);

// Load configurations
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$pageTitle = 'About Us';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> | <?php echo $pageTitle; ?></title>
    <meta name="description" content="Learn about PT Komodo Industrial Indonesia and our mission to provide premium stainless steel cookware.">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #000000;
            color: #ffffff;
        }
    </style>
</head>
<body class="antialiased">
    <!-- Navigation -->
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <!-- Main Content -->
    <div class="pt-20 min-h-screen">
        <!-- Hero Section -->
        <section class="bg-gray-900 py-20">
            <div class="container mx-auto px-6">
                <div class="text-center">
                    <h1 class="text-4xl md:text-6xl font-bold mb-4">About <span class="text-green-500">Us</span></h1>
                    <p class="text-gray-400 max-w-2xl mx-auto text-lg">
                        Learn more about our company, mission, and the values that drive us.
                    </p>
                </div>
            </div>
        </section>

        <!-- Content Section -->
        <section class="py-20">
            <div class="container mx-auto px-6">
                <div class="max-w-4xl mx-auto">
                    <div class="bg-gray-800 rounded-2xl p-8 md:p-12">
                        <h2 class="text-3xl font-bold mb-6">Our Story</h2>
                        <p class="text-gray-300 mb-6 leading-relaxed">
                            PT Komodo Industrial Indonesia was established in 2023 with a vision to provide high-quality stainless steel cookware and tableware. Located in Serang, Banten, we are equipped with modern production machinery and a dedicated team of professionals.
                        </p>
                        
                        <h3 class="text-2xl font-bold mt-8 mb-4">Our Mission</h3>
                        <p class="text-gray-300 mb-6 leading-relaxed">
                            To deliver durable, functional, and aesthetically pleasing kitchen products that enhance the cooking experience for both home cooks and professional chefs.
                        </p>

                        <h3 class="text-2xl font-bold mt-8 mb-4">Our Vision</h3>
                        <p class="text-gray-300 mb-6 leading-relaxed">
                            To become a leading manufacturer of stainless steel cookware in Indonesia and expand our reach to international markets.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
                            <div class="text-center">
                                <div class="text-green-500 text-4xl font-bold mb-2">300+</div>
                                <div class="text-gray-400">Professional Clients</div>
                            </div>
                            <div class="text-center">
                                <div class="text-green-500 text-4xl font-bold mb-2">100+</div>
                                <div class="text-gray-400">Regional Coverage</div>
                            </div>
                            <div class="text-center">
                                <div class="text-green-500 text-4xl font-bold mb-2">02</div>
                                <div class="text-gray-400">Years of Experience</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>