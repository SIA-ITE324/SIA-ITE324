<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'About Fleur' ?></title>
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
                    <a href="app.php?action=about" class="text-purple-600 font-semibold">About</a>
                    <a href="app.php?action=contact" class="text-gray-700 hover:text-purple-600">Contact</a>
                    
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
    <section class="bg-gradient-to-r from-purple-600 to-pink-600 text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl md:text-5xl font-bold mb-4">About Fleur</h2>
            <p class="text-xl max-w-2xl mx-auto">
                Bringing beauty and joy to every moment with our exquisite flower arrangements
            </p>
        </div>
    </section>

    <!-- Our Story -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h3 class="text-3xl font-bold text-gray-800 mb-6">Our Story</h3>
                    <p class="text-gray-600 mb-4">
                        Founded in 2025, Fleur began as a small circle of friends-owned flower shop with a simple mission: to bring beauty and happiness to people's lives through fresh, beautiful flowers.
                    </p>
                    <p class="text-gray-600 mb-4">
                        What started as a modest storefront has grown into a trusted name in floral arrangements, serving thousands of customers across the region. Our commitment to quality, creativity, and customer satisfaction has remained unchanged since day one.
                    </p>
                    <p class="text-gray-600 mb-6">
                        Today, Fleur continues to be a family-run business, combining traditional floral artistry with modern design sensibilities to create arrangements that speak to the heart.
                    </p>
                    <div class="flex space-x-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-purple-600">5000+</div>
                            <div class="text-sm text-gray-600">Happy Customers</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-purple-600">100+</div>
                            <div class="text-sm text-gray-600">Flower Varieties</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-purple-600">4.9★</div>
                            <div class="text-sm text-gray-600">Customer Rating</div>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-purple-100 to-pink-100 rounded-lg p-8 text-center">
                    <i class="fas fa-spa text-8xl text-purple-400"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Values -->
    <section class="bg-gray-100 py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h3 class="text-3xl font-bold text-gray-800 mb-4">Our Values</h3>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    The principles that guide everything we do, from selecting flowers to serving our customers
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-leaf text-purple-600 text-xl"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-800 mb-2">Freshness First</h4>
                    <p class="text-gray-600">We source only the freshest flowers from trusted growers to ensure maximum beauty and longevity.</p>
                </div>
                
                <div class="text-center">
                    <div class="bg-pink-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-heart text-pink-600 text-xl"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-800 mb-2">Artistic Excellence</h4>
                    <p class="text-gray-600">Our talented florists create stunning arrangements that combine artistry with emotion.</p>
                </div>
                
                <div class="text-center">
                    <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-purple-600 text-xl"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-800 mb-2">Customer Care</h4>
                    <p class="text-gray-600">We treat every customer like family, providing personalized service and attention to detail.</p>
                </div>
                
                <div class="text-center">
                    <div class="bg-pink-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-award text-pink-600 text-xl"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-800 mb-2">Quality Promise</h4>
                    <p class="text-gray-600">We stand behind our work with a satisfaction guarantee on every arrangement we create.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Meet the Team -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h3 class="text-3xl font-bold text-gray-800 mb-4">Meet Our Team</h3>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    The passionate people behind Fleur who bring beauty to life every day
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-32 h-32 bg-gradient-to-br from-purple-200 to-pink-200 rounded-full mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-user text-purple-600 text-3xl"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-800 mb-2">Bea Colitan</h4>
                    <p class="text-purple-600 mb-2">Founder & Head Florist</p>
                    <p class="text-gray-600 text-sm">With over 15 years of experience, Bea brings creativity and passion to every arrangement.</p>
                </div>
                
                <div class="text-center">
                    <div class="w-32 h-32 bg-gradient-to-br from-purple-200 to-pink-200 rounded-full mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-user text-purple-600 text-3xl"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-800 mb-2">Fiona Reeze Herrada</h4>
                    <p class="text-purple-600 mb-2">Operations Manager</p>
                    <p class="text-gray-600 text-sm">Fiona ensures smooth operations and exceptional customer service every step of the way.</p>
                </div>
                
                <div class="text-center">
                    <div class="w-32 h-32 bg-gradient-to-br from-purple-200 to-pink-200 rounded-full mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-user text-purple-600 text-3xl"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-800 mb-2">Mabelle Magbanua</h4>
                    <p class="text-purple-600 mb-2">Lead Designer</p>
                    <p class="text-gray-600 text-sm">Mabbelle's artistic vision and attention to detail create breathtaking floral designs.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-gradient-to-r from-purple-600 to-pink-600 text-white py-16">
        <div class="container mx-auto px-4 text-center">
            <h3 class="text-3xl font-bold mb-4">Ready to Experience the Fleur Difference?</h3>
            <p class="text-xl mb-8 max-w-2xl mx-auto">
                Let us help you create beautiful moments with our exquisite flower arrangements
            </p>
            <div class="space-x-4">
                <a href="app.php?action=products" class="bg-white text-purple-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100">
                    Browse Products
                </a>
                <a href="app.php?action=contact" class="border-2 border-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-purple-600">
                    Contact Us
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
                <p>&copy; 2024 Fleur. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
