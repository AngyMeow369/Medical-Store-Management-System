<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: pharmLogin.php");
    exit();
}
include "config.php";
// Handle AJAX POSTs
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $response = ['success' => false];
    if (isset($_POST['dispatch_order_id'])) {
        $order_id = intval($_POST['dispatch_order_id']);
        $result = mysqli_query($conn, "
            UPDATE orders 
            SET delivery_status = 'dispatched', dispatched = 1 
            WHERE order_id = $order_id
        ");
        $response['success'] = $result;
        echo json_encode($response);
        exit();
    }
    if (isset($_POST['close_order_id'])) {
        $order_id = intval($_POST['close_order_id']);
        $order = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM orders WHERE order_id = $order_id"));
        if ($order && strtolower(trim($order['delivery_status'] ?? '')) === 'received') {
            mysqli_query($conn, "
                INSERT INTO order_history 
                (order_id, user_id, order_date, status, total_amount, pharmacist_id, action_date, action_notes, latitude, longitude, payment_method, payment_status, payment_id, email_sent, order_type, ready_for_pickup, delivery_status, order_closed, dispatched)
                VALUES (
                    '{$order['order_id']}', '{$order['user_id']}', '{$order['order_date']}', '{$order['status']}', '{$order['total_amount']}', 
                    '{$order['pharmacist_id']}', '{$order['action_date']}', '{$order['action_notes']}', '{$order['latitude']}', '{$order['longitude']}', 
                    '{$order['payment_method']}', '{$order['payment_status']}', '{$order['payment_id']}', '{$order['email_sent']}', 
                    '{$order['order_type']}', '{$order['ready_for_pickup']}', '{$order['delivery_status']}', 1, '{$order['dispatched']}'
                )
            ");
            $items = mysqli_query($conn, "SELECT * FROM order_items WHERE order_id = $order_id");
            while ($item = mysqli_fetch_assoc($items)) {
                mysqli_query($conn, "
                    INSERT INTO order_items_history (order_id, med_id, quantity, unit_price, total_price)
                    VALUES (
                        '{$item['order_id']}', '{$item['med_id']}', '{$item['quantity']}', '{$item['unit_price']}', '{$item['total_price']}'
                    )
                ");
            }
            mysqli_query($conn, "DELETE FROM order_items WHERE order_id = $order_id");
            mysqli_query($conn, "DELETE FROM orders WHERE order_id = $order_id");
            $response['success'] = true;
        }
        echo json_encode($response);
        exit();
    }
}
// Get orders for display
$orders = mysqli_query($conn, "
    SELECT o.*, u.username, u.phone 
    FROM orders o 
    JOIN users u ON o.user_id = u.user_id 
    WHERE 
    o.status = 'approved' 
    AND (o.order_type = 'delivery' OR o.order_type = 'online')
    AND o.order_closed = 0
    ORDER BY o.order_date DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="refresh" content="3">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dispatch & Close Orders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #008080;
            --primary-light: #4ecdc4;
            --secondary: #006b9b;
            --accent: #ff6b6b;
            --success: #2ecc71;
            --warning: #f39c12;
            --danger: #e74c3c;
            --light: #f8f9fa;
            --dark: #2c3e50;
            --gray: #6c757d;
            --light-gray: #e9ecef;
            --card-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            --sidebar-width: 280px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa, #e4edf5);
            color: var(--dark);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }
        
        /* Navbar */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            color: var(--dark);
            padding: 0 30px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 1000;
        }
        
        .menu-toggle {
            background: none;
            border: none;
            color: var(--primary);
            font-size: 24px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .logo {
            font-size: 22px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--primary);
        }
        
        .logo i {
            font-size: 28px;
        }
        
        .user-section {
            display: flex;
            align-items: center;
            gap: 25px;
        }
        
        .notification {
            position: relative;
            cursor: pointer;
        }
        
        .notification i {
            font-size: 22px;
            color: var(--primary);
        }
        
        .badge {
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
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        
        .logout-btn {
            background: linear-gradient(135deg, #ff6b6b, #e74c3c);
            color: white;
            border: none;
            border-radius: 20px;
            padding: 8px 16px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.2);
        }
        
        .logout-btn:hover {
            background: linear-gradient(135deg, #e74c3c, #ff6b6b);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(231, 76, 60, 0.3);
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: -280px; /* Default closed position */
            width: 280px;
            height: 100%;
            background: white;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.08);
            transition: left 0.3s ease;
            z-index: 999;
            padding-top: 80px;
        }
        
        .sidebar.active {
            left: 0; /* Open position */
        }
        
        .sidebar-header {
            padding: 0 25px 20px;
            border-bottom: 1px solid var(--light-gray);
        }
        
        .sidebar-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--primary);
        }
        
        .nav-item {
            padding: 15px 25px;
            display: flex;
            align-items: center;
            gap: 15px;
            color: var(--gray);
            text-decoration: none;
            transition: all 0.3s;
            font-weight: 500;
        }
        
        .nav-item:hover, .nav-item.active {
            background: rgba(0, 128, 128, 0.08);
            color: var(--primary);
            border-left: 4px solid var(--primary);
        }
        
        .nav-item i {
            width: 24px;
            text-align: center;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 0;
            padding: 100px 30px 30px;
            transition: margin-left 0.3s ease;
        }
        
        .main-content.sidebar-active {
            margin-left: 280px; /* When sidebar is open */
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .back-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: transform 0.2s ease;
        }
        
        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .order-card {
            background: white;
            border: none;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.12);
        }
        
        .order-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, var(--primary), var(--primary-light));
        }
        
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .order-id {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark);
        }
        
        .order-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 15px;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #666;
            font-size: 14px;
        }
        
        .meta-item i {
            color: var(--primary);
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            text-transform: capitalize;
        }
        
        .status-badge.pending { 
            background-color: #fff3cd; 
            color: #856404; 
            border: 1px solid #ffeeba;
        }
        .status-badge.approved { 
            background-color: #d4edda; 
            color: #155724; 
            border: 1px solid #c3e6cb;
        }
        .status-badge.rejected { 
            background-color: #f8d7da; 
            color: #721c24; 
            border: 1px solid #f5c6cb;
        }
        
        .note {
            background: #f8f9fa;
            padding: 15px;
            margin-top: 15px;
            border-left: 4px solid #007bff;
            border-radius: 6px;
        }
        
        .note-title {
            font-weight: 600;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .note-title i {
            color: #007bff;
        }
        
        a.map-link {
            text-decoration: none;
            color: #007bff;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: color 0.3s ease;
        }
        
        a.map-link:hover {
            color: #0056b3;
            text-decoration: underline;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        .dispatch-btn {
            background: linear-gradient(135deg, #008080, #4ecdc4);
            color: white;
            border: none;
            border-radius: 30px;
            padding: 10px 20px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(0, 128, 128, 0.2);
        }
        
        .dispatch-btn:hover {
            background: linear-gradient(135deg, #4ecdc4, #008080);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 128, 128, 0.3);
        }
        
        .dispatch-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        .close-btn {
            background: linear-gradient(135deg, #007bff, #4da3ff);
            color: white;
            border: none;
            border-radius: 30px;
            padding: 10px 20px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2);
        }
        
        .close-btn:hover {
            background: linear-gradient(135deg, #4da3ff, #007bff);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 123, 255, 0.3);
        }
        
        .close-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
        }
        
        .empty-state i {
            font-size: 60px;
            color: #ccc;
            margin-bottom: 20px;
        }
        
        .empty-state h3 {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }
        
        .empty-state p {
            color: #666;
            font-size: 16px;
        }
        
        /* Animations */
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .order-card {
            animation: fadeIn 0.5s ease forwards;
            opacity: 0;
        }
        
        .order-card:nth-child(1) { animation-delay: 0.1s; }
        .order-card:nth-child(2) { animation-delay: 0.2s; }
        .order-card:nth-child(3) { animation-delay: 0.3s; }
        .order-card:nth-child(4) { animation-delay: 0.4s; }
        
        /* Overlay */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.05);
            z-index: 998;
            display: none;
        }
        
        .overlay.active {
            display: block;
        }
        
        /* Logout Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1001;
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 400px;
            max-width: 90%;
            padding: 30px;
            text-align: center;
            animation: modalFadeIn 0.3s ease-out;
        }
        
        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .modal-header {
            margin-bottom: 20px;
        }
        
        .modal-icon {
            font-size: 48px;
            color: var(--danger);
            margin-bottom: 15px;
        }
        
        .modal-title {
            font-size: 24px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 10px;
        }
        
        .modal-message {
            color: var(--gray);
            font-size: 16px;
            margin-bottom: 25px;
        }
        
        .modal-actions {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        
        .modal-btn {
            padding: 10px 20px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .cancel-btn {
            background-color: var(--light-gray);
            color: var(--dark);
            border: none;
        }
        
        .cancel-btn:hover {
            background-color: #d1d5db;
        }
        
        .confirm-btn {
            background: linear-gradient(135deg, #ff6b6b, #e74c3c);
            color: white;
            border: none;
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.2);
        }
        
        .confirm-btn:hover {
            background: linear-gradient(135deg, #e74c3c, #ff6b6b);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(231, 76, 60, 0.3);
        }
        
        /* Toast notification */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1002;
        }
        
        .toast {
            background: white;
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideIn 0.3s ease forwards;
            max-width: 300px;
        }
        
        .toast.success {
            border-left: 4px solid var(--success);
        }
        
        .toast.error {
            border-left: 4px solid var(--danger);
        }
        
        .toast-icon {
            font-size: 20px;
        }
        
        .toast.success .toast-icon {
            color: var(--success);
        }
        
        .toast.error .toast-icon {
            color: var(--danger);
        }
        
        .toast-message {
            font-size: 14px;
            font-weight: 500;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="menu-toggle" id="menuToggle">
            <i class="fas fa-bars"></i>
            <div class="logo">
                <i class="fas fa-pills"></i>
                <span>MediPharm</span>
            </div>
        </div>
        <div class="user-section">
            <div class="notification">
                <i class="fas fa-bell"></i>
                <div class="badge" id="orderCount">0</div>
            </div>
            <div class="user-info">
                <div class="user-avatar">JD</div>
                <span id="userName">Pharmacist</span>
            </div>
            <button class="logout-btn" id="logoutBtn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </div>
    </nav>
    
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-title">Dashboard</div>
        </div>
        <a href="pharmmainpage.php" class="nav-item">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
        <a href="pharm-inventory.php" class="nav-item">
            <i class="fas fa-prescription-bottle-alt"></i>
            <span>Inventory</span>
        </a>
        <a href="pharmacist-order.php" class="nav-item">
            <i class="fas fa-clipboard-list"></i>
            <span>Orders</span>
        </a>
        <a href="dispatch-order.php" class="nav-item active">
            <i class="fas fa-truck"></i>
            <span>Dispatch Orders</span>
        </a>
        <a href="pharmacist-order-history.php" class="nav-item">
            <i class="fas fa-history"></i>
            <span>Order History</span>
        </a>
    </div>
    
    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>
    
    <!-- Logout Modal -->
    <div class="modal" id="logoutModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon">
                    <i class="fas fa-sign-out-alt"></i>
                </div>
                <h2 class="modal-title">Confirm Logout</h2>
                <p class="modal-message">Are you sure you want to logout from your account?</p>
            </div>
            <div class="modal-actions">
                <button class="modal-btn cancel-btn" id="cancelLogout">Cancel</button>
                <button class="modal-btn confirm-btn" id="confirmLogout">Logout</button>
            </div>
        </div>
    </div>
    
    <!-- Toast Notifications Container -->
    <div class="toast-container" id="toastContainer"></div>
    
    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <div class="page-header">
            <div class="page-title">
                <i class="fas fa-truck"></i>
                Dispatch & Close Orders
            </div>
            <a href="pharmmainpage.php" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Back to Dashboard
            </a>
        </div>
        
        <?php
        if (mysqli_num_rows($orders) === 0) {
            echo '<div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>No Delivery Orders Available</h3>
                <p>There are no delivery orders awaiting dispatch or closure.</p>
            </div>';
        } else {
            while ($order = mysqli_fetch_assoc($orders)) {
                $order_id = intval($order['order_id']);
                $username = htmlspecialchars($order['username']);
                $delivery_status = strtolower(trim($order['delivery_status'] ?? ''));
                $dispatched = intval($order['dispatched']);
                $payment_status = $order['payment_status'] === 'paid' ? '✅ Paid' : '❌ Unpaid';
                
                echo "<div class='order-card'>";
                echo "<div class='order-header'>";
                echo "<div class='order-id'>Order #{$order_id}</div>";
                echo "<div class='status-badge approved'>Approved</div>";
                echo "</div>";
                
                echo "<div class='order-meta'>";
                echo "<div class='meta-item'><i class='far fa-user'></i> Customer: {$username}</div>";
                echo "<div class='meta-item'><i class='far fa-calendar-alt'></i> Date: {$order['order_date']}</div>";
                echo "<div class='meta-item'><i class='fas fa-money-bill-wave'></i> Total: ₹{$order['total_amount']}</div>";
                echo "<div class='meta-item'><i class='fas fa-credit-card'></i> Payment: {$order['payment_method']} - {$payment_status}</div>";
                echo "</div>";
                
                if (!empty($order['latitude']) && !empty($order['longitude'])) {
                    echo "<div class='meta-item' style='margin-top: 10px;'>
                        <i class='fas fa-map-marker-alt'></i> 
                        <a class='map-link' href='https://www.google.com/maps?q={$order['latitude']},{$order['longitude']}' target='_blank'>
                            <i class='fas fa-external-link-alt'></i> View on Map
                        </a>
                    </div>";
                }
                
                echo "<div class='action-buttons'>";
                // Show dispatch button if not yet dispatched
                if ($dispatched === 0) {
                    echo "<button class='dispatch-btn' onclick='dispatchOrder($order_id)'>
                        <i class='fas fa-truck'></i> Mark as Dispatched
                    </button>";
                } else {
                    echo "<button class='dispatch-btn' disabled>
                        <i class='fas fa-check-circle'></i> Already Dispatched
                    </button>";
                }
                
                // Show close button if marked received
                if ($delivery_status === 'received') {
                    echo "<button class='close-btn' onclick='closeOrder($order_id)'>
                        <i class='fas fa-check-circle'></i> Close Order
                    </button>";
                } else {
                    echo "<button class='close-btn' disabled>
                        <i class='fas fa-lock'></i> Waiting for Delivery
                    </button>";
                }
                echo "</div>";
                echo "</div>";
            }
        }
        ?>
    </div>
    
    <script>
        // DOM Elements
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const overlay = document.getElementById('overlay');
        const logoutBtn = document.getElementById('logoutBtn');
        const logoutModal = document.getElementById('logoutModal');
        const cancelLogout = document.getElementById('cancelLogout');
        const confirmLogout = document.getElementById('confirmLogout');
        const toastContainer = document.getElementById('toastContainer');
        
        // Track sidebar state
        let sidebarOpen = false;
        
        // Toggle sidebar function
        function toggleSidebar() {
            if (sidebarOpen) {
                // Close sidebar
                sidebar.style.left = '-280px';
                overlay.style.display = 'none';
                mainContent.style.marginLeft = '0';
            } else {
                // Open sidebar
                sidebar.style.left = '0';
                overlay.style.display = 'block';
                mainContent.style.marginLeft = '280px';
            }
            
            // Toggle state
            sidebarOpen = !sidebarOpen;
        }
        
        // Close sidebar function
        function closeSidebar() {
            sidebar.style.left = '-280px';
            overlay.style.display = 'none';
            mainContent.style.marginLeft = '0';
            sidebarOpen = false;
        }
        
        // Event Listeners
        menuToggle.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', closeSidebar);
        
        // Logout functionality with stylish modal
        logoutBtn.addEventListener('click', function() {
            logoutModal.style.display = 'flex';
        });
        
        cancelLogout.addEventListener('click', function() {
            logoutModal.style.display = 'none';
        });
        
        confirmLogout.addEventListener('click', function() {
            window.location.href = 'PharmLogout.php';
        });
        
        // Close modal when clicking outside
        logoutModal.addEventListener('click', function(e) {
            if (e.target === logoutModal) {
                logoutModal.style.display = 'none';
            }
        });
        
        // Order notification functionality
        function updateOrderCount() {
            fetch('check-new-orders.php')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('orderCount');
                    if (data.count > 0) {
                        badge.textContent = data.count;
                        badge.style.display = 'flex';
                    } else {
                        badge.style.display = 'none';
                    }
                })
                .catch(error => console.error('Error fetching order count:', error));
        }
        
        // Update order count every 2 seconds
        setInterval(updateOrderCount, 2000);
        
        // Initialize order count on page load
        document.addEventListener('DOMContentLoaded', updateOrderCount);
        
        // Show toast notification
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            
            const icon = type === 'success' 
                ? '<i class="fas fa-check-circle toast-icon"></i>' 
                : '<i class="fas fa-exclamation-circle toast-icon"></i>';
                
            toast.innerHTML = `
                ${icon}
                <div class="toast-message">${message}</div>
            `;
            
            toastContainer.appendChild(toast);
            
            // Remove toast after 3 seconds
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    toastContainer.removeChild(toast);
                }, 300);
            }, 3000);
        }
        
        // Dispatch order function
        function dispatchOrder(orderId) {
            fetch('', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'dispatch_order_id=' + orderId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Order dispatched successfully!', 'success');
                    // Find the button and update its state
                    const dispatchBtn = document.querySelector(`.dispatch-btn[onclick="dispatchOrder(${orderId})"]`);
                    if (dispatchBtn) {
                        dispatchBtn.innerHTML = '<i class="fas fa-check-circle"></i> Already Dispatched';
                        dispatchBtn.disabled = true;
                        dispatchBtn.onclick = '';
                    }
                } else {
                    showToast('Failed to dispatch order. Please try again.', 'error');
                }
            })
            .catch(error => {
                showToast('Error: ' + error.message, 'error');
            });
        }
        
        // Close order function
        function closeOrder(orderId) {
            fetch('', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'close_order_id=' + orderId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Order closed successfully!', 'success');
                    // Find the card and remove it with animation
                    const orderCard = document.querySelector(`.order-card:nth-child(${Array.from(document.querySelectorAll('.order-card')).findIndex(card => 
                        card.querySelector(`.close-btn[onclick="closeOrder(${orderId})"]`) !== null) + 1})`);
                    
                    if (orderCard) {
                        orderCard.style.opacity = '0';
                        orderCard.style.transform = 'translateY(20px)';
                        setTimeout(() => {
                            orderCard.remove();
                        }, 300);
                    }
                } else {
                    showToast('Failed to close order. Please try again.', 'error');
                }
            })
            .catch(error => {
                showToast('Error: ' + error.message, 'error');
            });
        }
    </script>
</body>
</html>