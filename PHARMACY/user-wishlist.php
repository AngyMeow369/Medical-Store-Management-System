<?php
session_start();
include "config.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: user-login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
// Handle remove action
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['remove_id'])) {
    $remove_id = intval($_POST['remove_id']);
    $result = mysqli_query($conn, "DELETE FROM wishlist WHERE id = $remove_id AND user_id = $user_id");
    if ($result) {
        // Set success message in session
        $_SESSION['success_message'] = "Item removed from wishlist successfully!";
        // Redirect to prevent form resubmission
        header("Location: user-wishlist.php");
        exit();
    } else {
        $error = "Error removing item: " . mysqli_error($conn);
    }
}
// Fetch wishlist items
$qry = "
    SELECT w.*, m.med_name, m.med_qty, m.med_price
    FROM wishlist w
    JOIN meds m ON w.med_id = m.med_id
    WHERE w.user_id = $user_id
    ORDER BY w.created_at DESC
";
$res = mysqli_query($conn, $qry);
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Wishlist - MediCare</title>
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
        
        .quick-actions {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px var(--shadow);
            margin-top: 40px;
        }
        
        .section-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 25px;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        
        .action-btn {
            background: white;
            border: 2px solid var(--border);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }
        
        .action-btn:hover {
            border-color: var(--primary);
            transform: translateY(-5px);
            box-shadow: 0 10px 20px var(--shadow);
        }
        
        .action-btn i {
            font-size: 28px;
            color: var(--primary);
        }
        
        .action-btn span {
            font-weight: 500;
            font-size: 14px;
        }
        
        .emergency-banner {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin: 30px 0;
            text-align: center;
            box-shadow: 0 10px 30px rgba(238, 90, 82, 0.3);
        }
        
        .emergency-banner h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }
        
        .emergency-banner p {
            opacity: 0.9;
        }
        
        .emergency-btn {
            background: white;
            color: #ff6b6b;
            padding: 10px 25px;
            border-radius: 25px;
            font-weight: 600;
            margin-top: 15px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .emergency-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(255, 255, 255, 0.3);
        }
        
        .cart-badge {
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }
        
        .special-offer {
            background: linear-gradient(135deg, #4caf50, #2e7d32);
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin: 30px 0;
            text-align: center;
            box-shadow: 0 10px 30px rgba(46, 125, 50, 0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .special-offer-text {
            flex: 1;
        }
        
        .special-offer h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }
        
        .special-offer p {
            opacity: 0.9;
        }
        
        .special-offer-btn {
            background: white;
            color: #2e7d32;
            padding: 10px 25px;
            border-radius: 25px;
            font-weight: 600;
            margin-left: 20px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .special-offer-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(255, 255, 255, 0.3);
        }
        
        /* Wishlist Styles */
        .wishlist-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
        }
        
        .wishlist-card {
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: 0 10px 30px var(--shadow);
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            border: 1px solid var(--border);
        }
        
        .wishlist-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .wishlist-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }
        
        .wishlist-header {
            padding: 25px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .medicine-name {
            font-weight: 600;
            font-size: 1.4rem;
            color: var(--text-color);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .medicine-name i {
            font-size: 1.6rem;
            color: var(--primary);
            background: rgba(30, 136, 229, 0.1);
            padding: 12px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .stock-status {
            padding: 8px 16px;
            border-radius: 50px;
            font-weight: 500;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .in-stock {
            background: rgba(76, 175, 80, 0.1);
            color: #4caf50;
            border: 1px solid rgba(76, 175, 80, 0.2);
        }
        
        .out-of-stock {
            background: rgba(244, 67, 54, 0.1);
            color: #f44336;
            border: 1px solid rgba(244, 67, 54, 0.2);
        }
        
        .wishlist-body {
            padding: 25px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 15px;
            border-bottom: 1px dashed var(--border);
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            color: var(--text-light);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .info-label i {
            color: var(--primary);
            font-size: 1.1rem;
        }
        
        .info-value {
            font-weight: 600;
            color: var(--text-color);
        }
        
        .notification-status {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 5px;
        }
        
        .notification-status i {
            font-size: 1.2rem;
        }
        
        .notified {
            color: #4caf50;
        }
        
        .waiting {
            color: #ff9800;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: auto;
            padding-top: 20px;
        }
        
        .remove-btn {
            background: linear-gradient(135deg, #f44336, #f87171);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }
        
        .remove-btn:hover {
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
            transform: translateY(-3px);
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 30px;
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: 0 10px 30px var(--shadow);
            margin-top: 30px;
        }
        
        .empty-state i {
            font-size: 5rem;
            color: var(--primary);
            margin-bottom: 25px;
        }
        
        .empty-state h3 {
            color: var(--text-color);
            margin-bottom: 20px;
            font-size: 1.8rem;
        }
        
        .empty-state p {
            color: var(--text-light);
            max-width: 600px;
            margin: 0 auto 30px;
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
        
        /* Success Modal */
        .success-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .success-modal.show {
            opacity: 1;
            visibility: visible;
        }
        
        .success-content {
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 500px;
            width: 90%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            transform: scale(0.8);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        .success-modal.show .success-content {
            transform: scale(1);
        }
        
        .success-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #4caf50, #2e7d32);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            animation: success-bounce 0.6s ease;
        }
        
        .success-icon i {
            font-size: 40px;
            color: white;
        }
        
        @keyframes success-bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-20px);
            }
            60% {
                transform: translateY(-10px);
            }
        }
        
        .success-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 15px;
        }
        
        .success-message {
            font-size: 18px;
            color: var(--text-light);
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .success-btn {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(30, 136, 229, 0.3);
        }
        
        .success-btn:hover {
            box-shadow: 0 6px 20px rgba(30, 136, 229, 0.4);
            transform: translateY(-3px);
        }
        
        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background: #4caf50;
            opacity: 0;
        }
        
        @keyframes confetti-fall {
            0% {
                transform: translateY(-100vh) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(100vh) rotate(720deg);
                opacity: 1;
            }
        }
        
        .notification-container {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: 15px;
            max-width: 350px;
        }
        
        .notification {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            transform: translateX(120%);
            opacity: 0;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border);
        }
        
        .notification.show {
            transform: translateX(0);
            opacity: 1;
        }
        
        .notification-content {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 1;
        }
        
        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        
        .notification-icon.success {
            background: rgba(76, 175, 80, 0.1);
            color: #4caf50;
            box-shadow: 0 0 10px rgba(76, 175, 80, 0.3);
            border: 1px solid rgba(76, 175, 80, 0.3);
        }
        
        .notification-icon.error {
            background: rgba(244, 67, 54, 0.1);
            color: #f44336;
            box-shadow: 0 0 10px rgba(244, 67, 54, 0.3);
            border: 1px solid rgba(244, 67, 54, 0.3);
        }
        
        .notification-text {
            font-size: 0.95rem;
            color: var(--text-color);
        }
        
        .notification-text strong {
            color: var(--primary);
            margin-right: 5px;
        }
        
        .notification-close {
            color: var(--text-light);
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .notification-close:hover {
            color: var(--text-color);
            transform: rotate(90deg);
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
            
            .wishlist-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .special-offer {
                flex-direction: column;
                text-align: center;
            }
            
            .special-offer-btn {
                margin-left: 0;
                margin-top: 15px;
            }
            
            .success-content {
                padding: 30px 20px;
            }
            
            .success-title {
                font-size: 24px;
            }
            
            .success-message {
                font-size: 16px;
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
            
            .actions-grid {
                grid-template-columns: 1fr;
            }
            
            .wishlist-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            
            .stock-status {
                align-self: flex-start;
            }
            
            .notification-container {
                max-width: 90%;
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
        <div class="dashboard-header">
            <h1 class="dashboard-title">My Wishlist</h1>
            <p class="dashboard-subtitle">Your saved medicines and health products</p>
        </div>
        
        <?php
        if (mysqli_num_rows($res) > 0) {
            echo '<div class="wishlist-grid">';
            
            while ($row = mysqli_fetch_assoc($res)) {
                $statusClass = $row['med_qty'] > 0 ? 'in-stock' : 'out-of-stock';
                $statusText = $row['med_qty'] > 0 ? $row['med_qty'] . ' in stock' : 'Out of Stock';
                $notificationIcon = $row['notified'] == 1 ? 'fa-bell' : 'fa-bell-slash';
                $notificationClass = $row['notified'] == 1 ? 'notified' : 'waiting';
                $notificationText = $row['notified'] == 1 ? 'You were notified!' : 'Waiting for stock';
                
                echo '<div class="wishlist-card" id="wishlist-'.$row['id'].'">
                    <div class="wishlist-header">
                        <div class="medicine-name">
                            <i class="fas fa-pills"></i>
                            '.htmlspecialchars($row['med_name']).'
                        </div>
                        <div class="stock-status '.$statusClass.'">
                            <i class="'.($row['med_qty'] > 0 ? 'fas fa-check-circle' : 'fas fa-times-circle').'"></i>
                            '.$statusText.'
                        </div>
                    </div>
                    
                    <div class="wishlist-body">
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Added On</span>
                            </div>
                            <div class="info-value">
                                '.date("d M Y, H:i", strtotime($row['created_at'])).'
                            </div>
                        </div>
                        
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-dollar-sign"></i>
                                <span>Price</span>
                            </div>
                            <div class="info-value">
                                $'.number_format($row['med_price'], 2).'
                            </div>
                        </div>
                        
                        <div class="notification-status">
                            <i class="'.($row['notified'] == 1 ? 'fas fa-check-circle' : 'fas fa-hourglass-half').'"></i>
                            <span class="'.$notificationClass.'">'.$notificationText.'</span>
                        </div>
                        
                        <div class="action-buttons">
                            <form method="post">
                                <input type="hidden" name="remove_id" value="'.$row['id'].'">
                                <button type="submit" class="remove-btn">
                                    <i class="fas fa-trash-alt"></i> Remove
                                </button>
                            </form>
                        </div>
                    </div>
                </div>';
            }
            
            echo '</div>';
        } else {
            echo '<div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <h3>Your Wishlist is Empty</h3>
                    <p>You haven\'t added any medicines to your wishlist yet. Browse our catalog and add medications you\'re interested in to keep track of their availability.</p>
                    <a href="user-medicines.php" class="btn">
                        <i class="fas fa-search"></i> Browse Medicines
                    </a>
                </div>';
        }
        
        // Display error message if there was an error removing an item
        if (isset($error)) {
            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    showNotification("' . addslashes($error) . '", "error");
                });
            </script>';
        }
        ?>
    </div>
    
    <!-- Success Modal -->
    <div class="success-modal" id="successModal">
        <div class="success-content">
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
            <h3 class="success-title">Success!</h3>
            <p class="success-message">Item removed from wishlist successfully!</p>
            <button class="success-btn" onclick="closeSuccessModal()">
                <i class="fas fa-check"></i> OK
            </button>
        </div>
    </div>
    
    <div class="notification-container" id="notification-container"></div>
    
    <script>
        // Handle form submission for removing items
        document.addEventListener('DOMContentLoaded', function() {
            // Check if there's a success message in session
            <?php if (isset($_SESSION['success_message']) && !empty($_SESSION['success_message'])): ?>
                showSuccessModal();
                // Clear the session message
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>
            
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const removeId = this.querySelector('input[name="remove_id"]').value;
                    
                    // Create a loading state
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Removing...';
                    submitBtn.disabled = true;
                    
                    // Send the request
                    fetch(window.location.href, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `remove_id=${removeId}`
                    })
                    .then(response => response.text())
                    .then(html => {
                        // Parse the response to check for error message
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const errorElement = doc.querySelector('script');
                        
                        if (errorElement && errorElement.textContent.includes('showNotification')) {
                            // There was an error, extract the error message
                            const errorMatch = errorElement.textContent.match(/showNotification\("([^"]+)", "error"\)/);
                            if (errorMatch && errorMatch[1]) {
                                showNotification(errorMatch[1], 'error');
                            }
                        } else {
                            // Success - reload the page to show success modal
                            window.location.reload();
                        }
                        
                        // Reset button state
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    })
                    .catch(() => {
                        showNotification("Network error while removing item.", 'error');
                        // Reset button state
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    });
                });
            });
        });
        
        function showSuccessModal() {
            const modal = document.getElementById('successModal');
            modal.classList.add('show');
            
            // Create confetti effect
            createConfetti();
        }
        
        function closeSuccessModal() {
            const modal = document.getElementById('successModal');
            modal.classList.remove('show');
        }
        
        function createConfetti() {
            const colors = ['#4caf50', '#2e7d32', '#1e88e5', '#1565c0', '#ff9800', '#f57c00'];
            const confettiCount = 100;
            
            for (let i = 0; i < confettiCount; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * 100 + '%';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.width = Math.random() * 10 + 5 + 'px';
                confetti.style.height = Math.random() * 10 + 5 + 'px';
                confetti.style.opacity = Math.random() * 0.7 + 0.3;
                confetti.style.animation = `confetti-fall ${Math.random() * 3 + 2}s linear forwards`;
                confetti.style.animationDelay = Math.random() * 2 + 's';
                
                document.body.appendChild(confetti);
                
                // Remove confetti after animation
                setTimeout(() => {
                    confetti.remove();
                }, 5000);
            }
        }
        
        function showNotification(message, type) {
            const container = document.getElementById('notification-container');
            
            // Create notification element
            const notification = document.createElement('div');
            notification.className = 'notification';
            
            // Create icon based on type
            const iconClass = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
            const iconBg = type === 'success' ? 'success' : 'error';
            
            notification.innerHTML = `
                <div class="notification-content">
                    <div class="notification-icon ${iconBg}">
                        <i class="${iconClass}"></i>
                    </div>
                    <div class="notification-text">
                        ${message}
                    </div>
                </div>
                <button class="notification-close">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            // Add close button functionality
            const closeBtn = notification.querySelector('.notification-close');
            closeBtn.addEventListener('click', () => {
                notification.classList.remove('show');
                setTimeout(() => {
                    notification.remove();
                }, 500);
            });
            
            // Add to container
            container.appendChild(notification);
            
            // Trigger animation
            setTimeout(() => {
                notification.classList.add('show');
            }, 10);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    notification.remove();
                }, 500);
            }, 5000);
        }
    </script>
</body>
</html>