<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Receipt - <?= $order['order_number'] ?></title>
    <style>
        @media print {
            .no-print { display: none; }
            body { font-family: Arial, sans-serif; }
            .receipt { max-width: 600px; margin: 0 auto; }
        }
        .receipt { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 20px; }
        .customer-info, .order-info { margin-bottom: 20px; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .items-table th, .items-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .items-table th { background-color: #f5f5f5; }
        .total-row { font-weight: bold; }
        .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <h1><i class="fas fa-spa text-pink-500"></i> Fleur</h1>
            <h2>Order Receipt</h2>
            <p>Quality Flowers for Every Occasion</p>
        </div>

        <div class="customer-info">
            <h3>Customer Information</h3>
            <p><strong>Name:</strong> <?= $order['customer_name'] ?? 'Guest' ?></p>
            <p><strong>Email:</strong> <?= $order['customer_email'] ?? 'N/A' ?></p>
            <p><strong>Phone:</strong> <?= $order['phone'] ?? 'N/A' ?></p>
            <p><strong>Address:</strong> <?= $order['shipping_address'] ?? 'N/A' ?></p>
        </div>

        <div class="order-info">
            <h3>Order Details</h3>
            <p><strong>Order Number:</strong> <?= $order['order_number'] ?></p>
            <p><strong>Date:</strong> <?= date('F d, Y h:i A', strtotime($order['created_at'])) ?></p>
            <p><strong>Status:</strong> <?= ucfirst($order['status']) ?></p>
            <p><strong>Payment Status:</strong> <?= ucfirst($order['payment_status']) ?></p>
        </div>

        <h3>Order Items</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order_items as $item): ?>
                    <tr>
                        <td><?= $item['product_name'] ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td>₱<?= number_format($item['unit_price'], 2) ?></td>
                        <td>₱<?= number_format($item['total_price'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td colspan="3"><strong>Total Amount:</strong></td>
                    <td><strong>₱<?= number_format($order['total_amount'], 2) ?></strong></td>
                </tr>
            </tbody>
        </table>

        <?php if ($order['notes']): ?>
            <div class="notes">
                <h3>Order Notes</h3>
                <p><?= nl2br(htmlspecialchars($order['notes'])) ?></p>
            </div>
        <?php endif; ?>

        <div class="footer">
            <p>Thank you for choosing Fleur!</p>
            <p>For inquiries, contact us at info@fleur.com or +1 234 567 890</p>
            <p>Generated on: <?= date('F d, Y h:i A') ?></p>
        </div>

        <div class="no-print" style="margin-top: 30px; text-align: center;">
            <button onclick="window.print()" class="bg-purple-600 text-white px-6 py-2 rounded hover:bg-purple-700">
                <i class="fas fa-print mr-2"></i>Print Receipt
            </button>
            <button onclick="window.close()" class="ml-4 bg-gray-600 text-white px-6 py-2 rounded hover:bg-gray-700">
                <i class="fas fa-times mr-2"></i>Close
            </button>
        </div>
    </div>
</body>
</html>
