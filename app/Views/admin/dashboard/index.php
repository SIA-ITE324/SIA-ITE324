<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Admin Dashboard' ?> - Fleur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <!-- Admin Navigation -->
    <nav class="bg-gray-800 shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-white">
                        <i class="fas fa-spa text-pink-500"></i> Fleur Admin
                    </h1>
                </div>
                
                <div class="flex items-center space-x-6">
                    <div class="relative group">
                        <button class="flex items-center text-white hover:text-pink-400">
                            <i class="fas fa-user mr-2"></i>
                            <?= session()->get('user_name') ?>
                            <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                            <a href="<?= site_url('/admin/profile') ?>" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
                            <a href="<?= site_url('/admin/settings') ?>" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Settings</a>
                            <a href="<?= site_url('/auth/logout') ?>" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 border-t">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 min-h-screen">
            <div class="p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="<?= site_url('/admin/dashboard') ?>" class="flex items-center text-white hover:bg-gray-700 p-3 rounded">
                            <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="<?= site_url('/admin/orders') ?>" class="flex items-center text-white hover:bg-gray-700 p-3 rounded">
                            <i class="fas fa-shopping-cart mr-3"></i> Orders
                        </a>
                    </li>
                    <li>
                        <a href="<?= site_url('/admin/products') ?>" class="flex items-center text-white hover:bg-gray-700 p-3 rounded">
                            <i class="fas fa-box mr-3"></i> Products
                        </a>
                    </li>
                    <li>
                        <a href="<?= site_url('/admin/customers') ?>" class="flex items-center text-white hover:bg-gray-700 p-3 rounded">
                            <i class="fas fa-users mr-3"></i> Customers
                        </a>
                    </li>
                    <li>
                        <a href="<?= site_url('/admin/inventory') ?>" class="flex items-center text-white hover:bg-gray-700 p-3 rounded">
                            <i class="fas fa-warehouse mr-3"></i> Inventory
                        </a>
                    </li>
                    <li>
                        <a href="<?= site_url('/admin/reports') ?>" class="flex items-center text-white hover:bg-gray-700 p-3 rounded">
                            <i class="fas fa-chart-bar mr-3"></i> Reports
                        </a>
                    </li>
                    <li>
                        <a href="<?= site_url('/admin/settings') ?>" class="flex items-center text-white hover:bg-gray-700 p-3 rounded">
                            <i class="fas fa-cog mr-3"></i> Settings
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
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

            <!-- Dashboard Header -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Dashboard</h2>
                <p class="text-gray-600">Welcome back, <?= session()->get('user_name') ?>!</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Orders Stats -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
                        </div>
                        <span class="text-sm text-gray-500">Total Orders</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800"><?= $stats['orders']['total_orders'] ?? 0 ?></h3>
                    <div class="flex items-center mt-2">
                        <span class="text-sm text-green-600">
                            <i class="fas fa-arrow-up mr-1"></i>
                            <?= $stats['orders']['pending_orders'] ?? 0 ?> pending
                        </span>
                    </div>
                </div>

                <!-- Revenue Stats -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                        </div>
                        <span class="text-sm text-gray-500">Total Revenue</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">&#x20B1;<?= number_format($stats['orders']['total_revenue'] ?? 0, 2) ?></h3>
                    <div class="flex items-center mt-2">
                        <span class="text-sm text-gray-600">
                            Avg: &#x20B1;<?= number_format($stats['orders']['avg_order_value'] ?? 0, 2) ?>
                        </span>
                    </div>
                </div>

                <!-- Products Stats -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-purple-100 p-3 rounded-full">
                            <i class="fas fa-box text-purple-600 text-xl"></i>
                        </div>
                        <span class="text-sm text-gray-500">Products</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800"><?= $stats['products']['total_products'] ?? 0 ?></h3>
                    <div class="flex items-center mt-2">
                        <span class="text-sm text-orange-600">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            <?= $stats['products']['low_stock_products'] ?? 0 ?> low stock
                        </span>
                    </div>
                </div>

                <!-- Customers Stats -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-pink-100 p-3 rounded-full">
                            <i class="fas fa-users text-pink-600 text-xl"></i>
                        </div>
                        <span class="text-sm text-gray-500">Customers</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800"><?= $stats['users']['total_customers'] ?? 0 ?></h3>
                    <div class="flex items-center mt-2">
                        <span class="text-sm text-blue-600">
                            <i class="fas fa-user-plus mr-1"></i>
                            <?= $stats['users']['new_today'] ?? 0 ?> today
                        </span>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Sales Chart -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Monthly Sales</h3>
                    <canvas id="salesChart" height="200"></canvas>
                </div>

                <!-- Order Status Chart -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Status</h3>
                    <canvas id="orderStatusChart" height="200"></canvas>
                </div>
            </div>

            <!-- Recent Orders and Top Products -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Orders -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Recent Orders</h3>
                        <a href="<?= site_url('/admin/orders') ?>" class="text-blue-600 hover:text-blue-800 text-sm">View All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left py-2">Order #</th>
                                    <th class="text-left py-2">Customer</th>
                                    <th class="text-left py-2">Amount</th>
                                    <th class="text-left py-2">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($recent_orders)): ?>
                                    <?php foreach ($recent_orders as $order): ?>
                                        <tr class="border-b">
                                            <td class="py-2">
                                                <a href="<?= site_url('/admin/orders/view/' . $order['id']) ?>" class="text-blue-600 hover:text-blue-800">
                                                    <?= $order['order_number'] ?>
                                                </a>
                                            </td>
                                            <td class="py-2"><?= $order['customer_name'] ?></td>
                                            <td class="py-2">&#x20B1;<?= number_format($order['total_amount'], 2) ?></td>
                                            <td class="py-2">
                                                <span class="px-2 py-1 text-xs rounded-full <?= $this->getStatusColor($order['status']) ?>">
                                                    <?= ucfirst($order['status']) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-gray-500">No recent orders</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Top Products -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Top Selling Products</h3>
                        <a href="<?= site_url('/admin/products') ?>" class="text-blue-600 hover:text-blue-800 text-sm">View All</a>
                    </div>
                    <div class="space-y-3">
                        <?php if (!empty($top_products)): ?>
                            <?php foreach ($top_products as $product): ?>
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                                    <div>
                                        <h4 class="font-medium text-gray-800"><?= $product['product_name'] ?></h4>
                                        <p class="text-sm text-gray-600"><?= $product['total_sold'] ?> sold</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold text-gray-800">&#x20B1;<?= number_format($product['total_revenue'], 2) ?></p>
                                        <p class="text-sm text-gray-600"><?= $product['order_count'] ?> orders</p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-center text-gray-500 py-4">No sales data available</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Low Stock Alert -->
            <?php if (!empty($low_stock_products)): ?>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mt-6">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                        <h3 class="text-lg font-semibold text-yellow-800">Low Stock Alert</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php foreach (array_slice($low_stock_products, 0, 6) as $product): ?>
                            <div class="flex items-center justify-between p-3 bg-white rounded">
                                <div>
                                    <h4 class="font-medium text-gray-800"><?= $product['name'] ?></h4>
                                    <p class="text-sm text-red-600">Stock: <?= $product['stock_quantity'] ?> / Min: <?= $product['min_stock_level'] ?></p>
                                </div>
                                <a href="<?= site_url('/admin/products/edit/' . $product['id']) ?>" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if (count($low_stock_products) > 6): ?>
                        <div class="text-center mt-4">
                            <a href="<?= site_url('/admin/inventory') ?>" class="text-yellow-600 hover:text-yellow-800">
                                View all low stock products (<?= count($low_stock_products) ?> total)
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <script>
        // Sales Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode(array_column($monthly_sales ?? [], 'month_name')) ?>,
                datasets: [{
                    label: 'Revenue',
                    data: <?= json_encode(array_column($monthly_sales ?? [], 'total_revenue')) ?>,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Order Status Chart
        const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Confirmed', 'Processing', 'Shipped', 'Delivered', 'Cancelled'],
                datasets: [{
                    data: [
                        <?= $stats['orders']['pending_orders'] ?? 0 ?>,
                        <?= $stats['orders']['confirmed_orders'] ?? 0 ?>,
                        <?= $stats['orders']['processing_orders'] ?? 0 ?>,
                        <?= $stats['orders']['shipped_orders'] ?? 0 ?>,
                        <?= $stats['orders']['delivered_orders'] ?? 0 ?>,
                        <?= $stats['orders']['cancelled_orders'] ?? 0 ?>
                    ],
                    backgroundColor: [
                        'rgb(251, 191, 36)',
                        'rgb(59, 130, 246)',
                        'rgb(147, 51, 234)',
                        'rgb(34, 197, 94)',
                        'rgb(16, 185, 129)',
                        'rgb(239, 68, 68)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Auto-refresh dashboard data
        setInterval(() => {
            fetch('<?= site_url('/admin/dashboard/quick-actions') ?>')
                .then(response => response.json())
                .then(data => {
                    // Update stats cards if needed
                    console.log('Dashboard data refreshed');
                })
                .catch(error => console.error('Error refreshing dashboard:', error));
        }, 30000); // Refresh every 30 seconds
    </script>

    <?php
    // Helper function to get status color
    function getStatusColor($status) {
        $colors = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'confirmed' => 'bg-blue-100 text-blue-800',
            'processing' => 'bg-purple-100 text-purple-800',
            'shipped' => 'bg-indigo-100 text-indigo-800',
            'delivered' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            'refunded' => 'bg-gray-100 text-gray-800'
        ];
        return $colors[$status] ?? 'bg-gray-100 text-gray-800';
    }
    ?>
</body>
</html>
