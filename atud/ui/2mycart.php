<?php
session_start();

if(!isset($_SESSION['username'])){
    header("Location: /atud/auth/login.php");
    exit();
}

// Medicine data (Ensure this matches the array in 1pharmacy.php)
$medicines = [
    1 => ['name' => 'Paracetamol', 'price' => 5.00],
    2 => ['name' => 'Amoxicillin', 'price' => 12.50],
    3 => ['name' => 'Ibuprofen', 'price' => 8.00],
    4 => ['name' => 'Vitamin C', 'price' => 15.00],
    5 => ['name' => 'Cetirizine', 'price' => 7.50],
    6 => ['name' => 'Loperamide', 'price' => 4.50],
];

$total_price = 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Cart - PharmAPP</title>
    <style>
        .cart-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .cart-table th, .cart-table td { padding: 15px; border-bottom: 1px solid #eee; text-align: left; }
        .total-section { margin-top: 20px; text-align: right; font-size: 20px; font-weight: bold; color: #7D3CFF; }
        .checkout-btn { background: #7D3CFF; color: white; border: none; padding: 12px 25px; border-radius: 8px; cursor: pointer; float: right; margin-top: 10px; font-weight: bold; }
        
        /* New Styles for Actions */
        .remove-btn { color: #ff4d4d; text-decoration: none; font-weight: bold; font-size: 14px; }
        .remove-btn:hover { text-decoration: underline; }
        .clear-btn { background: #f1f5f9; color: #64748b; border: none; padding: 12px 20px; border-radius: 8px; cursor: pointer; float: left; margin-top: 10px; text-decoration: none; font-size: 14px; }
        .clear-btn:hover { background: #e2e8f0; }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>
    
    <div class="main-content">
        <h1 class="page-header">Shopping Cart</h1>
        <div class="content-card">
            <?php if(!isset($_SESSION['cart']) || empty($_SESSION['cart'])): ?>
                <p>Your selected items will appear here.</p>
                <a href="1pharmacy.php" style="color: #7D3CFF; text-decoration: none;">Go back to Pharmacy</a>
            <?php else: ?>
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($_SESSION['cart'] as $id => $qty): 
                            if(!isset($medicines[$id])) continue; // Skip if ID doesn't exist
                            $item = $medicines[$id];
                            $subtotal = $item['price'] * $qty;
                            $total_price += $subtotal;
                        ?>
                        <tr>
                            <td><?php echo $item['name']; ?></td>
                            <td><?php echo $qty; ?></td>
                            <td>₱<?php echo number_format($item['price'], 2); ?></td>
                            <td>₱<?php echo number_format($subtotal, 2); ?></td>
                            <td>
                                <a href="cart_operations.php?action=remove&id=<?php echo $id; ?>" class="remove-btn">Remove</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
               </table>
                
                <div class="total-section">Total: ₱<?php echo number_format($total_price, 2); ?></div>
                
                <a href="cart_operations.php?action=clear" class="clear-btn" onclick="return confirm('Empty your cart?')">Clear Cart</a>
                
                <a href="checkout.php" class="checkout-btn" style="text-decoration:none; text-align:center;">Proceed to Checkout</a>
                
                <div style="clear:both;"></div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>