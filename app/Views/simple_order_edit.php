<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Edit Order' ?></title>
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
                        <i class="fas fa-spa text-pink-500"></i> Fleur Admin
                    </h1>
                </div>
                
                <div class="hidden md:flex items-center space-x-6">
                    <a href="app.php?action=admin" class="text-gray-700 hover:text-purple-600">Dashboard</a>
                    <a href="app.php?action=products" class="text-gray-700 hover:text-purple-600">Products</a>
                    <a href="app.php?action=orders" class="text-purple-600 hover:text-purple-700">Orders</a>
                    <a href="app.php?action=customers" class="text-gray-700 hover:text-purple-600">Customers</a>
                    <a href="app.php?action=inventory" class="text-gray-700 hover:text-purple-600">Inventory</a>
                    <a href="app.php?action=reports" class="text-gray-700 hover:text-purple-600">Reports</a>
                    <a href="app.php?action=profile" class="text-gray-700 hover:text-purple-600">Profile</a>
                    <a href="app.php?action=logout" class="text-red-600 hover:text-red-700">
                        <i class="fas fa-sign-out-alt mr-1"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Edit Order</h1>
                    <p class="text-gray-600 mt-2">Manage order details and status</p>
                </div>
                <a href="app.php?action=orders" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Orders
                </a>
            </div>
        </div>

        <!-- Order Information -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Order Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                    <p class="text-gray-900"><?= $order['currency'] ?></p>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <form method="POST" action="app.php?action=update_order">
            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
            
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Update Status</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Order Status</label>
                        <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="confirmed" <?= $order['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                            <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>Processing</option>
                            <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                            <option value="delivered" <?= $order['status'] === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                            <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            <option value="refunded" <?= $order['status'] === 'refunded' ? 'selected' : '' ?>>Refunded</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                        <select name="payment_status" id="payment_status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="pending" <?= $order['payment_status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="paid" <?= $order['payment_status'] === 'paid' ? 'selected' : '' ?>>Paid</option>
                            <option value="failed" <?= $order['payment_status'] === 'failed' ? 'selected' : '' ?>>Failed</option>
                            <option value="refunded" <?= $order['payment_status'] === 'refunded' ? 'selected' : '' ?>>Refunded</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Shipping Information</h2>
                
                <div class="mb-4">
                    <label for="tracking_number" class="block text-sm font-medium text-gray-700 mb-2">Tracking Number</label>
                    <input type="text" name="tracking_number" id="tracking_number" value="<?= $order['tracking_number'] ?? '' ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                           placeholder="Enter tracking number">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Shipping Address</label>
                    <p class="text-gray-900 bg-gray-50 p-3 rounded"><?= nl2br(htmlspecialchars($order['shipping_address'])) ?></p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Admin Notes</h2>
                <textarea name="admin_notes" id="admin_notes" rows="4" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                          placeholder="Add internal notes about this order"><?= htmlspecialchars($order['admin_notes'] ?? '') ?></textarea>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="app.php?action=orders" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600">
                    Cancel
                </a>
                <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded hover:bg-purple-700">
                    <i class="fas fa-save mr-2"></i>Update Order
                </button>
            </div>
        </form>

        <!-- Order Items -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Order Items</h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($order_items as $item): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?= $item['product_name'] ?>
                                        <?php if (!empty($item['custom_bouquet_details'])): ?>
                                            <span class="ml-2 text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded">
                                                <i class="fas fa-magic mr-1"></i>Custom Bouquet
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!empty($item['custom_bouquet_details'])): ?>
                                        <?php 
                                        $custom_details = json_decode($item['custom_bouquet_details'], true);
                                        if ($custom_details):
                                        ?>
                                        <div class="mt-2 text-xs text-gray-600 space-y-1">
                                            <p><strong>Size:</strong> <?= ucfirst($custom_details['size']) ?></p>
                                            <p><strong>Style:</strong> <?= ucfirst($custom_details['style']) ?></p>
                                            <p><strong>Theme:</strong> <?= ucfirst($custom_details['color_theme']) ?></p>
                                            <?php if (!empty($custom_details['message'])): ?>
                                                <p><strong>Message:</strong> <?= htmlspecialchars($custom_details['message']) ?></p>
                                            <?php endif; ?>
                                            <div class="mt-2">
                                                <a href="app.php?action=edit_custom_bouquet&order_id=<?= $order['id'] ?>&order_item_id=<?= $item['id'] ?>" class="text-purple-600 hover:text-purple-800 text-xs font-medium">
                                                    <i class="fas fa-magic mr-1"></i>Edit Custom Bouquet
                                                </a>
                                            </div>
                                        </div>
                                        <?php else: ?>
                                            <div class="mt-2 text-xs text-red-600">
                                                <p><strong>Error:</strong> Invalid custom bouquet data</p>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <!-- Debug: Show if custom_bouquet_details is empty -->
                                        <?php if (isset($item['product_name']) && strpos($item['product_name'], 'Custom Bouquet') !== false): ?>
                                            <div class="mt-2 text-xs text-orange-600">
                                                <p><strong>Note:</strong> Custom bouquet data missing</p>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?= $item['quantity'] ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">₱<?= number_format($item['unit_price'], 2) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">₱<?= number_format($item['total_price'], 2) ?></div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
