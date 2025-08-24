<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root {
  --primary: #4f46e5;
  --secondary: #6366f1;
  --accent: #818cf8;
  --bg: #f8fafc;
  --white: #ffffff;
  --dark: #1e293b;
  --light-gray: #f1f5f9;
  --medium-gray: #64748b;
  --border: #e2e8f0;
  --shadow: rgba(0, 0, 0, 0.08);
  --success: #10b981;
  --warning: #f59e0b;
  --danger: #ef4444;
  --info: #3b82f6;
}
body {
  margin: 0;
  font-family: 'Inter', sans-serif;
  background: var(--bg);
  color: var(--dark);
  line-height: 1.6;
}
a {
  text-decoration: none;
  color: inherit;
}
.topnav {
  height: 64px;
  background: var(--white);
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 24px;
  border-bottom: 1px solid var(--border);
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}
.topnav a {
  color: var(--primary);
  font-weight: 500;
  font-size: 0.875rem;
  padding: 8px 16px;
  border-radius: 6px;
  transition: all 0.2s;
}
.topnav a:hover {
  background-color: var(--light-gray);
}
.menu-icon {
  font-size: 1.25rem;
  cursor: pointer;
  color: var(--dark);
  padding: 8px;
  border-radius: 6px;
  transition: background-color 0.2s;
}
.menu-icon:hover {
  background-color: var(--light-gray);
}
.sidenav {
  height: 100%;
  width: 0;
  position: fixed;
  z-index: 1200;
  top: 0;
  left: 0;
  background: var(--white);
  overflow-x: hidden;
  transition: 0.3s;
  box-shadow: 0 10px 15px -3px var(--shadow);
  padding-top: 20px;
  border-right: 1px solid var(--border);
}
.sidenav h2, .sidenav p {
  text-align: center;
  margin: 0.5rem;
  color: var(--dark);
  font-weight: 600;
  font-size: 1.125rem;
}
.sidenav a, .sidenav button {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 24px;
  font-size: 0.875rem;
  color: var(--dark);
  background: none;
  border: none;
  text-align: left;
  width: 100%;
  cursor: pointer;
  transition: all 0.2s;
  font-weight: 500;
}
.sidenav a i, .sidenav button i {
  min-width: 20px;
  text-align: center;
  color: var(--medium-gray);
}
.sidenav a:hover, .sidenav button:hover {
  background-color: var(--light-gray);
  color: var(--primary);
}
.sidenav a:hover i, .sidenav button:hover i {
  color: var(--primary);
}
.dropdown-container {
  display: none;
  background: var(--light-gray);
}
.dropdown-container a {
  padding-left: 48px;
  font-size: 0.8125rem;
  font-weight: 400;
}
.dropdown-btn:after {
  content: " ▼";
  margin-left: auto;
  color: var(--medium-gray);
  font-size: 0.75rem;
}
.dropdown-btn.active:after {
  content: " ▲";
}
/* Main content area */
.main-content {
  padding: 24px;
  max-width: 1200px;
  margin: 0 auto;
}
/* Dashboard Header */
.dashboard-header {
  background: linear-gradient(135deg, var(--primary), var(--secondary));
  border-radius: 12px;
  padding: 32px;
  margin-bottom: 32px;
  color: white;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.dashboard-header h1 {
  margin: 0 0 8px 0;
  font-size: 2rem;
  font-weight: 700;
}
.dashboard-header p {
  margin: 0;
  opacity: 0.9;
  font-size: 1rem;
  max-width: 600px;
}
.dashboard-header .user-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: rgba(255, 255, 255, 0.2);
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 0.875rem;
  margin-top: 16px;
  backdrop-filter: blur(4px);
}
.refresh-btn {
  background: rgba(255, 255, 255, 0.2);
  border: none;
  color: white;
  padding: 8px 16px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 0.875rem;
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: all 0.2s;
}
.refresh-btn:hover {
  background: rgba(255, 255, 255, 0.3);
}
.refresh-btn i {
  animation: spin 2s linear infinite;
}
.refresh-btn.loading i {
  animation: spin 1s linear infinite;
}
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
/* Stats Overview */
.stats-overview {
  margin-bottom: 32px;
}
.stats-overview h2 {
  margin: 0 0 20px 0;
  font-size: 1.25rem;
  font-weight: 600;
  color: var(--dark);
}
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 20px;
}
.stat-box {
  background: var(--white);
  border-radius: 12px;
  padding: 24px;
  border: 1px solid var(--border);
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}
.stat-box:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 15px rgba(0, 0, 0, 0.08);
}
.stat-box::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 4px;
}
.stat-box.primary::before {
  background-color: var(--primary);
}
.stat-box.success::before {
  background-color: var(--success);
}
.stat-box.warning::before {
  background-color: var(--warning);
}
.stat-box.danger::before {
  background-color: var(--danger);
}
.stat-box .stat-icon {
  width: 48px;
  height: 48px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 16px;
  font-size: 1.25rem;
}
.stat-box.primary .stat-icon {
  background-color: rgba(79, 70, 229, 0.1);
  color: var(--primary);
}
.stat-box.success .stat-icon {
  background-color: rgba(16, 185, 129, 0.1);
  color: var(--success);
}
.stat-box.warning .stat-icon {
  background-color: rgba(245, 158, 11, 0.1);
  color: var(--warning);
}
.stat-box.danger .stat-icon {
  background-color: rgba(239, 68, 68, 0.1);
  color: var(--danger);
}
.stat-box .stat-value {
  font-size: 1.875rem;
  font-weight: 700;
  margin: 0 0 4px 0;
  line-height: 1.2;
}
.stat-box.primary .stat-value {
  color: var(--primary);
}
.stat-box.success .stat-value {
  color: var(--success);
}
.stat-box.warning .stat-value {
  color: var(--warning);
}
.stat-box.danger .stat-value {
  color: var(--danger);
}
.stat-box .stat-label {
  font-size: 0.875rem;
  color: var(--medium-gray);
  margin: 0;
}
.stat-box .stat-change {
  font-size: 0.75rem;
  margin-top: 12px;
  display: flex;
  align-items: center;
  gap: 4px;
}
.stat-box .stat-change.positive {
  color: var(--success);
}
.stat-box .stat-change.negative {
  color: var(--danger);
}
/* Quick Actions Section */
.quick-actions {
  margin-bottom: 32px;
}
.quick-actions h2 {
  margin: 0 0 20px 0;
  font-size: 1.25rem;
  font-weight: 600;
  color: var(--dark);
}
.actions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 20px;
}
.action-item {
  background: var(--white);
  border-radius: 12px;
  padding: 24px;
  border: 1px solid var(--border);
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  transition: all 0.3s ease;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}
.action-item:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 15px rgba(0, 0, 0, 0.08);
  border-color: var(--primary);
}
.action-item img, .action-item i {
  width: 56px;
  height: 56px;
  margin-bottom: 16px;
  object-fit: contain;
  color: var(--primary);
  font-size: 2rem;
}
.action-item div {
  font-weight: 600;
  color: var(--dark);
  font-size: 1rem;
  margin-bottom: 4px;
}
.action-item .description {
  font-weight: 400;
  color: var(--medium-gray);
  font-size: 0.875rem;
  margin: 0;
}
/* Responsive adjustments */
@media (max-width: 768px) {
  .main-content {
    padding: 16px;
  }
  
  .dashboard-header {
    padding: 24px;
    flex-direction: column;
    align-items: flex-start;
  }
  
  .dashboard-header h1 {
    font-size: 1.75rem;
  }
  
  .dashboard-header .refresh-btn {
    margin-top: 16px;
  }
  
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
  }
  
  .actions-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
  }
}
@media (max-width: 480px) {
  .stats-grid, .actions-grid {
    grid-template-columns: 1fr;
  }
  
  .dashboard-header h1 {
    font-size: 1.5rem;
  }
}
</style>
</head>
<body>
<div class="topnav">
  <span class="menu-icon" onclick="toggleNav()"><i class="fas fa-bars"></i></span>
  <a href="logout.php">Logout (Logged in as Admin)</a>
</div>
<div id="mySidenav" class="sidenav">
  <h2>Medical Store</h2>
  
  <a href="adminmainpage.php"><i class="fas fa-home"></i> Dashboard</a>
  <button class="dropdown-btn"><i class="fas fa-pills"></i> Medicines</button>
  <div class="dropdown-container">
    <a href="meds-add.php"><i class="fas fa-plus-circle"></i> Add Medicine</a>
    <a href="meds-view.php"><i class="fas fa-list"></i> Manage Medicines</a>
  </div>
  <button class="dropdown-btn"><i class="fas fa-truck"></i> Suppliers</button>
  <div class="dropdown-container">
    <a href="supplier-add.php"><i class="fas fa-user-plus"></i> Add Supplier</a>
    <a href="supplier-view.php"><i class="fas fa-users"></i> Manage Suppliers</a>
  </div>
  <button class="dropdown-btn"><i class="fas fa-box"></i> Stock Purchase</button>
  <div class="dropdown-container">
    <a href="purchase-add.php"><i class="fas fa-plus-square"></i> Add Purchase</a>
    <a href="purchase-view.php"><i class="fas fa-clipboard-list"></i> Manage Purchases</a>
  </div>
  <button class="dropdown-btn"><i class="fas fa-user-md"></i> Employees</button>
  <div class="dropdown-container">
    <a href="employee-add.php"><i class="fas fa-user-plus"></i> Add Employee</a>
    <a href="employee-view.php"><i class="fas fa-users"></i> Manage Employees</a>
  </div>
  <button class="dropdown-btn"><i class="fas fa-user"></i> Customers</button>
  <div class="dropdown-container">
    <a href="customer-view.php"><i class="fas fa-address-book"></i> Manage Customers</a>
  </div>
  <a href="sales-view.php"><i class="fas fa-file-invoice"></i> Sales Invoices</a>
  <a href="salesitems-view.php"><i class="fas fa-boxes"></i> Sold Products</a>
  <button class="dropdown-btn"><i class="fas fa-chart-line"></i> Reports</button>
  <div class="dropdown-container">
    <a href="stockreport.php"><i class="fas fa-exclamation-triangle"></i> Low Stock</a>
    <a href="expiryreport.php"><i class="fas fa-calendar-times"></i> Expiry</a>
    <a href="admin-sales-summary.php"><i class="fas fa-receipt"></i> Transactions</a>
  </div>
</div>
<div class="main-content">
  <div class="dashboard-header">
    <div>
      <h1>Admin Dashboard</h1>
      <p>Welcome back! Here's what's happening with your store today.</p>
      <div class="user-badge">
        <i class="fas fa-user-circle"></i> Admin User
      </div>
    </div>
    <button class="refresh-btn" id="refreshBtn">
      <i class="fas fa-sync-alt"></i> Refresh
    </button>
  </div>
  <div class="stats-overview">
    <h2>Store Overview</h2>
    <div class="stats-grid">
      <div class="stat-box primary">
        <div class="stat-icon">
          <i class="fas fa-pills"></i>
        </div>
        <div class="stat-value" id="totalProducts">Loading...</div>
        <div class="stat-label">Total Medicines</div>
        <div class="stat-change positive" id="productsChangeContainer">
          <i class="fas fa-arrow-up"></i> <span id="productsChange">Loading...</span>
        </div>
      </div>
      <div class="stat-box success">
        <div class="stat-icon">
          <i class="fas fa-shopping-cart"></i>
        </div>
        <div class="stat-value" id="ordersToday">Loading...</div>
        <div class="stat-label">Orders Today</div>
        <div class="stat-change positive">
          <i class="fas fa-arrow-up"></i> <span id="ordersChange">Loading...</span>
        </div>
      </div>
      <div class="stat-box warning">
        <div class="stat-icon">
          <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="stat-value" id="lowStockItems">Loading...</div>
        <div class="stat-label">Low Stock Items</div>
        <div class="stat-change negative" id="lowStockChangeContainer">
          <i class="fas fa-arrow-up"></i> <span id="lowStockChange">Loading...</span>
        </div>
      </div>
      <div class="stat-box danger">
        <div class="stat-icon">
          <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="stat-value" id="revenueToday">Loading...</div>
        <div class="stat-label">Total Revenue Today</div>
        <div class="stat-change positive">
          <i class="fas fa-arrow-up"></i> <span id="revenueChange">Loading...</span>
        </div>
      </div>
    </div>
  </div>
  <div class="quick-actions">
    <h2>Quick Actions</h2>
    <div class="actions-grid">
      <a href="inventory-view.php" class="action-item">
        <i class="fas fa-boxes"></i>
        <div>View Medicines</div>
        <div class="description">Manage medicine stock</div>
      </a>
      <a href="employee-view.php" class="action-item">
        <i class="fas fa-users"></i>
        <div>View Employees</div>
        <div class="description">Manage staff accounts</div>
      </a>
      <a href="admin-sales-summary.php" class="action-item">
        <i class="fas fa-file-invoice-dollar"></i>
        <div>View Sales Invoice</div>
        <div class="description">Track transaction history</div>
      </a>
      <a href="stockreport.php" class="action-item">
        <i class="fas fa-exclamation-triangle"></i>
        <div>Low Stock Alert</div>
        <div class="description">Monitor inventory levels</div>
      </a>
      <a href="admin-orders-view.php" class="action-item">
        <i class="fas fa-receipt"></i>
        <div>Orders & Transaction</div>
        <div class="description">View all order history</div>
      </a>
    </div>
  </div>
</div>
<script>
function toggleNav() {
  var nav = document.getElementById("mySidenav");
  nav.style.width = nav.style.width === "250px" ? "0" : "250px";
}
var dropdown = document.getElementsByClassName("dropdown-btn");
for (let i = 0; i < dropdown.length; i++) {
  dropdown[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var dropdownContent = this.nextElementSibling;
    dropdownContent.style.display = dropdownContent.style.display === "block" ? "none" : "block";
  });
}
// Function to fetch dashboard data
function fetchDashboardData() {
  console.log("Fetching dashboard data...");
  
  // Show loading state
  document.getElementById('totalProducts').textContent = 'Loading...';
  document.getElementById('ordersToday').textContent = 'Loading...';
  document.getElementById('lowStockItems').textContent = 'Loading...';
  document.getElementById('revenueToday').textContent = 'Loading...';
  
  // Add loading animation to refresh button
  const refreshBtn = document.getElementById('refreshBtn');
  refreshBtn.classList.add('loading');
  
  // Add timestamp to prevent caching
  const timestamp = new Date().getTime();
  
  fetch('get_dashboard_data.php?t=' + timestamp)
    .then(response => {
      console.log("Response status:", response.status);
      if (!response.ok) {
        throw new Error('Network response was not ok: ' + response.status);
      }
      return response.json();
    })
    .then(data => {
      console.log("Dashboard data received:", data);
      updateDashboard(data);
    })
    .catch(error => {
      console.error('Error fetching dashboard data:', error);
      // Use fallback data if fetch fails
      updateDashboard({
        totalProducts: 0,
        productsChange: '',
        ordersToday: 0,
        ordersChange: 'Data unavailable',
        lowStockItems: 0,
        lowStockChange: '',
        revenueToday: 0,
        revenueChange: 'Data unavailable'
      });
    })
    .finally(() => {
      // Remove loading animation from refresh button
      refreshBtn.classList.remove('loading');
    });
}
// Function to update dashboard with data
function updateDashboard(data) {
  console.log("Updating dashboard with data:", data);
  
  // Update the dashboard with real data
  document.getElementById('totalProducts').textContent = data.totalProducts.toLocaleString();
  
  // Handle products change - hide if empty
  const productsChangeContainer = document.getElementById('productsChangeContainer');
  const productsChange = document.getElementById('productsChange');
  if (data.productsChange === '') {
    productsChangeContainer.style.display = 'none';
  } else {
    productsChangeContainer.style.display = 'flex';
    productsChange.textContent = data.productsChange;
  }
  
  document.getElementById('ordersToday').textContent = data.ordersToday.toLocaleString();
  document.getElementById('ordersChange').textContent = data.ordersChange;
  
  document.getElementById('lowStockItems').textContent = data.lowStockItems.toLocaleString();
  
  // Handle low stock change - hide if empty
  const lowStockChangeContainer = document.getElementById('lowStockChangeContainer');
  const lowStockChange = document.getElementById('lowStockChange');
  if (data.lowStockChange === '') {
    lowStockChangeContainer.style.display = 'none';
  } else {
    lowStockChangeContainer.style.display = 'flex';
    lowStockChange.textContent = data.lowStockChange;
  }
  
  // Change dollar sign to rupees
  document.getElementById('revenueToday').textContent = '₹' + data.revenueToday.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
  document.getElementById('revenueChange').textContent = data.revenueChange;
}
// Load dashboard data when page loads
window.addEventListener('DOMContentLoaded', function() {
  console.log("DOM loaded, fetching dashboard data");
  fetchDashboardData();
  
  // Refresh data every 30 seconds (30000 milliseconds) for more frequent updates
  setInterval(fetchDashboardData, 30000);
  
  // Add click event to refresh button
  document.getElementById('refreshBtn').addEventListener('click', fetchDashboardData);
});
</script>
</body>
</html>
