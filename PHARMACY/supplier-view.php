<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Suppliers</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
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
      font-family: 'Segoe UI', sans-serif;
      background: var(--bg);
      color: var(--text);
    }

    .sidenav {
      width: 250px;
      height: 100vh;
      background: var(--white);
      position: fixed;
      top: 0;
      left: 0;
      overflow-y: auto;
      padding-top: 20px;
      box-shadow: 2px 0 10px var(--shadow);
    }

    .sidenav h2 {
      color: var(--primary);
      text-align: center;
      font-size: 1.2rem;
      margin-bottom: 0.3rem;
    }

    .sidenav a,
    .sidenav button {
      display: block;
      color: var(--text);
      padding: 12px 20px;
      text-decoration: none;
      font-size: 1rem;
      border: none;
      background: none;
      width: 100%;
      text-align: left;
      cursor: pointer;
      transition: background 0.3s, color 0.3s;
    }

    .sidenav a:hover,
    .sidenav button:hover {
      background-color: var(--primary);
      color: var(--white);
    }

    .dropdown-container {
      display: none;
      background-color: var(--bg);
    }

    .dropdown-container a {
      padding-left: 40px;
    }

    .dropdown-btn:after {
      content: " \25BC";
      float: right;
    }

    .dropdown-btn.active:after {
      content: " \25B2";
    }

    .topnav {
      margin-left: 250px;
      height: 60px;
      background: var(--white);
      display: flex;
      justify-content: flex-end;
      align-items: center;
      padding: 0 20px;
      box-shadow: 0 2px 4px var(--shadow);
    }

    .topnav a {
      color: var(--primary);
      text-decoration: none;
      font-weight: 600;
    }

    .main {
      margin-left: 250px;
      padding: 80px 30px 30px;
    }

    h2 {
      text-align: center;
      color: var(--primary);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: var(--white);
      box-shadow: 0 2px 8px var(--shadow);
    }

    th, td {
      padding: 12px;
      border: 1px solid var(--border);
      text-align: center;
    }

    th {
      background: var(--primary);
      color: var(--white);
    }

    .edit-btn, .del-btn {
      background: var(--primary);
      color: var(--white);
      padding: 6px 12px;
      border: none;
      border-radius: 4px;
      text-decoration: none;
      margin: 2px;
    }

    .edit-btn:hover, .del-btn:hover {
      background: var(--accent);
      color: var(--text);
    }
  </style>
</head>

<body>
  <div class="sidenav">
    <h2><i class="fas fa-clinic-medical"></i> Medical Store</h2>
    <a href="adminmainpage.php"><i class="fas fa-home"></i> Dashboard</a>
    <button class="dropdown-btn"><i class="fas fa-boxes"></i> Inventory</button>
    <div class="dropdown-container">
      <a href="inventory-add.php"><i class="fas fa-plus"></i> Add Medicine</a>
      <a href="inventory-view.php"><i class="fas fa-warehouse"></i> Manage Inventory</a>
    </div>
    <button class="dropdown-btn"><i class="fas fa-truck"></i> Suppliers</button>
    <div class="dropdown-container">
      <a href="supplier-add.php"><i class="fas fa-plus-circle"></i> Add Supplier</a>
      <a href="supplier-view.php"><i class="fas fa-list"></i> Manage Suppliers</a>
    </div>
    <button class="dropdown-btn"><i class="fas fa-capsules"></i> Stock Purchase</button>
    <div class="dropdown-container">
      <a href="purchase-add.php"><i class="fas fa-cart-plus"></i> Add Purchase</a>
      <a href="purchase-view.php"><i class="fas fa-box-open"></i> Manage Purchases</a>
    </div>
    <button class="dropdown-btn"><i class="fas fa-user-md"></i> Employees</button>
    <div class="dropdown-container">
      <a href="employee-add.php"><i class="fas fa-user-plus"></i> Add Employee</a>
      <a href="employee-view.php"><i class="fas fa-users"></i> Manage Employees</a>
    </div>
    <button class="dropdown-btn"><i class="fas fa-users"></i> Customers</button>
    <div class="dropdown-container">
      <a href="customer-add.php"><i class="fas fa-user-plus"></i> Add Customer</a>
      <a href="customer-view.php"><i class="fas fa-user-friends"></i> Manage Customers</a>
    </div>
    <a href="sales-view.php"><i class="fas fa-file-invoice"></i> Sales Invoices</a>
    <a href="salesitems-view.php"><i class="fas fa-box"></i> Sold Products</a>
    <a href="pos1.php"><i class="fas fa-plus-square"></i> Add Sale</a>
    <button class="dropdown-btn"><i class="fas fa-chart-line"></i> Reports</button>
    <div class="dropdown-container">
      <a href="stockreport.php"><i class="fas fa-exclamation-triangle"></i> Low Stock</a>
      <a href="expiryreport.php"><i class="fas fa-hourglass-end"></i> Expiring Soon</a>
      <a href="salesreport.php"><i class="fas fa-chart-bar"></i> Transactions</a>
    </div>
  </div>

  <div class="topnav">
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </div>

  <div class="main">
    <h2>Suppliers List</h2>
    <table>
      <tr>
        <th>Supplier ID</th>
        <th>Company Name</th>
        <th>Address</th>
        <th>Phone Number</th>
        <th>Email</th>
        <th>Action</th>
      </tr>
      <?php
      include "config.php";
      $sql = "SELECT sup_id, sup_name, sup_add, sup_phno, sup_mail FROM suppliers";
      $result = $conn->query($sql);
      if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>{$row['sup_id']}</td>";
          echo "<td>{$row['sup_name']}</td>";
          echo "<td>{$row['sup_add']}</td>";
          echo "<td>{$row['sup_phno']}</td>";
          echo "<td>{$row['sup_mail']}</td>";
          echo "<td>
            <a class='edit-btn' href='supplier-update.php?id={$row['sup_id']}'>Edit</a>
            <a class='del-btn' href='supplier-delete.php?id={$row['sup_id']}'>Delete</a>
          </td>";
          echo "</tr>";
        }
      }
      $conn->close();
      ?>
    </table>
  </div>

  <script>
    const dropdowns = document.getElementsByClassName("dropdown-btn");
    for (let i = 0; i < dropdowns.length; i++) {
      dropdowns[i].addEventListener("click", function () {
        this.classList.toggle("active");
        const content = this.nextElementSibling;
        content.style.display = content.style.display === "block" ? "none" : "block";
      });
    }
  </script>
</body>

</html>
