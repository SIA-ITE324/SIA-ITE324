<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <style>
        @media print {
            body { font-family: Arial, sans-serif; font-size: 12px; }
            .no-print { display: none; }
            .page-break { page-break-after: always; }
        }
        
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .logo { font-size: 24px; font-weight: bold; color: #6f42c1; }
        .order-info { background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .section { margin-bottom: 25px; }
        .section-title { font-weight: bold; font-size: 14px; margin-bottom: 10px; border-bottom: 2px solid #6f42c1; padding-bottom: 5px; }
        .item-row { display: flex; justify-content: space-between; margin-bottom: 8px; }
        .item-details { flex: 1; }
        .item-quantity { width: 80px; text-align: center; }
        .item-price { width: 100px; text-align: right; }
        .total-row { font-weight: bold; border-top: 2px solid #333; padding-top: 10px; margin-top: 10px; }
        .notes { background: #fff3cd; border: 1px solid #ffeaa7; padding: 10px; border-radius: 5px; }
        .status-badge { padding: 4px 8px; border-radius: 3px; font-size: 11px; font-weight: bold; }
        .status-pending { background: #ffc107; color: #212529; }
        .status-confirmed { background: #17a2b8; color: white; }
        .status-processing { background: #6f42c1; color: white; }
        .status-shipped { background: #6610f2; color: white; }
        .status-delivered { background: #28a745; color: white; }
        .status-cancelled { background: #dc3545; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">Fleur Flower Shop</div>
        <div>Job Sheet - Florist Instructions</div>
        <div>Generated: <?= date('Y-m-d H:i:s') ?></div>
    </div>

    <div class="order-info">
        <div class="item-row">
            <div><strong>Order Number:</strong> <?= $order['order_number'] ?></div>
            <div><strong>Date:</strong> <?= date('M d, Y H:i', strtotime($order['created_at'])) ?></div>
        </div>
        <div class="item-row">
            <div><strong>Customer:</strong> <?= $order['customer_name'] ?></div>
            <div><strong>Phone:</strong> <?= $order['customer_phone'] ?? 'N/A' ?></div>
        </div>
        <div class="item-row">
            <div><strong>Status:</strong> <span class="status-badge status-<?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span></div>
            <div><strong>Payment:</strong> <span class="status-badge status-<?= $order['payment_status'] ?>"><?= ucfirst($order['payment_status']) ?></span></div>
        </div>
        <div class="item-row">
            <div><strong>Delivery Date:</strong> <?= date('M d, Y', strtotime($order['estimated_delivery'])) ?></div>
            <div><strong>Driver:</strong> <?= $order['assigned_driver'] ?? 'Not assigned' ?></div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Delivery Address</div>
        <div style="background: #e3f2fd; padding: 10px; border-radius: 5px;">
            <?= nl2br(htmlspecialchars($order['shipping_address'])) ?>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Bouquet Composition</div>
        <?php if (!empty($order['items'])): ?>
            <div class="item-row" style="font-weight: bold; border-bottom: 1px solid #ddd; padding-bottom: 5px;">
                <div class="item-details">Product Details</div>
                <div class="item-quantity">Qty</div>
                <div class="item-price">Price</div>
            </div>
            <?php foreach ($order['items'] as $item): ?>
                <div class="item-row">
                    <div class="item-details">
                        <strong><?= htmlspecialchars($item['product_name']) ?></strong>
                        <?php if (!empty($item['product_sku'])): ?>
                            <br><small style="color: #666;">SKU: <?= $item['product_sku'] ?></small>
                        <?php endif; ?>
                        <?php if (!empty($item['custom_message'])): ?>
                            <br><small style="color: #6f42c1;"><em>Message: "<?= htmlspecialchars($item['custom_message']) ?>"</em></small>
                        <?php endif; ?>
                    </div>
                    <div class="item-quantity"><?= $item['quantity'] ?></div>
                    <div class="item-price">&#x20B1;<?= number_format($item['unit_price'], 2) ?></div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="color: #666;">No items found for this order.</div>
        <?php endif; ?>
    </div>

    <?php if (!empty($order['customer_notes'])): ?>
        <div class="section">
            <div class="section-title">Customer Special Instructions</div>
            <div class="notes">
                <?= nl2br(htmlspecialchars($order['customer_notes'])) ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($order['admin_notes'])): ?>
        <div class="section">
            <div class="section-title">Admin Notes</div>
            <div class="notes">
                <?= nl2br(htmlspecialchars($order['admin_notes'])) ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="section">
        <div class="section-title">Order Summary</div>
        <div class="item-row">
            <div>Subtotal:</div>
            <div style="text-align: right;">&#x20B1;<?= number_format($order['subtotal'], 2) ?></div>
        </div>
        <div class="item-row">
            <div>Tax (8%):</div>
            <div style="text-align: right;">&#x20B1;<?= number_format($order['tax_amount'], 2) ?></div>
        </div>
        <div class="item-row">
            <div>Shipping:</div>
            <div style="text-align: right;">&#x20B1;<?= number_format($order['shipping_amount'], 2) ?></div>
        </div>
        <?php if ($order['discount_amount'] > 0): ?>
            <div class="item-row">
                <div>Discount:</div>
                <div style="text-align: right;">-&#x20B1;<?= number_format($order['discount_amount'], 2) ?></div>
            </div>
        <?php endif; ?>
        <div class="item-row total-row">
            <div><strong>Total Amount:</strong></div>
            <div style="text-align: right;"><strong>&#x20B1;<?= number_format($order['total_amount'], 2) ?></strong></div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Assembly Instructions</div>
        <div style="background: #f0f8ff; border: 1px solid #b3d9ff; padding: 15px; border-radius: 5px;">
            <div style="margin-bottom: 10px;"><strong>Priority:</strong> 
                <?php 
                $priority = 'Normal';
                if (strtotime($order['estimated_delivery']) <= strtotime('+1 day')) {
                    $priority = 'URGENT - Same Day Delivery';
                } elseif (strtotime($order['estimated_delivery']) <= strtotime('+2 days')) {
                    $priority = 'High - Next Day Delivery';
                }
                echo $priority;
                ?>
            </div>
            <div style="margin-bottom: 10px;"><strong>Assembly Deadline:</strong> <?= date('M d, Y H:i', strtotime($order['estimated_delivery'] . ' -2 hours')) ?></div>
            <div style="margin-bottom: 10px;"><strong>Quality Check:</strong> Ensure all flowers are fresh and arranged according to specifications</div>
            <div style="margin-bottom: 10px;"><strong>Card Message:</strong> 
                <?php 
                $hasMessage = false;
                foreach ($order['items'] as $item) {
                    if (!empty($item['custom_message'])) {
                        echo 'YES - "' . htmlspecialchars($item['custom_message']) . '"';
                        $hasMessage = true;
                        break;
                    }
                }
                if (!$hasMessage) {
                    echo 'No custom message';
                }
                ?>
            </div>
            <div><strong>Special Handling:</strong> Handle with care, keep in cool environment until delivery</div>
        </div>
    </div>

    <div class="no-print" style="margin-top: 30px; text-align: center;">
        <button onclick="window.print()" style="background: #6f42c1; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
            <i class="fas fa-print"></i> Print Job Sheet
        </button>
        <button onclick="window.close()" style="background: #6c757d; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            <i class="fas fa-times"></i> Close
        </button>
    </div>

    <script>
        // Auto-print when page loads (optional)
        // window.onload = function() { window.print(); };
    </script>
</body>
</html>
