<?php
// public/products.php
require_once '../config/database.php';

// Set currency symbol (make this dynamic as needed, e.g., from config or user session)
$currencySymbol = '$';

// Check database connection
if (!isset($conn) || $conn->connect_error) {
    die("Database connection failed: " . (isset($conn) ? $conn->connect_error : 'Connection variable not set'));
}

// Example SQL query (replace with your actual query)
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

$products = [];
if ($result === false) {
    // Handle SQL error
    error_log("Database query failed: " . $conn->error);
    echo "<div style='color: red; text-align: center;'>Failed to load products. Please try again later.</div>";
} elseif ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>

<?php include '../includes/header.php'; ?>

<!-- Products Header -->
<section class="py-20 bg-gray-900 mt-16">
    <div class="container mx-auto px-6 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4" data-en="Our Products" data-id="Produk Kami">Our <span class="text-green-500">Products</span></h1>
        <p class="text-gray-400 max-w-2xl mx-auto" data-en="Designed for social media & product packaging." data-id="Desain untuk sosial media dan produk..">
            Designed for social media & product packaging
        </p>
    </div>
</section>

<!-- All Products -->
<section class="py-20 bg-black">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if (!empty($products)): ?>
                <?php foreach($products as $product): ?>
                <div class="project-card bg-gray-800 rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition duration-300 fade-in">
                    <div class="relative overflow-hidden h-64">
                        <img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>" 
                             class="w-full h-full object-cover transition duration-500 hover:scale-105">
                    </div>
                    <div class="p-6">
                            <button type="button" class="text-green-500 font-medium inline-flex items-center focus:outline-none" aria-label="View Details">
                                View Details <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                            <span class="text-green-500 font-bold text-lg"><?php echo $currencySymbol . number_format($product['price'], 2); ?></span>
                            <a href="#" class="text-green-500 font-medium inline-flex items-center">
                                View Details <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Fallback static products -->
                <div class="project-card bg-gray-800 rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition duration-300 fade-in">
                    <div class="relative overflow-hidden h-64">
                        <img src="../uploads/desain.jpg" 
                             alt="contoh aja" 
                             class="w-full h-full object-cover transition duration-500 hover:scale-105">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2" data-en="demon slayer" data-id="pembasmi iblis">Demon Slayer</h3>
                        <p class="text-gray-400 mb-4" data-en="Stainless basin with a beautiful and charming polish made from Non Magnet stainless" data-id="Baskom Stainless dengan polesan cantik dan menawan berbahan stainless Non Magnet">Stainless basin with a beautiful and charming polish made from Non Magnet stainless</p>
                        <div class="flex justify-between items-center">
                            <span class="text-green-500 font-bold text-lg"><?php echo $currencySymbol . number_format(45.00, 2); ?></span>
                            <a href="#" class="text-green-500 font-medium inline-flex items-center">
                                View Details <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Add other static products as needed -->
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Product Categories -->
<section class="py-20 bg-gray-900">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold mb-4" data-en="Product Categories" data-id="Kategori Produk">Product <span class="text-green-500">Categories</span></h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="text-center p-6 bg-gray-800 rounded-xl hover:bg-gray-700 transition duration-300">
                <div class="text-green-500 text-3xl mb-4">
                    <i class="fas fa-utensils"></i>
                </div>
                <h3 class="font-bold mb-2" data-en="sample-1" data-id="Contoh-1">Sample-1</h3>
                <p class="text-gray-400 text-sm" data-en="sample" data-id="contoh">contoh</p>
            </div>
            <div class="text-center p-6 bg-gray-800 rounded-xl hover:bg-gray-700 transition duration-300">
                <div class="text-green-500 text-3xl mb-4">
                    <i class="fas fa-concierge-bell"></i>
                </div>
                <h3 class="font-bold mb-2" data-en="sample-2" data-id="Contoh-2">sample-2</h3>
                <p class="text-gray-400 text-sm" data-en="sample-2" data-id="contoh-2">sample-2</p>
            </div>
            <div class="text-center p-6 bg-gray-800 rounded-xl hover:bg-gray-700 transition duration-300">
                <div class="text-green-500 text-3xl mb-4">
                    <i class="fas fa-blender"></i>
                </div>
                <h3 class="font-bold mb-2" data-en="sample-3" data-id="Contoh-3">sample-3</h3>
                <p class="text-gray-400 text-sm" data-en="sample-3" data-id="contoh-3">Sample-3</p>
            </div>
            <div class="text-center p-6 bg-gray-800 rounded-xl hover:bg-gray-700 transition duration-300">
                <div class="text-green-500 text-3xl mb-4">
                    <i class="fas fa-chess-knight"></i>
                </div>
                <h3 class="font-bold mb-2" data-en="Specialty" data-id="Khusus">Specialty</h3>
                <p class="text-gray-400 text-sm" data-en="Professional equipment" data-id="Peralatan profesional">Professional equipment</p>
            </div>
        </div>
<?php 
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
include '../includes/footer.php'; 
?>
</section>

<?php include '../includes/footer.php'; ?>