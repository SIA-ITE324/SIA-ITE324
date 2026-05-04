<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Tracking - Fleur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .timeline-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #10b981;
            border: 2px solid white;
        }
    </style>
</head>
<body class="min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-white mb-2">
                    <i class="fas fa-spa text-pink-300"></i> Fleur
                </h1>
                <p class="text-white/80">Your Order Tracking</p>
            </div>

            <!-- Order Details Card -->
            <div class="glass-card rounded-2xl p-8 mb-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-white mb-2">Order #<?= $order->order_number ?></h2>
                        <p class="text-white/70">Placed on <?= date('F j, Y, g:i a', strtotime($order->created_at)) ?></p>
                    </div>
                    <div class="text-right">
                        <span class="status-badge bg-green-500 text-white">
                            <?= ucfirst($order->status) ?>
                        </span>
                    </div>
                </div>

                <!-- Order Timeline -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-white mb-4">Order Timeline</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="timeline-dot"></div>
                            <div class="ml-4">
                                <p class="text-white font-medium">Order Placed</p>
                                <p class="text-white/60 text-sm"><?= date('M j, Y h:i A', strtotime($order->created_at)) ?></p>
                            </div>
                        </div>
                        
                        <?php if ($order->status !== 'pending'): ?>
                        <div class="flex items-center">
                            <div class="timeline-dot"></div>
                            <div class="ml-4">
                                <p class="text-white font-medium">Order Confirmed</p>
                                <p class="text-white/60 text-sm">Your order has been confirmed and is being prepared</p>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (in_array($order->status, ['processing', 'shipped', 'delivered'])): ?>
                        <div class="flex items-center">
                            <div class="timeline-dot"></div>
                            <div class="ml-4">
                                <p class="text-white font-medium">Order Processing</p>
                                <p class="text-white/60 text-sm">Your order is being prepared by our florists</p>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (in_array($order->status, ['shipped', 'delivered'])): ?>
                        <div class="flex items-center">
                            <div class="timeline-dot"></div>
                            <div class="ml-4">
                                <p class="text-white font-medium">Order Shipped</p>
                                <p class="text-white/60 text-sm">Your order is on its way to you</p>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($order->status === 'delivered'): ?>
                        <div class="flex items-center">
                            <div class="timeline-dot"></div>
                            <div class="ml-4">
                                <p class="text-white font-medium">Order Delivered</p>
                                <p class="text-white/60 text-sm">Your order has been successfully delivered</p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Customer Information (Admin View) -->
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-white mb-4">
                        <i class="fas fa-user mr-2"></i> Customer Information
                    </h3>
                    <div class="glass-card rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-white/70 text-sm">Customer Name</p>
                                <p class="text-white font-medium"><?= $order->customer_name ?? 'Guest Customer' ?></p>
                            </div>
                            <div>
                                <p class="text-white/70 text-sm">Email</p>
                                <p class="text-white font-medium"><?= $order->customer_email ?></p>
                            </div>
                            <div>
                                <p class="text-white/70 text-sm">Phone</p>
                                <p class="text-white font-medium"><?= $order->customer_phone ?? 'Not provided' ?></p>
                            </div>
                            <div>
                                <p class="text-white/70 text-sm">Payment Method</p>
                                <p class="text-white font-medium"><?= ucfirst($order->payment_method ?? 'cash') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Order Items -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-white mb-4">
                        <i class="fas fa-shopping-bag mr-2"></i> Order Items
                    </h3>
                    <div class="space-y-4">
                        <?php foreach ($items as $item): ?>
                        <div class="glass-card rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="text-white font-medium text-lg">
                                        <?= $item['product_name'] ?>
                                        <?php if (!empty($item['custom_bouquet_details'])): ?>
                                        <span class="ml-2 bg-pink-500/20 text-pink-300 text-xs px-2 py-1 rounded-full">
                                            <i class="fas fa-palette mr-1"></i>Custom
                                        </span>
                                        <?php endif; ?>
                                    </h4>
                                    
                                    <?php if (!empty($item['description'])): ?>
                                    <p class="text-white/70 text-sm mt-1"><?= $item['description'] ?></p>
                                    <?php endif; ?>
                                    
                                    <!-- Custom Bouquet Details -->
                                    <?php if (!empty($item['custom_bouquet_details'])): ?>
                                    <div class="mt-3 p-3 bg-pink-500/10 rounded-lg border border-pink-500/30">
                                        <p class="text-pink-300 font-semibold text-sm mb-2">
                                            <i class="fas fa-palette mr-1"></i> Custom Bouquet Details
                                        </p>
                                        <div class="text-white/80 text-sm">
                                            <?php 
                                            $custom_details = json_decode($item['custom_bouquet_details'], true);
                                            if (is_array($custom_details)) {
                                                // Display size
                                                if (!empty($custom_details['size'])) {
                                                    echo '<div class="mb-2">';
                                                    echo '<span class="text-pink-300 font-medium">Size:</span> ';
                                                    echo '<span>' . ucfirst($custom_details['size']) . '</span>';
                                                    echo '</div>';
                                                }
                                                
                                                // Display style
                                                if (!empty($custom_details['style'])) {
                                                    echo '<div class="mb-2">';
                                                    echo '<span class="text-pink-300 font-medium">Style:</span> ';
                                                    echo '<span>' . ucfirst($custom_details['style']) . '</span>';
                                                    echo '</div>';
                                                }
                                                
                                                // Display color theme
                                                if (!empty($custom_details['color_theme'])) {
                                                    echo '<div class="mb-2">';
                                                    echo '<span class="text-pink-300 font-medium">Color Theme:</span> ';
                                                    echo '<span>' . ucfirst($custom_details['color_theme']) . '</span>';
                                                    echo '</div>';
                                                }
                                                
                                                // Display flowers with details
                                                if (!empty($custom_details['flowers']) && is_array($custom_details['flowers'])) {
                                                    echo '<div class="mb-2">';
                                                    echo '<span class="text-pink-300 font-medium">Flowers:</span>';
                                                    echo '<div class="ml-4 mt-1 space-y-1">';
                                                    
                                                    // Get flower names from database
                                                    $flower_ids = array_keys($custom_details['flowers']);
                                                    if (!empty($flower_ids)) {
                                                        $db = new mysqli('localhost', 'root', '', 'fleur_db');
                                                        $ids_str = implode(',', $flower_ids);
                                                        $result = $db->query("SELECT id, name FROM products WHERE id IN ($ids_str)");
                                                        $products = [];
                                                        while ($row = $result->fetch_assoc()) {
                                                            $products[$row['id']] = $row['name'];
                                                        }
                                                        $db->close();
                                                        
                                                        foreach ($custom_details['flowers'] as $product_id => $flower_data) {
                                                            $flower_name = isset($products[$product_id]) ? $products[$product_id] : 'Flower #' . $product_id;
                                                            $quantity = isset($flower_data['quantity']) ? $flower_data['quantity'] : '1';
                                                            
                                                            echo '<div class="flex items-center gap-2">';
                                                            echo '<i class="fas fa-spa text-pink-400 text-xs"></i>';
                                                            echo '<span>';
                                                            echo htmlspecialchars($flower_name);
                                                            echo ' (' . $quantity . ' stems)';
                                                            echo '</span>';
                                                            echo '</div>';
                                                        }
                                                    }
                                                    
                                                    echo '</div>';
                                                    echo '</div>';
                                                }
                                                
                                                // Display message
                                                if (!empty($custom_details['message'])) {
                                                    echo '<div class="mb-2">';
                                                    echo '<span class="text-pink-300 font-medium">Message:</span> ';
                                                    echo '<span>"' . htmlspecialchars($custom_details['message']) . '"</span>';
                                                    echo '</div>';
                                                }
                                                
                                                // Display any other custom fields
                                                foreach ($custom_details as $key => $value) {
                                                    if (!in_array($key, ['size', 'style', 'color_theme', 'flowers', 'message'])) {
                                                        echo '<div class="mb-2">';
                                                        echo '<span class="text-pink-300 font-medium">' . ucfirst(str_replace('_', ' ', $key)) . ':</span> ';
                                                        echo '<span>' . htmlspecialchars($value) . '</span>';
                                                        echo '</div>';
                                                    }
                                                }
                                            } else {
                                                // Fallback to raw display if not JSON
                                                echo nl2br(htmlspecialchars($item['custom_bouquet_details']));
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <!-- Product Options -->
                                    <?php if (!empty($item['product_options'])): ?>
                                    <div class="mt-3 p-3 bg-blue-500/10 rounded-lg border border-blue-500/30">
                                        <p class="text-blue-300 font-semibold text-sm mb-2">
                                            <i class="fas fa-cog mr-1"></i> Order Options
                                        </p>
                                        <div class="text-white/80 text-sm">
                                            <?php 
                                            $options = json_decode($item['product_options'], true);
                                            if (is_array($options)) {
                                                foreach ($options as $key => $value) {
                                                    echo '<div class="mb-1">';
                                                    echo '<span class="text-white/60">' . ucfirst($key) . ':</span> ';
                                                    echo '<span class="text-white">' . htmlspecialchars($value) . '</span>';
                                                    echo '</div>';
                                                }
                                            } else {
                                                echo nl2br(htmlspecialchars($item['product_options']));
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <div class="flex items-center gap-4 mt-3">
                                        <p class="text-white/60 text-sm">
                                            <i class="fas fa-box mr-1"></i> Quantity: <?= $item['quantity'] ?>
                                        </p>
                                        <p class="text-white/60 text-sm">
                                            <i class="fas fa-tag mr-1"></i> Price: &#x20B1;<?= number_format($item['unit_price'], 2) ?>
                                        </p>
                                        <?php if (!empty($item['category'])): ?>
                                        <p class="text-white/60 text-sm">
                                            <i class="fas fa-folder mr-1"></i> <?= ucfirst($item['category']) ?>
                                        </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="text-right ml-4">
                                    <p class="text-white font-medium text-lg">
                                        &#x20B1;<?= number_format($item['total_price'], 2) ?>
                                    </p>
                                    <p class="text-white/60 text-sm">total</p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="border-t border-white/20 pt-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-white/70">Subtotal</span>
                        <span class="text-white">&#x20B1;<?= number_format($order->total_amount * 0.92, 2) ?></span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-white/70">Tax</span>
                        <span class="text-white">&#x20B1;<?= number_format($order->total_amount * 0.08, 2) ?></span>
                    </div>
                    <div class="flex justify-between items-center pt-2 border-t border-white/20">
                        <span class="text-white font-semibold">Total</span>
                        <span class="text-white font-bold text-xl">&#x20B1;<?= number_format($order->total_amount, 2) ?></span>
                    </div>
                </div>

                <!-- Customer Notes (Admin View) -->
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin' && !empty($order['customer_notes'])): ?>
                <div class="mt-6 pt-6 border-t border-white/20">
                    <h3 class="text-lg font-semibold text-white mb-3">
                        <i class="fas fa-comment-alt mr-2"></i> Customer Notes
                    </h3>
                    <div class="glass-card rounded-lg p-4">
                        <div class="bg-yellow-500/10 rounded-lg p-3 border border-yellow-500/30">
                            <p class="text-yellow-300 font-semibold text-sm mb-2">
                                <i class="fas fa-star mr-1"></i> Special Instructions from Customer
                            </p>
                            <p class="text-white/80 text-sm whitespace-pre-line">
                                <?= nl2br(htmlspecialchars($order['customer_notes'])) ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Admin Order Management -->
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <div class="mt-6 pt-6 border-t border-white/20">
                    <h3 class="text-lg font-semibold text-white mb-3">
                        <i class="fas fa-cog mr-2"></i> Order Management
                    </h3>
                    <div class="glass-card rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-white/70 text-sm">Order ID</p>
                                <p class="text-white font-medium">#<?= $order->id ?></p>
                            </div>
                            <div>
                                <p class="text-white/70 text-sm">Payment Status</p>
                                <p class="text-white font-medium"><?= ucfirst($order->payment_status ?? 'pending') ?></p>
                            </div>
                            <div>
                                <p class="text-white/70 text-sm">Assigned Driver</p>
                                <p class="text-white font-medium"><?= $order->assigned_driver ?? 'Not assigned' ?></p>
                            </div>
                            <div>
                                <p class="text-white/70 text-sm">Estimated Delivery</p>
                                <p class="text-white font-medium"><?= $order->estimated_delivery ?? 'Not set' ?></p>
                            </div>
                        </div>
                        
                        <?php if (!empty($order->admin_notes)): ?>
                        <div class="mt-4">
                            <p class="text-white/70 text-sm mb-2">Admin Notes</p>
                            <div class="bg-white/10 rounded-lg p-3">
                                <p class="text-white/80 text-sm whitespace-pre-line"><?= nl2br(htmlspecialchars($order->admin_notes)) ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="mt-4 flex gap-3">
                            <a href="app.php?action=orders&edit=<?= $order->id ?>" class="bg-blue-500/20 text-blue-300 px-4 py-2 rounded-lg hover:bg-blue-500/30 transition text-sm">
                                <i class="fas fa-edit mr-1"></i> Edit Order
                            </a>
                            <a href="app.php?action=print-job-sheet&id=<?= $order->id ?>" class="bg-green-500/20 text-green-300 px-4 py-2 rounded-lg hover:bg-green-500/30 transition text-sm">
                                <i class="fas fa-print mr-1"></i> Print Job Sheet
                            </a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Delivery Information -->
                <?php if (!empty($order->shipping_address)): ?>
                <div class="mt-6 pt-6 border-t border-white/20">
                    <h3 class="text-lg font-semibold text-white mb-3">
                        <i class="fas fa-truck mr-2"></i> Delivery Information
                    </h3>
                    <div class="glass-card rounded-lg p-4">
                        <p class="text-white/80 whitespace-pre-line"><?= nl2br(htmlspecialchars($order->shipping_address)) ?></p>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Action Buttons -->
            <div class="text-center">
                <a href="<?= site_url('/login') ?>" class="inline-block bg-white/20 text-white px-6 py-3 rounded-lg hover:bg-white/30 transition mr-4">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Login
                </a>
                <button onclick="window.print()" class="inline-block bg-white/20 text-white px-6 py-3 rounded-lg hover:bg-white/30 transition">
                    <i class="fas fa-print mr-2"></i> Print Order
                </button>
            </div>
        </div>
    </div>
</body>
</html>
