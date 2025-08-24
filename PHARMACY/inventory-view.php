<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Medicines Inventory</title>
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

.topnav a {
  color: var(--primary);
  font-weight: 600;
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

.head h2 {
  margin-top: 2rem;
  color: var(--primary);
  font-size: 1.2rem;
}

#table1 {
  border-collapse: collapse;
  margin: 1rem auto;
  width: 92%;
  font-size: 0.85rem;
  background: var(--surface);
  box-shadow: 0 4px 12px var(--shadow);
  border-radius: 8px;
  overflow: hidden;
}

#table1 th, #table1 td {
  padding: 0.5rem;
  text-align: center;
  border-bottom: 1px solid #E5E7EB;
}

#table1 th {
  background: var(--primary);
  color: var(--surface);
  text-transform: uppercase;
}

#table1 tr:hover {
  background: #F1F5F9;
}

.button1 {
  padding: 0.3rem 0.6rem;
  margin: 0 0.1rem;
  border: none;
  border-radius: 5px;
  font-weight: 600;
  font-size: 0.75rem;
  cursor: pointer;
  color: var(--surface);
  text-decoration: none;
}

.edit-btn {
  background: var(--accent);
}

.del-btn {
  background: #EF4444;
}

.edit-btn:hover {
  background: #059669;
}

.del-btn:hover {
  background: #DC2626;
}

.search-container {
  text-align: center;
  margin: 1rem auto;
}

.search-container input {
  padding: 0.5rem;
  width: 230px;
  border: 1px solid #CBD5E1;
  border-radius: 5px;
  font-size: 0.85rem;
}

.search-container input:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 2px var(--primary-light);
}
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
    <a href="customer-view.php"><i class="fas fa-address-book"></i> Manage Customers</a>
  </div>
  <a href="sales-view.php"><i class="fas fa-file-invoice"></i> Sales Invoices</a>
  <a href="salesitems-view.php"><i class="fas fa-boxes"></i> Sold Products</a>
  <a href="admin-orders-view.php"><i class="fas fa-shopping-cart"></i> View New Sale</a>
  <button class="dropdown-btn"><i class="fas fa-chart-line"></i> Reports</button>
  <div class="dropdown-container">
    <a href="stockreport.php"><i class="fas fa-exclamation-triangle"></i> Low Stock</a>
    <a href="expiryreport.php"><i class="fas fa-calendar-times"></i> Expiry</a>
    <a href="admin-sales-summary.php"><i class="fas fa-receipt"></i> Transactions</a>
  </div>
</div>

<center>
  <div class="head">
    <h2>MEDICINE INVENTORY</h2>
  </div>
</center>

<div class="search-container">
  <input type="text" id="searchInput" placeholder="Search by any field...">
</div>

<table id="table1">
  <tr>
    <th>Medicine ID</th>
    <th>Medicine Name</th>
    <th>Quantity Available</th>
    <th>Category</th>
    <th>Price</th>
    <th>Location in Store</th>
    <th>Action</th>
  </tr>
  <?php
  include "config.php";
  $sql = "SELECT med_id, med_name, med_qty, category, med_price, location_rack FROM meds";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>{$row['med_id']}</td>";
          echo "<td>{$row['med_name']}</td>";
          echo "<td>{$row['med_qty']}</td>";
          echo "<td>{$row['category']}</td>";
          echo "<td>{$row['med_price']}</td>";
          echo "<td>{$row['location_rack']}</td>";
          echo "<td>";
          echo "<a class='button1 edit-btn' href='inventory-update.php?id={$row['med_id']}'>Edit</a>";
          echo "<a class='button1 del-btn' href='inventory-delete.php?id={$row['med_id']}'>Delete</a>";
          echo "</td>";
          echo "</tr>";
      }
  }
  $conn->close();
  ?>
</table>

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

const searchInput = document.getElementById("searchInput");
searchInput.addEventListener("keyup", function() {
  const filter = this.value.toLowerCase();
  const rows = document.querySelectorAll("#table1 tr:not(:first-child)");
  rows.forEach(row => {
    row.style.display = row.textContent.toLowerCase().includes(filter) ? "" : "none";
  });
});
</script>
</body>
</html>
