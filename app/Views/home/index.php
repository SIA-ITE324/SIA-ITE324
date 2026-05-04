<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Fleur - Flower Order Management System' ?></title>
    <meta name="description" content="<?= $meta_description ?? 'Beautiful flowers and arrangements for every occasion' ?>">
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
                    <a href="<?= site_url('/') ?>" class="text-gray-700 hover:text-purple-600">Home</a>
                    <a href="<?= site_url('/products') ?>" class="text-gray-700 hover:text-purple-600">Products</a>
                    <a href="<?= site_url('/about') ?>" class="text-gray-700 hover:text-purple-600">About</a>
                    <a href="<?= site_url('/contact') ?>" class="text-gray-700 hover:text-purple-600">Contact</a>
                    
                    <?php if (session()->get('is_logged_in')): ?>
                        <div class="relative group">
                            <button class="flex items-center text-gray-700 hover:text-purple-600">
                                <i class="fas fa-user mr-1"></i>
                                <?= session()->get('user_name') ?>
                                <i class="fas fa-chevron-down ml-1 text-xs"></i>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                <?php if (session()->get('user_role') === 'admin'): ?>
                                    <a href="<?= site_url('/admin/dashboard') ?>" class="block px-4 py-2 text-gray-700 hover:bg-purple-50">Admin Dashboard</a>
                                <?php elseif (session()->get('user_role') === 'staff'): ?>
                                    <a href="<?= site_url('/staff/dashboard') ?>" class="block px-4 py-2 text-gray-700 hover:bg-purple-50">Staff Dashboard</a>
                                <?php else: ?>
                                    <a href="<?= site_url('/customer/dashboard') ?>" class="block px-4 py-2 text-gray-700 hover:bg-purple-50">My Dashboard</a>
                                <?php endif; ?>
                                <a href="<?= site_url('/auth/logout') ?>" class="block px-4 py-2 text-gray-700 hover:bg-purple-50 border-t">Logout</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?= site_url('/login') ?>" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                            <i class="fas fa-sign-in-alt mr-1"></i> Login
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button class="text-gray-700 hover:text-purple-600" onclick="toggleMobileMenu()">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobileMenu" class="hidden md:hidden bg-white border-t">
            <div class="container mx-auto px-4 py-4 space-y-2">
                <a href="<?= site_url('/') ?>" class="block text-gray-700 hover:text-purple-600 py-2">Home</a>
                <a href="<?= site_url('/products') ?>" class="block text-gray-700 hover:text-purple-600 py-2">Products</a>
                <a href="<?= site_url('/about') ?>" class="block text-gray-700 hover:text-purple-600 py-2">About</a>
                <a href="<?= site_url('/contact') ?>" class="block text-gray-700 hover:text-purple-600 py-2">Contact</a>
                <?php if (session()->get('is_logged_in')): ?>
                    <a href="<?= site_url('/auth/logout') ?>" class="block text-gray-700 hover:text-purple-600 py-2">Logout</a>
                <?php else: ?>
                    <a href="<?= site_url('/login') ?>" class="block text-gray-700 hover:text-purple-600 py-2">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-purple-600 to-pink-600 text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl md:text-5xl font-bold mb-4">Welcome to Fleur</h2>
            <p class="text-xl mb-8">Beautiful flowers and arrangements for every occasion</p>
            <div class="space-x-4">
                <a href="<?= site_url('/products') ?>" class="bg-white text-purple-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100">
                    <i class="fas fa-shopping-cart mr-2"></i> Shop Now
                </a>
                <a href="<?= site_url('/about') ?>" class="border border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-purple-600">
                    <i class="fas fa-info-circle mr-2"></i> Learn More
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <h3 class="text-3xl font-bold text-center mb-12">Why Choose Fleur?</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-purple-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-truck text-purple-600 text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-semibold mb-2">Fast Delivery</h4>
                    <p class="text-gray-600">Same-day delivery available for orders placed before 2 PM</p>
                </div>
                <div class="text-center">
                    <div class="bg-pink-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-heart text-pink-600 text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-semibold mb-2">Fresh Flowers</h4>
                    <p class="text-gray-600">100% fresh flowers sourced from local and international growers</p>
                </div>
                <div class="text-center">
                    <div class="bg-green-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-green-600 text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-semibold mb-2">Quality Guarantee</h4>
                    <p class="text-gray-600">100% satisfaction guarantee on all our products</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <?php if (isset($featured_products) && !empty($featured_products)): ?>
    <section class="py-16 bg-gray-100">
        <div class="container mx-auto px-4">
            <h3 class="text-3xl font-bold text-center mb-12">Featured Products</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($featured_products as $product): ?>
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                        <div class="h-48 bg-gradient-to-br from-purple-100 to-pink-100 flex items-center justify-center">
                            <?php if ($product['images'] && !empty(json_decode($product['images']))): ?>
                                <img src="<?= base_url('uploads/products/' . json_decode($product['images'])[0]) ?>" 
                                     alt="<?= $product['name'] ?>" class="h-full w-full object-cover">
                            <?php else: ?>
                                <i class="fas fa-spa text-6xl text-purple-300"></i>
                            <?php endif; ?>
                        </div>
                        <div class="p-4">
                            <h4 class="font-semibold text-lg mb-2"><?= $product['name'] ?></h4>
                            <p class="text-gray-600 text-sm mb-3"><?= $product['short_description'] ?? 'Beautiful arrangement' ?></p>
                            <div class="flex justify-between items-center">
                                <span class="text-purple-600 font-bold">&#x20B1;<?= number_format($product['price'], 2) ?></span>
                                <a href="<?= site_url('/products/' . $product['slug']) ?>" 
                                   class="bg-purple-600 text-white px-3 py-1 rounded text-sm hover:bg-purple-700">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center mt-8">
                <a href="<?= site_url('/products') ?>" class="bg-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-purple-700">
                    <i class="fas fa-th mr-2"></i> View All Products
                </a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Categories Section -->
    <?php if (isset($categories) && !empty($categories)): ?>
    <section class="py-16">
        <div class="container mx-auto px-4">
            <h3 class="text-3xl font-bold text-center mb-12">Shop by Category</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <?php foreach ($categories as $category): ?>
                    <a href="<?= site_url('/products?category=' . $category['id']) ?>" 
                       class="bg-white rounded-lg shadow p-4 text-center hover:shadow-lg transition-shadow">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-100 to-pink-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-spa text-purple-600 text-xl"></i>
                        </div>
                        <h5 class="font-semibold text-sm"><?= $category['name'] ?></h5>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h4 class="text-xl font-bold mb-4">
                        <i class="fas fa-spa text-pink-500"></i> Fleur
                    </h4>
                    <p class="text-gray-400">Your trusted flower delivery service for all occasions.</p>
                </div>
                <div>
                    <h5 class="font-semibold mb-4">Quick Links</h5>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="<?= site_url('/products') ?>" class="hover:text-white">Products</a></li>
                        <li><a href="<?= site_url('/about') ?>" class="hover:text-white">About</a></li>
                        <li><a href="<?= site_url('/contact') ?>" class="hover:text-white">Contact</a></li>
                        <li><a href="<?= site_url('/faq') ?>" class="hover:text-white">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-semibold mb-4">Customer Service</h5>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="<?= site_url('/delivery') ?>" class="hover:text-white">Delivery Info</a></li>
                        <li><a href="<?= site_url('/terms') ?>" class="hover:text-white">Terms & Conditions</a></li>
                        <li><a href="<?= site_url('/privacy') ?>" class="hover:text-white">Privacy Policy</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-semibold mb-4">Contact Info</h5>
                    <ul class="space-y-2 text-gray-400">
                        <li><i class="fas fa-phone mr-2"></i> +1 234 567 8900</li>
                        <li><i class="fas fa-envelope mr-2"></i> info@fleur.com</li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i> 123 Flower St, City, State</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; <?= date('Y') ?> Fleur. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }
    </script>
</body>
</html>
