<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Medicines Low on Stock</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary: #17a2b8;
      --secondary: rgb(255, 0, 0);
      --bg: #f5f6fa;
      --white: #ffffff;
      --dark: #2f3542;
      --shadow: rgba(0, 0, 0, 0.08);
    }

    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: var(--bg);
      color: var(--dark);
      font-size: 14px;
      min-height: 110vh; /* Slightly increased body height */
    }

    a {
      text-decoration: none;
      color: inherit;
    }

    .topnav {
      height: 45px;
      background: var(--white);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 1rem;
      box-shadow: 0 2px 6px var(--shadow);
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1001;
    }

    .menu-toggle {
      font-size: 1.2rem;
      cursor: pointer;
      color: var(--dark);
    }

    .topnav a {
      color: var(--primary);
      font-weight: 600;
      font-size: 14px;
    }

    .sidenav {
      height: 100%;
      width: 240px;
      position: fixed;
      z-index: 1000;
      top: 45px;
      left: 0;
      background: var(--white);
      overflow-y: auto;
      box-shadow: 2px 0 6px var(--shadow);
      padding-top: 20px;
      transition: transform 0.3s ease;
    }

    .sidenav.closed {
      transform: translateX(-100%);
    }

    .sidenav h2 {
      text-align: center;
      margin: 0.6rem;
      color: var(--dark);
      font-size: 1.1rem;
    }

    .sidenav a, .sidenav button {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 0.6rem 1.5rem;
      font-size: 14px;
      color: var(--dark);
      background: none;
      border: none;
      text-align: left;
      width: 100%;
      cursor: pointer;
    }

    .sidenav a i, .sidenav button i {
      min-width: 20px;
      text-align: center;
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
      padding-left: 2rem;
    }

    .dropdown-btn:after {
      content: " ▼";
      margin-left: auto;
    }

    .dropdown-btn.active:after {
      content: " ▲";
    }

    .main-content {
      margin-left: 260px;
      padding: 70px 1.5rem 2rem;
      transition: margin-left 0.3s ease;
      min-height: 90vh;
    }

    .sidenav.closed ~ .main-content {
      margin-left: 0;
    }

    .head {
      text-align: center;
    }

    .head h2 {
      color: var(--primary);
      margin-bottom: 1.2rem;
      font-size: 1.2rem;
    }

    table {
      max-width: 1500px;
      margin: 0 auto;
      width: 100%;
      border-collapse: collapse;
      background: var(--white);
      box-shadow: 0 3px 10px var(--shadow);
      border-radius: 6px;
      overflow: hidden;
      font-size: 13.5px;
    }

    table th, table td {
      padding: 10px 55px;
      text-align: left;
      color: var(--dark);
    }

    table th {
      background: var(--primary);
      color: var(--white);
    }

    table tr:nth-child(even) {
      background: #f0f3f5;
    }

    table tr:hover {
      background: #e2f4f7;
    }

    td[style*="color:red;"] {
      color: var(--secondary) !important;
      font-weight: bold;
    }

    @media (max-width: 768px) {
      .sidenav {
        width: 100%;
        position: absolute;
        height: auto;
        top: 45px;
      }

      .sidenav.closed {
        transform: translateX(-100%);
      }

      .main-content {
        margin-left: 0 !important;
        padding: 70px 1rem 2rem;
      }

      table {
        font-size: 12px;
      }
    }
  </style>
</head>
<body>

<div class="topnav">
  <span class="menu-toggle" onclick="toggleSidenav()"><i class="fas fa-bars"></i></span>
  <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="sidenav" id="sidenav">
  <h2>Medical Store</h2>

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
  <a href="admin-orders-view.php"><i class="fas fa-shopping-cart"></i> View New Sales</a>

  <button class="dropdown-btn"><i class="fas fa-chart-line"></i> Reports</button>
  <div class="dropdown-container">
    <a href="stockreport.php"><i class="fas fa-exclamation-triangle"></i> Low Stock</a>
    <a href="expiryreport.php"><i class="fas fa-calendar-times"></i> Expiry</a>
    <a href="admin-sales-summary.php"><i class="fas fa-receipt"></i> Transactions</a>
  </div>
</div>

<div class="main-content">
  <div class="head">
    <h2>MEDICINES LOW ON STOCK (LESS THAN 50)</h2>
  </div>

  <table>
    <tr>
      <th>Medicine ID</th>
      <th>Medicine Name</th>
      <th>Quantity Available</th>
      <th>Category</th>
      <th>Price</th>
    </tr>

    <?php
    include "config.php";
    $result = mysqli_query($conn, "CALL STOCK();");
    if ($result && mysqli_num_rows($result) > 0) {
      while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["med_id"]. "</td>";
        echo "<td>" . $row["med_name"] . "</td>";
        echo "<td style='color:red;'>" . $row["med_qty"]. "</td>";
        echo "<td>" . $row["category"]. "</td>";
        echo "<td>" . $row["med_price"] . "</td>";
        echo "</tr>";
      }
    }
    $conn->close();
    ?>
  </table>
</div>

<script>
  function toggleSidenav() {
    document.getElementById("sidenav").classList.toggle("closed");
  }

  var dropdown = document.getElementsByClassName("dropdown-btn");
  for (let i = 0; i < dropdown.length; i++) {
    dropdown[i].addEventListener("click", function () {
      this.classList.toggle("active");
      const dropdownContent = this.nextElementSibling;
      dropdownContent.style.display =
        dropdownContent.style.display === "block" ? "none" : "block";
    });
  }
</script>

</body>
</html>
