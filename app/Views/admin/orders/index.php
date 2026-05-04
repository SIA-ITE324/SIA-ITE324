<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <!-- Revenue Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Sales (Today)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                &#x20B1;<?= number_format($stats['today_sales'] ?? 0, 2) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Pending Deliveries
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $stats['pending_deliveries'] ?? 0 ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-truck fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Out for Delivery
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $stats['out_for_delivery'] ?? 0 ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shipping-fast fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Completed Today
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $stats['completed_today'] ?? 0 ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Actions -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Orders Management</h6>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-primary" onclick="toggleView()">
                    <i class="fas fa-th-large"></i> Kanban View
                </button>
                <button class="btn btn-sm btn-success" onclick="showBulkUpdateModal()" id="bulkUpdateBtn" style="display: none;">
                    <i class="fas fa-edit"></i> Bulk Update
                </button>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" class="mb-3">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" <?= $filters['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="confirmed" <?= $filters['status'] == 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                            <option value="processing" <?= $filters['status'] == 'processing' ? 'selected' : '' ?>>Processing</option>
                            <option value="shipped" <?= $filters['status'] == 'shipped' ? 'selected' : '' ?>>Shipped</option>
                            <option value="delivered" <?= $filters['status'] == 'delivered' ? 'selected' : '' ?>>Delivered</option>
                            <option value="cancelled" <?= $filters['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Payment Status</label>
                        <select name="payment_status" class="form-select">
                            <option value="">All Payment</option>
                            <option value="pending" <?= $filters['payment_status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="paid" <?= $filters['payment_status'] == 'paid' ? 'selected' : '' ?>>Paid</option>
                            <option value="failed" <?= $filters['payment_status'] == 'failed' ? 'selected' : '' ?>>Failed</option>
                            <option value="refunded" <?= $filters['payment_status'] == 'refunded' ? 'selected' : '' ?>>Refunded</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Date From</label>
                        <input type="date" name="date_from" class="form-control" value="<?= $filters['date_from'] ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Date To</label>
                        <input type="date" name="date_to" class="form-control" value="<?= $filters['date_to'] ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Order #, Customer, Product..." value="<?= $filters['search'] ?? '' ?>">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="<?= base_url('/admin/orders') ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </form>

            <!-- Export Buttons -->
            <div class="d-flex gap-2 mb-3">
                <a href="<?= base_url('/admin/orders/export?format=csv') ?>" class="btn btn-sm btn-success">
                    <i class="fas fa-file-csv"></i> Export CSV
                </a>
                <a href="<?= base_url('/admin/orders/export?format=excel') ?>" class="btn btn-sm btn-success">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
                <button class="btn btn-sm btn-info" onclick="openDeliveryMap()">
                    <i class="fas fa-map-marked-alt"></i> Delivery Map
                </button>
            </div>

            <!-- Table View -->
            <div id="tableView">
                <div class="table-responsive">
                    <table class="table table-bordered" id="ordersTable">
                        <thead class="table-light">
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAll" onchange="toggleAllCheckboxes()">
                                </th>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Total</th>
                                <th>Driver</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($orders)): ?>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="order-checkbox" value="<?= $order['id'] ?>" onchange="updateBulkUpdateButton()">
                                        </td>
                                        <td>
                                            <strong><?= $order['order_number'] ?></strong>
                                        </td>
                                        <td>
                                            <?= date('M d, Y', strtotime($order['created_at'])) ?>
                                            <br>
                                            <small class="text-muted"><?= date('H:i', strtotime($order['created_at'])) ?></small>
                                        </td>
                                        <td>
                                            <div class="fw-bold"><?= $order['customer_name'] ?></div>
                                            <small class="text-muted"><?= $order['customer_email'] ?></small>
                                        </td>
                                        <td>
                                            <span class="badge badge-<?= getStatusColor($order['status']) ?>">
                                                <?= ucfirst($order['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-<?= getPaymentStatusColor($order['payment_status']) ?>">
                                                <?= ucfirst($order['payment_status']) ?>
                                            </span>
                                        </td>
                                        <td class="fw-bold">
                                            &#x20B1;<?= number_format($order['total_amount'], 2) ?>
                                        </td>
                                        <td>
                                            <select class="form-select form-select-sm driver-select" 
                                                    onchange="assignDriver(<?= $order['id'] ?>, this.value)">
                                                <option value="">Assign Driver</option>
                                                <option value="driver1" <?= $order['assigned_driver'] == 'driver1' ? 'selected' : '' ?>>John Driver</option>
                                                <option value="driver2" <?= $order['assigned_driver'] == 'driver2' ? 'selected' : '' ?>>Sarah Driver</option>
                                                <option value="driver3" <?= $order['assigned_driver'] == 'driver3' ? 'selected' : '' ?>>Mike Driver</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?= base_url('/admin/orders/view/' . $order['id']) ?>" 
                                                   class="btn btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?= base_url('/admin/orders/print/' . $order['id']) ?>" 
                                                   class="btn btn-success" title="Print Job Sheet">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                                <a href="<?= base_url('/admin/orders/edit/' . $order['id']) ?>" 
                                                   class="btn btn-warning" title="Edit Order">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-danger" onclick="deleteOrder(<?= $order['id'] ?>)" title="Delete Order">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No orders found matching your criteria.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?= $pagination ?>
            </div>

            <!-- Kanban View -->
            <div id="kanbanView" style="display: none;">
                <div class="row">
                    <div class="col-md-3">
                        <div class="kanban-column">
                            <h6 class="kanban-header bg-warning text-dark">Pending</h6>
                            <div class="kanban-cards" id="pending-cards">
                                <!-- Cards will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="kanban-column">
                            <h6 class="kanban-header bg-info text-white">Confirmed</h6>
                            <div class="kanban-cards" id="confirmed-cards">
                                <!-- Cards will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="kanban-column">
                            <h6 class="kanban-header bg-primary text-white">Processing</h6>
                            <div class="kanban-cards" id="processing-cards">
                                <!-- Cards will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="kanban-column">
                            <h6 class="kanban-header bg-success text-white">Delivered</h6>
                            <div class="kanban-cards" id="delivered-cards">
                                <!-- Cards will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Update Modal -->
<div class="modal fade" id="bulkUpdateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Update Orders</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Update Status</label>
                    <select class="form-select" id="bulkStatus">
                        <option value="">No Change</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Update Payment Status</label>
                    <select class="form-select" id="bulkPaymentStatus">
                        <option value="">No Change</option>
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                        <option value="failed">Failed</option>
                        <option value="refunded">Refunded</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Assign Driver</label>
                    <select class="form-select" id="bulkDriver">
                        <option value="">No Change</option>
                        <option value="driver1">John Driver</option>
                        <option value="driver2">Sarah Driver</option>
                        <option value="driver3">Mike Driver</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" id="bulkNotes" rows="3" placeholder="Add notes for this bulk update..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="performBulkUpdate()">Update Orders</button>
            </div>
        </div>
    </div>
</div>

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

.badge-pending { background-color: #ffc107; color: #212529; }
.badge-confirmed { background-color: #17a2b8; color: white; }
.badge-processing { background-color: #6f42c1; color: white; }
.badge-shipped { background-color: #6610f2; color: white; }
.badge-delivered { background-color: #28a745; color: white; }
.badge-cancelled { background-color: #dc3545; color: white; }

.badge-pending { background-color: #ffc107; color: #212529; }
.badge-paid { background-color: #28a745; color: white; }
.badge-failed { background-color: #dc3545; color: white; }
.badge-refunded { background-color: #6c757d; color: white; }
</style>

<script>
let currentView = 'table';
const orders = <?= json_encode($orders) ?>;

function toggleView() {
    if (currentView === 'table') {
        document.getElementById('tableView').style.display = 'none';
        document.getElementById('kanbanView').style.display = 'block';
        currentView = 'kanban';
        populateKanbanBoard();
    } else {
        document.getElementById('tableView').style.display = 'block';
        document.getElementById('kanbanView').style.display = 'none';
        currentView = 'table';
    }
}

function populateKanbanBoard() {
    const statuses = ['pending', 'confirmed', 'processing', 'delivered'];
    
    statuses.forEach(status => {
        const container = document.getElementById(`${status}-cards`);
        container.innerHTML = '';
        
        orders.filter(order => order.status === status).forEach(order => {
            const card = createKanbanCard(order);
            container.appendChild(card);
        });
    });
}

function createKanbanCard(order) {
    const card = document.createElement('div');
    card.className = 'kanban-card';
    card.innerHTML = `
        <div class="d-flex justify-content-between align-items-start mb-2">
            <strong>#${order.order_number}</strong>
            <span class="badge badge-${getStatusColor(order.status)}">${order.status}</span>
        </div>
        <div class="mb-2">
            <small class="text-muted">${order.customer_name}</small><br>
            <strong>$${parseFloat(order.total_amount).toFixed(2)}</strong>
        </div>
        <div class="btn-group btn-group-sm">
            <a href="/admin/orders/view/${order.id}" class="btn btn-outline-info btn-sm">
                <i class="fas fa-eye"></i>
            </a>
        </div>
    `;
    return card;
}

function getStatusColor(status) {
    const colors = {
        'pending': 'pending',
        'confirmed': 'confirmed', 
        'processing': 'processing',
        'shipped': 'shipped',
        'delivered': 'delivered',
        'cancelled': 'cancelled'
    };
    return colors[status] || 'secondary';
}

function getPaymentStatusColor(status) {
    const colors = {
        'pending': 'pending',
        'paid': 'paid',
        'failed': 'failed', 
        'refunded': 'refunded'
    };
    return colors[status] || 'secondary';
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
        bulkBtn.innerHTML = `<i class="fas fa-edit"></i> Bulk Update (${checkedBoxes.length})`;
    } else {
        bulkBtn.style.display = 'none';
    }
}

function showBulkUpdateModal() {
    const modal = new bootstrap.Modal(document.getElementById('bulkUpdateModal'));
    modal.show();
}

function performBulkUpdate() {
    const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
    const orderIds = Array.from(checkedBoxes).map(cb => cb.value);
    
    const updateData = {
        status: document.getElementById('bulkStatus').value,
        payment_status: document.getElementById('bulkPaymentStatus').value,
        driver: document.getElementById('bulkDriver').value,
        notes: document.getElementById('bulkNotes').value
    };
    
    fetch('<?= base_url('/admin/orders/bulkUpdate') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            order_ids: orderIds,
            updates: updateData
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating orders.');
    });
}

function assignDriver(orderId, driverId) {
    fetch(`<?= base_url('/admin/orders/assignDriver') ?>`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            order_id: orderId,
            driver_id: driverId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success notification
            showNotification('Driver assigned successfully', 'success');
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while assigning driver.');
    });
}

function deleteOrder(orderId) {
    if (confirm('Are you sure you want to delete this order? This action cannot be undone.')) {
        fetch(`<?= base_url('/admin/orders/delete') ?>/${orderId}`, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the order.');
        });
    }
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
        const mapUrl = `https://www.google.com/maps/dir/${addresses.join('/')}`;
        window.open(mapUrl, '_blank');
    } else {
        alert('No valid addresses found for delivery mapping.');
    }
}

function showNotification(message, type = 'info') {
    // Simple notification - you can replace with a proper toast library
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

// Live notifications (polling every 30 seconds)
setInterval(() => {
    fetch('<?= base_url('/admin/orders/checkNewOrders') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.hasNewOrders) {
                showNotification(`${data.newOrderCount} new order(s) received!`, 'success');
                // Play sound notification
                const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIG2m98OScTgwOUarm7blmFgU7k9n1unEiBC13yO/eizEIHWq+8+OWT');
                audio.play().catch(e => console.log('Could not play notification sound'));
            }
        })
        .catch(error => console.log('Error checking for new orders:', error));
}, 30000);
</script>

<?php
function getStatusColor($status) {
    $colors = [
        'pending' => 'pending',
        'confirmed' => 'confirmed',
        'processing' => 'processing', 
        'shipped' => 'shipped',
        'delivered' => 'delivered',
        'cancelled' => 'cancelled'
    ];
    return $colors[$status] ?? 'secondary';
}

function getPaymentStatusColor($status) {
    $colors = [
        'pending' => 'pending',
        'paid' => 'paid',
        'failed' => 'failed',
        'refunded' => 'refunded'
    ];
    return $colors[$status] ?? 'secondary';
}
?>
<?= $this->endSection() ?>
