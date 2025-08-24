<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Add Purchase</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <style>
    :root {
      --primary: #3A7CA5;
      --accent: #B2E0F5;
      --bg: #f0f8ff;
      --white: #ffffff;
      --text: #1c2e21;
      --shadow: rgba(0, 0, 0, 0.05);
      --border: #d0e3f0;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: var(--white);
      font-size: 14px;
      color: #333;
    }

    .sidenav {
      height: 100%;
      width: 230px;
      position: fixed;
      top: 0;
      left: 0;
      background: var(--white);
      box-shadow: 2px 0 6px var(--shadow);
      padding-top: 10px;
      overflow-y: auto;
      transition: all 0.3s ease;
      z-index: 1000;
    }

    .sidenav.collapsed {
      width: 0;
      padding: 0;
      overflow: hidden;
    }

    .sidenav h2, .sidenav a, .sidenav button, .dropdown-container {
      opacity: 1;
      transition: opacity 0.3s ease;
    }

    .sidenav.collapsed h2,
    .sidenav.collapsed a,
    .sidenav.collapsed button,
    .sidenav.collapsed .dropdown-container {
      opacity: 0;
      pointer-events: none;
    }

    .sidenav a, .sidenav button {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 20px;
      font-size: 0.85rem;
      color: #333;
      background: none;
      border: none;
      width: 100%;
      text-align: left;
      cursor: pointer;
      text-decoration: none;
      white-space: nowrap;
    }

    .sidenav a:hover, .sidenav button:hover {
      background: var(--primary);
      color: var(--white);
      border-radius: 0 25px 25px 0;
    }

    .dropdown-container {
      display: none;
      background: #ecf0f1;
    }

    .dropdown-container a {
      padding-left: 2.5rem;
    }

    .dropdown-btn:after {
      content: " \25BC";
      float: right;
    }

    .dropdown-btn.active:after {
      content: " \25B2";
    }

    .topnav {
      height: 50px;
      background: #fff;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 1rem;
      box-shadow: 0 2px 6px var(--shadow);
      position: fixed;
      top: 0;
      left: 230px;
      right: 0;
      transition: left 0.3s ease;
      z-index: 900;
    }

    .topnav.collapsed {
      left: 0;
    }

    .menu-icon {
      font-size: 1.1rem;
      cursor: pointer;
      color: var(--primary);
    }

    .topnav a {
      color: var(--primary);
      text-decoration: none;
      font-weight: bold;
      font-size: 0.85rem;
    }

    .main {
      margin-left: 230px;
      padding: 70px 20px 20px;
      transition: margin-left 0.3s ease;
    }

    .main.collapsed {
      margin-left: 0;
    }

    h2 {
      color: var(--primary);
      text-align: center;
      margin-bottom: 15px;
      font-size: 1.1rem;
    }

    form {
      background: #fff;
      max-width: 700px;
      margin: auto;
      padding: 20px;
      box-shadow: 0 4px 12px var(--shadow);
      border-radius: 10px;
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }

    .column {
      flex: 1 1 45%;
    }

    label {
      font-weight: bold;
      font-size: 0.85rem;
    }

    input[type="number"],
    input[type="date"] {
      width: 100%;
      padding: 7px;
      margin-top: 4px;
      margin-bottom: 10px;
      border: 1px solid var(--border);
      border-radius: 5px;
      font-size: 0.85rem;
    }

    input[type="submit"] {
      padding: 8px 18px;
      background-color: #28a745;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 0.9rem;
      margin: 0 auto;
    }

    input[type="submit"]:hover {
      background-color: #218838;
    }
  </style>
</head>
<body>
<div class="sidenav" id="sidenav">
  <h2><i class="fas fa-clinic-medical"></i> Medical Store</h2>
  <a href="adminmainpage.php"><i class="fas fa-home"></i> Dashboard</a>
  <button class="dropdown-btn"><i class="fas fa-pills"></i> Inventory</button>
  <div class="dropdown-container">
    <a href="inventory-add.php">Add Medicine</a>
    <a href="inventory-view.php">Manage Inventory</a>
  </div>
  <button class="dropdown-btn"><i class="fas fa-truck"></i> Suppliers</button>
  <div class="dropdown-container">
    <a href="supplier-add.php">Add Supplier</a>
    <a href="supplier-view.php">Manage Suppliers</a>
  </div>
  <button class="dropdown-btn"><i class="fas fa-box"></i> Stock Purchase</button>
  <div class="dropdown-container">
    <a href="purchase-add.php">Add Purchase</a>
    <a href="purchase-view.php">Manage Purchases</a>
  </div>
  <button class="dropdown-btn"><i class="fas fa-user-md"></i> Employees</button>
  <div class="dropdown-container">
    <a href="employee-add.php">Add Employee</a>
    <a href="employee-view.php">Manage Employees</a>
  </div>
  <button class="dropdown-btn"><i class="fas fa-user"></i> Customers</button>
  <div class="dropdown-container">
    <a href="customer-add.php">Add Customer</a>
    <a href="customer-view.php">Manage Customers</a>
  </div>
  <a href="sales-view.php"><i class="fas fa-file-invoice"></i> Sales Invoice</a>
  <a href="salesitems-view.php"><i class="fas fa-boxes"></i> Sold Products</a>
  <a href="admin-orders-view.php"><i class="fas fa-shopping-cart"></i> View New Sales</a>
  <button class="dropdown-btn"><i class="fas fa-chart-line"></i> Reports</button>
  <div class="dropdown-container">
    <a href="stockreport.php">Low Stock</a>
    <a href="expiryreport.php">Soon to Expire</a>
    <a href="salesreport.php">Transactions</a>
  </div>
</div>

<div class="topnav" id="topnav">
  <i class="fas fa-bars menu-icon" onclick="toggleSidebar()"></i>
  <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="main" id="main">
  <h2>Add Purchase</h2>
  <form method="post">
    <div class="column">
      <label>Purchase ID</label>
      <input type="number" name="pid" required>
      <label>Supplier ID</label>
      <input type="number" name="sid" required>
      <label>Medicine ID</label>
      <input type="number" name="mid" required>
      <label>Quantity</label>
      <input type="number" name="pqty" required>
    </div>
    <div class="column">
      <label>Cost</label>
      <input type="number" step="0.01" name="pcost" required>
      <label>Purchase Date</label>
      <input type="date" name="pdate" required>
      <label>MFG Date</label>
      <input type="date" name="mdate" required>
      <label>Expiry Date</label>
      <input type="date" name="edate" required>
    </div>
    <input type="submit" name="add" value="Add Purchase">
  </form>
</div>

<script>
  const dropdown = document.getElementsByClassName("dropdown-btn");
  for (let i = 0; i < dropdown.length; i++) {
    dropdown[i].addEventListener("click", function () {
      this.classList.toggle("active");
      const content = this.nextElementSibling;
      if (!document.getElementById("sidenav").classList.contains("collapsed")) {
        content.style.display = content.style.display === "block" ? "none" : "block";
      }
    });
  }

  function toggleSidebar() {
    const sidenav = document.getElementById("sidenav");
    const main = document.getElementById("main");
    const topnav = document.getElementById("topnav");

    sidenav.classList.toggle("collapsed");
    main.classList.toggle("collapsed");
    topnav.classList.toggle("collapsed");

    if (sidenav.classList.contains("collapsed")) {
      const dropdowns = document.getElementsByClassName("dropdown-container");
      for (let d of dropdowns) d.style.display = "none";
    }
  }
</script>
</body>
</html>
