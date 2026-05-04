<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Customer Details' ?></title>
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
                        <a href="app.php?action=customers" class="flex items-center text-white bg-gray-700 p-3 rounded">
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
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center">
                    <a href="app.php?action=customers" class="text-gray-600 hover:text-gray-800 mr-4">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h2 class="text-3xl font-bold text-gray-800">Customer Details</h2>
                </div>
                <div class="flex space-x-4">
                    <button class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                        <i class="fas fa-envelope mr-2"></i>Contact Customer
                    </button>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        <i class="fas fa-download mr-2"></i>Export Data
                    </button>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <div class="flex items-start space-x-6">
                    <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-purple-600 text-2xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-gray-800">
                            <?= $customer['first_name'] . ' ' . $customer['last_name'] ?>
                        </h3>
                        <p class="text-gray-600 mb-2">Customer ID: #<?= str_pad($customer['id'], 6, '0', STR_PAD_LEFT) ?></p>
                        <div class="flex items-center space-x-4">
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                <?= ucfirst($customer['status']) ?>
                            </span>
                            <span class="text-sm text-gray-500">
                                Member since <?= date('F d, Y', strtotime($customer['created_at'])) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Contact Information</h4>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-gray-400 mr-3 w-5"></i>
                            <div>
                                <div class="text-sm text-gray-600">Email</div>
                                <div class="font-medium"><?= $customer['email'] ?></div>
                            </div>
                        </div>
                        <?php if ($customer['phone']): ?>
                            <div class="flex items-center">
                                <i class="fas fa-phone text-gray-400 mr-3 w-5"></i>
                                <div>
                                    <div class="text-sm text-gray-600">Phone</div>
                                    <div class="font-medium"><?= $customer['phone'] ?></div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($customer['address']): ?>
                            <div class="flex items-start">
                                <i class="fas fa-map-marker-alt text-gray-400 mr-3 w-5 mt-1"></i>
                                <div>
                                    <div class="text-sm text-gray-600">Address</div>
                                    <div class="font-medium">
                                        <?= $customer['address'] ?><br>
                                        <?= $customer['city'] ?>, <?= $customer['state'] ?><br>
                                        <?= $customer['postal_code'] ?>, <?= $customer['country'] ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Account Statistics</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Total Orders</span>
                            <span class="font-medium"><?= count($orders) ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Total Spent</span>
                            <span class="font-medium">₱<?= number_format(array_sum(array_column($orders, 'total_amount')), 2) ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Average Order</span>
                            <span class="font-medium">₱<?= number_format(count($orders) > 0 ? array_sum(array_column($orders, 'total_amount')) / count($orders) : 0, 2) ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Last Login</span>
                            <span class="font-medium"><?= $customer['last_login'] ? date('M d, Y', strtotime($customer['last_login'])) : 'Never' ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order History -->
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Order History</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2">Order #</th>
                                <th class="text-left py-2">Date</th>
                                <th class="text-left py-2">Status</th>
                                <th class="text-right py-2">Amount</th>
                                <th class="text-left py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($orders)): ?>
                                <?php foreach ($orders as $order): ?>
                                    <tr class="border-b">
                                        <td class="py-2">
                                            <a href="#" class="text-blue-600 hover:text-blue-800">
                                                <?= $order['order_number'] ?>
                                            </a>
                                        </td>
                                        <td class="py-2"><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                                        <td class="py-2">
                                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                                <?= ucfirst($order['status']) ?>
                                            </span>
                                        </td>
                                        <td class="py-2 text-right">₱<?= number_format($order['total_amount'], 2) ?></td>
                                        <td class="py-2">
                                            <button class="text-purple-600 hover:text-purple-900">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-gray-500">No orders found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
