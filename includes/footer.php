<?php
/**
 * Frontend Footer
 * PT Komodo Industrial Indonesia
 */

if (!defined('APP_ACCESS')) {
    die('Direct access not permitted');
}
?>

<!-- Footer -->
<footer class="py-12 bg-black border-t border-gray-800">
    <div class="container mx-auto px-6">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="mb-6 md:mb-0">
                <a href="index.php" class="text-2xl font-bold text-green-500">KOMODO</a>
                <p class="text-white mt-1">INDUSTRIAL</p>
                <p class="text-white">INDONESIA</p>
                <p class="text-gray-500 mt-2">Stainless Cookware Since 2023</p>
            </div>
            
            <div class="flex flex-wrap justify-center gap-6 md:gap-12">
                <div>
                    <h4 class="text-white font-bold mb-3">Products</h4>
                    <ul class="space-y-2">
                        <li><a href="products.php?category=basin" class="text-gray-500 hover:text-green-500 transition duration-300">Basin Series</a></li>
                        <li><a href="products.php?category=spoon" class="text-gray-500 hover:text-green-500 transition duration-300">Spoon Series</a></li>
                        <li><a href="products.php?category=knife" class="text-gray-500 hover:text-green-500 transition duration-300">Knife Series</a></li>
                        <li><a href="products.php?category=wok" class="text-gray-500 hover:text-green-500 transition duration-300">Wok Series</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-3">Company</h4>
                    <ul class="space-y-2">
                        <li><a href="about.php" class="text-gray-500 hover:text-green-500 transition duration-300">About Us</a></li>
                        <li><a href="about.php#vision" class="text-gray-500 hover:text-green-500 transition duration-300">Vision & Mission</a></li>
                        <li><a href="contact.php" class="text-gray-500 hover:text-green-500 transition duration-300">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-3">Support</h4>
                    <ul class="space-y-2">
                        <li><a href="contact.php" class="text-gray-500 hover:text-green-500 transition duration-300">Contact Us</a></li>
                        <li><a href="#" class="text-gray-500 hover:text-green-500 transition duration-300">FAQs</a></li>
                        <li><a href="#" class="text-gray-500 hover:text-green-500 transition duration-300">Warranty</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="border-t border-gray-800 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center">
            <p class="text-gray-500 mb-4 md:mb-0">&copy; <?php echo date('Y'); ?> PT Komodo Industrial Indonesia. All rights reserved.</p>
            <div class="flex space-x-6">
                <a href="#" class="text-gray-500 hover:text-green-500 transition duration-300">Privacy Policy</a>
                <a href="#" class="text-gray-500 hover:text-green-500 transition duration-300">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>