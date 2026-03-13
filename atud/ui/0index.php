<?php
session_start();
require_once __DIR__ . "/../config/db_connect.php";

$message = "";

// FIX: Define the variables used in the dashboard cards
$available_meds = 20; 
$total_deliveries = 0;
$service_rating = 4.9;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashed_password);
    if ($stmt->execute()) {
        $message = "Signup successful! You can now login.";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmAPP Dashboard</title>
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

        /* Main Content Styling */
        .main-content {
            margin-left: var(--sidebar-width);
            flex-grow: 1;
            padding: 40px;
        }

        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .status-badge {
            background: #DCFCE7;
            color: #166534;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border-bottom: 4px solid transparent;
        }

        .card.purple { border-color: #7D3CFF; }
        .card.green { border-color: #10B981; }
        .card.orange { border-color: #F59E0B; }

        .card-icon { font-size: 24px; margin-bottom: 10px; }
        .card-value { font-size: 28px; font-weight: bold; color: #1E293B; }
        .card-label { color: #94A3B8; font-size: 14px; }

        /* Banner */
        .promo-banner {
            background: linear-gradient(135deg, #6366F1 0%, #A855F7 100%);
            color: white;
            padding: 40px;
            border-radius: 20px;
            position: relative;
        }

        .promo-banner h2 { margin: 0 0 10px 0; font-size: 24px; }
        .promo-banner p { margin: 0 0 25px 0; opacity: 0.9; }

        .shop-btn {
            background: white;
            color: #4F46E5;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
        }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="main-content">
        <div class="header-row">
            <h1>Health Overview</h1>
            <div class="status-badge">Online Status: Active</div>
        </div>

        <div class="stats-container">
            <div class="card purple">
                <div class="card-icon">📦</div>
                <div class="card-value"><?php echo $available_meds; ?></div>
                <div class="card-label">Available Meds</div>
            </div>

            <div class="card green">
                <div class="card-icon">🚚</div>
                <div class="card-value"><?php echo $total_deliveries; ?></div>
                <div class="card-label">Total Deliveries</div>
            </div>

            <div class="card orange">
                <div class="card-icon">⭐</div>
                <div class="card-value"><?php echo $service_rating; ?></div>
                <div class="card-label">Service Rating</div>
            </div>
        </div>

        <div class="promo-banner">
            <h2>Need your medicine fast?</h2>
            <p>We offer same-day delivery within Metro Manila for orders before 5:00 PM.</p>
            <a href="1pharmacy.php" class="shop-btn">Shop Now</a>
        </div>
    </div>

</body>
</html>