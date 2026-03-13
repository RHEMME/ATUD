<?php
session_start();
if(!isset($_SESSION['username']) || empty($_SESSION['cart'])){
    header("Location: 2mycart.php");
    exit();
}

// FIX: Move calculation logic to the top so it is ready for the form
$medicines = [
    1 => ['name' => 'Paracetamol', 'price' => 5.00],
    2 => ['name' => 'Amoxicillin', 'price' => 12.50],
    3 => ['name' => 'Ibuprofen', 'price' => 8.00],
    4 => ['name' => 'Vitamin C', 'price' => 15.00],
    5 => ['name' => 'Cetirizine', 'price' => 7.50],
    6 => ['name' => 'Loperamide', 'price' => 4.50],
];

$summary_total = 0;
foreach($_SESSION['cart'] as $id => $qty) {
    if(isset($medicines[$id])) {
        $summary_total += ($medicines[$id]['price'] * $qty);
    }
}

$names = ["Rhiel Ivan Atud", "James Sigua", "CL Cueva"];
$random_name = $names[array_rand($names)];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout - PharmAPP</title>
    <style>
        .checkout-container { display: grid; grid-template-columns: 1fr 350px; gap: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #64748B; font-weight: 500; }
        input, select, textarea { width: 100%; padding: 10px; border: 1px solid #E2E8F0; border-radius: 8px; box-sizing: border-box; }
        .payment-option { border: 2px solid #E2E8F0; padding: 15px; border-radius: 10px; cursor: pointer; margin-bottom: 10px; transition: 0.3s; }
        .payment-option.active { border-color: #4F46E5; background: #EEF2FF; }
        #gcash-qr-section { display: none; text-align: center; background: #f8fafc; padding: 20px; border-radius: 10px; margin-top: 10px; }
        .qr-placeholder { width: 150px; height: 150px; background: #fff; border: 2px solid #000; margin: 10px auto; display: flex; align-items: center; justify-content: center; font-weight: bold; }
        .confirm-btn { background: #4F46E5; color: white; border: none; width: 100%; padding: 15px; border-radius: 8px; font-weight: bold; cursor: pointer; margin-top: 20px; }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="main-content">
        <h1 class="page-header">Checkout</h1>
        <div class="checkout-container">
            <div class="content-card">
                <form action="cart_operations.php?action=complete_order" method="POST">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="fullname" required placeholder="Enter your full name">
                    </div>
                    <div class="form-group">
                        <label>Contact Number</label>
                        <input type="tel" name="contact" required placeholder="09xxxxxxxxx">
                    </div>
                    <div class="form-group">
                        <label>Email (Optional)</label>
                        <input type="email" name="email" placeholder="example@mail.com">
                    </div>
                    <div class="form-group">
                        <label>Complete Delivery Address</label>
                        <textarea name="address" required rows="3"></textarea>
                    </div>

                    <h3>Payment Method</h3>
                    <div class="payment-option" onclick="selectPayment('cod', this)">
                        <input type="radio" name="payment_method" value="C.O.D" id="cod" checked>
                        <label style="display:inline; cursor:pointer;" for="cod">💵 Cash on Delivery (C.O.D)</label>
                    </div>
                    <div class="payment-option" onclick="selectPayment('gcash', this)">
                        <input type="radio" name="payment_method" value="GCash" id="gcash">
                        <label style="display:inline; cursor:pointer;" for="gcash">📱 GCash</label>
                    </div>

                    <div id="gcash-qr-section">
                        <p><strong>Scan to Pay via GCash</strong></p>
                        <div class="qr-placeholder">QR CODE</div>
                        <p style="color: #4F46E5;">Recipient: <?php echo $random_name; ?></p>
                        <small>Please screenshot your receipt after payment.</small>
                    </div>

                    <button type="submit" class="confirm-btn">Confirm Purchase</button>
                    <input type="hidden" name="total_hidden" value="<?php echo $summary_total; ?>">
                </form>
            </div>

            <div class="content-card" style="height: fit-content;">
                <h3>Summary</h3>
                <p>Verify your items before confirming.</p>
                <hr style="border:0; border-top:1px solid #eee; margin-bottom: 15px;">

                <div style="margin-bottom: 20px;">
                    <?php
                    // Calculation is already done at the top, just display here
                    foreach($_SESSION['cart'] as $id => $qty):
                        if(isset($medicines[$id])):
                            $item = $medicines[$id];
                            $subtotal = $item['price'] * $qty;
                    ?>
                        <div style="display: flex; justify-content: space-between; font-size: 14px; margin-bottom: 8px;">
                            <span><?php echo $item['name']; ?> <strong>x<?php echo $qty; ?></strong></span>
                            <span>₱<?php echo number_format($subtotal, 2); ?></span>
                        </div>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </div>

                <hr style="border:0; border-top:1px solid #eee;">
                <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 18px; color: #4F46E5; margin-top: 10px;">
                    <span>Total:</span>
                    <span>₱<?php echo number_format($summary_total, 2); ?></span>
                </div>

                <div style="margin-top: 20px;">
                    <a href="2mycart.php" style="color: #64748B; text-decoration: none; font-size: 13px;">← Edit Cart</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function selectPayment(type, element) {
            document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('active'));
            element.classList.add('active');
            const qrSection = document.getElementById('gcash-qr-section');
            if(type === 'gcash') {
                qrSection.style.display = 'block';
                document.getElementById('gcash').checked = true;
            } else {
                qrSection.style.display = 'none';
                document.getElementById('cod').checked = true;
            }
        }
    </script>
</body>
</html>