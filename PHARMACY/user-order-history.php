<?php
session_start();
include "config.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: user-login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
// Fetch user data for navbar
$userQuery = mysqli_query($conn, "SELECT username FROM users WHERE user_id = $user_id");
if (!$userQuery) {
    die("Error fetching user data: " . mysqli_error($conn));
}
$userData = mysqli_fetch_assoc($userQuery);
if (!$userData) {
    header("Location: user-login.php");
    exit();
}
// Fetch cart count for navbar
$cartQuery = mysqli_query($conn, "SELECT COUNT(*) as cart_count FROM cart WHERE user_id = " . (int)$user_id);
$cartCount = 0;
if ($cartQuery) {
    $cartData = mysqli_fetch_assoc($cartQuery);
    $cartCount = isset($cartData['cart_count']) ? $cartData['cart_count'] : 0;
}
// Fetch order history
$orderHistoryQuery = mysqli_query($conn, "
    SELECT * FROM order_history 
    WHERE user_id = $user_id 
    ORDER BY order_date DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order History | MediCare Pharmacy</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        :root {
            --primary: #1e88e5;
            --primary-dark: #1565c0;
            --secondary: #43a047;
            --secondary-dark: #2e7d32;
            --accent: #ff5722;
            --light-bg: #f5f9ff;
            --card-bg: rgba(255, 255, 255, 0.95);
            --text-color: #2c3e50;
            --text-light: #7f8c8d;
            --shadow: rgba(0, 0, 0, 0.08);
            --border: #e0e6ed;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Outfit', sans-serif;
            background: var(--light-bg);
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="%23f5f9ff"/><path d="M0,0 L100,100 M100,0 L0,100" stroke="%23e0e6ed" stroke-width="0.5"/></svg>');
            min-height: 100vh;
            color: var(--text-color);
        }
        
        .topnav {
            position: sticky;
            top: 0;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(15px);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 40px;
            box-shadow: 0 4px 20px var(--shadow);
            z-index: 100;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        
        .logo-icon {
            width: 40px;
            height: 40px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }
        
        .logo-text {
            font-size: 24px;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
            font-size: 15px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        .welcome-text {
            color: var(--text-light);
        }
        
        .logout-btn {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            border: none;
            cursor: pointer;
        }
        
        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(238, 90, 82, 0.3);
        }
        
        .cart-indicator {
            position: relative;
            margin-left: 10px;
        }
        
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--accent);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }
        
        .dashboard-header {
            text-align: center;
            margin: 40px 0;
        }
        
        .dashboard-title {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--primary);
        }
        
        .dashboard-subtitle {
            color: var(--text-light);
            font-size: 18px;
        }
        
        .dashboard-container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .card {
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: 0 10px 30px var(--shadow);
            text-align: center;
            text-decoration: none;
            color: var(--text-color);
            padding: 40px 30px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            border: 1px solid var(--border);
        }
        
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }
        
        .card-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, rgba(30, 136, 229, 0.1), rgba(67, 160, 71, 0.1));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            transition: all 0.3s ease;
        }
        
        .card:hover .card-icon {
            transform: scale(1.1);
            background: linear-gradient(135deg, rgba(30, 136, 229, 0.2), rgba(67, 160, 71, 0.2));
        }
        
        .card i {
            font-size: 36px;
            color: var(--primary);
        }
        
        .card h3 {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .card p {
            color: var(--text-light);
            font-size: 16px;
            margin-bottom: 20px;
        }
        
        .card-stats {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
        }
        
        .stat-label {
            font-size: 12px;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Order History Styles */
        .page-title {
            text-align: center;
            margin-bottom: 2rem;
            color: var(--primary);
            font-size: 2.2rem;
            font-weight: 700;
            position: relative;
            padding-bottom: 15px;
            letter-spacing: 0.5px;
        }
        
        .page-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 120px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 2px;
            box-shadow: 0 2px 10px rgba(0, 119, 182, 0.3);
        }
        
        .orders-container {
            display: grid;
            gap: 25px;
        }
        
        .order-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px var(--shadow);
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            border: 1px solid var(--border);
        }
        
        .order-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .order-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }
        
        .order-header {
            background: var(--light-bg);
            padding: 25px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            border-bottom: 1px solid var(--border);
        }
        
        .order-detail {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .order-detail i {
            color: var(--primary);
            font-size: 1.3rem;
            width: 30px;
            height: 30px;
            background: rgba(30, 136, 229, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
        
        .detail-content {
            display: flex;
            flex-direction: column;
        }
        
        .detail-label {
            font-size: 0.85rem;
            color: var(--text-light);
            margin-bottom: 3px;
        }
        
        .detail-value {
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .status-badge {
            padding: 8px 18px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-transform: capitalize;
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .status-badge i {
            font-size: 0.85rem;
        }
        
        .order-body {
            padding: 30px;
            position: relative;
        }
        
        .order-body::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--border), transparent);
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
            border-radius: 10px;
            overflow: hidden;
            background: white;
        }
        
        .items-table th, .items-table td {
            padding: 15px 18px;
            font-size: 0.95rem;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }
        
        .items-table th {
            background: var(--primary);
            color: white;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.85rem;
        }
        
        .items-table tr:last-child td {
            border-bottom: none;
        }
        
        .items-table tr:nth-child(even) {
            background: var(--light-bg);
        }
        
        .items-table tr:hover {
            background: rgba(30, 136, 229, 0.05);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 30px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px var(--shadow);
            position: relative;
            overflow: hidden;
            margin-top: 30px;
        }
        
        .empty-state::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(30, 136, 229, 0.05) 0%, transparent 70%);
            z-index: 0;
        }
        
        .empty-state i {
            font-size: 6rem;
            color: var(--primary);
            margin-bottom: 25px;
            position: relative;
            z-index: 1;
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
        
        .empty-state h3 {
            color: var(--text-color);
            margin-bottom: 20px;
            font-size: 1.8rem;
            position: relative;
            z-index: 1;
        }
        
        .empty-state p {
            color: var(--text-light);
            max-width: 600px;
            margin: 0 auto 30px;
            position: relative;
            z-index: 1;
            font-size: 1.1rem;
            line-height: 1.8;
        }
        
        .empty-state .btn {
            margin-top: 0;
            font-size: 1.1rem;
            padding: 12px 30px;
            font-weight: 600;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(30, 136, 229, 0.3);
        }
        
        .empty-state .btn:hover {
            box-shadow: 0 6px 20px rgba(30, 136, 229, 0.4);
            transform: translateY(-3px);
        }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        @media (max-width: 768px) {
            .topnav {
                flex-direction: column;
                align-items: flex-start;
                padding: 15px;
                gap: 15px;
            }
            
            .user-info {
                width: 100%;
                justify-content: space-between;
            }
            
            .dashboard-container {
                padding: 10px;
            }
            
            .card {
                padding: 30px 20px;
            }
            
            .card-icon {
                width: 60px;
                height: 60px;
            }
            
            .card i {
                font-size: 28px;
            }
            
            .card h3 {
                font-size: 18px;
            }
            
            .order-header {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .page-title {
                font-size: 1.8rem;
            }
            
            .order-body {
                padding: 20px;
            }
            
            .items-table {
                display: block;
                overflow-x: auto;
            }
        }
        
        @media (max-width: 480px) {
            .logo-text {
                font-size: 20px;
            }
            
            .dashboard-title {
                font-size: 28px;
            }
            
            .card-grid {
                grid-template-columns: 1fr;
            }
            
            .page-title {
                font-size: 1.5rem;
            }
            
            .order-body {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <div class="topnav">
        <a href="user-dashboard.php" class="logo">
            <div class="logo-icon">
                <i class="fas fa-heartbeat"></i>
            </div>
            <div class="logo-text">Medical Store</div>
        </a>
        <div class="user-info">
            <div class="welcome-text">Welcome, <?php echo isset($userData['username']) ? htmlspecialchars($userData['username']) : 'Guest'; ?></div>
            <div class="user-avatar"><?php echo isset($userData['username']) ? htmlspecialchars(substr($userData['username'], 0, 1)) : 'G'; ?></div>
            <div class="cart-indicator">
                <a href="user-cart.php">
                    <i class="fas fa-shopping-cart" style="font-size: 24px; color: var(--primary);"></i>
                    <div class="cart-badge">
                        <?php echo $cartCount; ?>
                    </div>
                </a>
            </div>
            <button class="logout-btn" onclick="window.location.href='user-logout.php'">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </button>
        </div>
    </div>
    
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1 class="dashboard-title">Order History</h1>
            <p class="dashboard-subtitle">Your past orders and purchase history</p>
        </div>
        
        <?php
        if (mysqli_num_rows($orderHistoryQuery) === 0) {
            echo '<div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <h3>No Order History Yet</h3>
                    <p>You haven\'t placed any orders yet. Start your health journey by placing your first order. Our team of pharmacists is ready to assist you with all your medication needs.</p>
                    <a href="user-dashboard.php" class="btn">
                        <i class="fas fa-shopping-cart"></i> Shop Now
                    </a>
                </div>';
        } else {
            echo '<div class="orders-container">';
            
            while ($order = mysqli_fetch_assoc($orderHistoryQuery)) {
                $orderId = $order['order_id'];
                echo '<div class="order-card" id="order-'.$order['order_id'].'">';
                
                echo '<div class="order-header">
                    <div class="order-detail">
                        <i class="fas fa-hashtag"></i>
                        <div class="detail-content">
                            <div class="detail-label">Order ID</div>
                            <strong>#'.$order['order_id'].'</strong>
                        </div>
                    </div>
                    <div class="order-detail">
                        <i class="fas fa-calendar-alt"></i>
                        <div class="detail-content">
                            <div class="detail-label">Date</div>
                            <strong>'.$order['order_date'].'</strong>
                        </div>
                    </div>
                    <div class="order-detail">
                        <i class="fas fa-rupee-sign"></i>
                        <div class="detail-content">
                            <div class="detail-label">Total Amount</div>
                            <strong>₹'.$order['total_amount'].'</strong>
                        </div>
                    </div>
                    <div class="order-detail">
                        <i class="fas fa-check-circle"></i>
                        <div class="detail-content">
                            <div class="detail-label">Status</div>
                            <span class="status-badge">Completed</span>
                        </div>
                    </div>
                </div>';
                
                $itemsQuery = mysqli_query($conn, "
                    SELECT oi.quantity, oi.unit_price, oi.total_price, m.MED_NAME 
                    FROM order_items_history oi
                    JOIN meds m ON oi.med_id = m.MED_ID
                    WHERE oi.order_id = $orderId
                ");
                
                echo '<div class="order-body">
                    <h3 class="section-title"><i class="fas fa-pills"></i> Order Items</h3>';
                
                if (!$itemsQuery) {
                    echo '<div class="note"><i class="fas fa-exclamation-triangle"></i> Error loading order items: '.mysqli_error($conn).'</div>';
                } else {
                    echo '<table class="items-table">
                            <thead>
                                <tr>
                                    <th>Medicine</th>
                                    <th>Quantity</th>
                                    <th>Unit Price (₹)</th>
                                    <th>Subtotal (₹)</th>
                                </tr>
                            </thead>
                            <tbody>';
                    
                    while ($item = mysqli_fetch_assoc($itemsQuery)) {
                        echo '<tr>
                                <td>'.$item['MED_NAME'].'</td>
                                <td>'.$item['quantity'].'</td>
                                <td>'.$item['unit_price'].'</td>
                                <td>'.$item['total_price'].'</td>
                              </tr>';
                    }
                    
                    echo '</tbody></table>';
                }
                echo '</div></div>';
            }
            
            echo '</div>';
        }
        ?>
    </div>
</body>
</html>