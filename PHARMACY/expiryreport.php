<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Expiry Report</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    :root {
      --primary: #17a2b8;
      --secondary: #fd7e14;
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

    .sidenav {
      height: 100%;
      width: 240px;
      position: fixed;
      top: 45px;
      left: 0;
      background: var(--white);
      overflow-y: auto;
      box-shadow: 2px 0 6px var(--shadow);
      padding-top: 8px;
      transition: transform 0.3s ease;
      z-index: 1000;
      font-size: 14px;
    }

    .sidenav.closed {
      transform: translateX(-100%);
    }

    .sidenav h2 {
      text-align: center;
      margin: 0.6rem 0;
      font-size: 1.1rem;
      color: var(--dark);
    }

    .sidenav a,
    .sidenav button {
      display: block;
      padding: 0.6rem 1.2rem;
      font-size: 14px;
      color: var(--dark);
      background: none;
      border: none;
      text-align: left;
      width: 100%;
      cursor: pointer;
      text-decoration: none;
    }

    .sidenav a:hover,
    .sidenav button:hover {
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

    .dropdown-btn::after {
      content: " ▼";
      float: right;
    }

    .dropdown-btn.active::after {
      content: " ▲";
    }

    .head {
      margin-left: 250px;
      padding-top: 60px;
      text-align: center;
    }

    .sidenav.closed ~ .head,
    .sidenav.closed ~ .content {
      margin-left: 0;
    }

    .head h2 {
      color: var(--primary);
      font-size: 1.3rem;
    }

    .content {
      margin-left: 250px;
      padding: 1rem;
      transition: margin-left 0.3s ease;
    }

    table {
      border-collapse: collapse;
      width: 95%;
      margin: 1rem auto;
      background: var(--white);
      box-shadow: 0 3px 10px var(--shadow);
      border-radius: 6px;
      font-size: 13.5px;
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

    td[style*="color:red;"] {
      color: var(--secondary) !important;
      font-weight: bold;
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

  <div class="head">
    <h2>STOCK EXPIRING WITHIN 6 MONTHS</h2>
  </div>

  <div class="content">
    <table>
      <tr>
        <th>Purchase ID</th>
        <th>Supplier ID</th>
        <th>Medicine ID</th>
        <th>Quantity</th>
        <th>Cost</th>
        <th>Purchase Date</th>
        <th>Manufacture</th>
        <th>Expiry</th>
      </tr>
      <?php
        include "config.php";
        $query = "SELECT p_id, sup_id, med_id, p_qty, p_cost, pur_date, mfg_date, exp_date 
                  FROM purchase 
                  WHERE exp_date <= DATE_ADD(CURDATE(), INTERVAL 6 MONTH)";
        $result = mysqli_query($conn, $query);
        if ($result && mysqli_num_rows($result) > 0) {
          while($row = mysqli_fetch_assoc($result)) {
            $pur_date = date("d-m-Y", strtotime($row['pur_date']));
            $mfg_date = date("d-m-Y", strtotime($row['mfg_date']));
            $exp_date = date("d-m-Y", strtotime($row['exp_date']));
            echo "<tr>";
            echo "<td>{$row['p_id']}</td>";
            echo "<td>{$row['sup_id']}</td>";
            echo "<td>{$row['med_id']}</td>";
            echo "<td>{$row['p_qty']}</td>";
            echo "<td>{$row['p_cost']}</td>";
            echo "<td>{$pur_date}</td>";
            echo "<td>{$mfg_date}</td>";
            echo "<td style='color:red;'>{$exp_date}</td>";
            echo "</tr>";
          }
        }
        mysqli_close($conn);
      ?>
    </table>
  </div>

  <script>
    const dropdowns = document.getElementsByClassName("dropdown-btn");
    for (let i = 0; i < dropdowns.length; i++) {
      dropdowns[i].addEventListener("click", function () {
        this.classList.toggle("active");
        let content = this.nextElementSibling;
        content.style.display = content.style.display === "block" ? "none" : "block";
      });
    }

    function toggleSidenav() {
      document.getElementById("sidenav").classList.toggle("closed");
    }
  </script>

</body>
</html>
