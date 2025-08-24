<?php include "config.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Stock Purchase List</title>
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

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: var(--bg);
      color: #333;
      font-size: 14px;
    }

    .sidenav {
      height: 100%;
      width: 230px;
      position: fixed;
      top: 0;
      left: 0;
      background: var(--white);
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

    .sidenav h2,
    .sidenav a,
    .sidenav button,
    .sidenav .dropdown-container {
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
      content: " ▼";
      float: right;
    }

    .dropdown-btn.active:after {
      content: " ▲";
    }

    .topnav {
      height: 50px;
      background: var(--white);
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
      max-width: 100%;
      overflow-x: auto;
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

    table {
      width: 100%;
      border-collapse: collapse;
      background: var(--white);
      box-shadow: 0 4px 12px var(--shadow);
      border-radius: 8px;
      overflow: hidden;
      font-size: 0.85rem;
      min-width: 900px;
    }

    table th, table td {
      padding: 10px 12px;
      text-align: center;
      border-bottom: 1px solid var(--border);
    }

    table th {
      background: var(--primary);
      color: var(--white);
      font-weight: 600;
    }

    table tr:hover {
      background-color: var(--hover);
    }

    .button1 {
      display: block;
      padding: 4px 8px;
      margin: 3px auto;
      border-radius: 5px;
      text-decoration: none;
      color: white;
      font-size: 0.75rem;
      width: 60px;
      text-align: center;
    }

    .edit-btn {
      background-color: #28a745;
    }

    .del-btn {
      background-color: #dc3545;
    }

    .edit-btn:hover {
      background-color: #218838;
    }

    .del-btn:hover {
      background-color: #c82333;
    }
  </style>
</head>
<body>

<!-- SIDENAV -->
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

<!-- TOPNAV -->
<div class="topnav" id="topnav">
  <i class="fas fa-bars menu-icon" onclick="toggleSidebar()"></i>
  <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<!-- MAIN -->
<div class="main" id="main">
  <h2>Stock Purchase List</h2>
  <table>
    <tr>
      <th>Purchase ID</th>
      <th>Supplier ID</th>
      <th>Medicine ID</th>
      <th>Medicine Name</th>
      <th>Quantity</th>
      <th>Cost</th>
      <th>Purchase Date</th>
      <th>Manufacturing Date</th>
      <th>Expiry Date</th>
      <th>Action</th>
    </tr>

    <?php
    $sql = "SELECT p_id, sup_id, med_id, p_qty, p_cost, pur_date, mfg_date, exp_date FROM purchase";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $sql1 = "SELECT med_name FROM meds WHERE med_id = ".$row["med_id"];
        $result1 = $conn->query($sql1);
        $medName = ($result1 && $result1->num_rows > 0) ? $result1->fetch_assoc()["med_name"] : "N/A";

        echo "<tr>";
        echo "<td>{$row['p_id']}</td>";
        echo "<td>{$row['sup_id']}</td>";
        echo "<td>{$row['med_id']}</td>";
        echo "<td>$medName</td>";
        echo "<td>{$row['p_qty']}</td>";
        echo "<td>{$row['p_cost']}</td>";
        echo "<td>{$row['pur_date']}</td>";
        echo "<td>{$row['mfg_date']}</td>";
        echo "<td>{$row['exp_date']}</td>";
        echo "<td>
                <a class='button1 edit-btn' href='purchase-update.php?pid={$row['p_id']}&sid={$row['sup_id']}&mid={$row['med_id']}'>Edit</a>
                <a class='button1 del-btn' href='purchase-delete.php?pid={$row['p_id']}&sid={$row['sup_id']}&mid={$row['med_id']}'>Delete</a>
              </td>";
        echo "</tr>";
      }
    }
    $conn->close();
    ?>
  </table>
</div>

<!-- SCRIPT -->
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

    // Hide all dropdowns if sidebar is collapsed
    if (side.classList.contains("collapsed")) {
      const dropdowns = document.getElementsByClassName("dropdown-container");
      for (let d of dropdowns) {
        d.style.display = "none";
      }
    }
  }
</script>

</body>
</html>
