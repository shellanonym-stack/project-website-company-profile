<?php
// public/index.php
require_once '../config/database.php';

// Get featured products from database
$featured_products = [];
$sql = "SELECT * FROM products WHERE featured = 1 AND status = 'active' LIMIT 3";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $featured_products[] = $row;
    }
}

// Get quick stats
$stats = [
    'clients' => 300,
    'coverage' => 100,
    'experience' => 2
];

// Try to get actual stats from database if available
$clients_sql = "SELECT COUNT(DISTINCT email) as total_clients FROM contacts";
$clients_result = $conn->query($clients_sql);
if ($clients_result && $clients_row = $clients_result->fetch_assoc()) {
    $stats['clients'] = $clients_row['total_clients'] + 280; // Base + actual
}
?>

<?php include '../includes/header.php'; ?>

<!-- Hero Section -->
<section class="hero-section flex items-center pt-16">
    <div class="container mx-auto px-6 py-2 flex justify-between items-center pt-24 pb-16">
        <div class="container mx-auto">
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
                <a href="#featured" class="text-green-500 animate-bounce inline-block">
                    <i class="fas fa-chevron-down text-2xl"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section id="featured" class="py-20 bg-gray-900">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold mb-4" data-en="Featured Products" data-id="Produk Unggulan">Featured <span class="text-green-500">Products</span></h2>
            <p class="text-gray-400 max-w-2xl mx-auto" data-en="Designed for home kitchens, cafes, and restaurants, made with the finest stainless steel." data-id="Dirancang untuk dapur rumahan, kafe & restoran yang dibuat dengan bahan stainless steel terbaik.">
                Designed for home kitchens, cafes, and restaurants, made with the finest stainless steel.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if (!empty($featured_products)): ?>
                <?php foreach($featured_products as $product): ?>
                <div class="project-card bg-gray-800 rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition duration-300 fade-in">
                    <div class="relative overflow-hidden h-64">
                        <img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>" 
                             class="w-full h-full object-cover transition duration-500 hover:scale-105">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="text-gray-400 mb-4"><?php echo htmlspecialchars($product['description']); ?></p>
                        <a href="products.php" class="text-green-500 font-medium inline-flex items-center">
                            View Details <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Fallback static products if no database products -->
                <div class="project-card bg-gray-800 rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition duration-300 fade-in">
                    <div class="relative overflow-hidden h-64">
                        <img src="https://www.nagakomodo.co.id/uploads/2025/07/460079fa21b586104f386bb2328fa12b_1b673a35f9b27e042037eebd785f1410.jpg" 
                             alt="Professional Cookware Set" 
                             class="w-full h-full object-cover transition duration-500 hover:scale-105">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2" data-en="Basin Series" data-id="Seri Baskom">Basin Series</h3>
                        <p class="text-gray-400 mb-4" data-en="Stainless basin with a beautiful and charming polish made from Non Magnet stainless" data-id="Baskom Stainless dengan polesan cantik dan menawan berbahan stainless Non Magnet">Stainless basin with a beautiful and charming polish made from Non Magnet stainless</p>
                        <a href="products.php" class="text-green-500 font-medium inline-flex items-center">
                            View Details <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Add other static products as fallback -->
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-12">
            <a href="products.php" class="inline-flex items-center px-6 py-3 border-2 border-green-600 text-white font-medium rounded-full hover:bg-green-600 hover:text-black transition duration-300" data-en="View Full Product Catalog" data-id="Lihat Katalog Produk Lengkap">
                View Full Product Catalog <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Quick Stats -->
<section class="py-20 bg-black">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div class="fade-in">
                <div class="text-green-500 text-5xl font-bold mb-2"><?php echo $stats['clients']; ?>+</div>
                <div class="text-gray-400 text-xl" data-en="Professional Clients" data-id="Klien Profesional">Professional Clients</div>
            </div>
            <div class="fade-in">
                <div class="text-green-500 text-5xl font-bold mb-2"><?php echo $stats['coverage']; ?>+</div>
                <div class="text-gray-400 text-xl" data-en="Regional Coverage" data-id="Jangkauan Wilayah">Regional Coverage</div>
            </div>
            <div class="fade-in">
                <div class="text-green-500 text-5xl font-bold mb-2"><?php echo $stats['experience']; ?></div>
                <div class="text-gray-400 text-xl" data-en="Years of Experience" data-id="Tahun Pengalaman">Years of Experience</div>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>