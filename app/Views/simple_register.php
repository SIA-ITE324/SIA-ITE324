<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Fleur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-pink-50 to-purple-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <!-- Logo and Title -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-purple-800 mb-2">
                    <i class="fas fa-spa text-pink-500"></i> Fleur
                </h1>
                <p class="text-gray-600">Create your account</p>
            </div>

            <!-- Registration Card -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-2xl font-semibold text-center mb-6">Register</h2>

                <!-- Flash Messages -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <?= $_SESSION['error'] ?>
                        <?php unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <i class="fas fa-check-circle mr-2"></i>
                        <?= $_SESSION['success'] ?>
                        <?php unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <!-- Registration Form -->
                <form action="app.php?action=register" method="post">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="first_name" class="block text-gray-700 text-sm font-bold mb-2">
                                <i class="fas fa-user mr-1"></i> First Name
                            </label>
                            <input type="text" id="first_name" name="first_name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500"
                                   placeholder="Enter your first name">
                        </div>
                        <div>
                            <label for="last_name" class="block text-gray-700 text-sm font-bold mb-2">
                                <i class="fas fa-user mr-1"></i> Last Name
                            </label>
                            <input type="text" id="last_name" name="last_name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500"
                                   placeholder="Enter your last name">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">
                            <i class="fas fa-envelope mr-1"></i> Email Address
                        </label>
                        <input type="email" id="email" name="email" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500"
                               placeholder="Enter your email address">
                    </div>

                    <div class="mb-4">
                        <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">
                            <i class="fas fa-phone mr-1"></i> Phone Number
                        </label>
                        <input type="tel" id="phone" name="phone" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500"
                               placeholder="Enter your phone number">
                    </div>

                    <div class="mb-4">
                        <label for="address" class="block text-gray-700 text-sm font-bold mb-2">
                            <i class="fas fa-home mr-1"></i> Address
                        </label>
                        <textarea id="address" name="address" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500"
                                  placeholder="Enter your complete address"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label for="city" class="block text-gray-700 text-sm font-bold mb-2">
                                <i class="fas fa-city mr-1"></i> City
                            </label>
                            <input type="text" id="city" name="city" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500"
                                   placeholder="City">
                        </div>
                        <div>
                            <label for="state" class="block text-gray-700 text-sm font-bold mb-2">
                                <i class="fas fa-map mr-1"></i> State/Province
                            </label>
                            <input type="text" id="state" name="state" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500"
                                   placeholder="State/Province">
                        </div>
                        <div>
                            <label for="postal_code" class="block text-gray-700 text-sm font-bold mb-2">
                                <i class="fas fa-mail-bulk mr-1"></i> Postal Code
                            </label>
                            <input type="text" id="postal_code" name="postal_code" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500"
                                   placeholder="Postal Code">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 text-sm font-bold mb-2">
                            <i class="fas fa-lock mr-1"></i> Password
                        </label>
                        <input type="password" id="password" name="password" required minlength="6"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500"
                               placeholder="Enter your password (min 6 characters)">
                    </div>

                    <div class="mb-6">
                        <label for="confirm_password" class="block text-gray-700 text-sm font-bold mb-2">
                            <i class="fas fa-lock mr-1"></i> Confirm Password
                        </label>
                        <input type="password" id="confirm_password" name="confirm_password" required minlength="6"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500"
                               placeholder="Confirm your password">
                    </div>

                    <div class="mb-6">
                        <div class="flex items-center">
                            <input type="checkbox" id="terms" name="terms" required class="mr-2">
                            <label for="terms" class="text-sm text-gray-600">
                                I agree to the <a href="#" class="text-purple-600 hover:text-purple-800">Terms and Conditions</a>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-purple-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-purple-700 transition duration-200">
                        <i class="fas fa-user-plus mr-2"></i> Create Account
                    </button>
                </form>

                <div class="text-center mt-6">
                    <p class="text-gray-600">
                        Already have an account? 
                        <a href="app.php?action=login" class="text-purple-600 hover:text-purple-800 font-semibold">
                            Sign In
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Password confirmation validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match. Please try again.');
                return false;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters long.');
                return false;
            }
        });
    </script>
</body>
</html>
