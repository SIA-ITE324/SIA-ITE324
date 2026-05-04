<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'My Orders' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .kanban-column {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 10px;
            min-height: 400px;
        }
        .kanban-header {
            padding: 10px;
            margin: -10px -10px 10px -10px;
            border-radius: 8px 8px 0 0;
            font-weight: bold;
        }
        .kanban-cards {
            min-height: 350px;
        }
        .kanban-card {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .kanban-card:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
        }
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        .slide-in {
            animation: slideIn 0.3s ease-out;
        }
    </style>
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
                
                <div class="flex items-center space-x-6">
                    <a href="app.php?action=home" class="text-gray-700 hover:text-purple-600">Home</a>
                    <a href="app.php?action=profile" class="text-gray-700 hover:text-purple-600">My Profile</a>
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
                            <a href="app.php?action=logout" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 border-t">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <!-- Revenue Summary Cards -->
        <?php if ($_SESSION['user_role'] === 'admin'): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Sales (Today)</p>
                        <p class="text-2xl font-bold text-gray-900">&#x20B1;<?= number_format(getTodaySales(), 2) ?></p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fas fa-peso-sign text-purple-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pending Deliveries</p>
                        <p class="text-2xl font-bold text-gray-900"><?= getPendingDeliveries() ?></p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-truck text-blue-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Out for Delivery</p>
                        <p class="text-2xl font-bold text-gray-900"><?= getOutForDelivery() ?></p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <i class="fas fa-shipping-fast text-yellow-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Completed Today</p>
                        <p class="text-2xl font-bold text-gray-900"><?= getCompletedToday() ?></p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Header with Controls -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <h2 class="text-2xl font-bold text-gray-800">
                    <?= $_SESSION['user_role'] === 'admin' ? 'All Orders' : 'My Orders' ?>
                </h2>
                
                <div class="flex flex-wrap gap-2">
                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                        <button onclick="toggleView()" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                            <i class="fas fa-th-large mr-2"></i>Kanban View
                        </button>
                        <button onclick="showBulkUpdateModal()" id="bulkUpdateBtn" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700" style="display: none;">
                            <i class="fas fa-edit mr-2"></i>Bulk Update
                        </button>
                        <button onclick="openDeliveryMap()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            <i class="fas fa-map-marked-alt mr-2"></i>Delivery Map
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Enhanced Filters -->
            <div class="mt-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">All Status</option>
                            <option value="pending" <?= $_GET['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="confirmed" <?= $_GET['status'] == 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                            <option value="processing" <?= $_GET['status'] == 'processing' ? 'selected' : '' ?>>Processing</option>
                            <option value="shipped" <?= $_GET['status'] == 'shipped' ? 'selected' : '' ?>>Shipped</option>
                            <option value="delivered" <?= $_GET['status'] == 'delivered' ? 'selected' : '' ?>>Delivered</option>
                            <option value="cancelled" <?= $_GET['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
                        <select name="payment_status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">All Payment</option>
                            <option value="pending" <?= $_GET['payment_status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="paid" <?= $_GET['payment_status'] == 'paid' ? 'selected' : '' ?>>Paid</option>
                            <option value="failed" <?= $_GET['payment_status'] == 'failed' ? 'selected' : '' ?>>Failed</option>
                            <option value="refunded" <?= $_GET['payment_status'] == 'refunded' ? 'selected' : '' ?>>Refunded</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                        <input type="date" name="date_from" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" value="<?= $_GET['date_from'] ?? '' ?>">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                        <input type="date" name="date_to" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" value="<?= $_GET['date_to'] ?? '' ?>">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input type="text" name="search" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Order #, Customer..." value="<?= $_GET['search'] ?? '' ?>">
                    </div>
                    
                    <div class="lg:col-span-5 flex gap-2 mt-3">
                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                            <i class="fas fa-search mr-2"></i>Filter
                        </button>
                        <a href="app.php?action=orders" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                            <i class="fas fa-times mr-2"></i>Clear
                        </a>
                        <?php if ($_SESSION['user_role'] === 'admin'): ?>
                            <a href="app.php?action=export_orders&format=csv" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                <i class="fas fa-file-csv mr-2"></i>Export CSV
                            </a>
                            <a href="app.php?action=export_orders&format=excel" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                <i class="fas fa-file-excel mr-2"></i>Export Excel
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table View -->
        <div id="tableView">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <input type="checkbox" id="selectAll" onchange="toggleAllCheckboxes()" class="rounded">
                                    </th>
                                <?php endif; ?>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Driver</th>
                                <?php endif; ?>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (!empty($orders)): ?>
                            <?php foreach ($orders as $order): ?>
                                <tr class="hover:bg-gray-50">
                                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" class="order-checkbox rounded" value="<?= $order['id'] ?>" onchange="updateBulkUpdateButton()">
                                        </td>
                                    <?php endif; ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?= $order['order_number'] ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            <?= date('M d, Y', strtotime($order['created_at'])) ?>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <?= date('H:i', strtotime($order['created_at'])) ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                                <!-- Extract customer name from shipping address -->
                                                <?php
                                                $shipping_lines = explode("\n", $order['shipping_address']);
                                                $customer_name = 'Unknown';
                                                foreach ($shipping_lines as $line) {
                                                    if (strpos($line, 'Customer:') === 0) {
                                                        $customer_name = trim(substr($line, 9));
                                                        break;
                                                    }
                                                }
                                                echo $customer_name;
                                                ?>
                                            <?php else: ?>
                                                <?= $_SESSION['user_name'] ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
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
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
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
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            ₱<?= number_format($order['total_amount'], 2) ?>
                                        </div>
                                    </td>
                                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <select class="border border-gray-300 rounded px-2 py-1 text-sm driver-select" onchange="assignDriver(<?= $order['id'] ?>, this.value)">
                                                <option value="">Assign Driver</option>
                                                <option value="john" <?= ($order['assigned_driver'] ?? '') == 'john' ? 'selected' : '' ?>>John Driver</option>
                                                <option value="sarah" <?= ($order['assigned_driver'] ?? '') == 'sarah' ? 'selected' : '' ?>>Sarah Driver</option>
                                                <option value="mike" <?= ($order['assigned_driver'] ?? '') == 'mike' ? 'selected' : '' ?>>Mike Driver</option>
                                            </select>
                                        </td>
                                    <?php endif; ?>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="app.php?action=order_details&id=<?= $order['id'] ?>" class="text-purple-600 hover:text-purple-900 mr-3" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                            <a href="app.php?action=print_job_sheet&id=<?= $order['id'] ?>" class="text-green-600 hover:text-green-900 mr-3" title="Print Job Sheet">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            <a href="app.php?action=edit_order&id=<?= $order['id'] ?>" class="text-indigo-600 hover:text-indigo-900 mr-3" title="Edit Order">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="text-red-600 hover:text-red-900" onclick="deleteOrder(<?= $order['id'] ?>)" title="Delete Order">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="<?= $_SESSION['user_role'] === 'admin' ? '9' : '7' ?>" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-shopping-cart text-gray-300 text-5xl mb-4"></i>
                                        <p class="text-gray-500 text-lg">No orders found</p>
                                        <p class="text-gray-400 text-sm mt-1">
                                            <?= $_SESSION['user_role'] === 'admin' ? 'No orders have been placed yet.' : 'You haven\'t placed any orders yet.' ?>
                                        </p>
                                        <?php if ($_SESSION['user_role'] !== 'admin'): ?>
                                            <a href="app.php?action=products" class="mt-4 bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                                                <i class="fas fa-shopping-cart mr-2"></i>Start Shopping
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

                </table>
            </div>
        </div>
        
        <!-- Pagination -->
        <?php if (!empty($orders)): ?>
            <div class="mt-6 flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing <span class="font-medium">1</span> to <span class="font-medium"><?= count($orders) ?></span> of 
                    <span class="font-medium"><?= count($orders) ?></span> results
                </div>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-500 bg-white hover:bg-gray-50" disabled>
                        Previous
                    </button>
                    <button class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-500 bg-white hover:bg-gray-50" disabled>
                        Next
                    </button>
                </div>
            </div>
        <?php endif; ?>
        </div>
        
        <!-- Kanban View -->
        <div id="kanbanView" style="display: none;">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="kanban-column">
                    <div class="kanban-header bg-yellow-100 text-yellow-800 rounded-t-lg">
                        <h6 class="font-bold">Pending (<?= countOrdersByStatus('pending') ?>)</h6>
                    </div>
                    <div class="kanban-cards">
                        <?php foreach ($orders as $order): ?>
                            <?php if ($order['status'] == 'pending'): ?>
                                <?= createKanbanCard($order) ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="kanban-column">
                    <div class="kanban-header bg-blue-100 text-blue-800 rounded-t-lg">
                        <h6 class="font-bold">Confirmed (<?= countOrdersByStatus('confirmed') ?>)</h6>
                    </div>
                    <div class="kanban-cards">
                        <?php foreach ($orders as $order): ?>
                            <?php if ($order['status'] == 'confirmed'): ?>
                                <?= createKanbanCard($order) ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="kanban-column">
                    <div class="kanban-header bg-purple-100 text-purple-800 rounded-t-lg">
                        <h6 class="font-bold">Processing (<?= countOrdersByStatus('processing') ?>)</h6>
                    </div>
                    <div class="kanban-cards">
                        <?php foreach ($orders as $order): ?>
                            <?php if ($order['status'] == 'processing'): ?>
                                <?= createKanbanCard($order) ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="kanban-column">
                    <div class="kanban-header bg-green-100 text-green-800 rounded-t-lg">
                        <h6 class="font-bold">Delivered (<?= countOrdersByStatus('delivered') ?>)</h6>
                    </div>
                    <div class="kanban-cards">
                        <?php foreach ($orders as $order): ?>
                            <?php if ($order['status'] == 'delivered'): ?>
                                <?= createKanbanCard($order) ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bulk Update Modal -->
    <div id="bulkUpdateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" style="display: none;">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-bold text-gray-900">Bulk Update Orders</h3>
                <div class="mt-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Update Status</label>
                        <select id="bulkStatus" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            <option value="">No Change</option>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Update Payment Status</label>
                        <select id="bulkPaymentStatus" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            <option value="">No Change</option>
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="failed">Failed</option>
                            <option value="refunded">Refunded</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Assign Driver</label>
                        <select id="bulkDriver" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            <option value="">No Change</option>
                            <option value="john">John Driver</option>
                            <option value="sarah">Sarah Driver</option>
                            <option value="mike">Mike Driver</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea id="bulkNotes" class="w-full border border-gray-300 rounded-lg px-3 py-2" rows="3" placeholder="Add notes for this bulk update..."></textarea>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button onclick="closeBulkUpdateModal()" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400">Cancel</button>
                    <button onclick="performBulkUpdate()" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">Update Orders</button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        let currentView = 'table';
        const orders = <?= json_encode($orders ?? []) ?>;
        
        function toggleView() {
            if (currentView === 'table') {
                document.getElementById('tableView').style.display = 'none';
                document.getElementById('kanbanView').style.display = 'block';
                currentView = 'kanban';
            } else {
                document.getElementById('tableView').style.display = 'block';
                document.getElementById('kanbanView').style.display = 'none';
                currentView = 'table';
            }
        }
        
        function toggleAllCheckboxes() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.order-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
            
            updateBulkUpdateButton();
        }
        
        function updateBulkUpdateButton() {
            const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
            const bulkBtn = document.getElementById('bulkUpdateBtn');
            
            if (checkedBoxes.length > 0) {
                bulkBtn.style.display = 'inline-block';
                bulkBtn.innerHTML = '<i class="fas fa-edit mr-2"></i>Bulk Update (' + checkedBoxes.length + ')';
            } else {
                bulkBtn.style.display = 'none';
            }
        }
        
        function showBulkUpdateModal() {
            document.getElementById('bulkUpdateModal').style.display = 'block';
        }
        
        function closeBulkUpdateModal() {
            document.getElementById('bulkUpdateModal').style.display = 'none';
        }
        
        function performBulkUpdate() {
            const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
            const orderIds = Array.from(checkedBoxes).map(cb => cb.value);
            
            if (orderIds.length === 0) {
                alert('Please select at least one order to update.');
                return;
            }
            
            const status = document.getElementById('bulkStatus').value;
            const paymentStatus = document.getElementById('bulkPaymentStatus').value;
            const driver = document.getElementById('bulkDriver').value;
            const notes = document.getElementById('bulkNotes').value;
            
            // Create form for bulk update
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'app.php?action=bulk_update_orders';
            
            orderIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'order_ids[]';
                input.value = id;
                form.appendChild(input);
            });
            
            if (status) {
                const statusInput = document.createElement('input');
                statusInput.type = 'hidden';
                statusInput.name = 'status';
                statusInput.value = status;
                form.appendChild(statusInput);
            }
            
            if (paymentStatus) {
                const paymentInput = document.createElement('input');
                paymentInput.type = 'hidden';
                paymentInput.name = 'payment_status';
                paymentInput.value = paymentStatus;
                form.appendChild(paymentInput);
            }
            
            if (driver) {
                const driverInput = document.createElement('input');
                driverInput.type = 'hidden';
                driverInput.name = 'driver';
                driverInput.value = driver;
                form.appendChild(driverInput);
            }
            
            if (notes) {
                const notesInput = document.createElement('input');
                notesInput.type = 'hidden';
                notesInput.name = 'notes';
                notesInput.value = notes;
                form.appendChild(notesInput);
            }
            
            document.body.appendChild(form);
            form.submit();
        }
        
        function assignDriver(orderId, driverId) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'app.php?action=assign_driver';
            
            const orderIdInput = document.createElement('input');
            orderIdInput.type = 'hidden';
            orderIdInput.name = 'order_id';
            orderIdInput.value = orderId;
            
            const driverInput = document.createElement('input');
            driverInput.type = 'hidden';
            driverInput.name = 'driver_id';
            driverInput.value = driverId;
            
            form.appendChild(orderIdInput);
            form.appendChild(driverInput);
            document.body.appendChild(form);
            form.submit();
        }
        
        function openDeliveryMap() {
            const pendingOrders = orders.filter(order => 
                order.status === 'confirmed' || order.status === 'processing'
            );
            
            if (pendingOrders.length === 0) {
                alert('No pending deliveries found.');
                return;
            }
            
            let addresses = [];
            pendingOrders.forEach(order => {
                // Extract address from shipping_address field
                const addressMatch = order.shipping_address.match(/Address:\s*(.+?)(?:\n|$)/);
                if (addressMatch) {
                    addresses.push(encodeURIComponent(addressMatch[1].trim()));
                }
            });
            
            if (addresses.length > 0) {
                const mapUrl = 'https://www.google.com/maps/dir/' + addresses.join('/');
                window.open(mapUrl, '_blank');
            } else {
                alert('No valid addresses found for delivery mapping.');
            }
        }
        
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = 'notification alert alert-' + (type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info') + ' slide-in';
            notification.innerHTML = `
                ${message}
                <button type="button" class="btn-close float-end" onclick="this.parentElement.remove()"></button>
            `;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }
        
        function deleteOrder(orderId) {
            if (confirm('Are you sure you want to delete this order? This action cannot be undone.')) {
                // Create a form for deletion
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'app.php?action=delete_order';
                
                const orderIdInput = document.createElement('input');
                orderIdInput.type = 'hidden';
                orderIdInput.name = 'order_id';
                orderIdInput.value = orderId;
                
                form.appendChild(orderIdInput);
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        // Live notifications (polling every 30 seconds)
        setInterval(() => {
            fetch('app.php?action=check_new_orders')
                .then(response => response.json())
                .then(data => {
                    if (data.hasNewOrders) {
                        showNotification(data.newOrderCount + ' new order(s) received!', 'success');
                        // Play sound notification
                        const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIG2m98OScTgwOUarm7blmFgU7k9n1unEiBC13yO/eizEIHWq+8+OWT');
                        audio.play().catch(e => console.log('Could not play notification sound'));
                    }
                })
                .catch(error => console.log('Error checking for new orders:', error));
        }, 30000);
    </script>
    
    <?php
    // Helper functions for the view
    function getTodaySales() {
        // This would typically query the database
        // For now, return a placeholder value
        return 1250.75;
    }
    
    function getPendingDeliveries() {
        global $orders;
        return count(array_filter($orders ?? [], function($order) {
            return in_array($order['status'] ?? '', ['confirmed', 'processing']);
        }));
    }
    
    function getOutForDelivery() {
        global $orders;
        return count(array_filter($orders ?? [], function($order) {
            return ($order['status'] ?? '') === 'shipped';
        }));
    }
    
    function getCompletedToday() {
        global $orders;
        return count(array_filter($orders ?? [], function($order) {
            return ($order['status'] ?? '') === 'delivered' && 
                   date('Y-m-d', strtotime($order['created_at'])) === date('Y-m-d');
        }));
    }
    
    function countOrdersByStatus($status) {
        global $orders;
        return count(array_filter($orders ?? [], function($order) use ($status) {
            return ($order['status'] ?? '') === $status;
        }));
    }
    
    function createKanbanCard($order) {
        $shipping_lines = explode("\n", $order['shipping_address']);
        $customer_name = 'Unknown';
        foreach ($shipping_lines as $line) {
            if (strpos($line, 'Customer:') === 0) {
                $customer_name = trim(substr($line, 9));
                break;
            }
        }
        
        return '<div class="kanban-card">
            <div class="flex justify-between items-start mb-2">
                <strong>#' . $order['order_number'] . '</strong>
                <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">' . ucfirst($order['status']) . '</span>
            </div>
            <div class="mb-2">
                <small class="text-gray-600">' . $customer_name . '</small><br>
                <strong>₱' . number_format($order['total_amount'], 2) . '</strong>
            </div>
            <div class="flex space-x-2">
                <a href="app.php?action=order_details&id=' . $order['id'] . '" class="text-purple-600 hover:text-purple-900 text-sm">
                    <i class="fas fa-eye"></i> View
                </a>
            </div>
        </div>';
    }
    ?>
</body>
</html>
