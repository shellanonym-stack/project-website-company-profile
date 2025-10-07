<?php
// admin/products.php
session_start();
require_once '../config/database.php';
require_once 'auth.php';

checkAuth();

$database = new Database();
$db = $database->getConnection();

// Handle product actions
$action = $_GET['action'] ?? '';
$product_id = $_GET['id'] ?? '';

$message = '';
$message_type = '';

// Handle file upload
function handleImageUpload($existing_image = '') {
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        
        // Create uploads directory if not exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $fileType = mime_content_type($_FILES['image']['tmp_name']);
        
        if (!in_array($fileType, $allowedTypes)) {
            throw new Exception('Only JPG, PNG, GIF, and WebP images are allowed.');
        }
        
        // Generate unique filename
        $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . '_' . time() . '.' . $fileExtension;
        $targetPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            // Delete old image if exists
            if ($existing_image && file_exists('../' . $existing_image)) {
                unlink('../' . $existing_image);
            }
            return 'uploads/' . $fileName;
        } else {
            throw new Exception('Failed to upload image.');
        }
    }
    
    return $existing_image; // Return existing image if no new upload
}

// Add/Edit product
if ($_POST['action'] ?? '' === 'save_product') {
    try {
        $name = $_POST['name'] ?? '';
        $name_id = $_POST['name_id'] ?? '';
        $description = $_POST['description'] ?? '';
        $description_id = $_POST['description_id'] ?? '';
        $category = $_POST['category'] ?? '';
        $price = $_POST['price'] ?? 0;
        $stock_quantity = $_POST['stock_quantity'] ?? 0;
        $is_featured = isset($_POST['is_featured']) ? 1 : 0;
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        // Handle image upload
        $existing_image = $_POST['existing_image'] ?? '';
        $image_url = handleImageUpload($existing_image);
        
        // Prepare specifications JSON
        $specifications = [
            'material' => $_POST['material'] ?? '',
            'size' => $_POST['size'] ?? '',
            'weight' => $_POST['weight'] ?? '',
            'dimensions' => $_POST['dimensions'] ?? ''
        ];
        
        if ($_POST['product_id']) {
            // Update existing product
            $stmt = $db->prepare("
                UPDATE products 
                SET name = ?, name_id = ?, description = ?, description_id = ?, category = ?, 
                    price = ?, image_url = ?, specifications = ?, stock_quantity = ?, 
                    is_featured = ?, is_active = ?, updated_at = NOW()
                WHERE id = ?
            ");
            $stmt->execute([
                $name, $name_id, $description, $description_id, $category,
                $price, $image_url, json_encode($specifications), $stock_quantity,
                $is_featured, $is_active, $_POST['product_id']
            ]);
            $message = 'Product updated successfully!';
        } else {
            // Insert new product
            $stmt = $db->prepare("
                INSERT INTO products (name, name_id, description, description_id, category, price, image_url, specifications, stock_quantity, is_featured, is_active)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $name, $name_id, $description, $description_id, $category,
                $price, $image_url, json_encode($specifications), $stock_quantity,
                $is_featured, $is_active
            ]);
            $message = 'Product added successfully!';
        }
        $message_type = 'success';
    } catch (Exception $e) {
        $message = 'Error saving product: ' . $e->getMessage();
        $message_type = 'error';
    }
}

// Delete product
if ($action === 'delete' && $product_id) {
    try {
        // Get product image before deletion
        $stmt = $db->prepare("SELECT image_url FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();
        
        // Delete image file
        if ($product && $product['image_url'] && file_exists('../' . $product['image_url'])) {
            unlink('../' . $product['image_url']);
        }
        
        $stmt = $db->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $message = 'Product deleted successfully!';
        $message_type = 'success';
    } catch (Exception $e) {
        $message = 'Error deleting product: ' . $e->getMessage();
        $message_type = 'error';
    }
}

// Toggle featured status
if ($action === 'toggle_featured' && $product_id) {
    try {
        $stmt = $db->prepare("UPDATE products SET is_featured = NOT is_featured WHERE id = ?");
        $stmt->execute([$product_id]);
        $message = 'Featured status updated!';
        $message_type = 'success';
    } catch (Exception $e) {
        $message = 'Error updating product: ' . $e->getMessage();
        $message_type = 'error';
    }
}

// Get products for listing
try {
    $stmt = $db->query("
        SELECT * FROM products 
        ORDER BY created_at DESC
    ");
    $products = $stmt->fetchAll();
} catch (Exception $e) {
    $products = [];
    $message = 'Error loading products: ' . $e->getMessage();
    $message_type = 'error';
}

// Get product for editing
$edit_product = null;
if ($action === 'edit' && $product_id) {
    try {
        $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $edit_product = $stmt->fetch();
    } catch (Exception $e) {
        $message = 'Error loading product: ' . $e->getMessage();
        $message_type = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - KOMODO INDUSTRIAL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-gray-800 text-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <a href="index.php" class="text-lg font-bold">KOMODO INDUSTRIAL</a>
                    <span class="text-gray-400">/</span>
                    <span>Product Management</span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-300"><?php echo htmlspecialchars($_SESSION['admin_fullname']); ?></span>
                    <a href="logout.php" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Product Management</h1>
            <button onclick="showProductForm()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-plus mr-2"></i>Add New Product
            </button>
        </div>

        <!-- Message -->
        <?php if ($message): ?>
            <div class="mb-4 p-4 rounded-lg <?php echo $message_type === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- Product Form -->
        <div id="product-form" class="bg-white rounded-lg shadow mb-6 <?php echo $edit_product ? '' : 'hidden'; ?>">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold">
                    <?php echo $edit_product ? 'Edit Product' : 'Add New Product'; ?>
                </h2>
            </div>
            <form method="POST" enctype="multipart/form-data" class="p-6">
                <input type="hidden" name="action" value="save_product">
                <input type="hidden" name="product_id" value="<?php echo $edit_product['id'] ?? ''; ?>">
                <input type="hidden" name="existing_image" value="<?php echo $edit_product['image_url'] ?? ''; ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Basic Information</h3>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Product Name (English)</label>
                            <input type="text" name="name" value="<?php echo htmlspecialchars($edit_product['name'] ?? ''); ?>" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Product Name (Indonesian)</label>
                            <input type="text" name="name_id" value="<?php echo htmlspecialchars($edit_product['name_id'] ?? ''); ?>" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                                <option value="">Select Category</option>
                                <option value="basin" <?php echo ($edit_product['category'] ?? '') === 'basin' ? 'selected' : ''; ?>>Basin</option>
                                <option value="spoon" <?php echo ($edit_product['category'] ?? '') === 'spoon' ? 'selected' : ''; ?>>Spoon</option>
                                <option value="knife" <?php echo ($edit_product['category'] ?? '') === 'knife' ? 'selected' : ''; ?>>Knife</option>
                                <option value="wok" <?php echo ($edit_product['category'] ?? '') === 'wok' ? 'selected' : ''; ?>>Wok</option>
                                <option value="cookware" <?php echo ($edit_product['category'] ?? '') === 'cookware' ? 'selected' : ''; ?>>Cookware</option>
                                <option value="utensils" <?php echo ($edit_product['category'] ?? '') === 'utensils' ? 'selected' : ''; ?>>Utensils</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Price ($)</label>
                            <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($edit_product['price'] ?? ''); ?>" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        </div>
                    </div>

                    <!-- Description & Image -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Description & Image</h3>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description (English)</label>
                            <textarea name="description" rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required><?php echo htmlspecialchars($edit_product['description'] ?? ''); ?></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description (Indonesian)</label>
                            <textarea name="description_id" rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required><?php echo htmlspecialchars($edit_product['description_id'] ?? ''); ?></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Product Image</label>
                            <input type="file" name="image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            <p class="text-sm text-gray-500 mt-1">Max file size: 2MB. Allowed types: JPG, PNG, GIF, WebP</p>
                            <?php if ($edit_product && $edit_product['image_url']): ?>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600">Current Image:</p>
                                    <img src="../<?php echo htmlspecialchars($edit_product['image_url']); ?>" alt="Current Product Image" class="h-20 object-cover rounded mt-1">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Specifications -->
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Specifications</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php
                        $specs = $edit_product ? json_decode($edit_product['specifications'], true) : [];
                        ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Material</label>
                            <input type="text" name="material" value="<?php echo htmlspecialchars($specs['material'] ?? ''); ?>" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Size</label>
                            <input type="text" name="size" value="<?php echo htmlspecialchars($specs['size'] ?? ''); ?>" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Weight</label>
                            <input type="text" name="weight" value="<?php echo htmlspecialchars($specs['weight'] ?? ''); ?>" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dimensions</label>
                            <input type="text" name="dimensions" value="<?php echo htmlspecialchars($specs['dimensions'] ?? ''); ?>" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity</label>
                        <input type="number" name="stock_quantity" value="<?php echo htmlspecialchars($edit_product['stock_quantity'] ?? 0); ?>" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="is_featured" id="is_featured" 
                               <?php echo ($edit_product['is_featured'] ?? 0) ? 'checked' : ''; ?>
                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                        <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                            Featured Product
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" 
                               <?php echo ($edit_product['is_active'] ?? 1) ? 'checked' : ''; ?>
                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                            Active
                        </label>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="hideProductForm()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        <?php echo $edit_product ? 'Update Product' : 'Add Product'; ?>
                    </button>
                </div>
            </form>
        </div>

        <!-- Products Table -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">All Products</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Product
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Category
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Price
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Stock
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <?php if ($product['image_url']): ?>
                                                <img class="h-10 w-10 rounded-lg object-cover" src="../<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                            <?php else: ?>
                                                <div class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center">
                                                    <i class="fas fa-image text-gray-400"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?php echo htmlspecialchars($product['name']); ?>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <?php echo date('M j, Y', strtotime($product['created_at'])); ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <?php echo ucfirst($product['category']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    $<?php echo number_format($product['price'], 2); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo $product['stock_quantity']; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($product['is_featured']): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Featured
                                        </span>
                                    <?php endif; ?>
                                    <?php if (!$product['is_active']): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Inactive
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="?action=edit&id=<?php echo $product['id']; ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="?action=toggle_featured&id=<?php echo $product['id']; ?>" class="text-yellow-600 hover:text-yellow-900 mr-3">
                                        <?php echo $product['is_featured'] ? '<i class="fas fa-star"></i> Unfeature' : '<i class="far fa-star"></i> Feature'; ?>
                                    </a>
                                    <a href="?action=delete&id=<?php echo $product['id']; ?>" 
                                       onclick="return confirm('Are you sure you want to delete this product?')"
                                       class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function showProductForm() {
            document.getElementById('product-form').classList.remove('hidden');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
        
        function hideProductForm() {
            document.getElementById('product-form').classList.add('hidden');
            window.location.href = 'products.php';
        }
        
        // Show form if there's an edit product
        <?php if ($edit_product): ?>
            document.getElementById('product-form').classList.remove('hidden');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        <?php endif; ?>
    </script>
</body>
</html>