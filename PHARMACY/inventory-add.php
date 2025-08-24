<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Medicine</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
:root {
  --primary: #17a2b8;
  --primary-light: rgba(26, 173, 195, 0.9);
  --accent: #10B981;
  --bg: #F9FAFB;
  --surface: #FFFFFF;
  --text: #374151;
  --text-light: #6B7280;
  --shadow: rgba(0, 0, 0, 0.06);
}

body {
  margin: 0;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: var(--bg);
  color: var(--text);
}

a {
  text-decoration: none;
  color: inherit;
}

.topnav {
  height: 60px;
  background: var(--surface);
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 1.5rem;
  box-shadow: 0 2px 8px var(--shadow);
  position: sticky;
  top: 0;
  z-index: 1000;
}

.menu-icon {
  font-size: 1.8rem;
  cursor: pointer;
  color: var(--primary);
  transition: transform 0.2s;
}

.menu-icon:hover {
  transform: scale(1.1);
}

.sidenav {
  height: 100%;
  width: 0;
  position: fixed;
  z-index: 1200;
  top: 0;
  left: 0;
  background: var(--surface);
  overflow-x: hidden;
  transition: 0.4s;
  box-shadow: 2px 0 12px var(--shadow);
  padding-top: 20px;
}

.sidenav.open {
  width: 220px;
}

.sidenav h2 {
  text-align: center;
  margin: 0.5rem;
  font-size: 1.1rem;
  color: var(--primary);
}

.sidenav a, .sidenav button {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 0.6rem 1.5rem;
  font-size: 0.95rem;
  color: var(--text);
  background: none;
  border: none;
  text-align: left;
  width: 100%;
  cursor: pointer;
  transition: background 0.2s, color 0.2s, transform 0.2s;
}

.sidenav a i, .sidenav button i {
  min-width: 20px;
  text-align: center;
}

.sidenav a:hover, .sidenav button:hover {
  background: var(--primary-light);
  color: var(--surface);
  transform: translateX(4px);
  border-radius: 0 20px 20px 0;
}

.dropdown-container {
  display: none;
  background: #EFF6FF;
}

.dropdown-container a {
  padding-left: 2.5rem;
}

.dropdown-btn:after {
  content: " \25BC";
  margin-left: auto;
}

.dropdown-btn.active:after {
  content: " \25B2";
}

.form-container {
  max-width: 750px;
  margin: 2rem auto;
  background: var(--surface);
  padding: 2rem;
  border-radius: 12px;
  box-shadow: 0 4px 12px var(--shadow);
}

.form-container form {
  display: flex;
  flex-wrap: wrap;
  gap: 1.5rem;
}

.form-container .column {
  flex: 1 1 45%;
}

.form-container label {
  font-weight: 600;
  display: block;
  margin-bottom: 0.3rem;
}

.form-container input, .form-container select {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #CBD5E1;
  border-radius: 6px;
  font-size: 0.9rem;
}

.form-container input[type="submit"] {
  background: var(--primary);
  color: var(--surface);
  font-weight: 600;
  cursor: pointer;
  margin-top: 1rem;
  width: 100%;
}

.form-container input[type="submit"]:hover {
  background: var(--primary-light);
}

.success, .error {
  text-align: center;
  margin-top: 1rem;
  font-size: 0.95rem;
}

.success { color: var(--accent); }
.error { color: #EF4444; }
</style>
</head>
<body>
<div class="topnav">
  <span class="menu-icon" onclick="toggleNav()"><i class="fas fa-bars"></i></span>
  <a href="logout.php">Logout</a>
</div>

<div id="mySidenav" class="sidenav">
  <h2>Medical Store</h2>
  <a href="javascript:void(0)" onclick="toggleNav()"><i class="fas fa-times"></i> Close</a>
  <a href="adminmainpage.php"><i class="fas fa-home"></i> Dashboard</a>
  <button class="dropdown-btn"><i class="fas fa-pills"></i> Inventory</button>
  <div class="dropdown-container">
    <a href="inventory-add.php"><i class="fas fa-plus-circle"></i> Add Medicine</a>
    <a href="inventory-view.php"><i class="fas fa-list"></i> Manage Inventory</a>
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
    <a href="customer-add.php"><i class="fas fa-user-plus"></i> Add Customer</a>
    <a href="customer-view.php"><i class="fas fa-address-book"></i> Manage Customers</a>
  </div>
  <a href="sales-view.php"><i class="fas fa-file-invoice"></i> Sales Invoices</a>
  <a href="salesitems-view.php"><i class="fas fa-boxes"></i> Sold Products</a>
  <a href="admin-orders-view.php"><i class="fas fa-shopping-cart"></i> View New Sale</a>
  <button class="dropdown-btn"><i class="fas fa-chart-line"></i> Reports</button>
  <div class="dropdown-container">
    <a href="stockreport.php"><i class="fas fa-exclamation-triangle"></i> Low Stock</a>
    <a href="expiryreport.php"><i class="fas fa-calendar-times"></i> Expiry</a>
    <a href="salesreport.php"><i class="fas fa-receipt"></i> Transactions</a>
  </div>
</div>

<div class="form-container">
  <form action="<?=$_SERVER['PHP_SELF']?>" method="post">
    <div class="column">
      <label for="medid">Medicine ID:</label>
      <input type="number" name="medid" required>
      <label for="medname">Medicine Name:</label>
      <input type="text" name="medname" required>
      <label for="qty">Quantity:</label>
      <input type="number" name="qty" required>
      <label for="cat">Category:</label>
      <select name="cat" required>
        <option>Tablet</option>
        <option>Capsule</option>
        <option>Syrup</option>
      </select>
    </div>
    <div class="column">
      <label for="sp">Price:</label>
      <input type="number" step="0.01" name="sp" required>
      <label for="loc">Location:</label>
      <input type="text" name="loc" required>
    </div>
    <input type="submit" name="add" value="Add Medicine">
  </form>

  <?php
  include "config.php";
  if (isset($_POST['add'])) {
      $id = mysqli_real_escape_string($conn, $_REQUEST['medid']);
      $name = mysqli_real_escape_string($conn, $_REQUEST['medname']);
      $qty = mysqli_real_escape_string($conn, $_REQUEST['qty']);
      $category = mysqli_real_escape_string($conn, $_REQUEST['cat']);
      $sprice = mysqli_real_escape_string($conn, $_REQUEST['sp']);
      $location = mysqli_real_escape_string($conn, $_REQUEST['loc']);
      $sql = "INSERT INTO meds VALUES ($id, '$name', $qty, '$category', $sprice, '$location')";
      if (mysqli_query($conn, $sql)) {
          echo "<div class='success'>Medicine details successfully added!</div>";
      } else {
          echo "<div class='error'>Error! Check details.</div>";
      }
  }
  $conn->close();
  ?>
</div>

<script>
function toggleNav() {
  const nav = document.getElementById("mySidenav");
  nav.classList.toggle("open");
}

const dropdowns = document.getElementsByClassName("dropdown-btn");
for (let i = 0; i < dropdowns.length; i++) {
  dropdowns[i].addEventListener("click", function() {
    this.classList.toggle("active");
    const dropdownContent = this.nextElementSibling;
    dropdownContent.style.display = dropdownContent.style.display === "block" ? "none" : "block";
  });
}
</script>
</body>
</html>
