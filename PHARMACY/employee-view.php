<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Employee List</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <style>
    :root {
      --primary: #4a90e2;
      --secondary: #6c757d;
      --bg: #fdfdfd;
      --white: #ffffff;
      --hover: #f1f5f9;
      --shadow: rgba(0, 0, 0, 0.05);
      --border: #dee2e6;
    }

    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: var(--bg);
      color: #333;
      font-size: 14px;
    }

    .sidenav {
      height: 100vh;
      width: 230px;
      position: fixed;
      top: 0;
      left: 0;
      background: var(--white);
      box-shadow: 2px 0 6px var(--shadow);
      padding-top: 16px;
      overflow-y: auto;
      transition: all 0.3s ease;
      z-index: 1000;
    }

    .sidenav.collapsed {
      width: 0;
      padding: 0;
      overflow: hidden;
    }

    .sidenav h2 {
      text-align: center;
      margin: 1rem 0;
      color: var(--primary);
      font-size: 1.4rem;
      padding: 0 1rem;
      word-wrap: break-word;
      z-index: 1002;
      position: relative;
    }

    .sidenav h2 i {
      margin-right: 8px;
    }

    .sidenav a, .sidenav button {
      display: block;
      padding: 0.6rem 1.8rem;
      font-size: 0.95rem;
      color: #333;
      background: none;
      border: none;
      text-align: left;
      width: 100%;
      cursor: pointer;
      text-decoration: none;
    }

    .sidenav a:hover, .sidenav button:hover {
      background: var(--primary);
      color: var(--white);
      border-radius: 0 20px 20px 0;
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
      justify-content: space-between;
      align-items: center;
      padding: 0 1rem;
      box-shadow: 0 2px 6px var(--shadow);
      position: fixed;
      top: 0;
      left: 230px;
      right: 0;
      z-index: 999;
      transition: left 0.3s ease;
    }

    .topnav.collapsed {
      left: 0;
    }

    .topnav .toggle-btn {
      font-size: 1.2rem;
      cursor: pointer;
      color: var(--primary);
    }

    .topnav a {
      color: #333;
      text-decoration: none;
      font-weight: bold;
      font-size: 0.95rem;
    }

    .main-content {
      margin-left: 230px;
      padding: 70px 1rem 2rem 1rem;
      transition: margin-left 0.3s ease;
    }

    .main-content.collapsed {
      margin-left: 0;
    }

    .main-content h2 {
      color: var(--primary);
      text-align: center;
      margin-bottom: 15px;
      font-size: 1.1rem;
    }

    table {
      border-collapse: collapse;
      width: 100%;
      background: var(--white);
      box-shadow: 0 4px 12px var(--shadow);
      border-radius: 6px;
      overflow: hidden;
      font-size: 0.88rem;
    }

    th, td {
      padding: 10px 12px;
      text-align: left;
    }

    th {
      background: var(--primary);
      color: var(--white);
    }

    tr:nth-child(even) {
      background: #f0f3f5;
    }

    tr:hover {
      background: #e2f4f7;
    }

    .button1 {
      padding: 4px 8px;
      border: none;
      border-radius: 4px;
      margin: 2px;
      text-decoration: none;
      color: white;
      font-size: 0.85rem;
    }

    .edit-btn { background-color: #28a745; }
    .del-btn { background-color: #dc3545; }
  </style>
</head>
<body>

<div class="sidenav" id="sidebar">
  <h2><i class="fas fa-notes-medical"></i>Medical Store</h2>

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

  <a href="sales-view.php"><i class="fas fa-file-invoice"></i> Sales Invoice</a>
  <a href="salesitems-view.php"><i class="fas fa-boxes"></i> Sold Products</a>
  <a href="admin-orders-view.php"><i class="fas fa-shopping-cart"></i> View New Sales</a>

  <button class="dropdown-btn"><i class="fas fa-chart-line"></i> Reports</button>
  <div class="dropdown-container">
    <a href="stockreport.php"><i class="fas fa-exclamation-triangle"></i> Low Stock</a>
    <a href="expiryreport.php"><i class="fas fa-calendar-times"></i> Soon to Expire</a>
    <a href="admin-sales-summary.php"><i class="fas fa-receipt"></i> Transactions</a>
  </div>
</div>

<div class="topnav" id="topnav">
  <span class="toggle-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></span>
  <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="main-content" id="main">
  <h2>EMPLOYEE LIST</h2>

  <?php
  include "config.php";
  $sql = "SELECT e_id, e_fname, e_lname, bdate, e_age, e_type, e_jdate, e_sal, e_phno, e_mail, e_add FROM employee WHERE e_id <> 1";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr>
      <th>ID</th>
      <th>First Name</th>
      <th>Last Name</th>
      <th>DOB</th>
      <th>Age</th>
      <th>Type</th>
      <th>Joining</th>
      <th>Salary</th>
      <th>Phone</th>
      <th>Email</th>
      <th>Address</th>
      <th>Action</th>
    </tr>";
    while($row = $result->fetch_assoc()) {
      echo "<tr>
        <td>{$row['e_id']}</td>
        <td>{$row['e_fname']}</td>
        <td>{$row['e_lname']}</td>
        <td>{$row['bdate']}</td>
        <td>{$row['e_age']}</td>
        <td>{$row['e_type']}</td>
        <td>{$row['e_jdate']}</td>
        <td>{$row['e_sal']}</td>
        <td>{$row['e_phno']}</td>
        <td>{$row['e_mail']}</td>
        <td>{$row['e_add']}</td>
        <td>
          <a class='button1 edit-btn' href='employee-update.php?id={$row['e_id']}'>Edit</a>
          <a class='button1 del-btn' href='employee-delete.php?id={$row['e_id']}' onclick=\"return confirm('Are you sure to delete?');\">Delete</a>
        </td>
      </tr>";
    }
    echo "</table>";
  } else {
    echo "<p>No employees found.</p>";
  }
  $conn->close();
  ?>
</div>

<script>
  // Sidebar Dropdowns
  const dropdowns = document.getElementsByClassName("dropdown-btn");
  for (let i = 0; i < dropdowns.length; i++) {
    dropdowns[i].addEventListener("click", function () {
      this.classList.toggle("active");
      const container = this.nextElementSibling;
      container.style.display = container.style.display === "block" ? "none" : "block";
    });
  }

  // Sidebar Toggle
  function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    const main = document.getElementById("main");
    const topnav = document.getElementById("topnav");

    sidebar.classList.toggle("collapsed");
    main.classList.toggle("collapsed");
    topnav.classList.toggle("collapsed");
  }
</script>

</body>
</html>
