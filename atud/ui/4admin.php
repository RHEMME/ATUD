<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: /atud/auth/login.php");
    exit();
}

$medicines = [
    1 => ['name' => 'Paracetamol', 'price' => 5.00],
    2 => ['name' => 'Amoxicillin', 'price' => 12.50],
    3 => ['name' => 'Ibuprofen', 'price' => 8.00],
    4 => ['name' => 'Vitamin C', 'price' => 15.00],
    5 => ['name' => 'Cetirizine', 'price' => 7.50],
    6 => ['name' => 'Loperamide', 'price' => 4.50],
];

$total_orders = isset($_SESSION['history']) ? count($_SESSION['history']) : 0;
$total_revenue = 0;

if(isset($_SESSION['history'])) {
    foreach($_SESSION['history'] as $order) {
        // We cast to float to ensure number_format works
        $val = (isset($order['total']) && is_numeric($order['total'])) ? (float)$order['total'] : 0;
        $total_revenue += $val;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - PharmAPP</title>
    <style>
        .admin-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-left: 4px solid #4F46E5; }
        .stat-card h3 { margin: 0; color: #64748B; font-size: 14px; text-transform: uppercase; }
        .stat-card p { margin: 10px 0 0; font-size: 24px; font-weight: bold; color: #1E293B; }
        .admin-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .admin-table th, .admin-table td { padding: 15px; border-bottom: 1px solid #eee; text-align: left; font-size: 14px; }
        .status-pill { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: bold; background: #D1FAE5; color: #059669; }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="main-content">
        <h1 class="page-header">Admin Dashboard</h1>
        <div class="admin-stats">
            <div class="stat-card"><h3>Total Orders</h3><p><?php echo $total_orders; ?></p></div>
            <div class="stat-card" style="border-left-color: #10B981;"><h3>Total Revenue</h3><p>₱<?php echo number_format($total_revenue, 2); ?></p></div>
            <div class="stat-card" style="border-left-color: #F59E0B;"><h3>Items Listed</h3><p><?php echo count($medicines); ?></p></div>
        </div>
        <div class="content-card">
            <h3>Recent Customer Transactions</h3>
            <?php if(empty($_SESSION['history'])): ?>
                <p style="color: #94A3B8; padding: 20px 0;">No transactions yet.</p>
            <?php else: ?>
                <table class="admin-table">
                    <thead>
                        <tr><th>Order ID</th><th>Customer</th><th>Items</th><th>Payment</th><th>Total</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach(array_reverse($_SESSION['history']) as $order): ?>
                        <tr>
                            <td><small style="color: #4F46E5; font-weight: bold;"><?php echo $order['order_id'] ?? 'N/A'; ?></small></td>
                            <td><?php echo htmlspecialchars($order['customer'] ?? 'Unknown'); ?></td>
                            <td>
                                <?php 
                                $itemList = [];
                                if(!empty($order['items'])) {
                                    foreach($order['items'] as $id => $qty) {
                                        if(isset($medicines[$id])) $itemList[] = $medicines[$id]['name'] . " (x$qty)";
                                    }
                                }
                                echo implode(', ', $itemList);
                                ?>
                            </td>
                            <td><?php echo $order['payment'] ?? 'N/A'; ?></td>
                            <td><strong>₱<?php echo number_format((float)($order['total'] ?? 0), 2); ?></strong></td>
                            <td><span class="status-pill"><?php echo $order['status'] ?? 'Successful'; ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>