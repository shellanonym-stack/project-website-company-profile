
<?php
/**
 * Frontend Products Page
 * PT Komodo Industrial Indonesia
 */

define('APP_ACCESS', true);

// Load configurations
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// Get category from query string
$category = get('category', '');
$page = (int)get('page', 1);

// Build query
$where = ['is_active = 1'];
$params = [];

if (!empty($category) && array_key_exists($category, PRODUCT_CATEGORIES)) {
    $where[] = 'category = ?';
    $params[] = $category;
}

$whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

// Get total products
$countSql = "SELECT COUNT(*) as count FROM products {$whereClause}";
$totalItems = $db->fetchOne($countSql, $params)['count'];

// Get pagination
$pagination = getPaginationData($totalItems, $page, PRODUCTS_PER_PAGE);

// Get products
$sql = "SELECT * FROM products {$whereClause} ORDER BY display_order ASC, created_at DESC LIMIT ? OFFSET ?";
$params[] = $pagination['items_per_page'];
$params[] = $pagination['offset'];
$products = $db->fetchAll($sql, $params);

$pageTitle = 'Our Products';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> | <?php echo $pageTitle; ?></title>
    <meta name="description" content="Explore our premium stainless steel cookware and tableware products.">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #000000;
            color: #ffffff;
        }
        
        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
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
                    <h1 class="text-4xl md:text-6xl font-bold mb-4">Our <span class="text-green-500">Products</span></h1>
                    <p class="text-gray-400 max-w-2xl mx-auto text-lg">
                        Discover our high-quality stainless steel cookware designed for professional and home use.
                    </p>
                </div>
            </div>
        </section>

        <!-- Products Section -->
        <section class="py-20">
            <div class="container mx-auto px-6">
                <!-- Category Filters -->
                <div class="flex flex-wrap justify-center gap-4 mb-12">
                    <a href="products.php" 
                       class="px-6 py-3 rounded-full border-2 border-green-600 text-white hover:bg-green-600 transition duration-300 <?php echo empty($category) ? 'bg-green-600' : ''; ?>">
                        All Products
                    </a>
                    <?php foreach (PRODUCT_CATEGORIES as $key => $cat): ?>
                        <a href="products.php?category=<?php echo $key; ?>" 
                           class="px-6 py-3 rounded-full border-2 border-green-600 text-white hover:bg-green-600 transition duration-300 <?php echo $category === $key ? 'bg-green-600' : ''; ?>">
                            <?php echo $cat['en']; ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <!-- Products Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <div class="product-card bg-gray-800 rounded-xl overflow-hidden shadow-lg">
                                <div class="relative overflow-hidden h-64">
                                    <img src="<?php echo htmlspecialchars($product['image']); ?>" 
                                         alt="<?php echo htmlspecialchars($product['name_en']); ?>" 
                                         class="w-full h-full object-cover transition duration-500 hover:scale-105">
                                </div>
                                <div class="p-6">
                                    <h3 class="text-xl font-bold mb-2"><?php echo htmlspecialchars($product['name_en']); ?></h3>
                                    <p class="text-gray-400 mb-4 line-clamp-3">
                                        <?php echo truncate($product['description_en'], 100); ?>
                                    </p>
                                    <div class="flex items-center justify-between">
                                        <span class="px-3 py-1 bg-green-600 text-white text-sm rounded-full">
                                            <?php echo PRODUCT_CATEGORIES[$product['category']]['en']; ?>
                                        </span>
                                        <a href="#product-<?php echo $product['id']; ?>" 
                                           class="text-green-500 hover:text-green-400 font-medium">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-span-full text-center py-12">
                            <p class="text-gray-400 text-lg">No products found in this category.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Pagination -->
                <?php if ($pagination['total_pages'] > 1): ?>
                    <div class="mt-12">
                        <?php echo renderPagination($pagination, 'products.php'); ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>