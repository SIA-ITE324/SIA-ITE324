<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Add Product' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-purple-800">
                        <i class="fas fa-spa text-pink-500"></i> Fleur Admin
                    </h1>
                </div>
                
                <div class="flex items-center space-x-6">
                    <a href="app.php?action=home" class="text-gray-700 hover:text-purple-600">
                        <i class="fas fa-home mr-2"></i>Home
                    </a>
                    <div class="relative group">
                        <button class="flex items-center text-gray-700 hover:text-purple-600">
                            <i class="fas fa-user mr-2"></i>
                            <?= $_SESSION['user_name'] ?>
                            <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                            <a href="app.php?action=profile" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
                            <a href="app.php?action=logout" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 border-t">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 min-h-screen">
            <div class="p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="app.php?action=admin" class="flex items-center text-white hover:bg-gray-700 p-3 rounded">
                            <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="app.php?action=orders" class="flex items-center text-white hover:bg-gray-700 p-3 rounded">
                            <i class="fas fa-shopping-cart mr-3"></i> Orders
                        </a>
                    </li>
                    <li>
                        <a href="app.php?action=products" class="flex items-center text-white bg-gray-700 p-3 rounded">
                            <i class="fas fa-box mr-3"></i> Products
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center text-white hover:bg-gray-700 p-3 rounded">
                            <i class="fas fa-users mr-3"></i> Customers
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center text-white hover:bg-gray-700 p-3 rounded">
                            <i class="fas fa-warehouse mr-3"></i> Inventory
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center text-white hover:bg-gray-700 p-3 rounded">
                            <i class="fas fa-chart-bar mr-3"></i> Reports
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-bold text-gray-800">Add New Product</h2>
                <a href="app.php?action=products" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Products
                </a>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <form method="POST" action="app.php?action=save_product" enctype="multipart/form-data">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Left Column - Basic Info -->
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Product Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Product Name *
                                </label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    placeholder="Enter product name"
                                >
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Description *
                                </label>
                                <textarea 
                                    id="description" 
                                    name="description" 
                                    rows="4" 
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    placeholder="Detailed product description"
                                ></textarea>
                            </div>

                            <!-- Short Description -->
                            <div>
                                <label for="short_description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Short Description
                                </label>
                                <textarea 
                                    id="short_description" 
                                    name="short_description" 
                                    rows="2" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    placeholder="Brief description for product listings"
                                ></textarea>
                            </div>

                            <!-- SKU and Category -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">
                                        SKU *
                                    </label>
                                    <input 
                                        type="text" 
                                        id="sku" 
                                        name="sku" 
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                        placeholder="e.g., RB-001"
                                    >
                                </div>
                                
                                <div>
                                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Category
                                    </label>
                                    <select 
                                        id="category_id" 
                                        name="category_id" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    >
                                        <option value="">Select Category</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Price Information -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                        Regular Price *
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-2 text-gray-500">₱</span>
                                        <input 
                                            type="number" 
                                            id="price" 
                                            name="price" 
                                            step="0.01" 
                                            min="0" 
                                            required
                                            class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                            placeholder="0.00"
                                        >
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="sale_price" class="block text-sm font-medium text-gray-700 mb-2">
                                        Sale Price
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-2 text-gray-500">₱</span>
                                        <input 
                                            type="number" 
                                            id="sale_price" 
                                            name="sale_price" 
                                            step="0.01" 
                                            min="0" 
                                            class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                            placeholder="0.00"
                                        >
                                    </div>
                                </div>
                            </div>

                            <!-- Stock Information -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                        Stock Quantity *
                                    </label>
                                    <input 
                                        type="number" 
                                        id="stock_quantity" 
                                        name="stock_quantity" 
                                        min="0" 
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                        placeholder="0"
                                    >
                                </div>
                                
                                <div>
                                    <label for="min_stock_level" class="block text-sm font-medium text-gray-700 mb-2">
                                        Minimum Stock Level
                                    </label>
                                    <input 
                                        type="number" 
                                        id="min_stock_level" 
                                        name="min_stock_level" 
                                        min="0" 
                                        value="5"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                        placeholder="5"
                                    >
                                </div>
                            </div>

                            <!-- Status and Options -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                        Status *
                                    </label>
                                    <select 
                                        id="status" 
                                        name="status" 
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    >
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="out_of_stock">Out of Stock</option>
                                    </select>
                                </div>
                                
                                <div class="flex items-center">
                                    <input 
                                        type="checkbox" 
                                        id="is_featured" 
                                        name="is_featured" 
                                        class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded"
                                    >
                                    <label for="is_featured" class="ml-2 block text-sm text-gray-700">
                                        Featured Product
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Image Upload -->
                        <div class="space-y-6">
                            <!-- Product Image -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Product Image
                                </label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-purple-500 transition-colors">
                                    <div id="imagePreview" class="mb-4">
                                        <i class="fas fa-image text-gray-400 text-4xl"></i>
                                        <p class="text-gray-500 text-sm mt-2">No image selected</p>
                                    </div>
                                    <input 
                                        type="file" 
                                        id="product_image" 
                                        name="product_image" 
                                        accept="image/*"
                                        class="hidden"
                                        onchange="previewImage(event)"
                                    >
                                    <label for="product_image" class="cursor-pointer bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                                        <i class="fas fa-upload mr-2"></i>Choose Image
                                    </label>
                                    <p class="text-xs text-gray-500 mt-2">
                                        JPG, PNG, GIF up to 5MB
                                    </p>
                                </div>
                            </div>

                            <!-- Image Guidelines -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <h4 class="font-semibold text-blue-800 mb-2">
                                    <i class="fas fa-info-circle mr-2"></i>Image Guidelines
                                </h4>
                                <ul class="text-sm text-blue-700 space-y-1">
                                    <li>• Recommended size: 800x800 pixels</li>
                                    <li>• Maximum file size: 5MB</li>
                                    <li>• Formats: JPG, PNG, GIF</li>
                                    <li>• Use high-quality images</li>
                                    <li>• Plain background recommended</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-8 flex justify-end space-x-4 border-t pt-6">
                        <a href="app.php?action=products" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                            Cancel
                        </a>
                        <button 
                            type="submit" 
                            class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2"
                        >
                            <i class="fas fa-save mr-2"></i>
                            Save Product
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('imagePreview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `
                        <img src="${e.target.result}" alt="Product preview" class="w-full h-48 object-cover rounded">
                        <p class="text-gray-500 text-sm mt-2">${file.name}</p>
                    `;
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>
