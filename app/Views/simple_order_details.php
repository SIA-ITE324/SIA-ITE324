<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Order Details' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
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
                    <a href="app.php?action=cart" class="text-purple-600 hover:text-purple-700 relative">
                        <i class="fas fa-shopping-cart mr-1"></i>Cart
                    </a>
                    <div class="relative group">
                        <button class="flex items-center text-gray-700 hover:text-purple-600">
                            <i class="fas fa-user mr-2"></i>
                            <?= $_SESSION['user_name'] ?>
                            <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
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
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Order Details</h1>
                    <p class="text-gray-600 mt-2">Track and monitor your order</p>
                </div>
                <a href="app.php?action=orders" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Orders
                </a>
            </div>
        </div>

        <!-- Order Information -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Order Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Order Number</label>
                    <p class="text-gray-900 font-semibold"><?= $order['order_number'] ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Order Date</label>
                    <p class="text-gray-900"><?= date('F j, Y, g:i a', strtotime($order['created_at'])) ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Total Amount</label>
                    <p class="text-gray-900 font-semibold text-green-600">₱<?= number_format($order['total_amount'], 2) ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                    <p class="text-gray-900"><?= ucfirst($order['payment_method']) ?></p>
                </div>
            </div>
        </div>

        <!-- Order Status -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Order Status</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Order Status</label>
                    <div class="flex items-center">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            <?php
                            $status_colors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'confirmed' => 'bg-blue-100 text-blue-800',
                                'processing' => 'bg-purple-100 text-purple-800',
                                'shipped' => 'bg-indigo-100 text-indigo-800',
                                'delivered' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800'
                            ];
                            echo $status_colors[$order['status']] ?? 'bg-gray-100 text-gray-800';
                            ?>">
                            <?= ucfirst($order['status']) ?>
                        </span>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                    <div class="flex items-center">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            <?php
                            $payment_colors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'paid' => 'bg-green-100 text-green-800',
                                'failed' => 'bg-red-100 text-red-800',
                                'refunded' => 'bg-gray-100 text-gray-800'
                            ];
                            echo $payment_colors[$order['payment_status']] ?? 'bg-gray-100 text-gray-800';
                            ?>">
                            <?= ucfirst($order['payment_status']) ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Tracking Information -->
            <?php if (!empty($order['tracking_number'])): ?>
            <div class="mt-6 pt-6 border-t">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tracking Number</label>
                <p class="text-gray-900 font-mono bg-gray-50 p-2 rounded"><?= $order['tracking_number'] ?></p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Shipping Information -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Shipping Information</h2>
            <div class="bg-gray-50 p-4 rounded">
                <p class="text-gray-900 whitespace-pre-line"><?= nl2br(htmlspecialchars($order['shipping_address'])) ?></p>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Order Items</h2>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($order_items as $item): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <?php if (!empty($item['image'])): ?>
                                            <img src="uploads/<?= $item['image'] ?>" alt="<?= $item['product_name'] ?>" class="w-12 h-12 object-cover rounded mr-3">
                                        <?php else: ?>
                                            <div class="w-12 h-12 bg-gray-200 rounded mr-3 flex items-center justify-center">
                                                <i class="fas fa-image text-gray-400"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900"><?= $item['product_name'] ?></div>
                                            <div class="text-sm text-gray-500">SKU: <?= $item['product_id'] ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if (!empty($item['custom_details'])): ?>
                                        <div class="text-sm text-gray-600">
                                            <?php 
                                            $custom = json_decode($item['custom_details'], true);
                                            if ($custom): 
                                            ?>
                                                <div class="space-y-1">
                                                    <?php if (isset($custom['size'])): ?>
                                                        <div><strong>Size:</strong> <?= ucfirst($custom['size']) ?></div>
                                                    <?php endif; ?>
                                                    <?php if (isset($custom['style'])): ?>
                                                        <div><strong>Style:</strong> <?= ucfirst($custom['style']) ?></div>
                                                    <?php endif; ?>
                                                    <?php if (isset($custom['theme'])): ?>
                                                        <div><strong>Theme:</strong> <?= ucfirst($custom['theme']) ?></div>
                                                    <?php endif; ?>
                                                    <?php if (isset($custom['flowers']) && is_array($custom['flowers'])): ?>
                                                        <div><strong>Flowers:</strong> <?= implode(', ', array_keys($custom['flowers'])) ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-sm text-gray-500">Regular Product</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= $item['quantity'] ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ₱<?= number_format($item['price'], 2) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    ₱<?= number_format($item['price'] * $item['quantity'], 2) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                                Total:
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-bold text-gray-900">
                                ₱<?= number_format($order['total_amount'], 2) ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Order Notes -->
        <?php if (!empty($order['customer_notes'])): ?>
        <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Order Notes</h2>
            <div class="bg-gray-50 p-4 rounded">
                <p class="text-gray-900"><?= nl2br(htmlspecialchars($order['customer_notes'])) ?></p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
