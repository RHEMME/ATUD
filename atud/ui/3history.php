<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: /atud/auth/login.php");
    exit();
}

// Medicine reference for names and prices
$medicines = [
    1 => ['name' => 'Paracetamol', 'price' => 5.00],
    2 => ['name' => 'Amoxicillin', 'price' => 12.50],
    3 => ['name' => 'Ibuprofen', 'price' => 8.00],
    4 => ['name' => 'Vitamin C', 'price' => 15.00],
    5 => ['name' => 'Cetirizine', 'price' => 7.50],
    6 => ['name' => 'Loperamide', 'price' => 4.50],
];
?>
<!DOCTYPE html>
<html>
<head>
    <title>History - PharmAPP</title>
    <style>
        .history-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .history-table th, .history-table td { padding: 15px; border-bottom: 1px solid #eee; text-align: left; }
        
        /* Status Badge Styling */
        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: capitalize;
        }
        .status-successful { background: #D1FAE5; color: #059669; }
        .status-pending { background: #FEF3C7; color: #92400E; }
        .status-cancelled { background: #FEE2E2; color: #B91C1C; }
        
        .order-items { font-size: 13px; color: #64748B; }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>
    
    <div class="main-content">
        <h1 class="page-header">Transaction History</h1>
        <div class="content-card">
            <p>View your past orders and deliveries.</p>

            <?php if(!isset($_SESSION['history']) || empty($_SESSION['history'])): ?>
                <div style="text-align: center; padding: 40px; color: #94A3B8;">
                    <span style="font-size: 48px;">📄</span>
                    <p>No transactions found yet.</p>
                </div>
            <?php else: ?>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Items Purchased</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // Show newest orders first
                        foreach(array_reverse($_SESSION['history']) as $order): 
                            $order_total = 0;
                            $items_list = [];
                            
                            foreach($order['items'] as $id => $qty) {
                                if(isset($medicines[$id])) {
                                    $items_list[] = $medicines[$id]['name'] . " (x$qty)";
                                    $order_total += ($medicines[$id]['price'] * $qty);
                                }
                            }
                            
                            // Determine CSS class based on status
                            $status = strtolower($order['status']); // e.g., 'Successful'
                            $status_class = "status-" . $status;
                        ?>
                        <tr>
                            <td>
                                <strong><?php echo date('M d, Y', strtotime($order['date'])); ?></strong><br>
                                <small style="color: #94A3B8;"><?php echo date('h:i A', strtotime($order['date'])); ?></small>
                            </td>
                            <td class="order-items">
                                <?php echo implode(', ', $items_list); ?>
                            </td>
                            <td><strong>₱<?php echo number_format($order_total, 2); ?></strong></td>
                            <td>
                                <span class="badge <?php echo $status_class; ?>">
                                    <?php echo $order['status']; ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>