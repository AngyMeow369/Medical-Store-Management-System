<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add'])) {
  $id   = mysqli_real_escape_string($conn, $_POST['sid']);
  $name = mysqli_real_escape_string($conn, $_POST['sname']);
  $add  = mysqli_real_escape_string($conn, $_POST['sadd']);
  $phno = mysqli_real_escape_string($conn, $_POST['sphno']);
  $mail = mysqli_real_escape_string($conn, $_POST['smail']);

  $sql = "INSERT INTO suppliers (SUP_ID, SUP_NAME, SUP_ADD, SUP_PHNO, SUP_MAIL)
          VALUES ('$id', '$name', '$add', '$phno', '$mail')";

  if (mysqli_query($conn, $sql)) {
    header("Location: supplier-view.php");
    exit();
  } else {
    echo "<p style='text-align:center; color:red; font-weight:bold;'>Error: " . mysqli_error($conn) . "</p>";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Add Supplier</title>
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
      --danger: #dc3545;
    }

    * { box-sizing: border-box; }

    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: var(--bg);
      color: var(--text);
      height: 100vh;
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
      z-index: 1000;
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
      padding: 30px;
      height: calc(100vh - 60px);
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .form-section {
      background: var(--white);
      padding: 30px;
      box-shadow: 0 2px 8px var(--shadow);
      border-radius: 8px;
      width: 100%;
      max-width: 500px;
    }

    h2 {
      text-align: center;
      color: var(--primary);
      margin-bottom: 1.5rem;
    }

    .form-section label {
      display: block;
      margin-bottom: 6px;
      font-weight: 500;
    }

    .form-section input {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid var(--border);
      border-radius: 4px;
    }

    .form-section input[type="submit"] {
      background: var(--primary);
      color: var(--white);
      border: none;
      cursor: pointer;
      transition: background 0.3s;
    }

    .form-section input[type="submit"]:hover {
      background: #2c6c93;
    }
  </style>
</head>

<body>
  <div class="sidenav">
    <h2><i class="fas fa-clinic-medical"></i> Medical Store</h2>
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
    <a href="pos1.php"><i class="fas fa-shopping-cart"></i> Add New Sale</a>

    <button class="dropdown-btn"><i class="fas fa-chart-line"></i> Reports</button>
    <div class="dropdown-container">
      <a href="stockreport.php"><i class="fas fa-exclamation-triangle"></i> Low Stock</a>
      <a href="expiryreport.php"><i class="fas fa-calendar-times"></i> Soon to Expire</a>
      <a href="salesreport.php"><i class="fas fa-receipt"></i> Transactions</a>
    </div>
  </div>

  <div class="topnav">
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </div>

  <div class="main">
    <div class="form-section">
      <h2>Add Supplier Details</h2>
      <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
        <label for="sid">Supplier ID:</label>
        <input type="number" name="sid" required>

        <label for="sname">Supplier Company Name:</label>
        <input type="text" name="sname" required>

        <label for="sadd">Address:</label>
        <input type="text" name="sadd" required>

        <label for="sphno">Phone Number:</label>
        <input type="number" name="sphno" required>

        <label for="smail">Email Address:</label>
        <input type="email" name="smail" required>

        <input type="submit" name="add" value="Add Supplier">
      </form>
    </div>
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
