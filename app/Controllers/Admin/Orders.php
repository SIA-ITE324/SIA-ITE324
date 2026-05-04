<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\UserModel;

class Orders extends BaseController
{
    protected $orderModel;
    protected $orderItemModel;
    protected $userModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->userModel = new UserModel();
    }

    /**
     * Display orders list
     */
    public function index()
    {
        $this->requireRole('admin');

        $status = $this->request->getGet('status');
        $paymentStatus = $this->request->getGet('payment_status');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');
        $page = $this->request->getGet('page', 1);

        $orders = $this->orderModel->getAllOrders($status, $paymentStatus, $dateFrom, $dateTo, $page);
        $stats = $this->orderModel->getEnhancedOrderStats($dateFrom, $dateTo);

        $data = [
            'orders' => $orders['data'],
            'pagination' => $orders['pagination'],
            'stats' => $stats,
            'filters' => [
                'status' => $status,
                'payment_status' => $paymentStatus,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ],
            'page_title' => 'Orders Management',
        ];

        return view('admin/orders/index', $data);
    }

    /**
     * View order details
     */
    public function view($orderId)
    {
        $this->requireRole('admin');

        $order = $this->orderModel->getOrderWithDetails($orderId);

        if (!$order) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Order not found');
        }

        $data = [
            'order' => $order,
            'page_title' => 'Order Details - ' . $order['order_number'],
        ];

        return view('admin/orders/view', $data);
    }

    /**
     * Create new order
     */
    public function create()
    {
        $this->requireRole('admin');

        $customers = $this->userModel->getActiveUsersByRole('customer');

        $data = [
            'customers' => $customers,
            'page_title' => 'Create New Order',
        ];

        return view('admin/orders/create', $data);
    }

    /**
     * Store new order
     */
    public function store()
    {
        $this->requireRole('admin');

        $rules = [
            'customer_id' => 'required|integer',
            'payment_method' => 'required|in_list[cod,bank_transfer,credit_card,paypal]',
            'shipping_address' => 'required|string',
            'billing_address' => 'permit_empty|string',
            'customer_notes' => 'permit_empty|string',
            'shipping_method' => 'required|string',
            'estimated_delivery' => 'required|valid_date[Y-m-d]',
            'products' => 'required|array',
            'products.*.product_id' => 'required|integer',
            'products.*.quantity' => 'required|integer|greater_than[0]',
            'products.*.unit_price' => 'required|numeric|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $orderData = [
            'customer_id' => $this->request->getPost('customer_id'),
            'payment_method' => $this->request->getPost('payment_method'),
            'shipping_address' => $this->request->getPost('shipping_address'),
            'billing_address' => $this->request->getPost('billing_address') ?: $this->request->getPost('shipping_address'),
            'customer_notes' => $this->request->getPost('customer_notes'),
            'shipping_method' => $this->request->getPost('shipping_method'),
            'estimated_delivery' => $this->request->getPost('estimated_delivery'),
            'status' => 'confirmed',
            'payment_status' => 'pending',
            'currency' => 'USD',
        ];

        $products = $this->request->getPost('products');
        $orderItems = [];

        $subtotal = 0;
        foreach ($products as $product) {
            $totalPrice = $product['quantity'] * $product['unit_price'];
            $subtotal += $totalPrice;

            $orderItems[] = [
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'unit_price' => $product['unit_price'],
                'total_price' => $totalPrice,
                'product_name' => $product['product_name'],
                'product_sku' => $product['product_sku'],
                'product_image' => $product['product_image'] ?? null,
            ];
        }

        $shippingAmount = 10.00; // Default shipping
        $taxAmount = $subtotal * 0.08; // 8% tax
        $discountAmount = 0;
        $totalAmount = $subtotal + $taxAmount + $shippingAmount - $discountAmount;

        $orderData['subtotal'] = $subtotal;
        $orderData['tax_amount'] = $taxAmount;
        $orderData['shipping_amount'] = $shippingAmount;
        $orderData['discount_amount'] = $discountAmount;
        $orderData['total_amount'] = $totalAmount;

        $orderId = $this->orderModel->createOrder($orderData, $orderItems);

        if ($orderId) {
            $this->logActivity('create', 'order', $orderId, "Order created by admin");
            return redirect()->to('/admin/orders/view/' . $orderId)->with('success', 'Order created successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create order. Please try again.');
        }
    }

    /**
     * Edit order
     */
    public function edit($orderId)
    {
        $this->requireRole('admin');

        $order = $this->orderModel->getOrderWithDetails($orderId);

        if (!$order) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Order not found');
        }

        // Check if order can be edited
        if (in_array($order['status'], ['shipped', 'delivered'])) {
            return redirect()->back()->with('error', 'Order cannot be edited in current status.');
        }

        $data = [
            'order' => $order,
            'page_title' => 'Edit Order - ' . $order['order_number'],
        ];

        return view('admin/orders/edit', $data);
    }

    /**
     * Update order
     */
    public function update($orderId)
    {
        $this->requireRole('admin');

        $order = $this->orderModel->find($orderId);

        if (!$order) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Order not found');
        }

        // Check if order can be updated
        if (in_array($order['status'], ['shipped', 'delivered'])) {
            return redirect()->back()->with('error', 'Order cannot be updated in current status.');
        }

        $rules = [
            'shipping_address' => 'required|string',
            'billing_address' => 'permit_empty|string',
            'admin_notes' => 'permit_empty|string',
            'shipping_method' => 'required|string',
            'estimated_delivery' => 'required|valid_date[Y-m-d]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $updateData = [
            'shipping_address' => $this->request->getPost('shipping_address'),
            'billing_address' => $this->request->getPost('billing_address') ?: $this->request->getPost('shipping_address'),
            'admin_notes' => $this->request->getPost('admin_notes'),
            'shipping_method' => $this->request->getPost('shipping_method'),
            'estimated_delivery' => $this->request->getPost('estimated_delivery'),
        ];

        if ($this->orderModel->update($orderId, $updateData)) {
            $this->logActivity('update', 'order', $orderId, "Order updated by admin");
            return redirect()->to('/admin/orders/view/' . $orderId)->with('success', 'Order updated successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update order. Please try again.');
        }
    }

    /**
     * Update order status
     */
    public function updateStatus($orderId)
    {
        $this->requireRole('admin');

        $order = $this->orderModel->find($orderId);

        if (!$order) {
            return $this->response->setJSON(['success' => false, 'message' => 'Order not found']);
        }

        $status = $this->request->getPost('status');
        $notes = $this->request->getPost('notes');

        if ($this->orderModel->updateStatus($orderId, $status, $notes)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Order status updated successfully.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update order status.']);
        }
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus($orderId)
    {
        $this->requireRole('admin');

        $order = $this->orderModel->find($orderId);

        if (!$order) {
            return $this->response->setJSON(['success' => false, 'message' => 'Order not found']);
        }

        $paymentStatus = $this->request->getPost('payment_status');

        if ($this->orderModel->updatePaymentStatus($orderId, $paymentStatus)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Payment status updated successfully.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update payment status.']);
        }
    }

    /**
     * Cancel order
     */
    public function cancel($orderId)
    {
        $this->requireRole('admin');

        $order = $this->orderModel->find($orderId);

        if (!$order) {
            return redirect()->back()->with('error', 'Order not found.');
        }

        if ($this->request->getMethod() === 'post') {
            $reason = $this->request->getPost('reason');

            if ($this->orderModel->cancelOrder($orderId, $reason)) {
                return redirect()->to('/admin/orders/view/' . $orderId)->with('success', 'Order cancelled successfully.');
            } else {
                return redirect()->back()->with('error', 'Failed to cancel order. Please try again.');
            }
        }

        $data = [
            'order' => $order,
            'page_title' => 'Cancel Order - ' . $order['order_number'],
        ];

        return view('admin/orders/cancel', $data);
    }

    /**
     * Delete order
     */
    public function delete($orderId)
    {
        $this->requireRole('admin');

        $order = $this->orderModel->find($orderId);

        if (!$order) {
            return $this->response->setJSON(['success' => false, 'message' => 'Order not found']);
        }

        // Check if order can be deleted
        if (!in_array($order['status'], ['cancelled', 'refunded'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Order cannot be deleted in current status.']);
        }

        if ($this->orderModel->delete($orderId)) {
            $this->logActivity('delete', 'order', $orderId, "Order deleted by admin");
            return $this->response->setJSON(['success' => true, 'message' => 'Order deleted successfully.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete order.']);
        }
    }

    /**
     * Print order
     */
    public function print($orderId)
    {
        $this->requireRole('admin');

        $order = $this->orderModel->getOrderWithDetails($orderId);

        if (!$order) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Order not found');
        }

        $data = [
            'order' => $order,
        ];

        return view('admin/orders/print', $data);
    }

    /**
     * Export orders
     */
    public function export()
    {
        $this->requireRole('admin');

        $status = $this->request->getGet('status');
        $paymentStatus = $this->request->getGet('payment_status');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        $orders = $this->orderModel->getAllOrders($status, $paymentStatus, $dateFrom, $dateTo, 1, 10000);

        $exportData = [];
        foreach ($orders['data'] as $order) {
            $exportData[] = [
                'Order Number' => $order['order_number'],
                'Customer' => $order['customer_name'],
                'Email' => $order['customer_email'],
                'Status' => $order['status'],
                'Payment Status' => $order['payment_status'],
                'Payment Method' => $order['payment_method'],
                'Total Amount' => $order['total_amount'],
                'Currency' => $order['currency'],
                'Created At' => $order['created_at'],
                'Updated At' => $order['updated_at'],
            ];
        }

        $filename = 'orders_export_' . date('Y-m-d_H-i-s');
        $headers = array_keys($exportData[0] ?? []);

        $this->exportToExcel($exportData, $filename, $headers);
    }

    /**
     * Enhanced search orders (AJAX)
     */
    public function search()
    {
        $this->requireRole('admin');

        $searchTerm = $this->request->getGet('search') ?? $this->request->getGet('q');
        $status = $this->request->getGet('status');
        $paymentStatus = $this->request->getGet('payment_status');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');
        $page = $this->request->getGet('page', 1);

        $orders = $this->orderModel->searchOrdersAdvanced($searchTerm, $status, $paymentStatus, $dateFrom, $dateTo, $page);

        return $this->response->setJSON($orders);
    }

    /**
     * Get order statistics (AJAX)
     */
    public function stats()
    {
        $this->requireRole('admin');

        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        $stats = $this->orderModel->getOrderStats($dateFrom, $dateTo);

        return $this->response->setJSON($stats);
    }

    /**
     * Bulk update orders
     */
    public function bulkUpdate()
    {
        $this->requireRole('admin');

        $jsonInput = $this->request->getJSON();
        $orderIds = $jsonInput->order_ids ?? [];
        $updates = $jsonInput->updates ?? [];

        if (empty($orderIds)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No orders selected']);
        }

        $successCount = 0;
        $errorCount = 0;

        foreach ($orderIds as $orderId) {
            $order = $this->orderModel->find($orderId);
            if (!$order) {
                $errorCount++;
                continue;
            }

            $updateData = [];
            $logMessages = [];

            // Update status if provided
            if (!empty($updates->status) && $updates->status !== $order['status']) {
                $updateData['status'] = $updates->status;
                $logMessages[] = "Status changed from {$order['status']} to {$updates->status}";
            }

            // Update payment status if provided
            if (!empty($updates->payment_status) && $updates->payment_status !== $order['payment_status']) {
                $updateData['payment_status'] = $updates->payment_status;
                $logMessages[] = "Payment status changed from {$order['payment_status']} to {$updates->payment_status}";
            }

            // Update driver if provided
            if (!empty($updates->driver) && $updates->driver !== ($order['assigned_driver'] ?? '')) {
                $updateData['assigned_driver'] = $updates->driver;
                $logMessages[] = "Driver assigned: {$updates->driver}";
            }

            // Add notes if provided
            if (!empty($updates->notes)) {
                $updateData['admin_notes'] = ($order['admin_notes'] ?? '') . "\n\n" . date('Y-m-d H:i:s') . ": " . $updates->notes;
                $logMessages[] = "Admin notes added";
            }

            if (!empty($updateData)) {
                if ($this->orderModel->update($orderId, $updateData)) {
                    $successCount++;
                    
                    // Log the activity
                    foreach ($logMessages as $message) {
                        $this->logActivity('bulk_update', 'order', $orderId, $message . " (Bulk update)");
                    }
                } else {
                    $errorCount++;
                }
            }
        }

        $message = "Updated {$successCount} orders successfully";
        if ($errorCount > 0) {
            $message .= ". Failed to update {$errorCount} orders.";
        }

        return $this->response->setJSON([
            'success' => $successCount > 0,
            'message' => $message,
            'success_count' => $successCount,
            'error_count' => $errorCount
        ]);
    }

    /**
     * Assign driver to order
     */
    public function assignDriver()
    {
        $this->requireRole('admin');

        $jsonInput = $this->request->getJSON();
        $orderId = $jsonInput->order_id ?? null;
        $driverId = $jsonInput->driver_id ?? null;

        if (!$orderId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Order ID is required']);
        }

        $order = $this->orderModel->find($orderId);
        if (!$order) {
            return $this->response->setJSON(['success' => false, 'message' => 'Order not found']);
        }

        $updateData = ['assigned_driver' => $driverId];
        
        if ($this->orderModel->update($orderId, $updateData)) {
            $driverName = $driverId ? $driverId : 'unassigned';
            $this->logActivity('assign_driver', 'order', $orderId, "Driver assigned: {$driverName}");
            
            return $this->response->setJSON(['success' => true, 'message' => 'Driver assigned successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to assign driver']);
        }
    }

    /**
     * Check for new orders (for live notifications)
     */
    public function checkNewOrders()
    {
        $this->requireRole('admin');

        $lastCheck = $this->request->getGet('last_check') ?? date('Y-m-d H:i:s', strtotime('-5 minutes'));
        
        $newOrders = $this->orderModel->getOrdersSince($lastCheck);
        $hasNewOrders = !empty($newOrders);

        return $this->response->setJSON([
            'hasNewOrders' => $hasNewOrders,
            'newOrderCount' => count($newOrders),
            'last_check' => date('Y-m-d H:i:s')
        ]);
    }

    
    /**
     * Get order timeline/activity logs
     */
    public function getTimeline($orderId)
    {
        $this->requireRole('admin');

        $order = $this->orderModel->find($orderId);
        if (!$order) {
            return $this->response->setJSON(['success' => false, 'message' => 'Order not found']);
        }

        $activityModel = new \App\Models\ActivityModel();
        $timeline = $activityModel->getOrderTimeline($orderId);

        return $this->response->setJSON([
            'success' => true,
            'timeline' => $timeline
        ]);
    }

    /**
     * Generate printable job sheet (PDF)
     */
    public function printJobSheet($orderId)
    {
        $this->requireRole('admin');

        $order = $this->orderModel->getOrderWithDetails($orderId);
        if (!$order) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Order not found');
        }

        $data = [
            'order' => $order,
            'page_title' => 'Job Sheet - ' . $order['order_number'],
        ];

        // For now, return HTML view. In a real implementation, you'd use a PDF library like DomPDF
        return view('admin/orders/job_sheet', $data);
    }
}
