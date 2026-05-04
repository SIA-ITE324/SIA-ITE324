<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Shopping Cart' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
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
                    <a href="app.php?action=about" class="text-gray-700 hover:text-purple-600">About</a>
                    <a href="app.php?action=contact" class="text-gray-700 hover:text-purple-600">Contact</a>
                    <a href="app.php?action=cart" class="text-purple-600 hover:text-purple-700 relative">
                        <i class="fas fa-shopping-cart mr-1"></i>
                        Cart
                        <?php if (!empty($_SESSION['cart'])): ?>
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                <?= array_sum($_SESSION['cart']) ?>
                            </span>
                        <?php endif; ?>
                    </a>
                    
                    <div class="relative group">
                        <button class="flex items-center text-gray-700 hover:text-purple-600">
                            <i class="fas fa-user mr-2"></i>
                            <?= $_SESSION['user_name'] ?>
                            <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                <a href="app.php?action=admin" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Admin Dashboard</a>
                            <?php endif; ?>
                            <a href="app.php?action=profile" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">My Profile</a>
                            <a href="app.php?action=orders" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">My Orders</a>
                            <a href="app.php?action=logout" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 border-t">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <!-- Flash Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <i class="fas fa-check-circle mr-2"></i>
                <?= $_SESSION['success'] ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Shopping Cart</h2>
            <a href="app.php?action=products" class="text-purple-600 hover:text-purple-800">
                <i class="fas fa-arrow-left mr-2"></i>Continue Shopping
            </a>
        </div>

        <?php if (!empty($cart_items)): ?>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Cart Items</h3>
                        
                        <div class="space-y-4">
                            <?php foreach ($cart_items as $item): ?>
                                <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                                    <!-- Product Image -->
                                    <div class="w-20 h-20 bg-gray-100 rounded flex items-center justify-center flex-shrink-0">
                                        <?php if ($item['product']['images']): ?>
                                            <img src="<?= $item['product']['images'] ?>" alt="<?= $item['product']['name'] ?>" class="w-full h-full object-cover rounded">
                                        <?php else: ?>
                                            <i class="fas fa-spa text-gray-400 text-2xl"></i>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Product Details -->
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-800">
                                            <?= $item['product']['name'] ?>
                                            <?php if (isset($item['is_custom']) && $item['is_custom']): ?>
                                                <span class="ml-2 text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded">
                                                    <i class="fas fa-magic mr-1"></i>Custom
                                                </span>
                                            <?php endif; ?>
                                        </h4>
                                        <?php if (isset($item['is_custom']) && $item['is_custom']): ?>
                                            <div class="text-sm text-gray-600 mb-2">
                                                <div class="space-y-1">
                                                    <p><strong>Size:</strong> <?= ucfirst($_SESSION['custom_bouquets'][$item['product']['id']]['size']) ?></p>
                                                    <p><strong>Style:</strong> <?= ucfirst($_SESSION['custom_bouquets'][$item['product']['id']]['style']) ?></p>
                                                    <p><strong>Theme:</strong> <?= ucfirst($_SESSION['custom_bouquets'][$item['product']['id']]['color_theme']) ?></p>
                                                    <?php if (!empty($_SESSION['custom_bouquets'][$item['product']['id']]['message'])): ?>
                                                        <p><strong>Message:</strong> <?= $_SESSION['custom_bouquets'][$item['product']['id']]['message'] ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <p class="text-sm text-gray-600"><?= $item['product']['short_description'] ?></p>
                                        <?php endif; ?>
                                        <div class="flex items-center mt-2">
                                            <?php if (isset($item['is_custom']) && $item['is_custom']): ?>
                                                <span class="text-lg font-bold text-purple-600">₱<?= number_format($_SESSION['custom_bouquets'][$item['product']['id']]['total_price'], 2) ?></span>
                                            <?php elseif ($item['product']['sale_price']): ?>
                                                <span class="text-lg font-bold text-purple-600">₱<?= number_format($item['product']['sale_price'], 2) ?></span>
                                                <span class="text-sm text-gray-500 line-through ml-2">₱<?= number_format($item['product']['price'], 2) ?></span>
                                            <?php else: ?>
                                                <span class="text-lg font-bold text-purple-600">₱<?= number_format($item['product']['price'], 2) ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <!-- Quantity and Actions -->
                                    <div class="text-right">
                                        <div class="mb-2">
                                            <span class="text-sm text-gray-600">Qty: <?= $item['quantity'] ?></span>
                                        </div>
                                        <div class="text-sm font-semibold text-gray-800">
                                            ₱<?= number_format($item['subtotal'], 2) ?>
                                        </div>
                                        <a href="app.php?action=remove_from_cart&product_id=<?= $item['product']['id'] ?>" class="text-red-600 hover:text-red-800 text-sm">
                                            <i class="fas fa-trash mr-1"></i>Remove
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-lg p-6 sticky top-24">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Summary</h3>
                        
                        <div class="space-y-3 mb-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium">₱<?= number_format($total, 2) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Shipping</span>
                                <span class="font-medium text-green-600">Free</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tax</span>
                                <span class="font-medium">₱0.00</span>
                            </div>
                        </div>
                        
                        <div class="border-t pt-4 mb-6">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-semibold text-gray-800">Total</span>
                                <span class="text-2xl font-bold text-purple-600">₱<?= number_format($total, 2) ?></span>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <a href="app.php?action=checkout" class="w-full bg-purple-600 text-white py-3 px-4 rounded-lg hover:bg-purple-700 text-center block">
                                <i class="fas fa-credit-card mr-2"></i>Proceed to Checkout
                            </a>
                            <a href="app.php?action=products" class="w-full bg-gray-200 text-gray-700 py-3 px-4 rounded-lg hover:bg-gray-300 text-center block">
                                <i class="fas fa-shopping-cart mr-2"></i>Continue Shopping
                            </a>
                        </div>
                        
                        <!-- Security Badge -->
                        <div class="mt-6 text-center">
                            <div class="flex items-center justify-center text-sm text-gray-500">
                                <i class="fas fa-lock text-green-600 mr-2"></i>
                                Secure Checkout
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Empty Cart -->
            <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                <i class="fas fa-shopping-cart text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Your cart is empty</h3>
                <p class="text-gray-600 mb-6">Looks like you haven't added any items to your cart yet.</p>
                <a href="app.php?action=products" class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700">
                    <i class="fas fa-shopping-cart mr-2"></i>Start Shopping
                </a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
