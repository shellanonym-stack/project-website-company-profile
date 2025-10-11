<?php
/**
 * Products Management - Create
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

$errors = [];
$formData = [];

// Handle form submission
if (isPost()) {
    // Get form data
    $formData = [
        'name_en' => post('name_en'),
        'name_id' => post('name_id'),
        'description_en' => post('description_en'),
        'description_id' => post('description_id'),
        'image_url' => post('image_url'),
        'category' => post('category'),
        'is_active' => post('is_active', 0),
        'is_featured' => post('is_featured', 0),
        'display_order' => (int)post('display_order', 0)
    ];

    // Validation
    if (empty($formData['name_en'])) {
        $errors[] = 'English name is required';
    }
    
    if (empty($formData['name_id'])) {
        $errors[] = 'Indonesian name is required';
    }
    
    if (empty($formData['description_en'])) {
        $errors[] = 'English description is required';
    }
    
    if (empty($formData['description_id'])) {
        $errors[] = 'Indonesian description is required';
    }
    
    if (empty($formData['category'])) {
        $errors[] = 'Category is required';
    }

    // Handle image upload or URL
    $imageUrl = '';
    if (!empty($_FILES['image']['name'])) {
        // File upload
        $uploadResult = uploadFile($_FILES['image'], PRODUCTS_IMAGES_PATH);
        if ($uploadResult['success']) {
            $imageUrl = $uploadResult['url']; // Use the URL from upload function
        } else {
            $errors[] = $uploadResult['error'];
        }
    } elseif (!empty($formData['image_url'])) {
        // Use URL
        $imageUrl = $formData['image_url'];
    } else {
        $errors[] = 'Product image is required (upload or URL)';
    }

    // Insert if no errors
    if (empty($errors)) {
        $sql = "INSERT INTO products (name_en, name_id, description_en, description_id, image, category, is_active, is_featured, display_order, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $params = [
            $formData['name_en'],
            $formData['name_id'],
            $formData['description_en'],
            $formData['description_id'],
            $imageUrl,
            $formData['category'],
            $formData['is_active'],
            $formData['is_featured'],
            $formData['display_order']
        ];

        if ($db->query($sql, $params)) {
            setFlash('success', 'Product created successfully!');
            redirect(ADMIN_URL . '/modules/products/index.php');
        } else {
            $errors[] = 'Failed to create product. Please try again.';
        }
    }
}

$pageTitle = 'Add New Product';
include __DIR__ . '/../../includes/header.php';
?>

<!-- Create Product -->
<div class="p-6">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
            <a href="index.php" class="hover:text-green-600">Products</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-gray-900">Add New Product</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-900">Add New Product</h1>
    </div>

    <!-- Error Messages -->
    <?php if (!empty($errors)): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-start">
                <i class="fas fa-exclamation-circle mt-0.5 mr-3"></i>
                <div>
                    <p class="font-medium mb-1">Please fix the following errors:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Product Form -->
    <form method="POST" action="" enctype="multipart/form-data" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Product Names -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Product Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- English Name -->
                        <div>
                            <label for="name_en" class="block text-sm font-medium text-gray-700 mb-2">
                                English Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="name_en" 
                                   name="name_en" 
                                   value="<?php echo htmlspecialchars($formData['name_en'] ?? ''); ?>"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="e.g., Basin Series"
                                   required>
                        </div>

                        <!-- Indonesian Name -->
                        <div>
                            <label for="name_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Indonesian Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="name_id" 
                                   name="name_id" 
                                   value="<?php echo htmlspecialchars($formData['name_id'] ?? ''); ?>"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="e.g., Seri Baskom"
                                   required>
                        </div>
                    </div>

                    <!-- Category -->
                    <div class="mt-4">
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <select id="category" 
                                name="category" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                required>
                            <option value="">Select Category</option>
                            <?php foreach (PRODUCT_CATEGORIES as $key => $value): ?>
                                <option value="<?php echo $key; ?>" <?php echo ($formData['category'] ?? '') === $key ? 'selected' : ''; ?>>
                                    <?php echo $value['en']; ?> / <?php echo $value['id']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Descriptions -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Product Description</h2>
                    
                    <!-- English Description -->
                    <div class="mb-4">
                        <label for="description_en" class="block text-sm font-medium text-gray-700 mb-2">
                            English Description <span class="text-red-500">*</span>
                        </label>
                        <textarea id="description_en" 
                                  name="description_en" 
                                  rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                  placeholder="Describe the product in English..."
                                  required><?php echo htmlspecialchars($formData['description_en'] ?? ''); ?></textarea>
                    </div>

                    <!-- Indonesian Description -->
                    <div>
                        <label for="description_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Indonesian Description <span class="text-red-500">*</span>
                        </label>
                        <textarea id="description_id" 
                                  name="description_id" 
                                  rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                  placeholder="Jelaskan produk dalam Bahasa Indonesia..."
                                  required><?php echo htmlspecialchars($formData['description_id'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Product Image -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Product Image</h2>
                    
                    <!-- Image Preview -->
                    <div class="mb-4">
                        <img id="imagePreview" 
                             src="<?php echo getDefaultProductImage(); ?>" 
                             alt="Preview" 
                             class="w-full h-48 object-cover rounded-lg border-2 border-gray-200">
                    </div>

                    <!-- Upload Image -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Upload Image
                        </label>
                        <input type="file" 
                               name="image" 
                               accept="image/*"
                               onchange="previewImage(this, 'imagePreview')"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <p class="mt-1 text-xs text-gray-500">Max 5MB. Formats: JPG, PNG, GIF, WebP</p>
                        <p class="mt-1 text-xs text-green-600">File akan disimpan di: <?php echo PRODUCTS_IMAGES_PATH; ?></p>
                    </div>

                    <!-- OR -->
                    <div class="text-center text-gray-500 my-2">OR</div>

                    <!-- Image URL -->
                    <div>
                        <label for="image_url" class="block text-sm font-medium text-gray-700 mb-2">
                            Image URL
                        </label>
                        <input type="url" 
                               id="image_url" 
                               name="image_url" 
                               value="<?php echo htmlspecialchars($formData['image_url'] ?? ''); ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               placeholder="https://example.com/image.jpg"
                               onchange="document.getElementById('imagePreview').src = this.value">
                    </div>
                </div>

                <!-- Settings -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Settings</h2>
                    
                    <!-- Display Order -->
                    <div class="mb-4">
                        <label for="display_order" class="block text-sm font-medium text-gray-700 mb-2">
                            Display Order
                        </label>
                        <input type="number" 
                               id="display_order" 
                               name="display_order" 
                               value="<?php echo htmlspecialchars($formData['display_order'] ?? 0); ?>"
                               min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <p class="mt-1 text-xs text-gray-500">Lower numbers appear first</p>
                    </div>

                    <!-- Active Status -->
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1"
                                   <?php echo ($formData['is_active'] ?? 1) ? 'checked' : ''; ?>
                                   class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <span class="ml-2 text-sm text-gray-700">Active (visible on website)</span>
                        </label>
                    </div>

                    <!-- Featured Status -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_featured" 
                                   value="1"
                                   <?php echo ($formData['is_featured'] ?? 0) ? 'checked' : ''; ?>
                                   class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <span class="ml-2 text-sm text-gray-700">Featured (show on homepage)</span>
                        </label>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="space-y-2">
                        <button type="submit" 
                                class="w-full px-4 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition duration-200">
                            <i class="fas fa-save mr-2"></i>
                            Create Product
                        </button>
                        <a href="index.php" 
                           class="block w-full px-4 py-3 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition duration-200 text-center">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>