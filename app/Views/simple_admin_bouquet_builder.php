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
                        <i class="fas fa-spa text-pink-500"></i> Fleur Admin
                    </h1>
                </div>
                
                <div class="flex items-center space-x-6">
                    <a href="app.php?action=admin" class="text-gray-700 hover:text-purple-600">
                        <i class="fas fa-tachometer-alt mr-1"></i>Dashboard
                    </a>
                    <a href="app.php?action=orders" class="text-gray-700 hover:text-purple-600">
                        <i class="fas fa-shopping-bag mr-1"></i>Orders
                    </a>
                    <a href="app.php?action=products" class="text-gray-700 hover:text-purple-600">
                        <i class="fas fa-box mr-1"></i>Products
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

    <div class="container mx-auto px-4 py-8">
        <!-- Page Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Admin Custom Bouquet Builder</h1>
            <p class="text-gray-600 text-lg">Create custom bouquets for customers or special orders</p>
            <div class="mt-4 flex justify-center space-x-4">
                <a href="app.php?action=bouquet_builder" class="text-purple-600 hover:text-purple-800 text-sm">
                    <i class="fas fa-external-link-alt mr-1"></i>View Customer Version
                </a>
                <a href="app.php?action=admin" class="text-gray-600 hover:text-gray-800 text-sm">
                    <i class="fas fa-arrow-left mr-1"></i>Back to Dashboard
                </a>
            </div>
        </div>

        <form method="POST" action="app.php?action=save_admin_bouquet" id="adminBouquetForm">
            <!-- Step 1: Choose Size -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">
                    <i class="fas fa-ruler-combined text-purple-600 mr-2"></i>Step 1: Choose Your Size
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <?php foreach ($bouquet_sizes as $size_key => $size): ?>
                        <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-purple-500 cursor-pointer transition-colors size-option" data-size="<?= $size_key ?>" data-price="<?= $size['base_price'] ?>">
                            <div class="text-center">
                                <div class="text-3xl mb-2">
                                    <?php
                                    $icons = ['small' => 'fa-seedling', 'medium' => 'fa-leaf', 'large' => 'fa-tree', 'xlarge' => 'fa-spa'];
                                    echo '<i class="fas ' . $icons[$size_key] . ' text-purple-600"></i>';
                                    ?>
                                </div>
                                <h3 class="font-semibold text-lg"><?= $size['name'] ?></h3>
                                <p class="text-gray-600 text-sm">Up to <?= $size['max_flowers'] ?> flowers</p>
                                <p class="text-purple-600 font-bold mt-2">₱<?= number_format($size['base_price'], 2) ?></p>
                            </div>
                            <input type="radio" name="size" value="<?= $size_key ?>" class="hidden" required>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Step 2: Choose Style -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">
                    <i class="fas fa-palette text-purple-600 mr-2"></i>Step 2: Choose Your Style
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php foreach ($bouquet_styles as $style_key => $style): ?>
                        <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-purple-500 cursor-pointer transition-colors style-option" data-style="<?= $style_key ?>">
                            <div class="flex items-center">
                                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mr-4">
                                    <i class="fas fa-flower text-purple-600 text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg"><?= $style['name'] ?></h3>
                                    <p class="text-gray-600 text-sm"><?= $style['description'] ?></p>
                                </div>
                            </div>
                            <input type="radio" name="style" value="<?= $style_key ?>" class="hidden" required>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Step 3: Choose Color Theme -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">
                    <i class="fas fa-paint-brush text-purple-600 mr-2"></i>Step 3: Choose Color Theme
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach ($color_themes as $theme_key => $theme): ?>
                        <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-purple-500 cursor-pointer transition-colors theme-option" data-theme="<?= $theme_key ?>">
                            <div class="text-center">
                                <div class="flex justify-center space-x-2 mb-3">
                                    <?php foreach ($theme['colors'] as $color): ?>
                                        <div class="w-8 h-8 rounded-full" style="background-color: <?= $color ?>"></div>
                                    <?php endforeach; ?>
                                </div>
                                <h3 class="font-semibold"><?= $theme['name'] ?></h3>
                            </div>
                            <input type="radio" name="color_theme" value="<?= $theme_key ?>" class="hidden" required>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Step 4: Select Flowers -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">
                    <i class="fas fa-spa text-purple-600 mr-2"></i>Step 4: Select Your Flowers
                </h2>
                
                <!-- Flower Counter -->
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-seedling text-purple-600 mr-2"></i>
                            <span class="font-semibold text-purple-800">Flower Counter:</span>
                            <span id="flowerCount" class="ml-2 text-lg font-bold text-purple-600">0</span>
                            <span id="maxFlowerCount" class="ml-1 text-gray-600">/ 0 flowers</span>
                        </div>
                        <div id="flowerWarning" class="hidden text-orange-600 text-sm font-medium">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            <span id="warningText"></span>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach ($flowers as $flower): ?>
                        <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-purple-500 transition-colors flower-option">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h4 class="font-semibold"><?= $flower['name'] ?></h4>
                                    <p class="text-gray-600 text-sm">₱<?= number_format($flower['price'], 2) ?> each</p>
                                    <p class="text-xs text-gray-500">Stock: <?= $flower['stock_quantity'] ?></p>
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

            <!-- Step 5: Personal Message -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">
                    <i class="fas fa-envelope text-purple-600 mr-2"></i>Step 5: Add a Personal Message (Optional)
                </h2>
                
                <div class="max-w-md">
                    <input type="text" name="bouquet_name" placeholder="Bouquet Name (e.g., 'Anniversary Special')" class="w-full px-4 py-2 border border-gray-300 rounded-lg mb-4 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <textarea name="message" rows="3" placeholder="Add a personal message for the card..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
                </div>
            </div>

            <!-- Step 6: Customer Information (Admin Only) -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">
                    <i class="fas fa-user text-purple-600 mr-2"></i>Step 6: Customer Information (Optional)
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Customer Name</label>
                        <input type="text" name="customer_name" placeholder="Enter customer name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Customer Email</label>
                        <input type="email" name="customer_email" placeholder="Enter customer email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Date</label>
                        <input type="date" name="delivery_date" min="<?= date('Y-m-d', strtotime('+1 day')) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Time</label>
                        <select name="delivery_time" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">Select time</option>
                            <option value="morning">Morning (9:00 AM - 12:00 PM)</option>
                            <option value="afternoon">Afternoon (12:00 PM - 5:00 PM)</option>
                            <option value="evening">Evening (5:00 PM - 8:00 PM)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Add-ons Section -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">
                    <i class="fas fa-gift text-pink-500 mr-2"></i>Make it Extra Special
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-purple-500 transition-colors">
                        <div class="flex items-center mb-3">
                            <input type="checkbox" name="add_ons[vase]" value="1" class="add-on-checkbox mr-3" data-price="250">
                            <i class="fas fa-wine-glass text-purple-600 text-xl mr-2"></i>
                            <div>
                                <h4 class="font-semibold">Add a Vase</h4>
                                <p class="text-purple-600 font-bold">+₱250</p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600">Beautiful glass vase perfect for your bouquet</p>
                    </div>
                    
                    <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-purple-500 transition-colors">
                        <div class="flex items-center mb-3">
                            <input type="checkbox" name="add_ons[chocolates]" value="1" class="add-on-checkbox mr-3" data-price="150">
                            <i class="fas fa-candy-cane text-purple-600 text-xl mr-2"></i>
                            <div>
                                <h4 class="font-semibold">Add Chocolates</h4>
                                <p class="text-purple-600 font-bold">+₱150</p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600">Premium chocolate assortment</p>
                    </div>
                    
                    <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-purple-500 transition-colors">
                        <div class="flex items-center mb-3">
                            <input type="checkbox" name="add_ons[teddy_bear]" value="1" class="add-on-checkbox mr-3" data-price="200">
                            <i class="fas fa-bear text-purple-600 text-xl mr-2"></i>
                            <div>
                                <h4 class="font-semibold">Add a Teddy Bear</h4>
                                <p class="text-purple-600 font-bold">+₱200</p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600">Cuddly teddy bear companion</p>
                    </div>
                </div>
            </div>

            <!-- Price Summary -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6 md:sticky md:top-4">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">
                    <i class="fas fa-calculator text-purple-600 mr-2"></i>Price Summary
                </h2>
                
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span>Base Price:</span>
                        <span id="basePrice">₱0.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Flower Cost:</span>
                        <span id="flowerCost">₱0.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Add-ons:</span>
                        <span id="addOnCost">₱0.00</span>
                    </div>
                    <div class="border-t pt-2">
                        <div class="flex justify-between text-xl font-bold text-purple-600">
                            <span>Total Price:</span>
                            <span id="totalPrice">₱0.00</span>
                        </div>
                    </div>
                </div>
                
                <input type="hidden" name="total_price" id="totalPriceInput" value="0">
                
                <!-- Selected Options Display -->
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <h4 class="font-semibold text-gray-700 mb-2">Selected Options:</h4>
                    <div id="selectedOptions" class="text-sm text-gray-600">
                        <p>Size: <span id="selectedSize" class="font-medium">None</span></p>
                        <p>Style: <span id="selectedStyle" class="font-medium">None</span></p>
                        <p>Theme: <span id="selectedTheme" class="font-medium">None</span></p>
                        <p>Flowers: <span id="selectedFlowers" class="font-medium">0</span></p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-center space-x-4">
                <a href="app.php?action=admin" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
                <button type="submit" class="bg-purple-600 text-white px-8 py-3 rounded-lg hover:bg-purple-700">
                    <i class="fas fa-save mr-2"></i>Create Custom Bouquet
                </button>
            </div>
        </form>
    </div>

    <script>
        // Initialize variables
        let selectedSize = null;
        let selectedStyle = null;
        let selectedTheme = null;
        let basePrice = 0;
        let flowerCost = 0;
        let addOnCost = 0;
        let maxFlowers = 0;
        
        // Size limits
        const sizeLimits = {
            'small': 6,
            'medium': 12,
            'large': 18,
            'xlarge': 24
        };

        // Size selection with enhanced UI
        document.querySelectorAll('.size-option').forEach(option => {
            option.addEventListener('click', function() {
                // Remove previous selection
                document.querySelectorAll('.size-option').forEach(opt => {
                    opt.classList.remove('border-purple-500', 'bg-purple-50', 'border-4');
                    opt.classList.add('border-gray-200');
                });
                
                // Add selection with thicker border and checkmark
                this.classList.remove('border-gray-200');
                this.classList.add('border-purple-500', 'bg-purple-50', 'border-4');
                
                // Add checkmark icon
                const existingCheck = this.querySelector('.checkmark');
                if (!existingCheck) {
                    const checkmark = document.createElement('div');
                    checkmark.className = 'checkmark absolute top-2 right-2 bg-purple-600 text-white rounded-full w-6 h-6 flex items-center justify-center';
                    checkmark.innerHTML = '<i class="fas fa-check text-xs"></i>';
                    this.style.position = 'relative';
                    this.appendChild(checkmark);
                }
                
                selectedSize = this.dataset.size;
                basePrice = parseFloat(this.dataset.price);
                maxFlowers = sizeLimits[selectedSize] || 0;
                
                // Update radio button
                this.querySelector('input[type="radio"]').checked = true;
                
                // Update displays
                updateFlowerCounter();
                updatePrice();
                updateSelectedOptions();
            });
        });

        // Style selection
        document.querySelectorAll('.style-option').forEach(option => {
            option.addEventListener('click', function() {
                // Remove previous selection
                document.querySelectorAll('.style-option').forEach(opt => {
                    opt.classList.remove('border-purple-500', 'bg-purple-50', 'border-4');
                    opt.classList.add('border-gray-200');
                });
                
                // Add selection
                this.classList.remove('border-gray-200');
                this.classList.add('border-purple-500', 'bg-purple-50', 'border-4');
                
                // Add checkmark
                const existingCheck = this.querySelector('.checkmark');
                if (!existingCheck) {
                    const checkmark = document.createElement('div');
                    checkmark.className = 'checkmark absolute top-2 right-2 bg-purple-600 text-white rounded-full w-6 h-6 flex items-center justify-center';
                    checkmark.innerHTML = '<i class="fas fa-check text-xs"></i>';
                    this.style.position = 'relative';
                    this.appendChild(checkmark);
                }
                
                selectedStyle = this.dataset.style;
                
                // Update radio button
                this.querySelector('input[type="radio"]').checked = true;
                
                updateSelectedOptions();
            });
        });

        // Theme selection
        document.querySelectorAll('.theme-option').forEach(option => {
            option.addEventListener('click', function() {
                // Remove previous selection
                document.querySelectorAll('.theme-option').forEach(opt => {
                    opt.classList.remove('border-purple-500', 'bg-purple-50', 'border-4');
                    opt.classList.add('border-gray-200');
                });
                
                // Add selection
                this.classList.remove('border-gray-200');
                this.classList.add('border-purple-500', 'bg-purple-50', 'border-4');
                
                // Add checkmark
                const existingCheck = this.querySelector('.checkmark');
                if (!existingCheck) {
                    const checkmark = document.createElement('div');
                    checkmark.className = 'checkmark absolute top-2 right-2 bg-purple-600 text-white rounded-full w-6 h-6 flex items-center justify-center';
                    checkmark.innerHTML = '<i class="fas fa-check text-xs"></i>';
                    this.style.position = 'relative';
                    this.appendChild(checkmark);
                }
                
                selectedTheme = this.dataset.theme;
                
                // Update radio button
                this.querySelector('input[type="radio"]').checked = true;
                
                updateSelectedOptions();
            });
        });

        // Flower selection with validation
        document.querySelectorAll('.flower-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const flowerId = this.dataset.flowerId;
                const quantityInput = document.querySelector(`.flower-quantity[data-flower-id="${flowerId}"]`);
                
                if (this.checked) {
                    // Check if adding this flower exceeds the limit
                    const currentCount = getCurrentFlowerCount();
                    const quantity = parseInt(quantityInput.value) || 1;
                    
                    if (currentCount + quantity > maxFlowers) {
                        // Show warning
                        showFlowerWarning();
                        this.checked = false;
                        return;
                    }
                    
                    quantityInput.disabled = false;
                    quantityInput.value = 1;
                } else {
                    quantityInput.disabled = true;
                    quantityInput.value = 0;
                    hideFlowerWarning();
                }
                
                updateFlowerCounter();
                updatePrice();
                updateSelectedOptions();
            });
        });

        // Flower quantity change with validation
        document.querySelectorAll('.flower-quantity').forEach(input => {
            input.addEventListener('input', function() {
                const flowerId = this.dataset.flowerId;
                const checkbox = document.querySelector(`.flower-checkbox[data-flower-id="${flowerId}"]`);
                const currentCount = getCurrentFlowerCount();
                const newQuantity = parseInt(this.value) || 0;
                
                if (checkbox.checked && currentCount > maxFlowers) {
                    // Adjust quantity to fit limit
                    const maxAllowed = maxFlowers - (currentCount - newQuantity);
                    this.value = Math.max(0, maxAllowed);
                    showFlowerWarning();
                } else {
                    hideFlowerWarning();
                }
                
                updateFlowerCounter();
                updatePrice();
                updateSelectedOptions();
            });
        });

        // Add-on selection
        document.querySelectorAll('.add-on-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', updatePrice);
        });

        // Update flower counter
        function updateFlowerCounter() {
            const count = getCurrentFlowerCount();
            document.getElementById('flowerCount').textContent = count;
            document.getElementById('maxFlowerCount').textContent = `/ ${maxFlowers} flowers`;
            
            // Update warning if needed
            if (count >= maxFlowers) {
                showFlowerWarning();
            } else {
                hideFlowerWarning();
            }
        }

        // Get current flower count
        function getCurrentFlowerCount() {
            let count = 0;
            document.querySelectorAll('.flower-checkbox:checked').forEach(checkbox => {
                const flowerId = checkbox.dataset.flowerId;
                const quantity = parseInt(document.querySelector(`.flower-quantity[data-flower-id="${flowerId}"]`).value) || 0;
                count += quantity;
            });
            return count;
        }

        // Show flower warning
        function showFlowerWarning() {
            const warning = document.getElementById('flowerWarning');
            const warningText = document.getElementById('warningText');
            
            if (selectedSize && getCurrentFlowerCount() >= maxFlowers) {
                warning.classList.remove('hidden');
                const nextSize = getNextSize(selectedSize);
                warningText.textContent = `You've reached the limit for a ${selectedSize} bouquet! Upgrade to ${nextSize} for more?`;
            }
        }

        // Hide flower warning
        function hideFlowerWarning() {
            document.getElementById('flowerWarning').classList.add('hidden');
        }

        // Get next size for upgrade suggestion
        function getNextSize(currentSize) {
            const sizes = ['small', 'medium', 'large', 'xlarge'];
            const currentIndex = sizes.indexOf(currentSize);
            return currentIndex < sizes.length - 1 ? sizes[currentIndex + 1] : currentSize;
        }

        // Update selected options display
        function updateSelectedOptions() {
            const sizeNames = {
                'small': 'Small', 'medium': 'Medium', 'large': 'Large', 'xlarge': 'Extra Large'
            };
            
            document.getElementById('selectedSize').textContent = selectedSize ? sizeNames[selectedSize] : 'None';
            document.getElementById('selectedStyle').textContent = selectedStyle ? selectedStyle.charAt(0).toUpperCase() + selectedStyle.slice(1) : 'None';
            document.getElementById('selectedTheme').textContent = selectedTheme ? selectedTheme.charAt(0).toUpperCase() + selectedTheme.slice(1) : 'None';
            document.getElementById('selectedFlowers').textContent = getCurrentFlowerCount();
        }

        // Enhanced price calculation
        function updatePrice() {
            flowerCost = 0;
            addOnCost = 0;
            
            // Calculate flower cost
            document.querySelectorAll('.flower-checkbox:checked').forEach(checkbox => {
                const flowerId = checkbox.dataset.flowerId;
                const price = parseFloat(checkbox.dataset.price);
                const quantity = parseInt(document.querySelector(`.flower-quantity[data-flower-id="${flowerId}"]`).value) || 0;
                flowerCost += price * quantity;
            });
            
            // Calculate add-on cost
            document.querySelectorAll('.add-on-checkbox:checked').forEach(checkbox => {
                addOnCost += parseFloat(checkbox.dataset.price);
            });
            
            const total = basePrice + flowerCost + addOnCost;
            
            // Update display
            document.getElementById('basePrice').textContent = '₱' + basePrice.toFixed(2);
            document.getElementById('flowerCost').textContent = '₱' + flowerCost.toFixed(2);
            document.getElementById('addOnCost').textContent = '₱' + addOnCost.toFixed(2);
            document.getElementById('totalPrice').textContent = '₱' + total.toFixed(2);
            document.getElementById('totalPriceInput').value = total;
        }

        // Form validation
        document.getElementById('adminBouquetForm').addEventListener('submit', function(e) {
            if (!selectedSize || !selectedStyle || !selectedTheme) {
                e.preventDefault();
                alert('Please select size, style, and color theme for your bouquet.');
                return false;
            }
            
            const selectedFlowers = document.querySelectorAll('.flower-checkbox:checked');
            if (selectedFlowers.length === 0) {
                e.preventDefault();
                alert('Please select at least one flower for your bouquet.');
                return false;
            }
            
            if (getCurrentFlowerCount() === 0) {
                e.preventDefault();
                alert('Please specify quantities for your selected flowers.');
                return false;
            }
            
            return true;
        });

        // Initialize displays
        updateFlowerCounter();
        updatePrice();
        updateSelectedOptions();
    </script>
</body>
</html>
