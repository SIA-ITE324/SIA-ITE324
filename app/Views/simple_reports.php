<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Reports & Analytics' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                        <a href="app.php?action=reports" class="flex items-center text-white bg-gray-700 p-3 rounded">
                            <i class="fas fa-chart-bar mr-3"></i> Reports
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-bold text-gray-800">Reports & Analytics</h2>
                <div class="flex space-x-4">
                    <select class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option>Last 30 Days</option>
                        <option>Last 90 Days</option>
                        <option>Last 12 Months</option>
                        <option>Custom Range</option>
                    </select>
                    <button class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                        <i class="fas fa-download mr-2"></i>Export PDF
                    </button>
                </div>
            </div>

            <!-- Overview Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
                        </div>
                        <span class="text-sm text-gray-500">Total Orders</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800"><?= $reports['total_orders'] ?></h3>
                    <div class="flex items-center mt-2">
                        <span class="text-sm text-green-600">
                            <i class="fas fa-arrow-up mr-1"></i>
                            All time
                        </span>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fas fa-peso-sign text-green-600 text-xl"></i>
                        </div>
                        <span class="text-sm text-gray-500">Total Revenue</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">₱<?= number_format($reports['total_revenue'], 2) ?></h3>
                    <div class="flex items-center mt-2">
                        <span class="text-sm text-green-600">
                            <i class="fas fa-arrow-up mr-1"></i>
                            All time
                        </span>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-purple-100 p-3 rounded-full">
                            <i class="fas fa-users text-purple-600 text-xl"></i>
                        </div>
                        <span class="text-sm text-gray-500">Total Customers</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800"><?= $reports['total_customers'] ?></h3>
                    <div class="flex items-center mt-2">
                        <span class="text-sm text-blue-600">
                            <i class="fas fa-user-plus mr-1"></i>
                            Registered
                        </span>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-pink-100 p-3 rounded-full">
                            <i class="fas fa-box text-pink-600 text-xl"></i>
                        </div>
                        <span class="text-sm text-gray-500">Products</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800"><?= $reports['total_products'] ?></h3>
                    <div class="flex items-center mt-2">
                        <span class="text-sm text-gray-600">
                            In catalog
                        </span>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Sales Chart -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Sales Trend</h3>
                    <div class="h-64 bg-gradient-to-br from-purple-50 to-pink-50 rounded flex items-center justify-center">
                        <div class="text-center">
                            <i class="fas fa-chart-line text-purple-400 text-4xl mb-2"></i>
                            <p class="text-gray-600">Sales chart visualization</p>
                            <p class="text-sm text-gray-500 mt-2">Monthly sales data</p>
                        </div>
                    </div>
                </div>

                <!-- Order Status Chart -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Status</h3>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm text-gray-600">Pending</span>
                                <span class="text-sm font-medium"><?= $reports['pending_orders'] ?></span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-yellow-500 h-2 rounded-full" style="width: <?= ($reports['pending_orders'] / max($reports['total_orders'], 1)) * 100 ?>%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm text-gray-600">Completed</span>
                                <span class="text-sm font-medium"><?= $reports['completed_orders'] ?></span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: <?= ($reports['completed_orders'] / max($reports['total_orders'], 1)) * 100 ?>%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm text-gray-600">Other</span>
                                <span class="text-sm font-medium"><?= $reports['total_orders'] - $reports['pending_orders'] - $reports['completed_orders'] ?></span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: <?= (($reports['total_orders'] - $reports['pending_orders'] - $reports['completed_orders']) / max($reports['total_orders'], 1)) * 100 ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Sales Table -->
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Monthly Sales Report</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2">Month</th>
                                <th class="text-right py-2">Orders</th>
                                <th class="text-right py-2">Revenue</th>
                                <th class="text-right py-2">Avg. Order</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($monthly_sales)): ?>
                                <?php foreach ($monthly_sales as $sale): ?>
                                    <tr class="border-b">
                                        <td class="py-2"><?= date('F Y', strtotime($sale['month'] . '-01')) ?></td>
                                        <td class="text-right py-2"><?= $sale['orders'] ?></td>
                                        <td class="text-right py-2">₱<?= number_format($sale['revenue'], 2) ?></td>
                                        <td class="text-right py-2">₱<?= number_format($sale['revenue'] / max($sale['orders'], 1), 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-gray-500">No sales data available</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Top Products -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Top Selling Products</h3>
                <div class="space-y-4">
                    <?php if (!empty($top_products)): ?>
                        <?php foreach ($top_products as $index => $product): ?>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-purple-600 font-semibold"><?= $index + 1 ?></span>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900"><?= $product['name'] ?></div>
                                        <div class="text-sm text-gray-500"><?= $product['total_sold'] ?> units sold</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-medium text-gray-900">₱<?= number_format($product['total_revenue'], 2) ?></div>
                                    <div class="text-sm text-gray-500">Revenue</div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-box text-gray-300 text-4xl mb-2"></i>
                            <p>No sales data available</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
