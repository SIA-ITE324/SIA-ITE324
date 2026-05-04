<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Checkout' ?></title>
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
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Checkout</h2>
            <a href="app.php?action=cart" class="text-purple-600 hover:text-purple-800">
                <i class="fas fa-arrow-left mr-2"></i>Back to Cart
            </a>
        </div>

        <!-- Flash Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <i class="fas fa-check-circle mr-2"></i>
                <?= $_SESSION['success'] ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Checkout Form -->
            <div class="lg:col-span-2">
                <form method="POST" action="app.php?action=checkout" class="space-y-6">
                    <!-- Shipping Information -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Shipping Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                                <input type="text" name="first_name" value="<?= $user['first_name'] ?? '' ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Enter your first name">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                                <input type="text" name="last_name" value="<?= $user['last_name'] ?? '' ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Enter your last name">
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" name="email" value="<?= $user['email'] ?? '' ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Enter your email address">
                        </div>
                        
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                            <input type="tel" name="phone" value="<?= $user['phone'] ?? '' ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Enter your phone number">
                        </div>
                        
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Shipping Address *</label>
                            <textarea name="shipping_address" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Enter your complete shipping address"><?= $user['address'] ?? '' ?></textarea>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                                <input type="text" name="city" value="<?= $user['city'] ?? '' ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Enter your city">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">State/Province *</label>
                                <input type="text" name="state" value="<?= $user['state'] ?? '' ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Enter your state or province">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code *</label>
                                <input type="text" name="postal_code" value="<?= $user['postal_code'] ?? '' ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Enter your postal code">
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Payment Method</h3>
                        
                        <div class="space-y-3">
                            <!-- Cash on Delivery -->
                            <div class="flex items-center p-3 border border-gray-200 rounded-lg hover:border-purple-500 cursor-pointer payment-option" data-payment="cod">
                                <input type="radio" name="payment_method" value="cod" checked class="mr-3">
                                <div class="flex-1">
                                    <div class="font-medium text-gray-800">Cash on Delivery</div>
                                    <div class="text-sm text-gray-600">Pay when you receive your order</div>
                                </div>
                                <i class="fas fa-money-bill-wave text-purple-600"></i>
                            </div>
                            
                            <!-- GCash -->
                            <div class="flex items-center p-3 border border-gray-200 rounded-lg hover:border-purple-500 cursor-pointer payment-option" data-payment="gcash">
                                <input type="radio" name="payment_method" value="gcash" class="mr-3">
                                <div class="flex-1">
                                    <div class="font-medium text-gray-800">GCash</div>
                                    <div class="text-sm text-gray-600">Pay instantly with GCash wallet</div>
                                </div>
                                <div class="w-8 h-8 bg-blue-500 rounded flex items-center justify-center">
                                    <i class="fas fa-mobile-alt text-white text-sm"></i>
                                </div>
                            </div>
                            
                            <!-- Maya -->
                            <div class="flex items-center p-3 border border-gray-200 rounded-lg hover:border-purple-500 cursor-pointer payment-option" data-payment="maya">
                                <input type="radio" name="payment_method" value="maya" class="mr-3">
                                <div class="flex-1">
                                    <div class="font-medium text-gray-800">Maya</div>
                                    <div class="text-sm text-gray-600">Pay instantly with Maya wallet</div>
                                </div>
                                <div class="w-8 h-8 bg-orange-500 rounded flex items-center justify-center">
                                    <i class="fas fa-mobile-alt text-white text-sm"></i>
                                </div>
                            </div>
                            
                            <!-- Credit/Debit Card -->
                            <div class="flex items-center p-3 border border-gray-200 rounded-lg hover:border-purple-500 cursor-pointer payment-option" data-payment="card">
                                <input type="radio" name="payment_method" value="card" class="mr-3">
                                <div class="flex-1">
                                    <div class="font-medium text-gray-800">Credit/Debit Card</div>
                                    <div class="text-sm text-gray-600">Pay with Visa, Mastercard, or other cards</div>
                                </div>
                                <i class="fas fa-credit-card text-purple-600"></i>
                            </div>
                            
                            <!-- Bank Transfer -->
                            <div class="flex items-center p-3 border border-gray-200 rounded-lg hover:border-purple-500 cursor-pointer payment-option" data-payment="bank">
                                <input type="radio" name="payment_method" value="bank" class="mr-3">
                                <div class="flex-1">
                                    <div class="font-medium text-gray-800">Bank Transfer</div>
                                    <div class="text-sm text-gray-600">Transfer directly to our bank account</div>
                                </div>
                                <i class="fas fa-university text-purple-600"></i>
                            </div>
                        </div>
                        
                        <!-- Payment Details Section (shown based on selection) -->
                        <div id="paymentDetails" class="mt-6 hidden">
                            <!-- GCash Details -->
                            <div id="gcashDetails" class="hidden p-4 bg-blue-50 rounded-lg">
                                <h4 class="font-semibold text-gray-800 mb-3">GCash Payment Details</h4>
                                <div class="bg-white p-4 rounded border border-blue-200">
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <p class="text-sm text-gray-600">GCash Number:</p>
                                            <p class="font-mono font-bold text-lg">0912-345-6789</p>
                                        </div>
                                        <div class="w-12 h-12 bg-blue-500 rounded flex items-center justify-center">
                                            <i class="fas fa-qrcode text-white"></i>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500">Please scan the QR code or send payment to the number above. Include your order number in the payment reference.</p>
                                </div>
                            </div>
                            
                            <!-- Maya Details -->
                            <div id="mayaDetails" class="hidden p-4 bg-orange-50 rounded-lg">
                                <h4 class="font-semibold text-gray-800 mb-3">Maya Payment Details</h4>
                                <div class="bg-white p-4 rounded border border-orange-200">
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <p class="text-sm text-gray-600">Maya Number:</p>
                                            <p class="font-mono font-bold text-lg">0912-345-6790</p>
                                        </div>
                                        <div class="w-12 h-12 bg-orange-500 rounded flex items-center justify-center">
                                            <i class="fas fa-qrcode text-white"></i>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500">Please scan the QR code or send payment to the number above. Include your order number in the payment reference.</p>
                                </div>
                            </div>
                            
                            <!-- Card Details -->
                            <div id="cardDetails" class="hidden p-4 bg-purple-50 rounded-lg">
                                <h4 class="font-semibold text-gray-800 mb-3">Card Payment Details</h4>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Card Number</label>
                                        <input type="text" name="card_number" placeholder="1234 5678 9012 3456" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                                            <input type="text" name="card_expiry" placeholder="MM/YY" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">CVV</label>
                                            <input type="text" name="card_cvv" placeholder="123" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Cardholder Name</label>
                                        <input type="text" name="cardholder_name" placeholder="John Doe" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Bank Transfer Details -->
                            <div id="bankDetails" class="hidden p-4 bg-green-50 rounded-lg">
                                <h4 class="font-semibold text-gray-800 mb-3">Bank Transfer Details</h4>
                                <div class="bg-white p-4 rounded border border-green-200">
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-600">Bank:</span>
                                            <span class="font-medium">BDO Unibank, Inc.</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-600">Account Name:</span>
                                            <span class="font-medium">Fleur Flower Shop</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-600">Account Number:</span>
                                            <span class="font-mono font-bold">1234-5678-9012</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-600">Branch:</span>
                                            <span class="font-medium">Makati Main Branch</span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-3">Please upload your payment receipt after transfer. Include your order number in the payment reference.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Personalization Options -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="fas fa-gift text-pink-500 mr-2"></i>Personalization Options
                        </h3>
                        
                        <!-- Delivery Date Picker -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt text-purple-600 mr-2"></i>Preferred Delivery Date
                            </label>
                            <input type="date" name="delivery_date" min="<?= date('Y-m-d', strtotime('+1 day')) ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <p class="text-xs text-gray-500 mt-1">Select your preferred delivery date (minimum 1 day advance notice)</p>
                        </div>
                        
                        <!-- Gift Message -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-envelope text-purple-600 mr-2"></i>Gift Message
                            </label>
                            <textarea name="gift_message" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Write your personal message here... (Happy Birthday, With Love, etc.)" maxlength="200"></textarea>
                            <div class="flex justify-between items-center mt-1">
                                <p class="text-xs text-gray-500">Make someone's day special with a personal message</p>
                                <span class="text-xs text-gray-400">0/200 characters</span>
                            </div>
                        </div>
                    </div>

                    <!-- Order Notes -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Notes (Optional)</h3>
                        <textarea name="order_notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Any special instructions for your order..."></textarea>
                    </div>

                    <!-- Place Order Button -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <button type="submit" class="w-full bg-purple-600 text-white py-3 px-4 rounded-lg hover:bg-purple-700 font-semibold">
                            <i class="fas fa-shopping-cart mr-2"></i>Place Order
                        </button>
                        <p class="text-sm text-gray-500 text-center mt-2">
                            By placing this order, you agree to our Terms of Service
                        </p>
                    </div>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-24">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Summary</h3>
                    
                    <!-- Order Items -->
                    <div class="space-y-3 mb-4 max-h-64 overflow-y-auto">
                        <?php foreach ($cart_items as $item): ?>
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gray-100 rounded flex items-center justify-center flex-shrink-0">
                                    <?php if ($item['product']['images']): ?>
                                        <img src="<?= $item['product']['images'] ?>" alt="<?= $item['product']['name'] ?>" class="w-full h-full object-cover rounded">
                                    <?php else: ?>
                                        <i class="fas fa-spa text-gray-400 text-sm"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-gray-800"><?= $item['product']['name'] ?></div>
                                    <div class="text-xs text-gray-600">Qty: <?= $item['quantity'] ?></div>
                                </div>
                                <div class="text-sm font-semibold text-gray-800">
                                    ₱<?= number_format($item['subtotal'], 2) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Pricing -->
                    <div class="border-t pt-4 space-y-3">
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
                    
                    <div class="border-t pt-4">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-gray-800">Total</span>
                            <span class="text-2xl font-bold text-purple-600">₱<?= number_format($total, 2) ?></span>
                        </div>
                    </div>
                    
                    <!-- Delivery Info -->
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <div class="flex items-center text-sm text-blue-800">
                            <i class="fas fa-truck mr-2"></i>
                            <span>Free delivery on all orders</span>
                        </div>
                        <div class="text-xs text-blue-600 mt-1">
                            Same day Delivery
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Personalization Features -->
    <script>
        // Gift Message Character Counter
        const giftMessageTextarea = document.querySelector('textarea[name="gift_message"]');
        const characterCounter = document.querySelector('.text-gray-400');
        
        if (giftMessageTextarea && characterCounter) {
            giftMessageTextarea.addEventListener('input', function() {
                const currentLength = this.value.length;
                const maxLength = 200;
                characterCounter.textContent = `${currentLength}/${maxLength} characters`;
                
                if (currentLength >= maxLength * 0.9) {
                    characterCounter.classList.add('text-orange-500');
                    characterCounter.classList.remove('text-gray-400');
                } else {
                    characterCounter.classList.remove('text-orange-500');
                    characterCounter.classList.add('text-gray-400');
                }
            });
        }

        // Delivery Date Validation
        const deliveryDateInput = document.querySelector('input[name="delivery_date"]');
        if (deliveryDateInput) {
            // Set minimum date to tomorrow
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            deliveryDateInput.min = tomorrow.toISOString().split('T')[0];
            
            // Set default date to 3 days from now
            const defaultDate = new Date();
            defaultDate.setDate(defaultDate.getDate() + 3);
            deliveryDateInput.value = defaultDate.toISOString().split('T')[0];
        }

        // Payment Method Selection
        const paymentOptions = document.querySelectorAll('.payment-option');
        const paymentDetails = document.getElementById('paymentDetails');
        const paymentDetailSections = {
            'gcash': document.getElementById('gcashDetails'),
            'maya': document.getElementById('mayaDetails'),
            'card': document.getElementById('cardDetails'),
            'bank': document.getElementById('bankDetails')
        };

        function showPaymentDetails(paymentMethod) {
            // Hide all payment detail sections
            Object.values(paymentDetailSections).forEach(section => {
                section.classList.add('hidden');
            });

            // Show the selected payment details
            if (paymentDetailSections[paymentMethod]) {
                paymentDetails.classList.remove('hidden');
                paymentDetailSections[paymentMethod].classList.remove('hidden');
            } else {
                paymentDetails.classList.add('hidden');
            }

            // Update border highlights
            paymentOptions.forEach(option => {
                if (option.dataset.payment === paymentMethod) {
                    option.classList.remove('border-gray-200');
                    option.classList.add('border-purple-500', 'bg-purple-50');
                } else {
                    option.classList.remove('border-purple-500', 'bg-purple-50');
                    option.classList.add('border-gray-200');
                }
            });
        }

        // Add click handlers to payment options
        paymentOptions.forEach(option => {
            option.addEventListener('click', function() {
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
                showPaymentDetails(this.dataset.payment);
            });
        });

        // Initialize with COD selected
        showPaymentDetails('cod');

        // Form Validation
        const checkoutForm = document.querySelector('form');
        if (checkoutForm) {
            checkoutForm.addEventListener('submit', function(e) {
                const shippingAddress = document.querySelector('textarea[name="shipping_address"]');
                if (!shippingAddress.value.trim()) {
                    e.preventDefault();
                    alert('Shipping address is required');
                    shippingAddress.focus();
                    return;
                }
            });
        }
    </script>
</body>
</html>
