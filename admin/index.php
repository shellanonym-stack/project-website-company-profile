<?php
/**
 * Admin Dashboard
 * PT Komodo Industrial Indonesia
 */

define('APP_ACCESS', true);

// Load configurations
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

// Check authentication
requireLogin();

// Get statistics
$stats = [
    'total_products' => $db->fetchOne("SELECT COUNT(*) as count FROM products")['count'],
    'active_products' => $db->fetchOne("SELECT COUNT(*) as count FROM products WHERE is_active = 1")['count'],
    'featured_products' => $db->fetchOne("SELECT COUNT(*) as count FROM products WHERE is_featured = 1")['count'],
    'total_contacts' => $db->fetchOne("SELECT COUNT(*) as count FROM contacts")['count'],
    'unread_contacts' => $db->fetchOne("SELECT COUNT(*) as count FROM contacts WHERE is_read = 0")['count'],
    'contacts_today' => $db->fetchOne("SELECT COUNT(*) as count FROM contacts WHERE DATE(created_at) = CURDATE()")['count']
];

// Get recent contacts
$recentContacts = $db->fetchAll(
    "SELECT * FROM contacts ORDER BY created_at DESC LIMIT 5"
);

// Get recent products
$recentProducts = $db->fetchAll(
    "SELECT * FROM products ORDER BY created_at DESC LIMIT 5"
);

$pageTitle = 'Dashboard';
include __DIR__ . '/includes/header.php';
?>

<!-- Dashboard Content -->
<div class="p-6">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard</h1>
        <p class="text-gray-600">Welcome back, <?php echo htmlspecialchars(getCurrentAdmin()['full_name']); ?>!</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Total Products -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Products</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo $stats['total_products']; ?></p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-box text-2xl text-blue-500"></i>
                </div>
            </div>
            <div class="mt-4 text-sm">
                <span class="text-green-600 font-medium"><?php echo $stats['active_products']; ?> active</span>
                <span class="text-gray-400 mx-2">•</span>
                <span class="text-gray-600"><?php echo $stats['featured_products']; ?> featured</span>
            </div>
        </div>

        <!-- Total Contacts -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Messages</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo $stats['total_contacts']; ?></p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-envelope text-2xl text-green-500"></i>
                </div>
            </div>
            <div class="mt-4 text-sm">
                <span class="text-red-600 font-medium"><?php echo $stats['unread_contacts']; ?> unread</span>
                <span class="text-gray-400 mx-2">•</span>
                <span class="text-gray-600"><?php echo $stats['contacts_today']; ?> today</span>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-md p-6 text-white">
            <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
            <div class="space-y-2">
                <a href="modules/products/create.php" class="block bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg px-4 py-2 text-sm transition duration-200">
                    <i class="fas fa-plus mr-2"></i> Add New Product
                </a>
                <a href="modules/contacts/index.php" class="block bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg px-4 py-2 text-sm transition duration-200">
                    <i class="fas fa-inbox mr-2"></i> View Messages
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Data Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Contacts -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Recent Messages</h3>
                <a href="modules/contacts/index.php" class="text-sm text-green-600 hover:text-green-700 font-medium">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php if (!empty($recentContacts)): ?>
                            <?php foreach ($recentContacts as $contact): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <?php echo htmlspecialchars($contact['full_name']); ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <?php echo ucfirst($contact['subject']); ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <?php echo timeAgo($contact['created_at']); ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if ($contact['is_read']): ?>
                                            <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600">Read</span>
                                        <?php else: ?>
                                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-600">Unread</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                    No messages yet
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Products -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Recent Products</h3>
                <a href="modules/products/index.php" class="text-sm text-green-600 hover:text-green-700 font-medium">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Featured</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php if (!empty($recentProducts)): ?>
                            <?php foreach ($recentProducts as $product): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <img src="<?php echo htmlspecialchars($product['image']); ?>" 
                                                 alt="<?php echo htmlspecialchars($product['name_en']); ?>"
                                                 class="w-10 h-10 rounded object-cover mr-3">
                                            <span class="text-sm text-gray-900">
                                                <?php echo truncate($product['name_en'], 30); ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <?php echo ucfirst($product['category']); ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if ($product['is_active']): ?>
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-600">Active</span>
                                        <?php else: ?>
                                            <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if ($product['is_featured']): ?>
                                            <i class="fas fa-star text-yellow-400"></i>
                                        <?php else: ?>
                                            <i class="far fa-star text-gray-300"></i>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                    No products yet
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>