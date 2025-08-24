<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: pharmLogin.php");
    exit();
}
include "config.php";
$pharmacist_id = $_SESSION['user'];
$search = '';
$status = '';
$order_type = '';
// Process form data
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get search term
    if (isset($_GET['search'])) {
        $search = mysqli_real_escape_string($conn, $_GET['search']);
    }
    
    // Get filters
    if (isset($_GET['status']) && $_GET['status'] !== '') {
        $status = mysqli_real_escape_string($conn, $_GET['status']);
    }
    
    if (isset($_GET['order_type']) && $_GET['order_type'] !== '') {
        $order_type = mysqli_real_escape_string($conn, $_GET['order_type']);
    }
}
// Build query based on search and filters
$baseQuery = "
    SELECT o.*, u.phone, u.username 
    FROM orders o 
    JOIN users u ON o.user_id = u.user_id 
    WHERE 1=1
";
// Add search condition if provided
if (!empty($search)) {
    $baseQuery .= " AND (u.username LIKE '%$search%' OR u.phone LIKE '%$search%' OR o.order_id LIKE '%$search%')";
}
// Add status filter if provided
if (!empty($status)) {
    $baseQuery .= " AND o.status = '$status'";
}
// Add order type filter if provided
if (!empty($order_type)) {
    $baseQuery .= " AND o.order_type = '$order_type'";
}
// Add sorting
$baseQuery .= " ORDER BY o.order_date DESC";
// Execute the query
$ordersQuery = mysqli_query($conn, $baseQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="20">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacist - Manage Orders</title>
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
            margin-bottom: 20px;
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
        
        /* MODERN FLOATING FILTER AREA - Moved to top */
        .filters-container {
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            padding: 15px;
            margin-bottom: 20px;
            position: relative;
            border-left: 5px solid var(--primary);
        }
        
        .filters-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 12px;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
        }
        
        .filter-label {
            margin-bottom: 6px;
            font-weight: 500;
            color: var(--dark);
            font-size: 14px;
        }
        
        .filter-select {
            padding: 10px;
            border: 2px solid var(--light-gray);
            border-radius: 8px;
            font-family: inherit;
            transition: var(--transition);
            background-color: white;
            font-size: 14px;
        }
        
        .filter-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 128, 128, 0.2);
        }
        
        .filter-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 10px;
        }
        
        .filter-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
        }
        
        .filter-btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .filter-btn-primary:hover {
            background: var(--secondary);
        }
        
        .filter-btn-secondary {
            background: var(--light-gray);
            color: var(--dark);
        }
        
        .filter-btn-secondary:hover {
            background: #dee2e6;
        }
        
        .actions-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .search-container {
            flex: 1;
            min-width: 300px;
            position: relative;
            display: flex;
        }
        
        .search-input {
            width: 100%;
            padding: 12px 16px 12px 45px;
            border: 2px solid var(--light-gray);
            border-radius: 8px;
            font-size: 1rem;
            transition: var(--transition);
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 128, 128, 0.2);
        }
        
        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
        }
        
        .search-btn {
            padding: 12px 76px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .search-btn:hover {
            background: var(--secondary);
        }
        
        .delete-btn {
            padding: 12px 20px;
            background: var(--danger);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: var(--transition);
            white-space: nowrap;
        }
        
        .delete-btn:hover {
            background: #d90429;
            transform: translateY(-2px);
        }
        
        .success-message {
            padding: 15px;
            background: #d1fae5;
            border-left: 4px solid #10b981;
            margin-bottom: 20px;
            border-radius: 8px;
            display: none;
        }
        
        .success-message.show {
            display: block;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
        }
        
        .empty-state i {
            font-size: 4rem;
            color: var(--gray);
            margin-bottom: 20px;
        }
        
        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: var(--dark);
        }
        
        .empty-state p {
            color: var(--gray);
            max-width: 400px;
            margin: 0 auto;
        }
        
        .orders-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 25px;
        }
        
        .order-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            transition: var(--transition);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border-color: var(--primary-light);
        }
        
        .order-header {
            padding: 20px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
        }
        
        .order-header h3 {
            font-size: 1.2rem;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .order-id {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .order-body {
            padding: 20px;
        }
        
        .order-info {
            margin-bottom: 20px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid var(--light-gray);
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 500;
            color: var(--gray);
        }
        
        .info-value {
            font-weight: 600;
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: capitalize;
        }
        
        .status-badge.pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-badge.approved {
            background: #d4edda;
            color: #155724;
        }
        
        .status-badge.rejected {
            background: #f8d7da;
            color: #721c24;
        }
        
        .order-items {
            margin: 20px 0;
        }
        
        .items-title {
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--primary);
        }
        
        .items-list {
            list-style: none;
        }
        
        .items-list li {
            padding: 8px 0;
            border-bottom: 1px solid var(--light-gray);
            display: flex;
            justify-content: space-between;
        }
        
        .items-list li:last-child {
            border-bottom: none;
        }
        
        .order-actions {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid var(--light-gray);
        }
        
        .action-group {
            margin-bottom: 15px;
        }
        
        .action-title {
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--dark);
        }
        
        .btn {
            padding: 10px 16px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
            font-size: 0.9rem;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--secondary);
        }
        
        .btn-success {
            background: var(--success);
            color: white;
        }
        
        .btn-success:hover {
            background: #3da5d9;
        }
        
        .btn-danger {
            background: var(--danger);
            color: white;
        }
        
        .btn-danger:hover {
            background: #d90429;
        }
        
        .btn-warning {
            background: var(--warning);
            color: white;
        }
        
        .btn-warning:hover {
            background: #e85d04;
        }
        
        .btn-gray {
            background: var(--gray);
            color: white;
        }
        
        .btn-gray:hover {
            background: #5a6268;
        }
        
        .btn-disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .notes-area {
            width: 100%;
            padding: 12px;
            border: 2px solid var(--light-gray);
            border-radius: 8px;
            font-family: inherit;
            resize: vertical;
            margin-bottom: 15px;
            transition: var(--transition);
        }
        
        .notes-area:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 128, 128, 0.2);
        }
        
        .location-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .location-link:hover {
            color: var(--secondary);
            text-decoration: underline;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .navbar, .actions-bar, .btn-group, .delete-btn, .search-container {
                display: none !important;
            }
            .order-card {
                box-shadow: none;
                border: 1px solid #ddd;
                break-inside: avoid;
            }
            .order-card {
                page-break-inside: avoid;
                margin-bottom: 30px;
            }
        }
        
        @media (max-width: 768px) {
            .navbar {
                padding: 0 15px;
            }
            
            .actions-bar {
                flex-direction: column;
            }
            
            .orders-grid {
                grid-template-columns: 1fr;
            }
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
                <div class="user-avatar">Meta</div>
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
        <a href="pharmacist-order.php" class="nav-item active">
            <i class="fas fa-clipboard-list"></i>
            <span>Orders</span>
        </a>
        <a href="dispatch-order.php" class="nav-item">
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
    
    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <div class="page-header">
            <div class="page-title">
                <i class="fas fa-clipboard-list"></i>
                Manage Orders
            </div>
            <a href="pharmmainpage.php" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Back to Dashboard
            </a>
        </div>
        
        <!-- MODERN FLOATING FILTER AREA - Moved to top -->
        <div class="filters-container">
            <div class="filters-title">
                <i class="fas fa-filter"></i> Quick Filters
            </div>
            <div class="filters-grid">
                <div class="filter-group">
                    <label class="filter-label" for="statusFilter">Order Status</label>
                    <select class="filter-select" id="statusFilter" name="status">
                        <option value="">All Statuses</option>
                        <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="approved" <?= $status === 'approved' ? 'selected' : '' ?>>Approved</option>
                        <option value="rejected" <?= $status === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label" for="orderTypeFilter">Order Type</label>
                    <select class="filter-select" id="orderTypeFilter" name="order_type">
                        <option value="">All Types</option>
                        <option value="pickup" <?= $order_type === 'pickup' ? 'selected' : '' ?>>Pickup</option>
                        <option value="delivery" <?= $order_type === 'delivery' ? 'selected' : '' ?>>Delivery</option>
                    </select>
                </div>
            </div>
            
            <div class="filter-actions">
                <button type="button" class="filter-btn filter-btn-secondary" id="clearFilters">
                    <i class="fas fa-times"></i> Clear
                </button>
                <button type="submit" class="filter-btn filter-btn-primary">
                    <i class="fas fa-search"></i> Apply
                </button>
            </div>
        </div>
        
        <div class="actions-bar">
            <div class="search-container">
                <form method="get" action="">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="search" class="search-input" placeholder="Search by name, phone, or ID" value="<?= htmlspecialchars($search) ?>" id="searchInput">
                </form>
            </div>
            
            <form method="post" action="clear-orders.php" onsubmit="return confirm('Are you sure? All current orders will be moved to history and cleared!');">
                <button type="submit" class="delete-btn">
                    <i class="fas fa-trash"></i> Delete All Orders
                </button>
            </form>
        </div>
        
        <?php
        if (isset($_GET['deleted']) && $_GET['deleted'] == 1) {
            echo '<div class="success-message show">
                <i class="fas fa-check-circle"></i> All orders moved to history and cleared successfully.
            </div>';
        }
        ?>
        
        <?php
        if (mysqli_num_rows($ordersQuery) === 0) {
            echo '<div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>No Orders Found</h3>
                <p>There are currently no orders to display. Check back later or try a different search.</p>
            </div>';
        } else {
            echo '<div class="orders-grid">';
            while ($order = mysqli_fetch_assoc($ordersQuery)) {
                $order_id = $order['order_id'];
                $status = strtolower(trim($order['status']));
                $payment_method = $order['payment_method'];
                $payment_status = $order['payment_status'];
                $username = $order['username'];
                $order_type = $order['order_type'];
                $delivery_status = $order['delivery_status'];
                $order_closed = $order['order_closed'];
                
                $statusClass = 'pending';
                $statusIcon = 'clock';
                
                if ($status === 'approved') {
                    $statusClass = 'approved';
                    $statusIcon = 'check-circle';
                } elseif ($status === 'rejected') {
                    $statusClass = 'rejected';
                    $statusIcon = 'times-circle';
                }
                
                echo "<div class='order-card'>";
                echo "<div class='order-header'>";
                // Prescription Thumbnail Preview
if (!empty($order['prescription_image']) && file_exists($order['prescription_image'])) {
    echo '<div class="prescription-preview" style="margin: 15px 0 10px 15px;">
        <strong><i class="fas fa-file-medical"></i> Prescription:</strong><br>
        <a href="'.$order['prescription_image'].'" target="_blank">
            <img src="'.$order['prescription_image'].'" alt="Prescription" style="width: 120px; height: auto; border-radius: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.2); margin-top: 5px;">
        </a>
    </div>';
}

                echo "<h3><i class='fas fa-receipt'></i> Order #" . htmlspecialchars($order_id) . "</h3>";
                echo "<div class='order-id'>Customer: " . htmlspecialchars($username) . " (ID: " . htmlspecialchars($order['user_id']) . ")</div>";
                echo "</div>";

                if (!empty($order['prescription_image']) && file_exists($order['prescription_image'])) {
    $imgId = 'prescription-' . $order['order_id'];
    echo '
    <div class="prescription-btn" style="margin: 10px 0 0 15px;">
        <button onclick="showPrescriptionModal(\'' . $imgId . '\')" class="confirm-btn">
            <i class="fas fa-file-medical"></i> View Prescription
        </button>
        <img id="'.$imgId.'" src="'.$order['prescription_image'].'" alt="Prescription" 
             style="display: none; max-width: 100%; border-radius: 8px;">
    </div>';
}


                echo "<div class='order-body'>";
                echo "<div class='order-info'>";
                echo "<div class='info-row'><span class='info-label'>Date:</span> <span class='info-value'>" . htmlspecialchars($order['order_date']) . "</span></div>";
                echo "<div class='info-row'><span class='info-label'>Total:</span> <span class='info-value'>₹" . htmlspecialchars($order['total_amount']) . "</span></div>";
                echo "<div class='info-row'><span class='info-label'>Status:</span> <span class='status-badge " . $statusClass . "'><i class='fas fa-" . $statusIcon . "'></i> " . htmlspecialchars($status) . "</span></div>";
                echo "<div class='info-row'><span class='info-label'>Order Type:</span> <span class='info-value'>" . ($order_type === 'pickup' ? '🛍️ Collect by Customer' : '🚚 Delivery') . "</span></div>";
                echo "<div class='info-row'><span class='info-label'>Payment Method:</span> <span class='info-value'>" . htmlspecialchars($payment_method) . "</span></div>";
                
                if ($payment_method === 'COD') {
                    echo "<div class='info-row'><span class='info-label'>Payment Status:</span> <span class='info-value'>❌ Yet to be Paid (Collect cash)</span></div>";
                } else {
                    echo "<div class='info-row'><span class='info-label'>Payment Status:</span> <span class='info-value'>" . ($payment_status === 'paid' ? '✅ Paid' : '❌ Unpaid') . "</span></div>";
                }
                
                echo "<div class='info-row'><span class='info-label'>Contact Number:</span> <span class='info-value'>" . htmlspecialchars($order['phone']) . "</span></div>";
                echo "</div>";
                
                $itemsQuery = mysqli_query($conn, "
                    SELECT oi.*, m.MED_NAME 
                    FROM order_items oi 
                    JOIN meds m ON oi.med_id = m.MED_ID 
                    WHERE order_id = $order_id
                ");
                
                echo "<div class='order-items'>";
                echo "<div class='items-title'><i class='fas fa-shopping-cart'></i> Items</div>";
                echo "<ul class='items-list'>";
                while ($item = mysqli_fetch_assoc($itemsQuery)) {
                    echo "<li>";
                    echo "<span>" . htmlspecialchars($item['MED_NAME']) . " | Qty: " . htmlspecialchars($item['quantity']) . "</span>";
                    echo "<span>₹" . htmlspecialchars($item['total_price']) . "</span>";
                    echo "</li>";
                }
                echo "</ul>";
                echo "</div>";
                
                if ($status === 'pending') {
                    $isPaid = $payment_status === 'paid' || $payment_method === 'COD';
                    echo "<div class='order-actions'>";
                    echo "<div class='action-group'>";
                    echo "<div class='action-title'>Order Actions</div>";
                    echo "<form method='post' action='pharmacist-handle-order.php'>";
                    echo "<input type='hidden' name='order_id' value='" . htmlspecialchars($order_id) . "'>";
                    echo "<textarea name='notes' class='notes-area' placeholder='Add notes (optional)...'></textarea>";
                    
                    if ($isPaid) {
                        echo "<button type='submit' class='btn btn-primary' name='action' value='approve'>
                            <i class='fas fa-check'></i> Approve
                        </button>";
                    } else {
                        echo "<button type='submit' class='btn btn-primary btn-disabled' disabled title='Cannot approve unpaid order'>
                            <i class='fas fa-check'></i> Approve
                        </button>";
                    }
                    
                    echo "<button type='submit' class='btn btn-danger' name='action' value='reject'>
                        <i class='fas fa-times'></i> Reject
                    </button>";
                    echo "</form>";
                    echo "</div>";
                }
                
                // Pickup logic remains unchanged
                if ($order_type === 'pickup' && $status === 'approved') {
                    if ($order['ready_for_pickup']) {
                        echo "<div class='info-row' style='margin-top: 15px; background: #d4edda; padding: 10px; border-radius: 8px;'>
                            <i class='fas fa-check-circle'></i> 🟢 Ready for Collection
                        </div>";
                    } else {
                        echo "<div class='order-actions'>";
                        echo "<div class='action-group'>";
                        echo "<form method='post' action='mark-ready.php'>";
                        echo "<input type='hidden' name='order_id' value='" . htmlspecialchars($order_id) . "'>";
                        echo "<button type='submit' class='btn btn-success'>
                            <i class='fas fa-check-circle'></i> Mark as Ready for Collection
                        </button>";
                        echo "</form>";
                        echo "</div>";
                        echo "</div>";
                    }
                }
                
                if (!empty($order['latitude']) && !empty($order['longitude'])) {
                    echo "<div class='info-row' style='margin-top: 15px;'>
                        <span class='info-label'><i class='fas fa-map-marker-alt'></i> Location:</span>
                        <a href='https://www.google.com/maps?q=" . htmlspecialchars($order['latitude']) . "," . htmlspecialchars($order['longitude']) . "' target='_blank' class='location-link'>
                            <i class='fas fa-external-link-alt'></i> View on Map
                        </a>
                    </div>";
                }
                
                echo "<div style='text-align: right; margin-top: 20px;'>
                    <button class='btn btn-gray' onclick='window.print()'>
                        <i class='fas fa-print'></i> Print Receipt
                    </button>
                </div>";
                
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
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
        
        // Clear filters button functionality
        document.getElementById('clearFilters').addEventListener('click', function() {
            document.getElementById('statusFilter').value = '';
            document.getElementById('orderTypeFilter').value = '';
            document.getElementById('searchInput').value = '';
            
            // Submit the form to clear filters
            document.querySelector('form[method="get"]').submit();
        });
        
        // Fix filter functionality - make sure the form submits correctly
        document.querySelector('.filters-container form').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
            const statusFilter = document.getElementById('statusFilter').value;
            const orderTypeFilter = document.getElementById('orderTypeFilter').value;
            
            // Build URL with parameters
            const url = new URL(window.location.href);
            
            if (statusFilter) {
                url.searchParams.set('status', statusFilter);
            }
            
            if (orderTypeFilter) {
                url.searchParams.set('order_type', orderTypeFilter);
            }
            
            // Remove search parameter when applying filters
            url.searchParams.delete('search');
            
            // Navigate to the new URL
            window.location.href = url.toString();
        });

        function showPrescriptionModal(imgId) {
    const img = document.getElementById(imgId);
    const modal = document.getElementById('prescriptionModal');
    const modalImg = document.getElementById('modalImage');
    if (img && modal && modalImg) {
        modalImg.src = img.src;
        modal.style.display = 'block';
    }
}

function hidePrescriptionModal() {
    document.getElementById('prescriptionModal').style.display = 'none';
}

    </script>

    <!-- Modal -->
<div id="prescriptionModal" class="modal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; overflow:auto; background-color:rgba(0,0,0,0.7);">
  <span class="close" onclick="hidePrescriptionModal()" style="position:absolute; top:20px; right:30px; font-size:30px; color:white; cursor:pointer;">&times;</span>
  <div style="margin:auto; display:flex; justify-content:center; align-items:center; height:100%;">
    <img id="modalImage" src="" style="max-width:90%; max-height:90%; border-radius:10px; box-shadow:0 0 10px black;">
  </div>
</div>

</body>
</html>