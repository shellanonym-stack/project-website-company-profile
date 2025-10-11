<?php
/**
 * Products Management - List
 * PT Komodo Industrial Indonesia
 */

define('APP_ACCESS', true);

// Load configurations
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../includes/auth.php';

// Check authentication
requireLogin();

// Get filters
$search = get('search', '');
$category = get('category', '');
$status = get('status', '');
$page = (int)get('page', 1);

// Build query
$where = [];
$params = [];

if (!empty($search)) {
    $where[] = "(name_en LIKE ? OR name_id LIKE ? OR description_en LIKE ? OR description_id LIKE ?)";
    $searchTerm = "%{$search}%";
    $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
}

if (!empty($category)) {
    $where[] = "category = ?";
    $params[] = $category;
}

if ($status !== '') {
    $where[] = "is_active = ?";
    $params[] = $status;
}

$whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

// Get total count
$countSql = "SELECT COUNT(*) as count FROM products {$whereClause}";
$totalItems = $db->fetchOne($countSql, $params)['count'];

// Get pagination data
$pagination = getPaginationData($totalItems, $page, ITEMS_PER_PAGE);

// Get products
$sql = "SELECT * FROM products {$whereClause} ORDER BY display_order ASC, created_at DESC LIMIT ? OFFSET ?";
$params[] = $pagination['items_per_page'];
$params[] = $pagination['offset'];
$products = $db->fetchAll($sql, $params);

$pageTitle = 'Products Management';
include __DIR__ . '/../../includes/header.php';
?>

<!-- Products Management -->
<div class="p-6">
    <!-- Page Header -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Products Management</h1>
            <p class="text-gray-600">Manage your product catalog</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="create.php" class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition duration-200">
                <i class="fas fa-plus mr-2"></i>
                Add New Product
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form method="GET" action="" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" 
                       name="search" 
                       value="<?php echo htmlspecialchars($search); ?>"
                       placeholder="Search products..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>

            <!-- Category Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">All Categories</option>
                    <?php foreach (PRODUCT_CATEGORIES as $key => $value): ?>
                        <option value="<?php echo $key; ?>" <?php echo $category === $key ? 'selected' : ''; ?>>
                            <?php echo $value['en']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="1" <?php echo $status === '1' ? 'selected' : ''; ?>>Active</option>
                    <option value="0" <?php echo $status === '0' ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
                <a href="index.php" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full" id="productsTable">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Image
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Product Name
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Category
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Featured
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Order
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <img src="<?php echo htmlspecialchars($product['image']); ?>" 
                                         alt="<?php echo htmlspecialchars($product['name_en']); ?>"
                                         class="w-16 h-16 object-cover rounded-lg">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?php echo htmlspecialchars($product['name_en']); ?>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <?php echo htmlspecialchars($product['name_id']); ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-600">
                                        <?php echo ucfirst($product['category']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <?php if ($product['is_active']): ?>
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-600">
                                            Active
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600">
                                            Inactive
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <?php if ($product['is_featured']): ?>
                                        <i class="fas fa-star text-yellow-400 text-lg"></i>
                                    <?php else: ?>
                                        <i class="far fa-star text-gray-300 text-lg"></i>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-gray-900">
                                    <?php echo $product['display_order']; ?>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="edit.php?id=<?php echo $product['id']; ?>" 
                                           class="text-blue-600 hover:text-blue-900"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="delete.php?id=<?php echo $product['id']; ?>" 
                                           class="text-red-600 hover:text-red-900"
                                           onclick="return confirmDelete('Are you sure you want to delete this product?')"
                                           title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-box-open text-4xl mb-4 text-gray-300"></i>
                                <p class="text-lg font-medium">No products found</p>
                                <p class="text-sm mt-2">Try adjusting your filters or add a new product</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($pagination['total_pages'] > 1): ?>
            <div class="px-6 py-4 border-t border-gray-200">
                <?php echo renderPagination($pagination, 'index.php'); ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Summary -->
    <div class="mt-4 text-sm text-gray-600">
        Showing <?php echo $pagination['offset'] + 1; ?> to <?php echo min($pagination['offset'] + $pagination['items_per_page'], $totalItems); ?> of <?php echo $totalItems; ?> products
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>