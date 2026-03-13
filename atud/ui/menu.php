<?php
// Count unique items in cart for the sidebar
$sidebar_cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>
<style>
    :root {
        --primary-purple: #7D3CFF;
        --bg-light: #F8FAFC;
        --sidebar-width: 240px;
    }

    body {
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: var(--bg-light);
        display: flex;
    }

    .sidebar {
        width: var(--sidebar-width);
        height: 100vh;
        background: white;
        padding: 20px;
        border-right: 1px solid #E2E8F0;
        position: fixed;
    }

    .logo {
        color: #4F46E5;
        font-weight: bold;
        font-size: 24px;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 40px;
        text-decoration: none;
    }

    .nav-item {
        padding: 12px 15px;
        margin-bottom: 5px;
        border-radius: 8px;
        color: #64748B;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: 0.3s;
    }

    .nav-item:hover {
        background: #F1F5F9;
    }

    .nav-item.active {
        background: #EEF2FF;
        color: #4F46E5;
        font-weight: 500;
    }

    .main-content {
        margin-left: var(--sidebar-width);
        flex-grow: 1;
        padding: 40px;
    }

    .page-header {
        margin-bottom: 20px;
        color: #1E293B;
    }

    .content-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
</style>

<div class="sidebar">
    <a href="index.php" class="logo">💊 PharmAPP</a>
    <a href="0index.php" class="nav-item">🏠 Dashboard</a>
    <a href="1pharmacy.php" class="nav-item">🗳️ Pharmacy</a>
    
    <a href="2mycart.php" class="nav-item" id="nav-cart">
        🛒 My Cart (<?php echo $sidebar_cart_count; ?>)
    </a>

    <a href="3history.php" class="nav-item">📜 History</a>
    <a href="4admin.php" class="nav-item">⚙️ Admin</a>
    <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
    <a href="/atud/auth/logout.php" class="nav-item" style="color: #ef4444;">🚪 Logout</a>
</div>