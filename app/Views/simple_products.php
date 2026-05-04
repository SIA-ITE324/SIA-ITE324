<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Our Products' ?></title>
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
                    <a href="app.php?action=products" class="text-purple-600 font-semibold">Products</a>
                    <a href="app.php?action=bouquet_builder" class="text-purple-600 hover:text-purple-700 font-semibold">
                        <i class="fas fa-magic mr-1"></i>Custom Bouquet
                    </a>
                    <a href="app.php?action=about" class="text-gray-700 hover:text-purple-600">About</a>
                    <a href="app.php?action=contact" class="text-gray-700 hover:text-purple-600">Contact</a>
                    <?php if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in']): ?>
                        <a href="app.php?action=cart" class="text-purple-600 hover:text-purple-700 relative">
                            <i class="fas fa-shopping-cart mr-1"></i>
                            Cart
                            <?php 
                            $cart_count = 0;
                            if (!empty($_SESSION['cart'])) {
                                $cart_count = array_sum($_SESSION['cart']);
                            }
                            if ($cart_count > 0): 
                            ?>
                                <span class="absolute -top-2 -right-2 bg-purple-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">
                                    <?= $cart_count ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in']): ?>
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
                    <?php else: ?>
                        <a href="app.php?action=login" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                            Login
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-purple-600 to-pink-600 text-white py-16">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold mb-4">Our Beautiful Products</h2>
            <p class="text-xl max-w-2xl mx-auto">
                Discover our stunning collection of fresh flowers and beautiful arrangements, perfect for any occasion
            </p>
        </div>
    </section>

    <!-- Filters Section -->
    <section class="py-8">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <select class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option>All Categories</option>
                        <option>Roses</option>
                        <option>Lilies</option>
                        <option>Tulips</option>
                        <option>Orchids</option>
                        <option>Mixed Bouquets</option>
                    </select>
                    <select class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option>Sort by: Name</option>
                        <option>Price: Low to High</option>
                        <option>Price: High to Low</option>
                        <option>Newest First</option>
                    </select>
                </div>
                <div class="flex items-center space-x-2">
                    <form method="GET" action="app.php" class="flex items-center space-x-2">
                        <input type="hidden" name="action" value="products">
                        <input type="text" name="search" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" placeholder="Search products..." class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    
    <!-- Search Results Message -->
    <?php if (isset($search_query) && !empty($search_query)): ?>
        <div class="container mx-auto px-4 py-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-blue-900">Search Results</h3>
                        <p class="text-blue-700">
                            <?php if (!empty($products)): ?>
                                Found <?= count($products) ?> product(s) for "<?= htmlspecialchars($search_query) ?>"
                            <?php else: ?>
                                No products found for "<?= htmlspecialchars($search_query) ?>"
                            <?php endif; ?>
                        </p>
                    </div>
                    <a href="app.php?action=products" class="text-blue-600 hover:text-blue-800 underline">
                        Clear Search
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Products Grid -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                            <!-- Product Image -->
                            <div class="h-64 bg-gradient-to-br from-purple-100 to-pink-100 flex items-center justify-center relative overflow-hidden">
                                <?php if ($product['images']): ?>
                                    <img src="<?= $product['images'] ?>" alt="<?= $product['name'] ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <i class="fas fa-spa text-6xl text-purple-400"></i>
                                <?php endif; ?>
                                <?php if ($product['is_featured']): ?>
                                    <span class="absolute top-4 left-4 bg-yellow-400 text-yellow-900 px-2 py-1 rounded-full text-xs font-semibold">
                                        Featured
                                    </span>
                                <?php endif; ?>
                                <?php if ($product['sale_price']): ?>
                                    <span class="absolute top-4 right-4 bg-red-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                                        Sale
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Product Details -->
                            <div class="p-6">
                                <!-- Category Badge -->
                                <div class="mb-2">
                                    <span class="inline-block px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">
                                        <?= $product['category_name'] ?? 'Uncategorized' ?>
                                    </span>
                                </div>
                                
                                <!-- Product Name -->
                                <h3 class="text-lg font-semibold text-gray-800 mb-2"><?= $product['name'] ?></h3>
                                
                                <!-- Product Description -->
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                    <?= $product['short_description'] ?? 'Beautiful flower arrangement perfect for any occasion.' ?>
                                </p>
                                
                                <!-- Price and Stock -->
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <?php if ($product['sale_price']): ?>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-lg font-bold text-purple-600">₱<?= number_format($product['sale_price'], 2) ?></span>
                                                <span class="text-sm text-gray-500 line-through">₱<?= number_format($product['price'], 2) ?></span>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-lg font-bold text-purple-600">₱<?= number_format($product['price'], 2) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500">Stock</p>
                                        <p class="text-sm font-semibold <?= $product['stock_quantity'] > 10 ? 'text-green-600' : 'text-red-600' ?>">
                                            <?= $product['stock_quantity'] ?>
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex space-x-2">
                                    <form method="POST" action="app.php?action=cart" class="flex-1">
                                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="w-full bg-purple-600 text-white py-2 px-4 rounded hover:bg-purple-700 transition duration-200">
                                            <i class="fas fa-shopping-cart mr-2"></i>
                                            Add to Cart
                                        </button>
                                    </form>
                                    <button class="bg-gray-200 text-gray-700 py-2 px-3 rounded hover:bg-gray-300 transition duration-200">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </div>
                                
                                <!-- Product Meta -->
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <div class="flex items-center justify-between text-xs text-gray-500">
                                        <span><i class="fas fa-tag mr-1"></i><?= $product['sku'] ?></span>
                                        <span><i class="fas fa-truck mr-1"></i>Free Delivery</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full text-center py-12">
                        <i class="fas fa-spa text-gray-300 text-6xl mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">No Products Available</h3>
                        <p class="text-gray-500">We're currently updating our inventory. Please check back soon!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="bg-purple-100 py-12">
        <div class="container mx-auto px-4 text-center">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Stay Updated with Our Latest Arrivals</h3>
            <p class="text-gray-600 mb-6">Get exclusive offers and be the first to know about new products</p>
            <div class="max-w-md mx-auto flex">
                <input type="email" placeholder="Enter your email" class="flex-1 px-4 py-2 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                <button class="bg-purple-600 text-white px-6 py-2 rounded-r-lg hover:bg-purple-700">
                    Subscribe
                </button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h5 class="text-xl font-bold mb-4">
                        <i class="fas fa-spa text-pink-500"></i> Fleur
                    </h5>
                    <p class="text-gray-400">Your trusted flower delivery service for all occasions.</p>
                </div>
                
                <div>
                    <h6 class="font-semibold mb-4">Quick Links</h6>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="app.php?action=about" class="hover:text-white">About Us</a></li>
                        <li><a href="app.php?action=products" class="hover:text-white">Products</a></li>
                        <li><a href="app.php?action=contact" class="hover:text-white">Contact</a></li>
                    </ul>
                </div>
                
                <div>
                    <h6 class="font-semibold mb-4">Services</h6>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Wedding Flowers</a></li>
                        <li><a href="#" class="hover:text-white">Corporate Events</a></li>
                        <li><a href="#" class="hover:text-white">Same Day Delivery</a></li>
                    </ul>
                </div>
                
                <div>
                    <h6 class="font-semibold mb-4">Contact Info</h6>
                    <ul class="space-y-2 text-gray-400">
                        <li><i class="fas fa-phone mr-2"></i> 09124567891</li>
                        <li><i class="fas fa-envelope mr-2"></i> info@fleur.com</li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i> General Santos City</li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2024 Fleur. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
