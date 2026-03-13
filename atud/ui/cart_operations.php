<?php
session_start();

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id = isset($_GET['id']) ? $_GET['id'] : null;

    // 1. Handle Remove and Clear Cart
    if ($action == 'remove' && $id) {
        unset($_SESSION['cart'][$id]);
        header("Location: 2mycart.php");
        exit();
    } 
    elseif ($action == 'clear') {
        unset($_SESSION['cart']);
        header("Location: 2mycart.php");
        exit();
    }

    // 2. Handle Complete Order (Checkout)
    if ($action == 'complete_order') {
        // Safety check: Make sure cart isn't empty and data was posted
        if (!empty($_SESSION['cart']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // Prepare the order record
            $new_order = [
                'order_id' => 'ORD-' . strtoupper(uniqid()),
                'date'     => date('Y-m-d H:i:s'),
                'items'    => $_SESSION['cart'],
                'status'   => 'Successful',
                'customer' => $_POST['fullname'] ?? 'Guest',
                'payment'  => $_POST['payment_method'] ?? 'Not Specified',
                'total'    => $_POST['total_hidden'] ?? 0 
            ];
            
            // Add to history session
            if (!isset($_SESSION['history'])) {
                $_SESSION['history'] = [];
            }
            $_SESSION['history'][] = $new_order;
            
            // Clear the cart
            unset($_SESSION['cart']);

            // Show Success Message UI
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <title>Order Success</title>
                <style>
                    body { font-family: 'Segoe UI', sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; background: #F8FAFC; }
                    .success-card { background: white; padding: 40px; border-radius: 20px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.05); max-width: 400px; border-top: 5px solid #4F46E5; }
                    .icon { font-size: 60px; color: #4F46E5; margin-bottom: 20px; }
                    h2 { color: #1E293B; margin-bottom: 10px; }
                    p { color: #64748B; margin-bottom: 30px; line-height: 1.6; }
                    .btn { background: #4F46E5; color: white; text-decoration: none; padding: 12px 25px; border-radius: 8px; font-weight: bold; display: inline-block; transition: 0.3s; }
                    .btn:hover { background: #3730A3; transform: translateY(-2px); }
                </style>
            </head>
            <body>
                <div class="success-card">
                    <div class="icon">✅</div>
                    <h2>Order Successful!</h2>
                    <p>Your medical supplies are being prepared. <br> Order ID: <strong><?php echo $new_order['order_id']; ?></strong></p>
                    <a href="1pharmacy.php" class="btn">Go back to Pharmacy</a>
                </div>
            </body>
            </html>
            <?php
            exit(); // Essential to stop script here
        }
    }
}

// 3. Fallback: Only redirect if NO action was taken
header("Location: 2mycart.php");
exit();
?>