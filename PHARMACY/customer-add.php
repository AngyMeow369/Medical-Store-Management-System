<?php
include "config.php";

$row = null;

if (isset($_POST['update'])) {
  $id = $_POST['cid'];
  $fname = $_POST['cfname'];
  $lname = $_POST['clname'];
  $age = $_POST['age'];
  $sex = $_POST['sex'];
  $phno = $_POST['phno'];
  $mail = $_POST['emid'];

  $sql = "UPDATE customer SET c_fname='$fname', c_lname='$lname', c_age='$age', c_sex='$sex', c_phno='$phno', c_mail='$mail' WHERE c_id='$id'";
  if ($conn->query($sql)) {
    header("Location: customer-view.php");
    exit();
  } else {
    $update_error = "Error! Unable to update.";
  }
} elseif (isset($_GET['id']) && is_numeric($_GET['id'])) {
  $id = $_GET['id'];
  $qry = "SELECT * FROM customer WHERE c_id='$id'";
  $result = $conn->query($qry);
  if ($result && $result->num_rows > 0) {
    $row = $result->fetch_row();
  } else {
    $update_error = "Customer not found.";
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Update Customer</title>
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

    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: var(--bg);
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

    .sidenav a, .sidenav button {
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
      transition: background 0.3s;
    }

    .sidenav a:hover, .sidenav button:hover {
      background: var(--primary);
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

    form {
      background: var(--white);
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
      box-shadow: 0 2px 8px var(--shadow);
      border-radius: 8px;
    }

    input[type="text"], input[type="number"], select {
      width: 100%;
      padding: 10px;
      margin-top: 6px;
      margin-bottom: 16px;
      border: 1px solid var(--border);
      border-radius: 4px;
    }

    input[type="submit"] {
      background-color: var(--primary);
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    input[type="submit"]:hover {
      background-color: var(--accent);
      color: var(--text);
    }

    .error {
      color: red;
      text-align: center;
      font-size: 0.9rem;
    }
  </style>
</head>
<body>

  <div class="sidenav">
    <h2><i class="fas fa-clinic-medical"></i> Medical Store</h2>
    <a href="adminmainpage.php"><i class="fas fa-home"></i> Dashboard</a>
    <button class="dropdown-btn"><i class="fas fa-boxes"></i> Inventory</button>
    <div class="dropdown-container">
      <a href="inventory-add.php">Add New Medicine</a>
      <a href="inventory-view.php">Manage Inventory</a>
    </div>
    <button class="dropdown-btn"><i class="fas fa-truck"></i> Suppliers</button>
    <div class="dropdown-container">
      <a href="supplier-add.php">Add New Supplier</a>
      <a href="supplier-view.php">Manage Suppliers</a>
    </div>
    <button class="dropdown-btn"><i class="fas fa-capsules"></i> Stock Purchase</button>
    <div class="dropdown-container">
      <a href="purchase-add.php">Add Purchase</a>
      <a href="purchase-view.php">Manage Purchases</a>
    </div>
    <button class="dropdown-btn"><i class="fas fa-user-md"></i> Employees</button>
    <div class="dropdown-container">
      <a href="employee-add.php">Add Employee</a>
      <a href="employee-view.php">Manage Employees</a>
    </div>
    <button class="dropdown-btn"><i class="fas fa-users"></i> Customers</button>
    <div class="dropdown-container">
      <a href="customer-add.php">Add New Customer</a>
      <a href="customer-view.php">Manage Customers</a>
    </div>
    <a href="sales-view.php"><i class="fas fa-file-invoice"></i> Sales Invoices</a>
    <a href="salesitems-view.php"><i class="fas fa-box"></i> Sold Products</a>
    <a href="pos1.php"><i class="fas fa-plus-square"></i> Add Sale</a>
    <button class="dropdown-btn"><i class="fas fa-chart-line"></i> Reports</button>
    <div class="dropdown-container">
      <a href="stockreport.php">Low Stock</a>
      <a href="expiryreport.php">Expiring Soon</a>
      <a href="salesreport.php">Transaction Reports</a>
    </div>
  </div>

  <div class="topnav">
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </div>

  <div class="main">
    <h2>Update Customer Details</h2>
    <?php if (isset($update_error)): ?>
      <p class="error"><?= $update_error ?></p>
    <?php endif; ?>
    <?php if ($row): ?>
    <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
      <label>Customer ID:</label>
      <input type="number" name="cid" value="<?= $row[0] ?>" readonly>

      <label>First Name:</label>
      <input type="text" name="cfname" value="<?= $row[1] ?>" required>

      <label>Last Name:</label>
      <input type="text" name="clname" value="<?= $row[2] ?>" required>

      <label>Age:</label>
      <input type="number" name="age" value="<?= $row[3] ?>" required>

      <label>Gender:</label>
      <input type="text" name="sex" value="<?= $row[4] ?>" required>

      <label>Phone Number:</label>
      <input type="number" name="phno" value="<?= $row[5] ?>" required>

      <label>Email ID:</label>
      <input type="text" name="emid" value="<?= $row[6] ?>" required>

      <input type="submit" name="update" value="Update">
    </form>
    <?php endif; ?>
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
