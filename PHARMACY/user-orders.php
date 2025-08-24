<?php
session_start();
include "config.php";
// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: user-login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
// Get user data safely
$userQuery = mysqli_query($conn, "SELECT username FROM users WHERE user_id = $user_id");
if (!$userQuery) {
    die("Database query failed: " . mysqli_error($conn));
}
$userData = mysqli_fetch_assoc($userQuery);
if (!$userData) {
    die("User data not found");
}
// Get orders safely - excluding cancelled orders
$orderQuery = mysqli_query($conn, "
    SELECT * FROM orders 
    WHERE user_id = $user_id AND status != 'cancelled'
    ORDER BY order_date DESC
");
if (!$orderQuery) {
    die("Orders query failed: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders - MediCare Pharmacy</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
        
        /* Exact Navbar Styles - Matching Dashboard */
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
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        /* Main Content Styles */
        .page-title {
            text-align: center;
            margin: 40px 0;
            font-size: 36px;
            font-weight: 700;
            color: var(--primary);
        }
        
        .dashboard-container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        /* Order Cards */
        .orders-container {
            display: grid;
            gap: 30px;
        }
        
        .order-card {
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: 0 10px 30px var(--shadow);
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            border: 1px solid var(--border);
        }
        
        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .order-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }
        
        .order-header {
            background: linear-gradient(135deg, rgba(30, 136, 229, 0.05), rgba(67, 160, 71, 0.05));
            padding: 25px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            border-bottom: 1px solid var(--border);
        }
        
        .order-detail {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: var(--transition);
        }
        
        .order-detail:hover {
            background: rgba(255, 255, 255, 0.9);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .order-detail i {
            color: var(--primary);
            font-size: 1.5rem;
            width: 30px;
            text-align: center;
        }
        
        .status-badge {
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-transform: capitalize;
        }
        
        .pending { 
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            color: #856404;
            border: 2px solid #f39c12;
        }
        
        .approved { 
            background: linear-gradient(135deg, #d4edda, #81c784);
            color: #155724;
            border: 2px solid #4caf50;
        }
        
        .rejected { 
            background: linear-gradient(135deg, #f8d7da, #e57373);
            color: #721c24;
            border: 2px solid #f44336;
        }
        
        .received { 
            background: linear-gradient(135deg, #d1ecf1, #64b5f6);
            color: #0c5460;
            border: 2px solid #2196f3;
        }
        
        .dispatched { 
            background: linear-gradient(135deg, #cce5ff, #90caf9);
            color: #004085;
            border: 2px solid #2196f3;
        }
        
        .order-body {
            padding: 30px;
        }
        
        .order-info {
            margin-bottom: 30px;
        }
        
        .info-section {
            margin-bottom: 25px;
            padding-bottom: 25px;
            border-bottom: 2px dashed var(--border);
        }
        
        .info-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .section-title {
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--primary);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .note {
            background: linear-gradient(135deg, rgba(30, 136, 229, 0.05), rgba(67, 160, 71, 0.05));
            padding: 20px;
            border-radius: 15px;
            margin: 20px 0;
            border-left: 5px solid var(--primary);
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }
        
        .note i {
            color: var(--primary);
            font-size: 1.4rem;
            margin-top: 3px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            border-radius: 15px;
            overflow: hidden;
            background: var(--card-bg);
        }
        
        .items-table th {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            text-align: left;
            padding: 20px;
            font-weight: 600;
            font-size: 1rem;
        }
        
        .items-table td {
            padding: 18px 20px;
            border-bottom: 1px solid var(--border);
        }
        
        .items-table tr:last-child td {
            border-bottom: none;
        }
        
        .items-table tr:nth-child(even) {
            background: rgba(30, 136, 229, 0.03);
        }
        
        .items-table tr:hover {
            background: rgba(30, 136, 229, 0.06);
        }
        
        .confirm-btn {
            background: linear-gradient(135deg, var(--secondary), var(--secondary-dark));
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            margin-top: 20px;
            box-shadow: 0 8px 25px rgba(67, 160, 71, 0.3);
        }
        
        .confirm-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 40px rgba(67, 160, 71, 0.4);
        }
        
        .cancel-btn {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            margin-top: 15px;
            box-shadow: 0 8px 25px rgba(238, 90, 82, 0.3);
        }
        
        .cancel-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 40px rgba(238, 90, 82, 0.4);
        }
        
        .cancel-prompt {
            font-size: 14px;
            color: var(--text-light);
            margin-top: 10px;
            font-style: italic;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .cancel-prompt i {
            color: var(--accent);
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 30px;
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: 0 10px 30px var(--shadow);
        }
        
        .empty-state i {
            font-size: 6rem;
            color: var(--primary);
            margin-bottom: 25px;
            opacity: 0.7;
        }
        
        .empty-state h3 {
            color: var(--primary);
            margin-bottom: 20px;
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .empty-state p {
            color: var(--text-light);
            max-width: 500px;
            margin: 0 auto 30px;
        }
        
        /* Notification Styles */
        .notification {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: var(--card-bg);
            border-radius: 15px;
            box-shadow: 0 10px 30px var(--shadow);
            padding: 20px 25px;
            display: flex;
            align-items: center;
            gap: 15px;
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 1000;
            max-width: 400px;
        }
        
        .notification.show {
            transform: translateY(0);
            opacity: 1;
        }
        
        .notification.success {
            border-left: 5px solid var(--secondary);
        }
        
        .notification.error {
            border-left: 5px solid #ff6b6b;
        }
        
        .notification i {
            font-size: 1.8rem;
        }
        
        .notification.success i {
            color: var(--secondary);
        }
        
        .notification.error i {
            color: #ff6b6b;
        }
        
        /* Responsive Design */
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
                flex-wrap: wrap;
            }
            
            .dashboard-container {
                padding: 10px;
            }
            
            .order-header {
                grid-template-columns: 1fr;
                gap: 15px;
                padding: 20px;
            }
            
            .items-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
            
            .page-title {
                font-size: 28px;
            }
            
            .order-body {
                padding: 20px;
            }
        }
        
        @media (max-width: 480px) {
            .logo-text {
                font-size: 20px;
            }
            
            .page-title {
                font-size: 24px;
            }
            
            .order-body {
                padding: 15px;
            }
            
            .note {
                flex-direction: column;
                gap: 10px;
            }
            
            .notification {
                bottom: 20px;
                right: 20px;
                left: 20px;
                max-width: none;
            }
        }
    </style>
</head>
<body>
    <!-- Exact Navigation - Matching Dashboard Style -->
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
                        <?php 
                            $cartQuery = mysqli_query($conn, "SELECT COUNT(*) as cart_count FROM cart WHERE user_id = " . (int)$user_id);
                            if ($cartQuery) {
                                $cartData = mysqli_fetch_assoc($cartQuery);
                                echo isset($cartData['cart_count']) ? $cartData['cart_count'] : '0';
                            } else {
                                echo '0';
                            }
                        ?>
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
        <h1 class="page-title">
            <i class="fas fa-clipboard-list"></i> My Orders
        </h1>
        
        <div class="orders-container">
        <?php
        if (mysqli_num_rows($orderQuery) === 0) {
            echo '<div class="empty-state">
                <i class="fas fa-box-open"></i>
                <h3>No Orders Found</h3>
                <p>You haven\'t placed any orders yet. Start your health journey by placing your first order.</p>
                <a href="user-dashboard.php" class="confirm-btn">
                    <i class="fas fa-shopping-cart"></i> Shop Now
                </a>
            </div>';
        } else {
            while ($order = mysqli_fetch_assoc($orderQuery)) {
                $status = $order['status'];
                $statusClass = 'pending';
                if ($status == 'approved') $statusClass = 'approved';
                elseif ($status == 'rejected') $statusClass = 'rejected';
                
                echo '<div class="order-card" id="order-'.$order['order_id'].'">';
                
                echo '<div class="order-header">
                    <div class="order-detail">
                        <i class="fas fa-receipt"></i>
                        <div>
                            <div>Order ID</div>
                            <strong>#'.$order['order_id'].'</strong>
                        </div>
                    </div>
                    <div class="order-detail">
                        <i class="fas fa-calendar-alt"></i>
                        <div>
                            <div>Date</div>
                            <strong>'.$order['order_date'].'</strong>
                        </div>
                    </div>
                    <div class="order-detail">
                        <i class="fas fa-rupee-sign"></i>
                        <div>
                            <div>Total Amount</div>
                            <strong>₹'.$order['total_amount'].'</strong>
                        </div>
                    </div>
                    <div class="order-detail">
                        <i class="fas fa-tag"></i>
                        <div>
                            <div>Status</div>
                            <span class="status-badge '.$statusClass.'">'.$status.'</span>
                        </div>
                    </div>
                </div>';
                
                echo '<div class="order-body">';
                
                // Order Information Section
                echo '<div class="order-info">
                    <h3 class="section-title"><i class="fas fa-info-circle"></i> Order Information</h3>';
                
                if ($status === 'pending') {
                    echo '<button class="cancel-btn" onclick="showCancelPrompt('.$order['order_id'].')">
                            <i class="fas fa-times-circle"></i> Cancel Order
                          </button>';
                    echo '<div class="cancel-prompt">
                            <i class="fas fa-info-circle"></i>
                            You can cancel this order until it is approved.
                          </div>';
                }
                
                if ($status === 'approved') {
                    if ($order['order_type'] === 'pickup') {
                        if (!empty($order['ready_for_pickup']) && $order['ready_for_pickup'] == 1) {
                            echo '<div class="note">
                                <i class="fas fa-check-circle"></i>
                                <div>
                                    <strong>Ready for Pickup:</strong> Your order is ready for collection from our store.
                                </div>
                            </div>';
                        } else {
                            echo '<div class="note">
                                <i class="fas fa-hourglass-half"></i>
                                <div>
                                    <strong>Preparing Order:</strong> Your order is being prepared and will be ready soon.
                                </div>
                            </div>';
                        }
                    } elseif ($order['order_type'] === 'delivery') {
                        echo '<div class="note">
                            <i class="fas fa-truck-medical"></i>
                            <div>
                                <strong>Delivery Status:</strong> Your order is on the way to your doorstep.
                            </div>
                        </div>';
                        
                        $deliveryLabel = strtolower(trim($order['delivery_status'] ?? ''));
                        $dsClass = 'pending';
                        if ($deliveryLabel === 'received') {
                            $dsClass = 'received';
                        } elseif ($deliveryLabel === 'dispatched') {
                            $dsClass = 'dispatched';
                        } elseif ($deliveryLabel === '') {
                            $deliveryLabel = 'pending';
                        }
                        
                        echo '<div class="order-detail">
                            <div>
                                <strong>Delivery Status:</strong> 
                                <span class="status-badge '.$dsClass.'" id="status-'.$order['order_id'].'">'.$deliveryLabel.'</span>
                            </div>
                        </div>';
                        
                        if (
                            in_array(strtolower($order['order_type']), ['delivery', 'online']) &&
                            $deliveryLabel === 'dispatched' &&
                            $order['order_closed'] == 0
                        ) {
                            echo '<button class="confirm-btn" onclick="confirmDelivery('.$order['order_id'].')" id="confirm-btn-'.$order['order_id'].'">
                                <i class="fas fa-check"></i> Confirm Delivery Received
                            </button>';
                        }
                    }
                }
                
                if ($status !== 'pending' && !empty($order['action_notes'])) {
                    echo '<div class="note">
                        <i class="fas fa-notes-medical"></i>
                        <div>
                            <strong>Pharmacist Note:</strong> '.htmlspecialchars($order['action_notes']).'
                        </div>
                    </div>';
                }
                
                // Prescription Preview Section
                echo '<div class="prescription-section" style="margin-top: 15px;">';
                if (!empty($order['prescription_image'])) {
                    echo '<strong>Prescription:</strong><br>
                          <a href="'.$order['prescription_image'].'" target="_blank">
                              <img src="'.$order['prescription_image'].'" alt="Prescription" style="width: 80px; height: auto; border-radius: 5px; border: 1px solid #ccc; box-shadow: 0 2px 6px rgba(0,0,0,0.1); margin-top: 5px;">
                          </a>';
                } else {
                    echo '<strong>Prescription:</strong> <span style="color: #999;">Not uploaded</span>';
                }
                echo '</div>';
                
                // Order Items Section
                echo '<h3 class="section-title"><i class="fas fa-pills"></i> Order Items</h3>';
                
                $orderId = $order['order_id'];
                $itemsQuery = mysqli_query($conn, "
                    SELECT oi.quantity, oi.unit_price, m.MED_NAME 
                    FROM order_items oi
                    JOIN meds m ON oi.med_id = m.MED_ID
                    WHERE oi.order_id = $orderId
                ");
                
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
                    
                    $total = 0;
                    while ($item = mysqli_fetch_assoc($itemsQuery)) {
                        $subtotal = $item['quantity'] * $item['unit_price'];
                        $total += $subtotal;
                        echo '<tr>
                            <td>'.$item['MED_NAME'].'</td>
                            <td>'.$item['quantity'].'</td>
                            <td>'.$item['unit_price'].'</td>
                            <td>'.$subtotal.'</td>
                        </tr>';
                    }
                    
                    echo '</tbody></table>';
                }
                echo '</div></div></div>';
            }
        }
        ?>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function showCancelPrompt(orderId) {
        // Create a custom modal for cancellation
        const modal = document.createElement('div');
        modal.className = 'modal fade show';
        modal.style.display = 'block';
        modal.style.backgroundColor = 'rgba(0,0,0,0.5)';
        modal.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cancel Order</h5>
                        <button type="button" class="btn-close" onclick="closeModal()"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to cancel this order?</p>
                        <div class="mb-3">
                            <label for="cancelReason" class="form-label">Reason for cancellation:</label>
                            <textarea class="form-control" id="cancelReason" rows="3" placeholder="Please enter the reason for cancelling this order..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeModal()">Close</button>
                        <button type="button" class="btn btn-danger" onclick="cancelOrder(${orderId})">Confirm Cancellation</button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Store the modal in a global variable so we can close it later
        window.currentModal = modal;
    }
    
    function closeModal() {
        if (window.currentModal) {
            window.currentModal.remove();
            window.currentModal = null;
        }
    }
    
    function cancelOrder(orderId) {
        const reason = document.getElementById('cancelReason').value.trim();
        
        if (!reason) {
            alert('Please provide a reason for cancellation.');
            return;
        }
        
        closeModal();
        
        fetch('user-cancel-order.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `order_id=${orderId}&reason=${encodeURIComponent(reason)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification("Order cancelled successfully.", "success");
                setTimeout(() => location.reload(), 1500);
            } else {
                showNotification("Error: " + (data.error || "Could not cancel order."), "error");
            }
        })
        .catch(() => {
            showNotification("Network error while cancelling order.", "error");
        });
    }
    
    function confirmDelivery(orderId) {
        fetch('user-mark-delivery-received.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `order_id=${orderId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`confirm-btn-${orderId}`).style.display = 'none';
                const statusSpan = document.getElementById(`status-${orderId}`);
                statusSpan.textContent = 'received';
                statusSpan.classList.remove('pending', 'dispatched');
                statusSpan.classList.add('received');
                
                // Show success notification
                showNotification('Delivery confirmed successfully!', 'success');
            } else {
                showNotification("Error: " + (data.error || "Failed to confirm delivery."), 'error');
            }
        })
        .catch(() => showNotification("Network error while confirming delivery.", 'error'));
    }
    
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('show');
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }, 10);
    }
    
    // Auto-refresh for pending orders
    setInterval(() => {
        const pendingStatuses = document.querySelectorAll('.status-badge.pending, .status-badge.dispatched');
        if (pendingStatuses.length > 0) {
            location.reload();
        }
    }, 10000);
    </script>
</body>
</html>