<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: /atud/auth/login.php");
    exit();
}

// Sample medicine data
$medicines = [
    ['id' => 1, 'name' => 'Paracetamol', 'price' => 5.00, 'desc' => '500mg - Pain reliever'],
    ['id' => 2, 'name' => 'Amoxicillin', 'price' => 12.50, 'desc' => '250mg - Antibiotic'],
    ['id' => 3, 'name' => 'Ibuprofen', 'price' => 8.00, 'desc' => '200mg - Anti-inflammatory'],
    ['id' => 4, 'name' => 'Vitamin C', 'price' => 15.00, 'desc' => '1000mg - Immunity Booster'],
    ['id' => 5, 'name' => 'Cetirizine', 'price' => 7.50, 'desc' => '10mg - Antihistamine'],
    ['id' => 6, 'name' => 'Loperamide', 'price' => 4.50, 'desc' => '2mg - Anti-diarrheal'],
];

// Count unique items in cart for the initial load
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pharmacy - PharmAPP</title>
    <style>
        /* Floating Card Styling */
        .medicine-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .med-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #f0f0f0;
            position: relative;
        }

        .med-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(125, 60, 255, 0.1);
        }

        .med-name { font-weight: bold; font-size: 18px; color: #333; margin-bottom: 5px; }
        .med-desc { font-size: 13px; color: #777; margin-bottom: 15px; }
        .med-price { font-size: 20px; color: #7D3CFF; font-weight: bold; margin-bottom: 15px; }

        /* Quantity Selector Styling */
        .quantity-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .qty-btn {
            background: #f1f5f9;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            color: #4F46E5;
        }

        .qty-input {
            width: 50px;
            text-align: center;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            padding: 5px;
            font-weight: bold;
        }

        .add-btn {
            background: #7D3CFF;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
            transition: 0.2s;
        }

        .add-btn:active { transform: scale(0.95); }

        /* Animation Element */
        .fly-item {
            position: fixed;
            z-index: 9999;
            font-size: 24px;
            transition: all 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            pointer-events: none;
        }

        /* Floating Cart Circle */
        .floating-cart {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: #7D3CFF;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 20px rgba(125, 60, 255, 0.3);
            z-index: 1000;
            color: white;
            font-size: 24px;
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.2s ease;
        }
        
        /* The Badge */
        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #FF3B30;
            color: white;
            font-size: 12px;
            font-weight: bold;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            display: <?php echo ($cart_count > 0) ? 'flex' : 'none'; ?>;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
        }

        .floating-cart:hover {
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>
    
    <div class="main-content">
        <h1 class="page-header">Available Medicines</h1>
        <div class="content-card">
            <p>Browse and order your medical supplies here.</p>
            
            <div class="medicine-grid">
                <?php foreach($medicines as $med): ?>
                <div class="med-card" id="med-<?php echo $med['id']; ?>">
                    <div class="med-name"><?php echo $med['name']; ?></div>
                    <div class="med-desc"><?php echo $med['desc']; ?></div>
                    <div class="med-price">₱<?php echo number_format($med['price'], 2); ?></div>
                    
                    <div class="quantity-wrapper">
                        <button class="qty-btn" onclick="changeQty(<?php echo $med['id']; ?>, -1)">-</button>
                        <input type="text" class="qty-input" id="qty-<?php echo $med['id']; ?>" value="1" readonly>
                        <button class="qty-btn" onclick="changeQty(<?php echo $med['id']; ?>, 1)">+</button>
                    </div>

                    <button class="add-btn" onclick="addToCart(event, <?php echo $med['id']; ?>)">Add to Cart</button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <a href="2mycart.php" class="floating-cart" id="main-cart">
        🛒
        <div class="cart-badge" id="cart-badge"><?php echo $cart_count; ?></div>
    </a>

    <script>
    // To track kinds of medicine locally for the badge
    let uniqueItems = new Set(<?php echo json_encode(isset($_SESSION['cart']) ? array_keys($_SESSION['cart']) : []); ?>);

    function updateBadge() {
    const badge = document.getElementById('cart-badge');
    const sidebarCart = document.getElementById('nav-cart'); // Get sidebar link
    const count = uniqueItems.size;
    
    // Update the floating badge
    if (count > 0) {
        badge.innerText = count;
        badge.style.display = 'flex';
    } else {
        badge.style.display = 'none';
    }

    // Update the Sidebar text
    if (sidebarCart) {
        sidebarCart.innerHTML = `🛒 My Cart (${count})`;
    }
}

    function changeQty(id, delta) {
        const input = document.getElementById('qty-' + id);
        let currentVal = parseInt(input.value);
        if (isNaN(currentVal)) currentVal = 1;
        
        let newVal = currentVal + delta;
        if (newVal < 1) newVal = 1; 
        
        input.value = newVal;
    }

    function addToCart(event, id) {
        const btn = event.target;
        const cart = document.getElementById('main-cart');
        const qty = document.getElementById('qty-' + id).value;

        const formData = new FormData();
        formData.append('id', id);
        formData.append('qty', qty);

        fetch('add_to_cart.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            // Add ID to our set and update the badge
            uniqueItems.add(id);
            updateBadge();
        });
        
        // VISUAL ANIMATION
        const flyer = document.createElement('div');
        flyer.className = 'fly-item';
        flyer.innerHTML = '💊';
        document.body.appendChild(flyer);

        const btnRect = btn.getBoundingClientRect();
        const cartRect = cart.getBoundingClientRect();

        flyer.style.left = btnRect.left + 'px';
        flyer.style.top = btnRect.top + 'px';

        setTimeout(() => {
            flyer.style.left = cartRect.left + 15 + 'px';
            flyer.style.top = cartRect.top + 15 + 'px';
            flyer.style.opacity = '0';
            flyer.style.transform = 'scale(0.5)';
        }, 10);

        setTimeout(() => {
            flyer.remove();
            cart.style.transform = 'scale(1.2)';
            setTimeout(() => cart.style.transform = 'scale(1)', 200);
        }, 800);
    }
    </script>
</body>
</html>