<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: user-login.php");
    exit();
}

include "config.php";
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
$user_id = $_SESSION['user_id'];

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
  <meta charset="UTF-8" />
  <title>MedCare Patient Portal</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
      
      .special-offer {
        flex-direction: column;
        text-align: center;
      }
      
      .special-offer-btn {
        margin-left: 0;
        margin-top: 15px;
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
    }
  </style>
</head>
<body>
<div class="topnav">
  <div class="logo">
    <div class="logo-icon">
      <i class="fas fa-heartbeat"></i>
    </div>
    <div class="logo-text">Medical Store</div>
  </div>
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
    <h1 class="dashboard-title">User Dashboard</h1>
    <p class="dashboard-subtitle">Your health, our priority</p>
  </div>
  <div class="card-grid">
  <!-- View Medicines -->
  <a href="user-medicines.php" class="card">
    <div class="card-icon">
      <i class="fas fa-pills"></i>
    </div>
    <h3>View Medicines</h3>
    <p>Browse and search for medicines</p>
    <div class="card-stats">
      <div class="stat-item">
        <div class="stat-value">500+</div>
        <div class="stat-label">Medicines</div>
      </div>
      <div class="stat-item">
        <div class="stat-value">20%</div>
        <div class="stat-label">Discount</div>
      </div>
    </div>
  </a>
  <!-- My Cart -->
  <a href="user-cart.php" class="card">
    <div class="card-icon">
      <i class="fas fa-shopping-basket"></i>
    </div>
    <h3>My Cart</h3>
    <p>Your selected medicines</p>
    <div class="card-stats">
      <div class="stat-item">
        <div class="stat-value">
          <?php
            $cartQuery = mysqli_query($conn, "SELECT COUNT(*) as cart_count FROM cart WHERE user_id = " . (int)$user_id);
            $cartData = mysqli_fetch_assoc($cartQuery);
            echo isset($cartData['cart_count']) ? $cartData['cart_count'] : '0';
          ?>
        </div>
        <div class="stat-label">Items</div>
      </div>
      <div class="stat-item">
        <div class="stat-value">
          <?php
            $totalQuery = mysqli_query($conn, "
              SELECT SUM(m.MED_PRICE * c.quantity) as total
              FROM cart c
              JOIN meds m ON c.med_id = m.MED_ID
              WHERE c.user_id = " . (int)$user_id
            );
            $totalData = mysqli_fetch_assoc($totalQuery);
            echo '₹' . number_format($totalData['total'] ?? 0, 2);
          ?>
        </div>
        <div class="stat-label">Total</div>
      </div>
    </div>
  </a>
  <!-- Live Order -->
  <a href="user-orders.php" class="card">
    <div class="card-icon">
      <i class="fas fa-box-open"></i>
    </div>
    <h3>Live Orders</h3>
    <p>Track your medication deliveries</p>
    <div class="card-stats">
      <div class="stat-item">
        <div class="stat-value">
          <?php
            $orderTotalQuery = mysqli_query($conn, "SELECT COUNT(*) as total_orders FROM orders WHERE user_id = $user_id");
            $orderTotal = mysqli_fetch_assoc($orderTotalQuery)['total_orders'] ?? 0;
            echo $orderTotal;
          ?>
        </div>
        <div class="stat-label">Total</div>
      </div>
      <div class="stat-item">
        <div class="stat-value">
          <?php
            $orderPendingQuery = mysqli_query($conn, "SELECT COUNT(*) as pending_orders FROM orders WHERE user_id = $user_id AND status = 'Pending'");
            $pending = mysqli_fetch_assoc($orderPendingQuery)['pending_orders'] ?? 0;
            echo $pending;
          ?>
        </div>
        <div class="stat-label">Pending</div>
      </div>
    </div>
  </a>

  <!-- Wishlist -->
<a href="user-wishlist.php" class="card">
  <div class="card-icon">
    <i class="fas fa-heart"></i>
  </div>
  <h3>My Wishlist</h3>
  <p>Check your wishlisted medicines</p>
  <div class="card-stats">
    <div class="stat-item">
      <div class="stat-value">
        <?php
          $wishlistQuery = mysqli_query($conn, "SELECT COUNT(*) as count FROM wishlist WHERE user_id = $user_id");
          $wishlistCount = mysqli_fetch_assoc($wishlistQuery)['count'] ?? 0;
          echo $wishlistCount;
        ?>
      </div>
      <div class="stat-label">Items</div>
    </div>
    <div class="stat-item">
      <div class="stat-value">💡</div>
      <div class="stat-label">Notifications</div>
    </div>
  </div>
</a>

  <!-- Order History -->
<a href="user-order-history.php" class="card">
  <div class="card-icon">
    <i class="fas fa-history"></i>
  </div>
  <h3>Order History</h3>
  <p>View completed or closed orders</p>
  <div class="card-stats">
    <div class="stat-item">
      <div class="stat-value">
        <?php
          $historyQuery = mysqli_query($conn, "
            SELECT COUNT(*) as history_count 
            FROM order_history 
            WHERE user_id = $user_id
          ");
          $historyCount = mysqli_fetch_assoc($historyQuery)['history_count'] ?? 0;
          echo $historyCount;
        ?>
      </div>
      <div class="stat-label">Past Orders</div>
    </div>
    <div class="stat-item">
      <div class="stat-value">📦</div>
      <div class="stat-label">Completed</div>
    </div>
  </div>
</a>

</div>
</body>
</html>
