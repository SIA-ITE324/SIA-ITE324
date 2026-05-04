<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Edit Inventory' ?></title>
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
                        <a href="app.php?action=products" class="flex items-center text-white hover:bg-gray-700 p-3 rounded">
                            <i class="fas fa-box mr-3"></i> Products
                        </a>
                    </li>
                    <li>
                        <a href="app.php?action=inventory" class="flex items-center text-white hover:bg-gray-700 p-3 rounded bg-gray-700">
                            <i class="fas fa-warehouse mr-3"></i> Inventory
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <!-- Flash Messages -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <i class="fas fa-check-circle mr-2"></i>
                    <?= $_SESSION['success'] ?>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <?= $_SESSION['error'] ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <!-- Edit Inventory Form -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Edit Inventory</h2>
                    <a href="app.php?action=inventory" class="text-gray-600 hover:text-gray-800">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Inventory
                    </a>
                </div>

                <form action="app.php?action=edit_inventory&id=<?= $product['id'] ?>" method="POST">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Product Info (Read-only) -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-700 mb-4">Product Information</h3>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Product Name</label>
                                <input type="text" value="<?= htmlspecialchars($product['name']) ?>" readonly
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-600">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">SKU</label>
                                <input type="text" value="<?= htmlspecialchars($product['sku']) ?>" readonly
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-600">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                                <input type="text" value="<?= htmlspecialchars($product['category_id'] ?? 'Not set') ?>" readonly
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-600">
                            </div>
                        </div>

                        <!-- Inventory Settings (Editable) -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-700 mb-4">Inventory Settings</h3>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Current Stock Quantity</label>
                                <input type="number" name="stock_quantity" value="<?= $product['stock_quantity'] ?>" required min="0"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500">
                                <p class="text-xs text-gray-500 mt-1">Number of units currently in stock</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Stock Level</label>
                                <input type="number" name="min_stock_level" value="<?= $product['min_stock_level'] ?>" required min="0"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500">
                                <p class="text-xs text-gray-500 mt-1">Alert when stock falls below this level</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Regular Price (₱)</label>
                                <input type="number" name="price" value="<?= $product['price'] ?>" required min="0" step="0.01"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Sale Price (₱)</label>
                                <input type="number" name="sale_price" value="<?= $product['sale_price'] ?>" min="0" step="0.01"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500">
                                <p class="text-xs text-gray-500 mt-1">Optional - leave empty if no sale</p>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Status Indicator -->
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <h4 class="font-medium text-gray-700 mb-2">Current Stock Status</h4>
                        <div class="flex items-center">
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                <?php
                                if ($product['stock_quantity'] <= $product['min_stock_level']) {
                                    echo 'bg-red-100 text-red-800';
                                } elseif ($product['stock_quantity'] <= $product['min_stock_level'] * 1.5) {
                                    echo 'bg-yellow-100 text-yellow-800';
                                } else {
                                    echo 'bg-green-100 text-green-800';
                                }
                                ?>">
                                <?php
                                if ($product['stock_quantity'] <= $product['min_stock_level']) {
                                    echo 'Low Stock';
                                } elseif ($product['stock_quantity'] <= $product['min_stock_level'] * 1.5) {
                                    echo 'Medium Stock';
                                } else {
                                    echo 'Good Stock';
                                }
                                ?>
                            </span>
                            <span class="ml-3 text-sm text-gray-600">
                                <?= $product['stock_quantity'] ?> units available
                            </span>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="app.php?action=inventory" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                            <i class="fas fa-save mr-2"></i>Update Inventory
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
