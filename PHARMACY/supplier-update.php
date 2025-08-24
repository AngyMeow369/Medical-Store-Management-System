<?php
include "config.php";

$row = null;

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $qry = "SELECT * FROM suppliers WHERE sup_id = '$id'";
  $result = $conn->query($qry);

  if ($result && $result->num_rows > 0) {
    $row = $result->fetch_row();
  } else {
    echo "<script>alert('Supplier not found.'); window.location.href='supplier-view.php';</script>";
    exit();
  }
} else {
  echo "<script>alert('No supplier ID provided.'); window.location.href='supplier-view.php';</script>";
  exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update'])) {
  $id = $_POST['sid'];
  $name = $_POST['sname'];
  $add = $_POST['sadd'];
  $phno = $_POST['sphno'];
  $mail = $_POST['smail'];

  $sql = "UPDATE suppliers SET sup_name='$name', sup_add='$add', sup_phno='$phno', sup_mail='$mail' WHERE sup_id='$id'";

  if ($conn->query($sql)) {
    header("Location: supplier-view.php");
    exit();
  } else {
    $error_message = "Error! Unable to update.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Update Supplier</title>
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

    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: var(--bg);
    }

    .sidenav {
      height: 100vh;
      width: 250px;
      position: fixed;
      top: 0;
      left: 0;
      background: var(--white);
      box-shadow: 2px 0 6px var(--shadow);
      padding-top: 20px;
      z-index: 1000;
    }

    .sidenav h2 {
      color: var(--primary);
      text-align: center;
      font-size: 1.4rem;
      margin-bottom: 1rem;
    }

    .sidenav a, .sidenav button {
      display: block;
      color: #333;
      padding: 12px 20px;
      text-decoration: none;
      background: none;
      border: none;
      width: 100%;
      text-align: left;
      font-size: 1rem;
      cursor: pointer;
    }

    .sidenav a:hover, .sidenav button:hover {
      background-color: var(--primary);
      color: var(--white);
    }

    .dropdown-container {
      display: none;
      background: #ecf0f1;
    }

    .dropdown-container a {
      padding-left: 40px;
    }

    .dropdown-btn:after {
      content: " ▼";
      float: right;
    }

    .dropdown-btn.active:after {
      content: " ▲";
    }

    .topnav {
      position: fixed;
      top: 0;
      left: 250px;
      right: 0;
      height: 60px;
      background: var(--white);
      box-shadow: 0 2px 6px var(--shadow);
      display: flex;
      justify-content: flex-end;
      align-items: center;
      padding: 0 1.5rem;
      z-index: 1001;
    }

    .topnav a {
      text-decoration: none;
      color: var(--primary);
      font-weight: bold;
    }

    .main {
      margin-left: 250px;
      padding: 100px 20px 30px;
      max-width: 800px;
      margin-right: auto;
      margin-left: auto;
    }

    h2 {
      text-align: center;
      color: var(--primary);
    }

    .form-section {
      background: var(--white);
      padding: 20px;
      box-shadow: 0 2px 8px var(--shadow);
      border-radius: 8px;
      margin-top: 20px;
    }

    label {
      display: block;
      margin-bottom: 6px;
      font-weight: 500;
    }

    input[type="text"],
    input[type="number"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid var(--border);
      border-radius: 4px;
    }

    input[type="submit"] {
      background: var(--primary);
      color: var(--white);
      border: none;
      padding: 10px 20px;
      cursor: pointer;
      border-radius: 4px;
    }

    input[type="submit"]:hover {
      background: var(--accent);
      color: var(--text);
    }

    .error {
      color: red;
      text-align: center;
    }
  </style>
</head>
<body>

<div class="sidenav">
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
  <a href="pos1.php"><i class="fas fa-shopping-cart"></i> Add New Sale</a>
  <button class="dropdown-btn"><i class="fas fa-chart-line"></i> Reports</button>
  <div class="dropdown-container">
    <a href="stockreport.php">Low Stock</a>
    <a href="expiryreport.php">Soon to Expire</a>
    <a href="salesreport.php">Transactions</a>
  </div>
</div>

<div class="topnav">
  <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="main">
  <h2>Update Supplier Details</h2>
  <?php if (isset($error_message)) echo "<p class='error'>$error_message</p>"; ?>
  <div class="form-section">
    <form action="<?= $_SERVER['PHP_SELF'] . '?id=' . $row[0] ?>" method="post">
      <label for="sid">Supplier ID:</label>
      <input type="number" name="sid" value="<?= $row[0]; ?>" readonly>

      <label for="sname">Supplier Company Name:</label>
      <input type="text" name="sname" value="<?= $row[1]; ?>" required>

      <label for="sadd">Address:</label>
      <input type="text" name="sadd" value="<?= $row[2]; ?>" required>

      <label for="sphno">Phone Number:</label>
      <input type="number" name="sphno" value="<?= $row[3]; ?>" required>

      <label for="smail">Email Address:</label>
      <input type="text" name="smail" value="<?= $row[4]; ?>" required>

      <input type="submit" name="update" value="Update">
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
