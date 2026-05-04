<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Admin Dashboard' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-purple-800">
                        <i class="fas fa-spa text-pink-500"></i> Fleur Admin
                    </h1>
                </div>
                
                <div class="flex items-center space-x-6">
                    <a href="app.php?action=home" class="text-gray-700 hover:text-purple-600">
                        <i class="fas fa-home mr-2"></i>Home
                    </a>
                    <div class="relative group">
                        <button class="flex items-center text-gray-700 hover:text-purple-600">
                            <i class="fas fa-user mr-2"></i>
                            <?= $_SESSION['user_name'] ?>
                            <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                            <a href="app.php?action=profile" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
                            <a href="app.php?action=logout" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 border-t">Logout</a>
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
                        <a href="app.php?action=admin" class="flex items-center text-white hover:bg-gray-700 p-3 rounded">
                            <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="app.php?action=orders" class="flex items-center text-white hover:bg-gray-700 p-3 rounded">
                            <i class="fas fa-shopping-cart mr-3"></i> Orders
                        </a>
                    </li>
                    <li>
                        <a href="app.php?action=products" class="flex items-center text-white hover:bg-gray-700 p-3 rounded">
                            <i class="fas fa-box mr-3"></i> Products
                        </a>
                    </li>
                    <li>
                        <a href="app.php?action=admin_bouquet_builder" class="flex items-center text-white hover:bg-gray-700 p-3 rounded">
                            <i class="fas fa-magic mr-3"></i> Custom Bouquet Builder
                        </a>
                    </li>
                    <li>
                        <a href="app.php?action=manage_bouquet_builder" class="flex items-center text-white hover:bg-gray-700 p-3 rounded">
                            <i class="fas fa-cog mr-3"></i> Bouquet Builder Settings
                        </a>
                    </li>
                    <li>
                        <a href="app.php?action=customers" class="flex items-center text-white hover:bg-gray-700 p-3 rounded">
                            <i class="fas fa-users mr-3"></i> Customers
                        </a>
                    </li>
                    <li>
                        <a href="app.php?action=inventory" class="flex items-center text-white hover:bg-gray-700 p-3 rounded">
                            <i class="fas fa-warehouse mr-3"></i> Inventory
                        </a>
                    </li>
                    <li>
                        <a href="app.php?action=reports" class="flex items-center text-white hover:bg-gray-700 p-3 rounded">
                            <i class="fas fa-chart-bar mr-3"></i> Reports
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <!-- Flash Messages -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <i class="fas fa-check-circle mr-2"></i>
                    <?= $_SESSION['success'] ?>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <!-- Dashboard Header -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Admin Dashboard</h2>
                <p class="text-gray-600">Welcome back, <?= $_SESSION['user_name'] ?>!</p>
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
                    <h3 class="text-2xl font-bold text-gray-800"><?= $stats['total_orders'] ?></h3>
                    <div class="flex items-center mt-2">
                        <span class="text-sm text-green-600">
                            <i class="fas fa-arrow-up mr-1"></i>
                            Active orders
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
                    <h3 class="text-2xl font-bold text-gray-800"><?= $stats['total_products'] ?></h3>
                    <div class="flex items-center mt-2">
                        <span class="text-sm text-gray-600">
                            In catalog
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
                    <h3 class="text-2xl font-bold text-gray-800"><?= $stats['total_users'] ?></h3>
                    <div class="flex items-center mt-2">
                        <span class="text-sm text-blue-600">
                            <i class="fas fa-user-plus mr-1"></i>
                            Registered
                        </span>
                    </div>
                </div>

                <!-- Revenue Stats -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                        </div>
                        <span class="text-sm text-gray-500">Revenue</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">₱<?= number_format($stats['total_revenue'], 2) ?></h3>
                    <div class="flex items-center mt-2">
                        <span class="text-sm text-gray-600">
                            Total sales
                        </span>
                    </div>
                </div>
            </div>

            <!-- Main Dashboard Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Sales Trend Chart (2/3 width) -->
                <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Sales Trend (Last 7 Days)</h3>
                    <div class="h-64">
                        <canvas id="salesTrendChart"></canvas>
                    </div>
                </div>

                <!-- Quick Actions (1/3 width) -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="app.php?action=add_product" class="block w-full bg-purple-600 text-white px-4 py-3 rounded-lg hover:bg-purple-700 text-center">
                            <i class="fas fa-plus mr-2"></i>Add New Product
                        </a>
                        <a href="app.php?action=add_order" class="block w-full bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 text-center">
                            <i class="fas fa-plus mr-2"></i>Create Manual Order
                        </a>
                        <a href="app.php?action=export_sales" class="block w-full bg-green-600 text-white px-4 py-3 rounded-lg hover:bg-green-700 text-center">
                            <i class="fas fa-download mr-2"></i>Export Today's Sales
                        </a>
                    </div>
                </div>
            </div>

            <!-- Second Row -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Order Status Distribution -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Status Distribution</h3>
                    <div class="h-48">
                        <canvas id="orderStatusChart"></canvas>
                    </div>
                </div>

                <!-- Inventory at Risk -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Low Stock Alerts</h3>
                    <div class="space-y-2 max-h-48 overflow-y-auto">
                        <?php if (!empty($low_stock_alerts)): ?>
                            <?php foreach ($low_stock_alerts as $alert): ?>
                                <div class="flex items-center justify-between p-2 bg-red-50 rounded">
                                    <span class="text-sm font-medium text-gray-800"><?= $alert['name'] ?></span>
                                    <span class="text-xs text-red-600"><?= $alert['stock_quantity'] ?> left</span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-sm text-gray-500">No low stock alerts</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Top Selling Products -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Top Selling Products</h3>
                    <div class="space-y-3">
                        <?php if (!empty($top_products)): ?>
                            <?php foreach ($top_products as $index => $product): ?>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <span class="text-lg font-bold text-purple-600 mr-3"><?= $index + 1 ?></span>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800"><?= $product['name'] ?></p>
                                            <p class="text-xs text-gray-500"><?= $product['order_count'] ?> orders</p>
                                        </div>
                                    </div>
                                    <span class="text-sm text-gray-600"><?= $product['total_quantity'] ?> sold</span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-sm text-gray-500">No sales data yet</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Enhanced Recent Orders -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Recent Orders</h3>
                    <a href="app.php?action=orders" class="text-blue-600 hover:text-blue-800 text-sm">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2">Order #</th>
                                <th class="text-left py-2">Customer</th>
                                <th class="text-left py-2">Status</th>
                                <th class="text-left py-2">Amount</th>
                                <th class="text-left py-2">Date</th>
                                <th class="text-left py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recent_orders)): ?>
                                <?php foreach ($recent_orders as $order): ?>
                                    <tr class="border-b">
                                        <td class="py-2">
                                            <a href="app.php?action=edit_order&id=<?= $order['id'] ?>" class="text-blue-600 hover:text-blue-800">
                                                <?= $order['order_number'] ?>
                                            </a>
                                        </td>
                                        <td class="py-2">
                                            <div>
                                                <p class="text-sm font-medium text-gray-800"><?= $order['customer_name'] ?? 'Guest' ?></p>
                                                <p class="text-xs text-gray-500"><?= $order['customer_email'] ?? '' ?></p>
                                            </div>
                                        </td>
                                        <td class="py-2">
                                            <?php
                                            $status_colors = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'processing' => 'bg-blue-100 text-blue-800',
                                                'shipped' => 'bg-indigo-100 text-indigo-800',
                                                'delivered' => 'bg-green-100 text-green-800',
                                                'cancelled' => 'bg-red-100 text-red-800'
                                            ];
                                            $color = $status_colors[$order['status']] ?? 'bg-gray-100 text-gray-800';
                                            ?>
                                            <span class="px-2 py-1 text-xs rounded-full <?= $color ?>">
                                                <?= ucfirst($order['status']) ?>
                                            </span>
                                        </td>
                                        <td class="py-2">₱<?= number_format($order['total_amount'], 2) ?></td>
                                        <td class="py-2"><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                                        <td class="py-2">
                                            <div class="flex space-x-2">
                                                <a href="app.php?action=edit_order&id=<?= $order['id'] ?>" class="text-blue-600 hover:text-blue-800" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="app.php?action=edit_order&id=<?= $order['id'] ?>" class="text-green-600 hover:text-green-800" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button onclick="printOrder(<?= $order['id'] ?>)" class="text-gray-600 hover:text-gray-800" title="Print">
                                                    <i class="fas fa-print"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-gray-500">No recent orders</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Chart JavaScript -->
            <script>
                // Sales Trend Chart
                const salesCtx = document.getElementById('salesTrendChart').getContext('2d');
                new Chart(salesCtx, {
                    type: 'line',
                    data: {
                        labels: <?= json_encode(array_column($sales_trend ?? [], 'date')) ?>,
                        datasets: [{
                            label: 'Orders',
                            data: <?= json_encode(array_column($sales_trend ?? [], 'orders')) ?>,
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                // Order Status Distribution Chart
                const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
                new Chart(statusCtx, {
                    type: 'pie',
                    data: {
                        labels: <?= json_encode(array_column($order_status_distribution ?? [], 'status')) ?>,
                        datasets: [{
                            data: <?= json_encode(array_column($order_status_distribution ?? [], 'count')) ?>,
                            backgroundColor: [
                                'rgba(250, 204, 21, 0.8)',
                                'rgba(59, 130, 246, 0.8)',
                                'rgba(99, 102, 241, 0.8)',
                                'rgba(34, 197, 94, 0.8)',
                                'rgba(239, 68, 68, 0.8)'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });

                // Print order function
                function printOrder(orderId) {
                    window.open('app.php?action=print_order&id=' + orderId, '_blank');
                }
            </script>
        </main>
    </div>
</body>
</html>
