<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Contact Fleur' ?></title>
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
                    <a href="app.php?action=contact" class="text-purple-600 font-semibold">Contact</a>
                    
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
            <h2 class="text-4xl md:text-5xl font-bold mb-4">Get in Touch</h2>
            <p class="text-xl max-w-2xl mx-auto">
                We'd love to hear from you! Whether you have questions, feedback, or want to place a custom order.
            </p>
        </div>
    </section>

    <!-- Contact Content -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Contact Form -->
                <div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Send us a Message</h3>
                    
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            <i class="fas fa-check-circle mr-2"></i>
                            <?= $_SESSION['success'] ?>
                            <?php unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>

                    <form class="space-y-6" method="POST" action="app.php?action=contact">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    First Name *
                                </label>
                                <input 
                                    type="text" 
                                    id="first_name" 
                                    name="first_name" 
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    placeholder="Your first name"
                                >
                            </div>
                            
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Last Name *
                                </label>
                                <input 
                                    type="text" 
                                    id="last_name" 
                                    name="last_name" 
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    placeholder="Your last name"
                                >
                            </div>
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address *
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                placeholder="your.email@example.com"
                            >
                        </div>
                        
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Phone Number
                            </label>
                            <input 
                                type="tel" 
                                id="phone" 
                                name="phone" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                placeholder="+1 (555) 123-4567"
                            >
                        </div>
                        
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                                Subject *
                            </label>
                            <select 
                                id="subject" 
                                name="subject" 
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            >
                                <option value="">Select a subject</option>
                                <option value="general">General Inquiry</option>
                                <option value="order">Order Question</option>
                                <option value="custom">Custom Order</option>
                                <option value="delivery">Delivery Issue</option>
                                <option value="feedback">Feedback</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                Message *
                            </label>
                            <textarea 
                                id="message" 
                                name="message" 
                                rows="6" 
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                placeholder="Tell us how we can help you..."
                            ></textarea>
                        </div>
                        
                        <div class="flex items-center">
                            <input 
                                type="checkbox" 
                                id="newsletter" 
                                name="newsletter" 
                                class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded"
                            >
                            <label for="newsletter" class="ml-2 block text-sm text-gray-700">
                                I'd like to receive updates and special offers
                            </label>
                        </div>
                        
                        <button 
                            type="submit" 
                            class="w-full bg-purple-600 text-white py-3 px-6 rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition duration-200"
                        >
                            <i class="fas fa-paper-plane mr-2"></i>
                            Send Message
                        </button>
                    </form>
                </div>
                
                <!-- Contact Information -->
                <div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Contact Information</h3>
                    
                    <div class="space-y-6">
                        <!-- Address -->
                        <div class="flex items-start space-x-4">
                            <div class="bg-purple-100 p-3 rounded-full">
                                <i class="fas fa-map-marker-alt text-purple-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-1">Visit Our Store</h4>
                                <p class="text-gray-600">
                                    General Santos City<br>
                        
                                </p>
                            </div>
                        </div>
                        
                        <!-- Phone -->
                        <div class="flex items-start space-x-4">
                            <div class="bg-purple-100 p-3 rounded-full">
                                <i class="fas fa-phone text-purple-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-1">Call Us</h4>
                                <p class="text-gray-600">
                                    Main: 09658436214<br>
                                    Orders: 09123456789<br>
                                    Support: 09548962145
                                </p>
                                <p class="text-sm text-gray-500 mt-1">
                                    Monday - Friday: 9:00 AM - 6:00 PM<br>
                                    Saturday: 10:00 AM - 4:00 PM<br>
                                    Sunday: Closed
                                </p>
                            </div>
                        </div>
                        
                        <!-- Email -->
                        <div class="flex items-start space-x-4">
                            <div class="bg-purple-100 p-3 rounded-full">
                                <i class="fas fa-envelope text-purple-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-1">Email Us</h4>
                                <p class="text-gray-600">
                                    General: info@fleur.com<br>
                                    Orders: orders@fleur.com<br>
                                    Support: support@fleur.com
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Social Media -->
                    <div class="mt-8">
                        <h4 class="font-semibold text-gray-800 mb-4">Follow Us</h4>
                        <div class="flex space-x-4">
                            <a href="#" class="bg-purple-600 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-purple-700">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="bg-pink-600 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-pink-700">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="bg-blue-400 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-blue-500">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="bg-red-600 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-red-700">
                                <i class="fab fa-pinterest"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="bg-gray-100 py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h3 class="text-3xl font-bold text-gray-800 mb-4">Frequently Asked Questions</h3>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Quick answers to common questions about our products and services
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <div class="bg-white rounded-lg p-6 shadow">
                    <h4 class="font-semibold text-gray-800 mb-2">How long do your flowers last?</h4>
                    <p class="text-gray-600 text-sm">With proper care, our fresh flowers typically last 7-10 days. We include care instructions with every arrangement.</p>
                </div>
                
                <div class="bg-white rounded-lg p-6 shadow">
                    <h4 class="font-semibold text-gray-800 mb-2">Do you offer same-day delivery?</h4>
                    <p class="text-gray-600 text-sm">Yes! Orders placed before 2 PM are eligible for same-day delivery within our service area.</p>
                </div>
                
                <div class="bg-white rounded-lg p-6 shadow">
                    <h4 class="font-semibold text-gray-800 mb-2">Can I customize my flower arrangement?</h4>
                    <p class="text-gray-600 text-sm">Absolutely! We love creating custom arrangements. Contact us with your ideas and we'll bring them to life.</p>
                </div>
                
                <div class="bg-white rounded-lg p-6 shadow">
                    <h4 class="font-semibold text-gray-800 mb-2">What if I'm not satisfied with my order?</h4>
                    <p class="text-gray-600 text-sm">Your satisfaction is guaranteed. If you're not happy with your order, please contact us within 24 hours.</p>
                </div>
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
                        <li><i class="fas fa-phone mr-2"></i> +1 234 567 890</li>
                        <li><i class="fas fa-envelope mr-2"></i> info@fleur.com</li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i> 123 Flower St, City</li>
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
