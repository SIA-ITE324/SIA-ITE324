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
                
                <!-- Search Bar -->
                <div class="hidden md:flex flex-1 max-w-md mx-8">
                    <form method="GET" action="app.php" class="w-full">
                        <input type="hidden" name="action" value="products">
                        <div class="relative">
                            <input type="text" name="search" placeholder="Search for flowers, bouquets..." class="w-full px-4 py-2 pl-10 pr-4 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </form>
                </div>
                
                <div class="hidden md:flex items-center space-x-6">
                    <a href="app.php?action=home" class="text-purple-600 hover:text-purple-700">Home</a>
                    <a href="app.php?action=products" class="text-gray-700 hover:text-purple-600">Products</a>
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
                
                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button class="text-gray-700 hover:text-purple-600">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section with Flower Background -->
    <section class="relative min-h-screen bg-gradient-to-br from-purple-100 via-pink-50 to-purple-100 flex items-center justify-center">
        <!-- Flower Bouquet Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 text-purple-300 text-8xl">💐</div>
            <div class="absolute top-20 right-20 text-pink-300 text-6xl">🌹💐</div>
            <div class="absolute bottom-20 left-20 text-purple-300 text-7xl">💐🌷</div>
            <div class="absolute bottom-10 right-10 text-pink-300 text-9xl">🌺💐</div>
            <div class="absolute top-1/2 left-1/3 text-purple-300 text-6xl">🌸💐</div>
            <div class="absolute top-1/3 right-1/3 text-pink-300 text-7xl">💐🌻</div>
        </div>
        
        <div class="container mx-auto px-4 text-center relative z-10">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-5xl md:text-7xl font-bold text-purple-800 mb-6">
                    Welcome to Fleur
                </h2>
                <div class="text-2xl md:text-3xl text-purple-600 mb-8">
                    🌸 Your Local Flower Shop 🌸
                </div>
                <p class="text-lg md:text-xl text-gray-700 mb-12 leading-relaxed max-w-3xl mx-auto">
                    We are a collective of friends dedicated to the art of floral design. Fleur Shop offers a refined selection of seasonal blooms, thoughtfully arranged and delivered with care. Simple, elegant, and always personal.
                </p>
                <div class="space-y-4 md:space-y-0 md:space-x-4">
                    <a href="app.php?action=products" class="inline-block bg-purple-600 text-white px-8 py-4 rounded-lg font-semibold hover:bg-purple-700 transition-colors text-lg">
                        <i class="fas fa-shopping-bag mr-2"></i>Shop Our Collection
                    </a>
                    <a href="app.php?action=contact" class="inline-block border-2 border-purple-600 text-purple-600 px-8 py-4 rounded-lg font-semibold hover:bg-purple-600 hover:text-white transition-colors text-lg">
                        <i class="fas fa-phone mr-2"></i>Contact Us
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Custom Bouquet Builder Section -->
    <section class="bg-gradient-to-r from-pink-50 to-purple-50 py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h3 class="text-3xl font-bold text-gray-800 mb-4">Create Your Perfect Bouquet</h3>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Design your own custom bouquet with our interactive builder. Choose your favorite flowers, style, and size.
                </p>
                
                <!-- Progress Steps -->
                <div class="flex justify-center items-center space-x-4 mt-6">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-purple-600 text-white rounded-full flex items-center justify-center text-sm font-bold">1</div>
                        <span class="ml-2 text-sm text-gray-700">Choose Flowers</span>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400"></i>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gray-300 text-white rounded-full flex items-center justify-center text-sm font-bold">2</div>
                        <span class="ml-2 text-sm text-gray-500">Choose Wrap</span>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400"></i>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gray-300 text-white rounded-full flex items-center justify-center text-sm font-bold">3</div>
                        <span class="ml-2 text-sm text-gray-500">Add Note</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-lg p-8 max-w-4xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                    <div>
                        <h4 class="text-2xl font-semibold text-gray-800 mb-4">
                            <i class="fas fa-magic text-purple-600 mr-2"></i>Custom Bouquet Builder
                        </h4>
                        <ul class="space-y-3 mb-6">
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                <span>Choose from our fresh flower selection</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                <span>Select your preferred size and style</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                <span>Add a personal message</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                <span>Perfect for any occasion</span>
                            </li>
                        </ul>
                        <a href="app.php?action=bouquet_builder" class="bg-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-purple-700 inline-block">
                            <i class="fas fa-magic mr-2"></i>Start Building
                        </a>
                    </div>
                    <div class="text-center">
                        <div id="bouquetPreview" class="w-64 h-64 mx-auto bg-gradient-to-br from-purple-100 to-pink-100 rounded-full flex items-center justify-center relative overflow-hidden transition-all duration-300">
                            <div id="previewContent" class="text-center">
                                <i class="fas fa-spa text-purple-600 text-6xl"></i>
                                <p class="text-sm text-gray-600 mt-2">Your bouquet preview</p>
                            </div>
                        </div>
                        <p class="text-gray-600 mt-4">Starting from ₱500</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h3 class="text-3xl font-bold text-gray-800 mb-4">Featured Products</h3>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Discover our most popular flower arrangements, perfect for any occasion
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php if (!empty($featured_products)): ?>
                    <?php foreach ($featured_products as $product): ?>
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow group">
                            <div class="h-48 bg-gradient-to-br from-purple-100 to-pink-100 flex items-center justify-center relative overflow-hidden">
                                <?php if ($product['images']): ?>
                                    <img src="<?= $product['images'] ?>" alt="<?= $product['name'] ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <i class="fas fa-spa text-6xl text-purple-400"></i>
                                <?php endif; ?>
                                <!-- Quick View Button -->
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                                    <button onclick="quickView(<?= $product['id'] ?>)" class="opacity-0 group-hover:opacity-100 bg-white text-purple-600 px-4 py-2 rounded-lg font-semibold hover:bg-purple-50 transition-all duration-300">
                                        <i class="fas fa-eye mr-2"></i>Quick View
                                    </button>
                                </div>
                            </div>
                            <div class="p-6">
                                <!-- Customer Rating -->
                                <div class="flex items-center mb-2">
                                    <div class="flex text-yellow-400">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                    </div>
                                    <span class="ml-2 text-sm text-gray-600">(4.8)</span>
                                </div>
                                
                                <h4 class="text-xl font-semibold text-gray-800 mb-2"><?= $product['name'] ?></h4>
                                <p class="text-gray-600 mb-3"><?= $product['short_description'] ?></p>
                                
                                <!-- Occasion Tags -->
                                <div class="flex flex-wrap gap-2 mb-4">
                                    <?php 
                                    $tags = ['Birthday', 'Romance', 'Anniversary'];
                                    $random_tags = array_rand($tags, 2);
                                    foreach ($random_tags as $tag_index): 
                                    ?>
                                        <span class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded-full">#<?= $tags[$tag_index] ?></span>
                                    <?php endforeach; ?>
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-2xl font-bold text-purple-600">₱<?= number_format($product['price'], 2) ?></span>
                                    <form method="POST" action="app.php?action=cart" class="inline">
                                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                                            <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full text-center py-8">
                        <i class="fas fa-spa text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No featured products available at the moment.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    
    <!-- Features Section -->
    <section class="bg-gray-100 py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-truck text-purple-600 text-xl"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-800 mb-2">Fast Delivery</h4>
                    <p class="text-gray-600">Same-day delivery available for orders placed before 2 PM</p>
                </div>
                
                <div class="text-center">
                    <div class="bg-pink-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-heart text-pink-600 text-xl"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-800 mb-2">Fresh Flowers</h4>
                    <p class="text-gray-600">100% fresh flowers sourced from the best gardens</p>
                </div>
                
                <div class="text-center">
                    <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-award text-purple-600 text-xl"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-800 mb-2">Quality Guarantee</h4>
                    <p class="text-gray-600">Satisfaction guaranteed or your money back</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Social Proof - Customer Photos -->
    <section class="bg-gray-50 py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h3 class="text-3xl font-bold text-gray-800 mb-4">As Seen On Instagram</h3>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Real customers sharing their beautiful moments with Fleur flowers
                </p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <div class="relative group">
                    <img src="https://i.pinimg.com/1200x/71/21/b2/7121b23353a39f71779d3c1f024390b5.jpg" alt="Customer bouquet" class="w-full h-full object-cover rounded-lg">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 rounded-lg flex items-center justify-center">
                        <i class="fab fa-instagram text-white text-2xl opacity-0 group-hover:opacity-100 transition-all duration-300"></i>
                    </div>
                </div>
                <div class="relative group">
                    <img src="https://i.pinimg.com/736x/16/db/f6/16dbf6e9f0a6e9a1b27ea534a708d2d6.jpg" alt="Customer bouquet" class="w-full h-full object-cover rounded-lg">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 rounded-lg flex items-center justify-center">
                        <i class="fab fa-instagram text-white text-2xl opacity-0 group-hover:opacity-100 transition-all duration-300"></i>
                    </div>
                </div>
                <div class="relative group">
                    <img src="https://i.pinimg.com/1200x/a0/81/f2/a081f22963f50475dc523329bfeda220.jpg" alt="Customer bouquet" class="w-full h-full object-cover rounded-lg">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 rounded-lg flex items-center justify-center">
                        <i class="fab fa-instagram text-white text-2xl opacity-0 group-hover:opacity-100 transition-all duration-300"></i>
                    </div>
                </div>
                <div class="relative group">
                    <img src="https://i.pinimg.com/736x/dc/d9/47/dcd9478ec51faeef843a8cba0d0327e4.jpg" alt="Customer bouquet" class="w-full h-full object-cover rounded-lg">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 rounded-lg flex items-center justify-center">
                        <i class="fab fa-instagram text-white text-2xl opacity-0 group-hover:opacity-100 transition-all duration-300"></i>
                    </div>
                </div>
                <div class="relative group">
                    <img src="https://i.pinimg.com/736x/81/67/b0/8167b0226b9e010f097794f5b581d3f1.jpg" alt="Customer bouquet" class="w-full h-full object-cover rounded-lg">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 rounded-lg flex items-center justify-center">
                        <i class="fab fa-instagram text-white text-2xl opacity-0 group-hover:opacity-100 transition-all duration-300"></i>
                    </div>
                </div>
                <div class="relative group">
                    <img src="https://i.pinimg.com/736x/bc/5f/fa/bc5ffa36154fedec2284e60dba798053.jpg" alt="Customer bouquet" class="w-full h-full object-cover rounded-lg">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 rounded-lg flex items-center justify-center">
                        <i class="fab fa-instagram text-white text-2xl opacity-0 group-hover:opacity-100 transition-all duration-300"></i>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-8">
                <a href="#" class="text-purple-600 hover:text-purple-700 font-semibold">
                    <i class="fab fa-instagram mr-2"></i>Follow @fleurflowers for more inspiration
                </a>
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
                <p>&copy; 2025 Fleur. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript for Interactive Features -->
    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            if (searchTerm.length > 2) {
                // You can implement live search here
                console.log('Searching for:', searchTerm);
            }
        });

        // Quick View functionality
        function quickView(productId) {
            // Create modal overlay
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            modal.innerHTML = `
                <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-800">Quick View</h3>
                        <button onclick="closeQuickView()" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="text-center">
                        <i class="fas fa-spa text-6xl text-purple-400 mb-4"></i>
                        <p class="text-gray-600">Product details would appear here</p>
                        <p class="text-sm text-gray-500 mt-2">Product ID: ${productId}</p>
                    </div>
                    <div class="mt-6 flex space-x-3">
                        <button onclick="closeQuickView()" class="flex-1 bg-gray-200 text-gray-800 py-2 rounded hover:bg-gray-300">
                            Close
                        </button>
                        <button class="flex-1 bg-purple-600 text-white py-2 rounded hover:bg-purple-700">
                            View Details
                        </button>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }

        function closeQuickView() {
            const modal = document.querySelector('.fixed.inset-0');
            if (modal) {
                modal.remove();
            }
        }

        // Interactive Bouquet Preview
        const bouquetPreview = document.getElementById('bouquetPreview');
        const previewContent = document.getElementById('previewContent');
        
        // Simulate flower selection animation
        let flowerCount = 0;
        const flowers = ['🌹', '🌺', '🌸', '🌷', '🌻', '🌼'];
        
        function animateBouquet() {
            flowerCount++;
            if (flowerCount > 3) flowerCount = 1;
            
            const selectedFlowers = flowers.slice(0, flowerCount);
            previewContent.innerHTML = `
                <div class="text-center">
                    <div class="text-4xl mb-2">${selectedFlowers.join(' ')}</div>
                    <p class="text-sm text-gray-600">Building your bouquet...</p>
                </div>
            `;
            
            bouquetPreview.style.transform = 'scale(1.05)';
            setTimeout(() => {
                bouquetPreview.style.transform = 'scale(1)';
            }, 200);
        }

        // Start bouquet animation
        setInterval(animateBouquet, 3000);

        // Cart badge update
        function updateCartBadge() {
            <?php if (isset($_SESSION['cart'])): ?>
                const cartCount = <?= array_sum($_SESSION['cart']) ?>;
                const badge = document.querySelector('.fa-shopping-cart').parentElement.querySelector('span');
                if (badge) {
                    badge.textContent = cartCount;
                    badge.style.display = cartCount > 0 ? 'flex' : 'none';
                }
            <?php endif; ?>
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateCartBadge();
        });
    </script>
</body>
</html>
