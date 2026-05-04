<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Inventory Management' ?></title>
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
                        <a href="app.php?action=inventory" class="flex items-center text-white bg-gray-700 p-3 rounded">
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

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <?= $_SESSION['error'] ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-bold text-gray-800">Inventory Management</h2>
                <div class="flex space-x-4">
                    <button class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                        <i class="fas fa-plus mr-2"></i>Add Stock
                    </button>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        <i class="fas fa-download mr-2"></i>Export Report
                    </button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-box text-blue-600 text-xl"></i>
                        </div>
                        <span class="text-sm text-gray-500">Total Items</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800"><?= count($inventory) ?></h3>
                    <div class="flex items-center mt-2">
                        <span class="text-sm text-gray-600">
                            In inventory
                        </span>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-red-100 p-3 rounded-full">
                            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                        </div>
                        <span class="text-sm text-gray-500">Low Stock</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800"><?= count($low_stock) ?></h3>
                    <div class="flex items-center mt-2">
                        <span class="text-sm text-red-600">
                            Need reordering
                        </span>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                        <span class="text-sm text-gray-500">In Stock</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">
                        <?php
                        $in_stock = 0;
                        foreach ($inventory as $item) {
                            if ($item['stock_quantity'] > $item['min_stock_level']) {
                                $in_stock++;
                            }
                        }
                        echo $in_stock;
                        ?>
                    </h3>
                    <div class="flex items-center mt-2">
                        <span class="text-sm text-green-600">
                            Available items
                        </span>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-purple-100 p-3 rounded-full">
                            <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                        </div>
                        <span class="text-sm text-gray-500">Total Value</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">
                        ₱<?= number_format(array_sum(array_column($inventory, 'stock_quantity')) * 150, 2) ?>
                    </h3>
                    <div class="flex items-center mt-2">
                        <span class="text-sm text-gray-600">
                            Est. value
                        </span>
                    </div>
                </div>
            </div>

            <!-- Low Stock Alert -->
            <?php if (!empty($low_stock)): ?>
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                        <h3 class="text-lg font-semibold text-red-800">Low Stock Alert</h3>
                    </div>
                    <p class="text-red-700 mb-3">The following products need to be restocked soon:</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        <?php foreach ($low_stock as $item): ?>
                            <div class="bg-white rounded p-3 border border-red-200">
                                <div class="flex justify-between items-center">
                                    <span class="font-medium text-gray-800"><?= $item['name'] ?></span>
                                    <span class="text-red-600 font-bold"><?= $item['stock_quantity'] ?></span>
                                </div>
                                <div class="text-sm text-gray-600">Min: <?= $item['min_stock_level'] ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Inventory Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Stock</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Min Level</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (!empty($inventory)): ?>
                                <?php foreach ($inventory as $item): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-gray-100 rounded flex items-center justify-center mr-3">
                                                    <i class="fas fa-spa text-gray-400"></i>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <?= $item['product_name'] ?>
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        <?= $item['status'] == 'active' ? 'Available' : 'Unavailable' ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                <?= $item['sku'] ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?= $item['stock_quantity'] ?>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                units
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                <?= $item['min_stock_level'] ?>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                units
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                <?php
                                                if ($item['stock_quantity'] <= $item['min_stock_level']) {
                                                    echo 'bg-red-100 text-red-800';
                                                } elseif ($item['stock_quantity'] <= $item['min_stock_level'] * 1.5) {
                                                    echo 'bg-yellow-100 text-yellow-800';
                                                } else {
                                                    echo 'bg-green-100 text-green-800';
                                                }
                                                ?>">
                                                <?php
                                                if ($item['stock_quantity'] <= $item['min_stock_level']) {
                                                    echo 'Low Stock';
                                                } elseif ($item['stock_quantity'] <= $item['min_stock_level'] * 1.5) {
                                                    echo 'Medium';
                                                } else {
                                                    echo 'Good';
                                                }
                                                ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                <?= $item['updated_at'] ? date('M d, Y', strtotime($item['updated_at'])) : 'Never' ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button class="text-blue-600 hover:text-blue-900 mr-3">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                            <a href="app.php?action=edit_inventory&id=<?= $item['id'] ?>" class="text-purple-600 hover:text-purple-900 mr-3" title="Edit Inventory">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="text-gray-600 hover:text-gray-900">
                                                <i class="fas fa-history"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-warehouse text-gray-300 text-5xl mb-4"></i>
                                            <p class="text-gray-500 text-lg">No inventory data found</p>
                                            <p class="text-gray-400 text-sm mt-1">Inventory tracking hasn't been set up yet.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-6 bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <button class="bg-purple-600 text-white px-4 py-3 rounded hover:bg-purple-700">
                        <i class="fas fa-plus mr-2"></i>Add Stock to Multiple Items
                    </button>
                    <button class="bg-blue-600 text-white px-4 py-3 rounded hover:bg-blue-700">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Generate Low Stock Report
                    </button>
                    <button class="bg-green-600 text-white px-4 py-3 rounded hover:bg-green-700">
                        <i class="fas fa-sync mr-2"></i>Sync with Product Database
                    </button>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
