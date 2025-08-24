<?php
include "config.php";

if (isset($_GET['pid']) && isset($_GET['sid']) && isset($_GET['mid'])) {
    $pid = $_GET['pid'];
    $sid = $_GET['sid'];
    $mid = $_GET['mid'];

    $qry1 = "SELECT * FROM purchase WHERE p_id='$pid' AND sup_id='$sid' AND med_id='$mid'";
    $result = $conn->query($qry1);
    $row = $result->fetch_row();
}

if (isset($_POST['update'])) {
    $pid = $_POST['pid'];
    $sid = $_POST['sid'];
    $mid = $_POST['mid'];
    $qty = $_POST['pqty'];
    $cost = $_POST['pcost'];
    $pdate = $_POST['pdate'];
    $mdate = $_POST['mdate'];
    $edate = $_POST['edate'];

    $sql = "UPDATE purchase SET p_cost='$cost', p_qty='$qty', pur_date='$pdate', mfg_date='$mdate', exp_date='$edate' 
            WHERE p_id='$pid' AND sup_id='$sid' AND med_id='$mid'";

    if ($conn->query($sql)) {
        header("Location: purchase-view.php");
        exit();
    } else {
        $error = "Error updating record.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Update Purchase</title>
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
      background: #fff;
      color: #333;
      font-size: 14px;
    }
    .sidenav {
      height: 100%;
      width: 230px;
      position: fixed;
      top: 0;
      left: 0;
      background: #fff;
      box-shadow: 2px 0 6px var(--shadow);
      padding-top: 15px;
      overflow-y: auto;
      transition: width 0.3s ease;
      z-index: 200;
    }
    .sidenav.collapsed {
      width: 0;
      overflow: hidden;
      padding: 0;
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
      padding: 0.6rem 1.2rem;
      font-size: 0.9rem;
      color: #333;
      background: none;
      border: none;
      text-align: left;
      width: 100%;
      cursor: pointer;
      text-decoration: none;
      white-space: nowrap;
    }
    .sidenav a:hover, .sidenav button:hover {
      background: var(--primary);
      color: #fff;
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
      content: " ▼";
      float: right;
    }
    .dropdown-btn.active:after {
      content: " ▲";
    }
    .topnav {
      height: 50px;
      background: #fff;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 1.2rem;
      box-shadow: 0 2px 6px var(--shadow);
      position: fixed;
      top: 0;
      left: 230px;
      right: 0;
      transition: left 0.3s ease;
      z-index: 100;
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
      font-size: 0.9rem;
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
      margin-bottom: 20px;
      font-size: 1.3rem;
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
    }
    input[type="number"],
    input[type="date"] {
      width: 100%;
      padding: 7px;
      margin-top: 5px;
      margin-bottom: 15px;
      border: 1px solid var(--border);
      border-radius: 5px;
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
    .error {
      text-align: center;
      color: red;
      margin-bottom: 20px;
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
  <h2>Update Purchase</h2>
  <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
  <form method="post">
    <div class="column">
      <label>Purchase ID</label>
      <input type="number" name="pid" value="<?= $row[0] ?>" readonly>
      <label>Supplier ID</label>
      <input type="number" name="sid" value="<?= $row[1] ?>" readonly>
      <label>Medicine ID</label>
      <input type="number" name="mid" value="<?= $row[2] ?>" readonly>
      <label>Quantity</label>
      <input type="number" name="pqty" value="<?= $row[3] ?>">
    </div>
    <div class="column">
      <label>Cost</label>
      <input type="number" step="0.01" name="pcost" value="<?= $row[4] ?>">
      <label>Purchase Date</label>
      <input type="date" name="pdate" value="<?= $row[5] ?>">
      <label>MFG Date</label>
      <input type="date" name="mdate" value="<?= $row[6] ?>">
      <label>Expiry Date</label>
      <input type="date" name="edate" value="<?= $row[7] ?>">
    </div>
    <input type="submit" name="update" value="Update">
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
    const side = document.getElementById("sidenav");
    side.classList.toggle("collapsed");
    document.getElementById("topnav").classList.toggle("collapsed");
    document.getElementById("main").classList.toggle("collapsed");
    if (side.classList.contains("collapsed")) {
      const dropdowns = document.getElementsByClassName("dropdown-container");
      for (let d of dropdowns) d.style.display = "none";
    }
  }
</script>
</body>
</html>
