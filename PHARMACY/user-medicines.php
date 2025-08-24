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
$userQuery = mysqli_query($conn, "SELECT username, profile_picture FROM users WHERE user_id = $user_id");
if (!$userQuery) {
    die("Error fetching user data: " . mysqli_error($conn));
}
$userData = mysqli_fetch_assoc($userQuery);
if (!$userData) {
    header("Location: user-login.php");
    exit();
}
// Initialize search term variable to fix undefined variable warning
$searchTerm = isset($_GET['search']) ? mysqli_real_escape_string($conn, trim($_GET['search'])) : '';
// Get all categories for the filter dropdown
$categoryResult = mysqli_query($conn, "SELECT DISTINCT CATEGORY FROM meds ORDER BY CATEGORY ASC");
// Handle search and category filter
$whereClause = 'WHERE 1';
if (!empty($searchTerm)) {
    $whereClause .= " AND (MED_NAME LIKE '%$searchTerm%' OR CATEGORY LIKE '%$searchTerm%')";
}
$selectedCategory = '';
if (!empty($_GET['category']) && trim($_GET['category']) !== '') {
    $selectedCategory = mysqli_real_escape_string($conn, trim($_GET['category']));
    $whereClause .= " AND CATEGORY = '$selectedCategory'";
}
// Removed random sort option - now default to alphabetical order
$sortOrder = 'MED_NAME ASC';
// Get total count for pagination
$countQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM meds $whereClause");
$totalRecords = mysqli_fetch_assoc($countQuery)['total'] ?? 0;
// Pagination variables
$limit = 10; // number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
// Calculate total pages - fixed undefined variable issue
$totalPages = ceil($totalRecords / $limit);
// Get medicines for current page
$query = "SELECT * FROM meds $whereClause ORDER BY $sortOrder LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);
// Check if we got results
if (!$result) {
    die("Error fetching medicines: " . mysqli_error($conn));
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
      --border-light: #d1d5db;
      --border-input: #cbd5e1;
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
      border-radius: 50%;
      overflow: hidden;
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: 600;
    }
    
    .user-avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    
    .welcome-text {
      color: var(--text-light);
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
    
    .search-container {
      background: white;
      border-radius: 16px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
      padding: 1.5rem;
      margin-bottom: 2rem;
    }
    
    .search-header {
      display: flex;
      justify-content: center;
      align-items: center;
      margin-bottom: 1.5rem;
    }
    
    .search-header h2 {
      color: var(--primary);
      font-weight: 600;
      font-size: 1.5rem;
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }
    
    .search-form {
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
      align-items: center;
      justify-content: center;
    }
    
    .search-form input[type="text"], .search-form select {
      padding: 0.625rem 1rem;
      border-radius: 8px;
      border: 1px solid var(--border-input); /* Added border */
      font-size: 0.875rem;
      flex: 1;
      min-width: 200px;
      max-width: 300px;
      transition: all 0.3s ease;
    }
    
    .search-form input[type="text"]:focus, .search-form select:focus {
      outline: none;
      border-color: var(--primary); /* Changed border color on focus */
      box-shadow: 0 0 0 3px rgba(30, 136, 229, 0.1); /* Added focus glow */
    }
    
    .search-form button {
      padding: 0.625rem 1.25rem;
      border: none;
      background-color: var(--primary);
      color: white;
      font-weight: 500;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .search-form button:hover {
      background-color: #0284c7;
      transform: translateY(-1px);
    }
    
    .med-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 1.5rem;
    }
    
    .med-card {
      background: white;
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      overflow: hidden;
      transition: all 0.3s ease;
      display: flex;
      flex-direction: column;
      height: 100%;
    }
    
    .med-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    }
    
    .med-image {
      height: 200px;
      overflow: hidden;
      position: relative;
    }
    
    .med-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s ease;
    }
    
    .med-card:hover .med-image img {
      transform: scale(1.05);
    }
    
    .med-details {
      padding: 1.25rem;
      flex: 1;
      display: flex;
      flex-direction: column;
    }
    
    .med-name {
      font-weight: 600;
      font-size: 1.125rem;
      margin-bottom: 0.5rem;
      color: var(--dark);
    }
    
    .med-category {
      display: inline-block;
      padding: 0.25rem 0.75rem;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 500;
      margin-bottom: 0.75rem;
      background: rgba(14, 165, 233, 0.1);
      color: var(--primary);
    }
    
    .med-price {
      font-weight: 700;
      font-size: 1.25rem;
      color: var(--primary);
      margin-bottom: 1rem;
    }
    
    .med-status {
      margin-bottom: 1rem;
    }
    
    .status-available {
      display: inline-block;
      padding: 0.25rem 0.75rem;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 500;
      background: rgba(16, 185, 129, 0.1);
      color: var(--success);
    }
    
    .status-unavailable {
      display: inline-block;
      padding: 0.25rem 0.75rem;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 500;
      background: rgba(239, 68, 68, 0.1);
      color: var(--danger);
    }
    
    .med-action {
      margin-top: auto;
      display: flex;
      gap: 0.5rem;
    }
    
    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      padding: 0.625rem 1.25rem;
      border-radius: 8px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
      border: none;
      font-size: 0.875rem;
      flex: 1;
    }
    
    .btn-primary {
      background-color: var(--primary);
      color: white;
    }
    
    .btn-primary:hover {
      background-color: #0284c7;
      transform: translateY(-1px);
    }
    
    .btn-warning {
      background-color:  #d97706;
      color: white;
    }
    
    .btn-warning:hover {
      background-color: #764207ff;
      transform: translateY(-1px);
    }
    
    .btn-disabled {
      background-color: var(--light-gray);
      color: var(--gray);
      cursor: not-allowed;
    }
    
    .pagination {
      display: flex;
      justify-content: center;
      margin-top: 2rem;
      gap: 0.5rem;
    }
    
    .pagination a {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      border-radius: 8px;
      background-color: white;
      color: var(--dark);
      text-decoration: none;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      transition: all 0.3s ease;
    }
    
    .pagination a:hover {
      background-color: var(--light-gray);
    }
    
    .pagination a.active {
      background-color: var(--primary);
      color: white;
    }
    
    .empty-results {
      text-align: center;
      padding: 3rem;
      color: var(--gray);
    }
    
    .empty-results i {
      font-size: 3rem;
      margin-bottom: 1rem;
      color: var(--light-gray);
    }
    
    .empty-results h3 {
      font-size: 1.5rem;
      margin-bottom: 0.5rem;
      color: var(--dark);
    }
    
    .toast {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: var(--success);
      color: white;
      padding: 1rem 1.5rem;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      display: flex;
      align-items: center;
      gap: 0.75rem;
      transform: translateY(100px);
      opacity: 0;
      transition: all 0.3s ease;
      z-index: 1000;
    }
    
    .toast.show {
      transform: translateY(0);
      opacity: 1;
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
      
      .med-card {
        padding: 30px 20px;
      }
      
      .med-image {
        height: 180px;
      }
      
      .med-details {
        padding: 1rem;
      }
      
      .med-name {
        font-size: 1rem;
      }
      
      .med-category {
        font-size: 0.7rem;
      }
      
      .med-price {
        font-size: 1.1rem;
      }
    }
    
    @media (max-width: 480px) {
      .logo-text {
        font-size: 20px;
      }
      
      .med-grid {
        grid-template-columns: 1fr;
      }
      
      .search-form {
        flex-direction: column;
        align-items: stretch;
      }
      
      .search-form input[type="text"], .search-form select {
        width: 100%;
      }
      
      .search-form button {
        width: 100%;
      }
    }
  </style>
</head>
<body>
<!-- Fixed Logo to link to dashboard -->
<div class="topnav">
  <a href="user-dashboard.php" class="logo">
    <div class="logo-icon">
      <i class="fas fa-heartbeat"></i>
    </div>
    <div class="logo-text">Medical Store</div>
  </a>
  <div class="user-info">
    <div class="welcome-text">Welcome, <?php echo isset($userData['username']) ? htmlspecialchars($userData['username']) : 'Guest'; ?></div>
    <div class="user-avatar">
      <?php 
        if (!empty($userData['profile_picture'])) {
          echo '<img src="data:image/jpeg;base64,' . base64_encode($userData['profile_picture']) . '" alt="Profile Picture" style="width: 100%; height: 100%; object-fit: cover;">';
        } else {
          echo '<span>' . htmlspecialchars(substr($userData['username'], 0, 1)) . '</span>';
        }
      ?>
    </div>
    <div class="cart-indicator">
      <a href="user-cart.php">
        <i class="fas fa-shopping-cart" style="font-size: 24px; color: var(--primary);"></i>
        <div class="cart-badge">
          <?php 
            $cartQuery = mysqli_query($conn, "SELECT COUNT(*) as cart_count FROM cart WHERE user_id = " . (int)$user_id);
            $cartData = mysqli_fetch_assoc($cartQuery);
            echo isset($cartData['cart_count']) ? $cartData['cart_count'] : '0';
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
<div class="container">
  <div class="search-container">
    <div class="search-header">
      <h2>
        <i class="fas fa-search"></i>
        Find Medicines
      </h2>
    </div>
    <form method="GET" class="search-form">
      <input type="text" name="search" placeholder="Search by medicine or category" value="<?php echo htmlspecialchars($searchTerm); ?>">
      <select name="category">
        <option value="">-- All Categories --</option>
        <?php
        while ($cat = mysqli_fetch_assoc($categoryResult)) {
          $selected = ($cat['CATEGORY'] === $selectedCategory) ? 'selected' : '';
          echo "<option value='" . htmlspecialchars($cat['CATEGORY']) . "' $selected>" . htmlspecialchars($cat['CATEGORY']) . "</option>";
        }
        ?>
      </select>
      <button type="submit">
        <i class="fas fa-search"></i> Search
      </button>
    </form>
  </div>
  
  <?php if (mysqli_num_rows($result) > 0): ?>
    <div class="med-grid">
      <?php 
        // Define category to image mapping
        $categoryImages = [
            'Accessory' => 'medicine-images/accessory.jpg',
            'Balm' => 'medicine-images/balm.jpg',
            'Bandage' => 'medicine-images/bandage.jpg',
            'Capsule' => 'medicine-images/capsule.jpg',
            'Cream' => 'medicine-images/cream.jpg',
            'Device' => 'medicine-images/device.jpg',
            'Drops' => 'medicine-images/drops.jpg',
            'Gel' => 'medicine-images/gel.jpg',
            'Injection' => 'medicine-images/injection.jpg',
            'Liquid' => 'medicine-images/liquid.jpg',
            'Lozenge' => 'medicine-images/lozenge.jpg',
            'Tablet' => 'medicine-images/tablet.jpg',
            'Oil' => 'medicine-images/oil.jpg',
            'Ointment' => 'medicine-images/ointment.jpg',
            'Paste' => 'medicine-images/paste.jpg',
            'Powder' => 'medicine-images/powder.jpg',
            'Sachet' => 'medicine-images/sachet.jpg',
            'Spray' => 'medicine-images/spray.jpg',
            'Supplement' => 'medicine-images/supplement.jpg',
            'Syrup' => 'medicine-images/syrup.jpg'
        ];
        
        $counter = 0; // Counter for first 10 medicines
        while ($row = mysqli_fetch_assoc($result)): 
          $med_id = $row['MED_ID'];
          $status = ($row['MED_QTY'] <= 0) ? 'unavailable' : 'available';
          $checkWishlist = mysqli_query($conn, "SELECT id FROM wishlist WHERE user_id = $user_id AND med_id = $med_id");
          $isWishlisted = mysqli_num_rows($checkWishlist) > 0;
          
          // Image selection logic - MODIFIED
          if (!empty($selectedCategory) || !empty($searchTerm) || $page > 1) {
              // Use category-based images when:
              // 1. A category is selected
              // 2. There's a search term
              // 3. We're on page > 1
              $category = $row['CATEGORY'];
              $imageUrl = isset($categoryImages[$category]) 
                  ? $categoryImages[$category] 
                  : 'medicine-images/default.jpg';
          } else {
              // Use numbered images for first page without filters
              $imageIndex = $counter + 1; // 1-10
              $imageUrl = "medicine-images/medicine{$imageIndex}.jpg";
          }
          
          $counter++;
      ?>
      <div class="med-card">
        <div class="med-image">
          <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="<?php echo htmlspecialchars($row['MED_NAME']); ?>">
        </div>
        <div class="med-details">
          <h3 class="med-name"><?php echo htmlspecialchars($row['MED_NAME']); ?></h3>
          <span class="med-category"><?php echo htmlspecialchars($row['CATEGORY']); ?></span>
          <div class="med-price">₹<?php echo number_format($row['MED_PRICE'], 2); ?></div>
          <div class="med-status">
            <?php if ($status === 'available'): ?>
              <span class="status-available">Available</span>
            <?php else: ?>
              <span class="status-unavailable">Out of Stock</span>
            <?php endif; ?>
          </div>
          <div class="med-action">
            <?php if ($status === 'available'): ?>
              <a href="add-to-cart.php?med_id=<?php echo $med_id; ?>" class="btn btn-primary">
                <i class="fas fa-cart-plus"></i> Add to Cart
              </a>
            <?php else: ?>
              <?php if ($isWishlisted): ?>
                <button class="btn btn-disabled">
                  <i class="fas fa-check-circle"></i> Wishlisted ✅
                </button>
              <?php else: ?>
                <a href="add-to-wishlist.php?med_id=<?php echo $med_id; ?>" class="btn btn-warning">
                  <i class="fas fa-bell"></i> Notify Me
                </a>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
    
    <div class="pagination">
      <?php
      for ($i = 1; $i <= $totalPages; $i++) {
        $isActive = ($i == $page) ? 'active' : '';
        $queryStr = http_build_query(array_merge($_GET, ['page' => $i]));
        echo "<a class='$isActive' href='?{$queryStr}'>$i</a>";
      }
      ?>
    </div>
  <?php else: ?>
    <div class="empty-results">
      <i class="fas fa-search"></i>
      <h3>No medicines found</h3>
      <p>Try adjusting your search or filter to find what you're looking for.</p>
    </div>
  <?php endif; ?>
</div>
</body>
</html>