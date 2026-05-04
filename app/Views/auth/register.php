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
                <?php if (session()->get('error')): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <?= session()->get('error') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->get('success')): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <i class="fas fa-check-circle mr-2"></i>
                        <?= session()->get('success') ?>
                    </div>
                <?php endif; ?>

                <!-- Registration Form -->
                <form action="<?= site_url('/register') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="first_name" class="block text-gray-700 text-sm font-bold mb-2">
                                <i class="fas fa-user mr-1"></i> First Name
                            </label>
                            <input type="text" id="first_name" name="first_name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500"
                                   placeholder="First name" value="<?= old('first_name') ?>">
                            <?php if (isset($validation) && $validation->getError('first_name')): ?>
                                <p class="text-red-500 text-xs mt-1"><?= $validation->getError('first_name') ?></p>
                            <?php endif; ?>
                        </div>

                        <div>
                            <label for="last_name" class="block text-gray-700 text-sm font-bold mb-2">
                                <i class="fas fa-user mr-1"></i> Last Name
                            </label>
                            <input type="text" id="last_name" name="last_name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500"
                                   placeholder="Last name" value="<?= old('last_name') ?>">
                            <?php if (isset($validation) && $validation->getError('last_name')): ?>
                                <p class="text-red-500 text-xs mt-1"><?= $validation->getError('last_name') ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">
                            <i class="fas fa-envelope mr-1"></i> Email Address
                        </label>
                        <input type="email" id="email" name="email" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500"
                               placeholder="Enter your email" value="<?= old('email') ?>">
                        <?php if (isset($validation) && $validation->getError('email')): ?>
                            <p class="text-red-500 text-xs mt-1"><?= $validation->getError('email') ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">
                            <i class="fas fa-phone mr-1"></i> Phone Number
                        </label>
                        <input type="tel" id="phone" name="phone"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500"
                               placeholder="Enter your phone number" value="<?= old('phone') ?>">
                        <?php if (isset($validation) && $validation->getError('phone')): ?>
                            <p class="text-red-500 text-xs mt-1"><?= $validation->getError('phone') ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">
                                <i class="fas fa-lock mr-1"></i> Password
                            </label>
                            <input type="password" id="password" name="password" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500"
                                   placeholder="Create a password">
                            <?php if (isset($validation) && $validation->getError('password')): ?>
                                <p class="text-red-500 text-xs mt-1"><?= $validation->getError('password') ?></p>
                            <?php endif; ?>
                        </div>

                        <div>
                            <label for="password_confirm" class="block text-gray-700 text-sm font-bold mb-2">
                                <i class="fas fa-lock mr-1"></i> Confirm Password
                            </label>
                            <input type="password" id="password_confirm" name="password_confirm" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500"
                                   placeholder="Confirm your password">
                            <?php if (isset($validation) && $validation->getError('password_confirm')): ?>
                                <p class="text-red-500 text-xs mt-1"><?= $validation->getError('password_confirm') ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Address Fields -->
                    <div class="border-t pt-4 mb-4">
                        <h3 class="text-lg font-semibold mb-3">Address Information (Optional)</h3>
                        
                        <div class="mb-4">
                            <label for="address" class="block text-gray-700 text-sm font-bold mb-2">
                                <i class="fas fa-home mr-1"></i> Address
                            </label>
                            <textarea id="address" name="address" rows="2"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500"
                                      placeholder="Enter your address"><?= old('address') ?></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="city" class="block text-gray-700 text-sm font-bold mb-2">
                                    <i class="fas fa-city mr-1"></i> City
                                </label>
                                <input type="text" id="city" name="city"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500"
                                       placeholder="City" value="<?= old('city') ?>">
                            </div>

                            <div>
                                <label for="state" class="block text-gray-700 text-sm font-bold mb-2">
                                    <i class="fas fa-map mr-1"></i> State
                                </label>
                                <input type="text" id="state" name="state"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500"
                                       placeholder="State" value="<?= old('state') ?>">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="postal_code" class="block text-gray-700 text-sm font-bold mb-2">
                                    <i class="fas fa-envelope mr-1"></i> Postal Code
                                </label>
                                <input type="text" id="postal_code" name="postal_code"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500"
                                       placeholder="Postal code" value="<?= old('postal_code') ?>">
                            </div>

                            <div>
                                <label for="country" class="block text-gray-700 text-sm font-bold mb-2">
                                    <i class="fas fa-globe mr-1"></i> Country
                                </label>
                                <input type="text" id="country" name="country"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500"
                                       placeholder="Country" value="<?= old('country') ?>">
                            </div>
                        </div>
                    </div>

                    <button type="submit" 
                            class="w-full bg-purple-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-purple-700 transition duration-200">
                        <i class="fas fa-user-plus mr-2"></i> Create Account
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Already have an account? 
                        <a href="<?= site_url('/login') ?>" class="text-purple-600 hover:text-purple-800 font-semibold">
                            Login here
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-focus on first name field
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('first_name').focus();
        });

        // Password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strength = checkPasswordStrength(password);
            // You can add password strength indicator here
        });

        function checkPasswordStrength(password) {
            let strength = 0;
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]+/)) strength++;
            if (password.match(/[A-Z]+/)) strength++;
            if (password.match(/[0-9]+/)) strength++;
            if (password.match(/[$@#&!]+/)) strength++;
            return strength;
        }
    </script>
</body>
</html>
