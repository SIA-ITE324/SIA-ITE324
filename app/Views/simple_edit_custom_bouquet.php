<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
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
                        <i class="fas fa-spa text-pink-500"></i> Fleur
                    </h1>
                </div>
                
                <div class="hidden md:flex items-center space-x-6">
                    <a href="app.php?action=admin" class="text-purple-600 hover:text-purple-700">
                        <i class="fas fa-tachometer-alt mr-1"></i>Admin Dashboard
                    </a>
                    <a href="app.php?action=orders" class="text-gray-700 hover:text-purple-600">
                        <i class="fas fa-shopping-bag mr-1"></i>Orders
                    </a>
                    <a href="app.php?action=edit_order&id=<?= $order_item['order_id'] ?>" class="text-gray-700 hover:text-purple-600">
                        <i class="fas fa-arrow-left mr-1"></i>Back to Order
                    </a>
                    <div class="relative group">
                        <button class="flex items-center text-gray-700 hover:text-purple-600">
                            <i class="fas fa-user mr-2"></i>
                            <?= $_SESSION['user_name'] ?>
                            <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                            <a href="app.php?action=profile" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">My Profile</a>
                            <a href="app.php?action=logout" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 border-t">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Edit Custom Bouquet</h1>
            <p class="text-gray-600">Order #<?= $order_item['order_number'] ?> - <?= $order_item['customer_name'] ?></p>
        </div>

        <form method="POST" action="app.php?action=update_custom_bouquet" id="editBouquetForm">
            <input type="hidden" name="order_item_id" value="<?= $order_item['id'] ?>">
            <input type="hidden" name="order_id" value="<?= $order_item['order_id'] ?>">
            
            <!-- Current Bouquet Info -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">
                    <i class="fas fa-info-circle text-purple-600 mr-2"></i>Current Bouquet Details
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-gray-50 p-4 rounded">
                        <p class="text-sm text-gray-600">Size</p>
                        <p class="font-semibold"><?= ucfirst($custom_details['size']) ?></p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded">
                        <p class="text-sm text-gray-600">Style</p>
                        <p class="font-semibold"><?= ucfirst($custom_details['style']) ?></p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded">
                        <p class="text-sm text-gray-600">Theme</p>
                        <p class="font-semibold"><?= ucfirst($custom_details['color_theme']) ?></p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded">
                        <p class="text-sm text-gray-600">Current Price</p>
                        <p class="font-semibold text-purple-600">₱<?= number_format($order_item['unit_price'], 2) ?></p>
                    </div>
                </div>
                
                <?php if (!empty($custom_details['flowers'])): ?>
                    <div class="mt-4">
                        <p class="text-sm text-gray-600 mb-2">Current Flowers:</p>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach ($custom_details['flowers'] as $flower): ?>
                                <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm">
                                    <?= $flower['name'] ?> (<?= $flower['quantity'] ?>)
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Edit Options -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">
                    <i class="fas fa-edit text-purple-600 mr-2"></i>Edit Options
                </h2>
                
                <!-- Size Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bouquet Size</label>
                    <select name="size" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="small" <?= $custom_details['size'] === 'small' ? 'selected' : '' ?>>Small (Up to 6 flowers)</option>
                        <option value="medium" <?= $custom_details['size'] === 'medium' ? 'selected' : '' ?>>Medium (Up to 12 flowers)</option>
                        <option value="large" <?= $custom_details['size'] === 'large' ? 'selected' : '' ?>>Large (Up to 18 flowers)</option>
                        <option value="xlarge" <?= $custom_details['size'] === 'xlarge' ? 'selected' : '' ?>>Extra Large (Up to 24 flowers)</option>
                    </select>
                </div>

                <!-- Style Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bouquet Style</label>
                    <select name="style" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="modern" <?= $custom_details['style'] === 'modern' ? 'selected' : '' ?>>Modern Linear</option>
                        <option value="classic" <?= $custom_details['style'] === 'classic' ? 'selected' : '' ?>>Classic Round</option>
                        <option value="cascading" <?= $custom_details['style'] === 'cascading' ? 'selected' : '' ?>>Cascading</option>
                        <option value="compact" <?= $custom_details['style'] === 'compact' ? 'selected' : '' ?>>Compact</option>
                    </select>
                </div>

                <!-- Color Theme Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Color Theme</label>
                    <select name="color_theme" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="romantic" <?= $custom_details['color_theme'] === 'romantic' ? 'selected' : '' ?>>Romantic</option>
                        <option value="tropical" <?= $custom_details['color_theme'] === 'tropical' ? 'selected' : '' ?>>Tropical</option>
                        <option value="elegant" <?= $custom_details['color_theme'] === 'elegant' ? 'selected' : '' ?>>Elegant</option>
                        <option value="vibrant" <?= $custom_details['color_theme'] === 'vibrant' ? 'selected' : '' ?>>Vibrant</option>
                        <option value="pastel" <?= $custom_details['color_theme'] === 'pastel' ? 'selected' : '' ?>>Pastel</option>
                        <option value="monochrome" <?= $custom_details['color_theme'] === 'monochrome' ? 'selected' : '' ?>>Monochrome</option>
                    </select>
                </div>

                <!-- Flower Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Flower Selection</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php foreach ($flowers as $flower): ?>
                            <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-purple-500 transition-colors">
                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <h4 class="font-semibold"><?= $flower['name'] ?></h4>
                                        <p class="text-gray-600 text-sm">₱<?= number_format($flower['price'], 2) ?> each</p>
                                    </div>
                                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-leaf text-purple-600"></i>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" name="flowers[<?= $flower['id'] ?>][selected]" value="1" class="flower-checkbox" data-flower-id="<?= $flower['id'] ?>" data-price="<?= $flower['price'] ?>">
                                    <input type="number" name="flowers[<?= $flower['id'] ?>][quantity]" min="0" max="10" value="0" class="w-20 px-2 py-1 border border-gray-300 rounded flower-quantity" data-flower-id="<?= $flower['id'] ?>" disabled>
                                    <span class="text-sm text-gray-600">quantity</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Message -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Personal Message</label>
                    <textarea name="message" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Add a personal message for the card..."><?= htmlspecialchars($custom_details['message'] ?? '') ?></textarea>
                </div>

                <!-- Price Adjustment -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price Adjustment (Optional)</label>
                    <div class="flex items-center space-x-4">
                        <input type="number" name="price_adjustment" step="0.01" placeholder="0.00" class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <span class="text-sm text-gray-600">Positive for increase, negative for discount</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-center space-x-4">
                <a href="app.php?action=edit_order&id=<?= $order_item['order_id'] ?>" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600">
                    <i class="fas fa-arrow-left mr-2"></i>Cancel
                </a>
                <button type="submit" class="bg-purple-600 text-white px-8 py-3 rounded-lg hover:bg-purple-700">
                    <i class="fas fa-save mr-2"></i>Update Bouquet
                </button>
            </div>
        </form>
    </div>

    <script>
        // Flower selection functionality
        document.querySelectorAll('.flower-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const flowerId = this.dataset.flowerId;
                const quantityInput = document.querySelector(`.flower-quantity[data-flower-id="${flowerId}"]`);
                
                if (this.checked) {
                    quantityInput.disabled = false;
                    quantityInput.value = 1;
                } else {
                    quantityInput.disabled = true;
                    quantityInput.value = 0;
                }
            });
        });

        // Pre-select current flowers
        <?php if (!empty($custom_details['flowers'])): ?>
            <?php foreach ($custom_details['flowers'] as $flower): ?>
                const checkbox<?= $flower['id'] ?> = document.querySelector(`.flower-checkbox[data-flower-id="<?= $flower['id'] ?>"]`);
                const quantityInput<?= $flower['id'] ?> = document.querySelector(`.flower-quantity[data-flower-id="<?= $flower['id'] ?>"]`);
                
                if (checkbox<?= $flower['id'] ?>) {
                    checkbox<?= $flower['id'] ?>.checked = true;
                    quantityInput<?= $flower['id'] ?>.disabled = false;
                    quantityInput<?= $flower['id'] ?>.value = <?= $flower['quantity'] ?>;
                }
            <?php endforeach; ?>
        <?php endif; ?>
    </script>
</body>
</html>
