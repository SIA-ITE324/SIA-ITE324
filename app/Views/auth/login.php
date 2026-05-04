<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Fleur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Glassmorphism effect */
        .glass-card {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }
        
        /* Seasonal backgrounds */
        .seasonal-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.15;
        }
        
        /* Animated logo */
        @keyframes bloom {
            0% { transform: scale(1) rotate(0deg); }
            50% { transform: scale(1.1) rotate(180deg); }
            100% { transform: scale(1) rotate(360deg); }
        }
        
        .logo-blooming {
            animation: bloom 2s ease-in-out infinite;
        }
        
        .logo-loading {
            animation: bloom 1s linear infinite;
        }
        
        /* Password toggle */
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6b7280;
            transition: color 0.2s;
        }
        
        .password-toggle:hover {
            color: #9333ea;
        }
        
        /* Admin badge */
        .admin-badge {
            background: linear-gradient(45deg, #dc2626, #ef4444);
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        
        /* Guest tracking section */
        .guest-section {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            margin-top: 20px;
            padding-top: 20px;
        }
        
        /* Loading spinner */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        
        .loading-content {
            text-align: center;
            color: white;
        }
        
        /* Remembered profile */
        .remembered-profile {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin: 0 auto 16px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #ec4899, #8b5cf6);
            color: white;
            font-weight: bold;
            font-size: 24px;
        }
    </style>
</head>
<body class="min-h-screen relative overflow-hidden">
    <!-- Seasonal Background -->
    <div class="seasonal-bg" id="seasonalBg"></div>
    
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="logo-blooming text-6xl mb-4">
                <i class="fas fa-spa text-pink-400"></i>
            </div>
            <p class="text-white text-lg">Authenticating...</p>
        </div>
    </div>

    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="glass-card rounded-2xl p-8 w-full max-w-md">
            <!-- Remembered Profile Picture -->
            <div id="rememberedProfile" class="remembered-profile" style="display: none;">
                <span id="profileInitial">A</span>
            </div>

            <!-- Logo and Title -->
            <div class="text-center mb-8">
                <div class="text-5xl font-bold text-white mb-2 logo-blooming" id="logo">
                    <i class="fas fa-spa text-pink-300"></i> Fleur
                </div>
                <div id="greeting" class="text-white/80 text-lg mb-2"></div>
                <p class="text-white/60 text-sm">Flower Order Management System</p>
                
                <!-- Admin Badge -->
                <?php if (old('email') && strpos(old('email'), 'admin') !== false): ?>
                    <div class="admin-badge mt-3">
                        <i class="fas fa-shield-alt text-xs"></i>
                        Admin Access Secure
                    </div>
                <?php endif; ?>
            </div>

            <!-- Flash Messages -->
            <?php if (session()->get('error')): ?>
                <div class="bg-red-500/20 border border-red-500/50 text-red-100 px-4 py-3 rounded-lg mb-4 backdrop-blur">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <?= session()->get('error') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->get('success')): ?>
                <div class="bg-green-500/20 border border-green-500/50 text-green-100 px-4 py-3 rounded-lg mb-4 backdrop-blur">
                    <i class="fas fa-check-circle mr-2"></i>
                    <?= session()->get('success') ?>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form action="<?= site_url('/login') ?>" method="post" id="loginForm">
                <?= csrf_field() ?>
                
                <div class="mb-4 relative">
                    <label for="email" class="block text-white/90 text-sm font-semibold mb-2">
                        <i class="fas fa-envelope mr-2"></i> Email Address
                    </label>
                    <input type="email" id="email" name="email" required
                           class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/50 focus:outline-none focus:border-white/60 focus:bg-white/30 backdrop-blur"
                           placeholder="Enter your email" value="<?= old('email') ?>">
                    <?php if (isset($validation) && $validation->getError('email')): ?>
                        <p class="text-red-300 text-xs mt-1"><?= $validation->getError('email') ?></p>
                    <?php endif; ?>
                </div>

                <div class="mb-4 relative">
                    <label for="password" class="block text-white/90 text-sm font-semibold mb-2">
                        <i class="fas fa-lock mr-2"></i> Password
                    </label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                               class="w-full px-4 py-3 pr-12 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/50 focus:outline-none focus:border-white/60 focus:bg-white/30 backdrop-blur"
                               placeholder="Enter your password">
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye" id="passwordIcon"></i>
                        </button>
                    </div>
                    <?php if (isset($validation) && $validation->getError('password')): ?>
                        <p class="text-red-300 text-xs mt-1"><?= $validation->getError('password') ?></p>
                    <?php endif; ?>
                </div>

                <div class="mb-6 flex items-center justify-between">
                    <label class="flex items-center text-white/80 text-sm">
                        <input type="checkbox" name="remember" class="mr-2 rounded">
                        <span>Remember me</span>
                    </label>
                    <a href="<?= site_url('/auth/forgot-password') ?>" class="text-white/80 hover:text-white text-sm">
                        Forgot password?
                    </a>
                </div>

                <button type="submit" 
                        class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold py-3 px-4 rounded-lg hover:from-purple-700 hover:to-pink-700 transition duration-200 transform hover:scale-105">
                    <i class="fas fa-sign-in-alt mr-2"></i> Sign In
                </button>
            </form>

            <!-- Guest Order Tracking -->
            <div class="guest-section">
                <div class="text-center mb-4">
                    <p class="text-white/60 text-sm mb-3">Don't have an account?</p>
                    <div class="flex gap-3">
                        <a href="<?= site_url('/register') ?>" class="flex-1 bg-white/20 text-white py-2 px-4 rounded-lg hover:bg-white/30 transition text-center">
                            <i class="fas fa-user-plus mr-2"></i> Register
                        </a>
                        <button onclick="showGuestTracking()" class="flex-1 bg-white/20 text-white py-2 px-4 rounded-lg hover:bg-white/30 transition">
                            <i class="fas fa-search mr-2"></i> Track Order
                        </button>
                    </div>
                </div>
                
                <!-- Guest Tracking Form (Hidden by default) -->
                <div id="guestTracking" class="hidden">
                    <h3 class="text-white/90 font-semibold mb-3 text-center">Track Your Order</h3>
                    <form action="<?= site_url('/track-order') ?>" method="get" class="space-y-3">
                        <input type="text" name="order_id" required
                               class="w-full px-4 py-2 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/50 focus:outline-none focus:border-white/60 backdrop-blur"
                               placeholder="Order Number">
                        <input type="email" name="email" required
                               class="w-full px-4 py-2 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/50 focus:outline-none focus:border-white/60 backdrop-blur"
                               placeholder="Email Address">
                        <button type="submit" class="w-full bg-white/30 text-white py-2 rounded-lg hover:bg-white/40 transition">
                            <i class="fas fa-search mr-2"></i> Track Order
                        </button>
                    </form>
                </div>
            </div>

            <!-- Magic Link Option -->
            <div class="mt-4 text-center">
                <button onclick="showMagicLink()" class="text-white/60 hover:text-white text-sm underline">
                    <i class="fas fa-magic mr-1"></i> Sign in with magic link
                </button>
            </div>
        </div>
    </div>

    <script>
        // Time-based greeting
        function setGreeting() {
            const hour = new Date().getHours();
            const greetingEl = document.getElementById('greeting');
            let greeting = '';
            
            if (hour >= 5 && hour < 12) {
                greeting = 'Good morning! Ready to spread some fragrance?';
            } else if (hour >= 12 && hour < 17) {
                greeting = 'Good afternoon! Managing today\'s beautiful orders?';
            } else if (hour >= 17 && hour < 21) {
                greeting = 'Good evening! Wrapping up the day\'s orders?';
            } else {
                greeting = 'Good night! Late night order management?';
            }
            
            greetingEl.textContent = greeting;
        }

        // Seasonal background
        function setSeasonalBackground() {
            const month = new Date().getMonth();
            const bg = document.getElementById('seasonalBg');
            let background = '';
            
            if (month >= 2 && month <= 4) { // Spring
                background = 'url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 400 400\'%3E%3Ccircle cx=\'100\' cy=\'100\' r=\'20\' fill=\'%23FFB6C1\' opacity=\'0.6\'/%3E%3Ccircle cx=\'300\' cy=\'100\' r=\'25\' fill=\'%23FFC0CB\' opacity=\'0.5\'/%3E%3Ccircle cx=\'200\' cy=\'200\' r=\'30\' fill=\'%23FFB6C1\' opacity=\'0.4\'/%3E%3Ccircle cx=\'100\' cy=\'300\' r=\'22\' fill=\'%23FFC0CB\' opacity=\'0.5\'/%3E%3Ccircle cx=\'300\' cy=\'300\' r=\'28\' fill=\'%23FFB6C1\' opacity=\'0.3\'/%3E%3C/svg%3E")';
            } else if (month >= 5 && month <= 7) { // Summer
                background = 'url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 400 400\'%3E%3Ccircle cx=\'100\' cy=\'100\' r=\'25\' fill=\'%23FFD700\' opacity=\'0.4\'/%3E%3Ccircle cx=\'300\' cy=\'100\' r=\'20\' fill=\'%23FFA500\' opacity=\'0.5\'/%3E%3Ccircle cx=\'200\' cy=\'200\' r=\'30\' fill=\'%23FFD700\' opacity=\'0.3\'/%3E%3Ccircle cx=\'100\' cy=\'300\' r=\'22\' fill=\'%23FFA500\' opacity=\'0.4\'/%3E%3Ccircle cx=\'300\' cy=\'300\' r=\'28\' fill=\'%23FFD700\' opacity=\'0.5\'/%3E%3C/svg%3E")';
            } else if (month >= 8 && month <= 10) { // Fall
                background = 'url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 400 400\'%3E%3Ccircle cx=\'100\' cy=\'100\' r=\'25\' fill=\'%23D2691E\' opacity=\'0.5\'/%3E%3Ccircle cx=\'300\' cy=\'100\' r=\'20\' fill=\'%23FF8C00\' opacity=\'0.4\'/%3E%3Ccircle cx=\'200\' cy=\'200\' r=\'30\' fill=\'%23D2691E\' opacity=\'0.3\'/%3E%3Ccircle cx=\'100\' cy=\'300\' r=\'22\' fill=\'%23FF8C00\' opacity=\'0.5\'/%3E%3Ccircle cx=\'300\' cy=\'300\' r=\'28\' fill=\'%23D2691E\' opacity=\'0.4\'/%3E%3C/svg%3E")';
            } else { // Winter
                background = 'url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 400 400\'%3E%3Ccircle cx=\'100\' cy=\'100\' r=\'20\' fill=\'%23E0F2FE\' opacity=\'0.6\'/%3E%3Ccircle cx=\'300\' cy=\'100\' r=\'25\' fill=\'%23BAE6FD\' opacity=\'0.5\'/%3E%3Ccircle cx=\'200\' cy=\'200\' r=\'30\' fill=\'%23E0F2FE\' opacity=\'0.4\'/%3E%3Ccircle cx=\'100\' cy=\'300\' r=\'22\' fill=\'%23BAE6FD\' opacity=\'0.5\'/%3E%3Ccircle cx=\'300\' cy=\'300\' r=\'28\' fill=\'%23E0F2FE\' opacity=\'0.3\'/%3E%3C/svg%3E")';
            }
            
            bg.style.background = background;
            bg.style.backgroundSize = 'cover';
        }

        // Password toggle
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('passwordIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.className = 'fas fa-eye-slash';
            } else {
                passwordInput.type = 'password';
                passwordIcon.className = 'fas fa-eye';
            }
        }

        // Guest tracking toggle
        function showGuestTracking() {
            const guestTracking = document.getElementById('guestTracking');
            guestTracking.classList.toggle('hidden');
        }

        // Magic link
        function showMagicLink() {
            const email = document.getElementById('email').value;
            if (email) {
                // Show loading and send magic link
                document.getElementById('loadingOverlay').style.display = 'flex';
                
                fetch('<?= site_url('/auth/send-magic-link') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ email: email })
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('loadingOverlay').style.display = 'none';
                    if (data.success) {
                        alert('Magic link sent! Check your email.');
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    document.getElementById('loadingOverlay').style.display = 'none';
                    alert('Error sending magic link.');
                });
            } else {
                alert('Please enter your email first.');
            }
        }

        // Login form submission with loading
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            // Show loading animation
            const logo = document.getElementById('logo');
            logo.classList.remove('logo-blooming');
            logo.classList.add('logo-loading');
            
            // Show loading overlay
            document.getElementById('loadingOverlay').style.display = 'flex';
        });

        // Check for remembered user
        function checkRememberedUser() {
            const rememberedEmail = localStorage.getItem('rememberedEmail');
            const rememberedName = localStorage.getItem('rememberedName');
            
            if (rememberedEmail && rememberedName) {
                document.getElementById('email').value = rememberedEmail;
                document.getElementById('rememberedProfile').style.display = 'block';
                document.getElementById('profileInitial').textContent = rememberedName.charAt(0).toUpperCase();
                document.querySelector('input[name="remember"]').checked = true;
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            setGreeting();
            setSeasonalBackground();
            checkRememberedUser();
            document.getElementById('email').focus();
            
            // Update greeting every minute
            setInterval(setGreeting, 60000);
        });
    </script>
</body>
</html>
