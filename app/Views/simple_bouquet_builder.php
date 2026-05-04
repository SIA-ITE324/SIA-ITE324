<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Create Your Custom Bouquet' ?></title>
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
                    <a href="app.php?action=home" class="text-gray-700 hover:text-purple-600">Home</a>
                    <a href="app.php?action=products" class="text-gray-700 hover:text-purple-600">Products</a>
                    <a href="app.php?action=bouquet_builder" class="text-purple-600 hover:text-purple-700 font-semibold">
                        <i class="fas fa-magic mr-1"></i>Custom Bouquet
                    </a>
                    <a href="app.php?action=about" class="text-gray-700 hover:text-purple-600">About</a>
                    <a href="app.php?action=contact" class="text-gray-700 hover:text-purple-600">Contact</a>
                    <?php if (isset($_SESSION['is_logged_in'])): ?>
                        <a href="app.php?action=cart" class="text-gray-700 hover:text-purple-600">
                            <i class="fas fa-shopping-cart mr-1"></i>Cart
                        </a>
                        <a href="app.php?action=<?= $_SESSION['user_role'] === 'admin' ? 'admin' : 'profile' ?>" class="text-gray-700 hover:text-purple-600">
                            <i class="fas fa-user mr-1"></i><?= ucfirst($_SESSION['user_role']) ?>
                        </a>
                        <a href="app.php?action=logout" class="text-red-600 hover:text-red-700">
                            <i class="fas fa-sign-out-alt mr-1"></i>Logout
                        </a>
                    <?php else: ?>
                        <a href="app.php?action=login" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <!-- Page Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Create Your Custom Bouquet</h1>
            <p class="text-gray-600 text-lg">Design the perfect bouquet by choosing your favorite flowers, style, and size</p>
        </div>

        <form method="POST" action="app.php?action=save_bouquet" id="bouquetForm">
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
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
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

            <!-- Step 5: Choose Fillers -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">
                    <i class="fas fa-spray-can text-purple-600 mr-2"></i>Step 5: Choose Your Fillers
                </h2>
                
                <p class="text-gray-600 mb-4">Add beautiful fillers to complement your flowers and create a fuller bouquet</p>
                
                <!-- Filler Counter -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-seedling text-green-600 mr-2"></i>
                            <span class="font-semibold text-green-800">Filler Counter:</span>
                            <span id="fillerCount" class="ml-2 text-lg font-bold text-green-600">0</span>
                            <span class="ml-1 text-gray-600">fillers selected</span>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Baby's Breath -->
                    <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-green-500 transition-colors filler-option">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h4 class="font-semibold">Baby's Breath</h4>
                                <p class="text-gray-600 text-sm">₱50 each</p>
                                <p class="text-gray-500 text-xs">Delicate white clusters</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-cloud text-green-600"></i>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" name="fillers[babys_breath][selected]" value="1" class="filler-checkbox" data-filler-id="babys_breath" data-price="50">
                            <input type="number" name="fillers[babys_breath][quantity]" min="0" max="10" value="0" class="w-20 px-2 py-1 border border-gray-300 rounded filler-quantity" data-filler-id="babys_breath" disabled>
                            <span class="text-sm text-gray-600">quantity</span>
                        </div>
                    </div>

                    <!-- Ferns -->
                    <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-green-500 transition-colors filler-option">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h4 class="font-semibold">Ferns</h4>
                                <p class="text-gray-600 text-sm">₱40 each</p>
                                <p class="text-gray-500 text-xs">Green foliage accent</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-leaf text-green-600"></i>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" name="fillers[ferns][selected]" value="1" class="filler-checkbox" data-filler-id="ferns" data-price="40">
                            <input type="number" name="fillers[ferns][quantity]" min="0" max="10" value="0" class="w-20 px-2 py-1 border border-gray-300 rounded filler-quantity" data-filler-id="ferns" disabled>
                            <span class="text-sm text-gray-600">quantity</span>
                        </div>
                    </div>

                    <!-- Eucalyptus -->
                    <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-green-500 transition-colors filler-option">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h4 class="font-semibold">Eucalyptus</h4>
                                <p class="text-gray-600 text-sm">₱60 each</p>
                                <p class="text-gray-500 text-xs">Aromatic silver-green</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-spa text-green-600"></i>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" name="fillers[eucalyptus][selected]" value="1" class="filler-checkbox" data-filler-id="eucalyptus" data-price="60">
                            <input type="number" name="fillers[eucalyptus][quantity]" min="0" max="10" value="0" class="w-20 px-2 py-1 border border-gray-300 rounded filler-quantity" data-filler-id="eucalyptus" disabled>
                            <span class="text-sm text-gray-600">quantity</span>
                        </div>
                    </div>

                    <!-- Wax Flower -->
                    <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-green-500 transition-colors filler-option">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h4 class="font-semibold">Wax Flower</h4>
                                <p class="text-gray-600 text-sm">₱55 each</p>
                                <p class="text-gray-500 text-xs">Tiny pink clusters</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-circle text-green-600 text-xs"></i>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" name="fillers[wax_flower][selected]" value="1" class="filler-checkbox" data-filler-id="wax_flower" data-price="55">
                            <input type="number" name="fillers[wax_flower][quantity]" min="0" max="10" value="0" class="w-20 px-2 py-1 border border-gray-300 rounded filler-quantity" data-filler-id="wax_flower" disabled>
                            <span class="text-sm text-gray-600">quantity</span>
                        </div>
                    </div>

                    <!-- Statice -->
                    <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-green-500 transition-colors filler-option">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h4 class="font-semibold">Statice</h4>
                                <p class="text-gray-600 text-sm">₱45 each</p>
                                <p class="text-gray-500 text-xs">Colorful paper-like flowers</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-star text-green-600"></i>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" name="fillers[statice][selected]" value="1" class="filler-checkbox" data-filler-id="statice" data-price="45">
                            <input type="number" name="fillers[statice][quantity]" min="0" max="10" value="0" class="w-20 px-2 py-1 border border-gray-300 rounded filler-quantity" data-filler-id="statice" disabled>
                            <span class="text-sm text-gray-600">quantity</span>
                        </div>
                    </div>

                    <!-- Italian Ruscus -->
                    <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-green-500 transition-colors filler-option">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h4 class="font-semibold">Italian Ruscus</h4>
                                <p class="text-gray-600 text-sm">₱35 each</p>
                                <p class="text-gray-500 text-xs">Elegant green leaves</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-feather text-green-600"></i>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" name="fillers[italian_ruscus][selected]" value="1" class="filler-checkbox" data-filler-id="italian_ruscus" data-price="35">
                            <input type="number" name="fillers[italian_ruscus][quantity]" min="0" max="10" value="0" class="w-20 px-2 py-1 border border-gray-300 rounded filler-quantity" data-filler-id="italian_ruscus" disabled>
                            <span class="text-sm text-gray-600">quantity</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 6: Personal Message -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">
                    <i class="fas fa-envelope text-purple-600 mr-2"></i>Step 6: Add a Personal Message (Optional)
                </h2>
                
                <div class="max-w-md">
                    <input type="text" name="bouquet_name" placeholder="Bouquet Name (e.g., 'Anniversary Special')" class="w-full px-4 py-2 border border-gray-300 rounded-lg mb-4 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <textarea name="message" rows="3" placeholder="Add a personal message for the card..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
                </div>
            </div>

            <!-- Step 6: Delivery Date & Time -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">
                    <i class="fas fa-calendar-alt text-purple-600 mr-2"></i>Step 6: Delivery Date & Time
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Date</label>
                        <input type="date" name="delivery_date" id="deliveryDate" min="<?= date('Y-m-d', strtotime('+1 day')) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                        <p class="text-xs text-gray-500 mt-1">Select your preferred delivery date (minimum 1 day advance notice)</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Time</label>
                        <select name="delivery_time" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
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
                        <span>Filler Cost:</span>
                        <span id="fillerCost">₱0.00</span>
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
                <button type="button" onclick="window.history.back()" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </button>
                <button type="submit" class="bg-purple-600 text-white px-8 py-3 rounded-lg hover:bg-purple-700">
                    <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
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

        // Color theme gradients for preview
        const themeGradients = {
            'romantic': 'linear-gradient(135deg, #ff6b9d, #feca57)',
            'tropical': 'linear-gradient(135deg, #f9ca24, #f0932b)',
            'elegant': 'linear-gradient(135deg, #636e72, #2d3436)',
            'vibrant': 'linear-gradient(135deg, #ff9ff3, #feca57, #48dbfb, #ff6b9d)',
            'pastel': 'linear-gradient(135deg, #ffeaa7, #fab1a0, #a29bfe, #fd79a8)',
            'monochrome': 'linear-gradient(135deg, #dfe6e9, #636e72)'
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

        // Style selection with tooltips
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
                
                // Update preview
                updateStylePreview();
                updateSelectedOptions();
            });
        });

        // Theme selection with visual preview
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
                
                // Update visual preview
                updateVisualPreview();
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

        // Filler selection
        document.querySelectorAll('.filler-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const fillerId = this.dataset.fillerId;
                const quantityInput = document.querySelector(`.filler-quantity[data-filler-id="${fillerId}"]`);
                
                if (this.checked) {
                    quantityInput.disabled = false;
                    quantityInput.value = 1;
                } else {
                    quantityInput.disabled = true;
                    quantityInput.value = 0;
                }
                
                updateFillerCounter();
                updatePrice();
                updateSelectedOptions();
            });
        });

        // Filler quantity change
        document.querySelectorAll('.filler-quantity').forEach(input => {
            input.addEventListener('input', function() {
                updateFillerCounter();
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

        // Update filler counter
        function updateFillerCounter() {
            const count = getCurrentFillerCount();
            document.getElementById('fillerCount').textContent = count;
        }

        // Get current filler count
        function getCurrentFillerCount() {
            let count = 0;
            document.querySelectorAll('.filler-checkbox:checked').forEach(checkbox => {
                const fillerId = checkbox.dataset.fillerId;
                const quantity = parseInt(document.querySelector(`.filler-quantity[data-filler-id="${fillerId}"]`).value) || 0;
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

        // Update visual preview based on theme
        function updateVisualPreview() {
            if (selectedTheme && themeGradients[selectedTheme]) {
                // Update style preview icons with theme colors
                document.querySelectorAll('.style-option .w-16').forEach(preview => {
                    preview.style.background = themeGradients[selectedTheme];
                });
            }
        }

        // Update style preview
        function updateStylePreview() {
            // This could update different visual elements based on selected style
            console.log('Style updated to:', selectedStyle);
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
            fillerCost = 0;
            addOnCost = 0;
            
            // Calculate flower cost
            document.querySelectorAll('.flower-checkbox:checked').forEach(checkbox => {
                const flowerId = checkbox.dataset.flowerId;
                const price = parseFloat(checkbox.dataset.price);
                const quantity = parseInt(document.querySelector(`.flower-quantity[data-flower-id="${flowerId}"]`).value) || 0;
                flowerCost += price * quantity;
            });
            
            // Calculate filler cost
            document.querySelectorAll('.filler-checkbox:checked').forEach(checkbox => {
                const fillerId = checkbox.dataset.fillerId;
                const price = parseFloat(checkbox.dataset.price);
                const quantity = parseInt(document.querySelector(`.filler-quantity[data-filler-id="${fillerId}"]`).value) || 0;
                fillerCost += price * quantity;
            });
            
            // Calculate add-on cost
            document.querySelectorAll('.add-on-checkbox:checked').forEach(checkbox => {
                addOnCost += parseFloat(checkbox.dataset.price);
            });
            
            const total = basePrice + flowerCost + fillerCost + addOnCost;
            
            // Update display
            document.getElementById('basePrice').textContent = '₱' + basePrice.toFixed(2);
            document.getElementById('flowerCost').textContent = '₱' + flowerCost.toFixed(2);
            document.getElementById('fillerCost').textContent = '₱' + fillerCost.toFixed(2);
            document.getElementById('addOnCost').textContent = '₱' + addOnCost.toFixed(2);
            document.getElementById('totalPrice').textContent = '₱' + total.toFixed(2);
            document.getElementById('totalPriceInput').value = total;
        }

        // Enhanced form validation
        document.getElementById('bouquetForm').addEventListener('submit', function(e) {
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

        // Initialize delivery date
        document.addEventListener('DOMContentLoaded', function() {
            const deliveryDate = document.getElementById('deliveryDate');
            if (deliveryDate) {
                // Set default date to 3 days from now
                const defaultDate = new Date();
                defaultDate.setDate(defaultDate.getDate() + 3);
                deliveryDate.value = defaultDate.toISOString().split('T')[0];
                
                // Disable weekends
                deliveryDate.addEventListener('change', function() {
                    const selectedDate = new Date(this.value);
                    const dayOfWeek = selectedDate.getDay();
                    
                    if (dayOfWeek === 0 || dayOfWeek === 6) {
                        alert('We don\'t deliver on weekends. Please select a weekday.');
                        this.value = '';
                    }
                });
            }
        });

        // Initialize displays
        updateFlowerCounter();
        updatePrice();
        updateSelectedOptions();
    </script>
</body>
</html>
