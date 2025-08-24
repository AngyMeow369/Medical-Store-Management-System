<?php
include "config.php";

if (isset($_POST['add'])) {
  $id = mysqli_real_escape_string($conn, $_REQUEST['eid']);
  $fname = mysqli_real_escape_string($conn, $_REQUEST['efname']);
  $lname = mysqli_real_escape_string($conn, $_REQUEST['elname']);
  $bdate = mysqli_real_escape_string($conn, $_REQUEST['ebdate']);
  $age = mysqli_real_escape_string($conn, $_REQUEST['eage']);
  $sex = mysqli_real_escape_string($conn, $_REQUEST['esex']);
  $etype = mysqli_real_escape_string($conn, $_REQUEST['etype']);
  $jdate = mysqli_real_escape_string($conn, $_REQUEST['ejdate']);
  $sal = mysqli_real_escape_string($conn, $_REQUEST['esal']);
  $phno = mysqli_real_escape_string($conn, $_REQUEST['ephno']);
  $mail = mysqli_real_escape_string($conn, $_REQUEST['e_mail']);
  $add = mysqli_real_escape_string($conn, $_REQUEST['eadd']);

  $sql = "INSERT INTO employee (e_id, e_fname, e_lname, bdate, e_age, e_sex, e_type, e_jdate, e_sal, e_phno, e_mail, e_add)
          VALUES ($id, '$fname', '$lname', '$bdate', $age, '$sex', '$etype', '$jdate', '$sal', $phno, '$mail', '$add')";

  if (mysqli_query($conn, $sql)) {
    echo "<p class='success'>Employee successfully added!</p>";
  } else {
    echo "<p class='error'>Error! Check details or ensure the employee ID is unique.</p>";
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Add Employee</title>
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
    }

    .sidenav {
      height: 100%;
      width: 250px;
      position: fixed;
      top: 0;
      left: 0;
      background: var(--white);
      box-shadow: 2px 0 6px var(--shadow);
      padding-top: 20px;
      overflow-x: hidden;
    }

    .sidenav h2 {
      text-align: center;
      margin: 0.5rem;
      color: var(--primary);
      font-size: 1.3rem;
    }

    .sidenav a, .sidenav button {
      display: block;
      padding: 0.8rem 2rem;
      font-size: 1rem;
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
      border-radius: 0 25px 25px 0;
    }

    .dropdown-container {
      display: none;
      background: #ecf0f1;
    }

    .dropdown-container a {
      padding-left: 3rem;
    }

    .dropdown-btn:after {
      content: " ▼";
      float: right;
    }

    .dropdown-btn.active:after {
      content: " ▲";
    }

    .topnav {
      height: 60px;
      background: var(--white);
      display: flex;
      justify-content: flex-end;
      align-items: center;
      padding: 0 1.5rem;
      box-shadow: 0 2px 6px var(--shadow);
      position: fixed;
      top: 0;
      left: 250px;
      right: 0;
    }

    .topnav a {
      color: var(--primary);
      text-decoration: none;
      font-weight: bold;
    }

    .main {
      margin-left: 250px;
      padding: 90px 30px 30px;
    }

    h2 {
      color: var(--primary);
      text-align: center;
    }

    form {
      max-width: 900px;
      margin: 0 auto;
      background: var(--white);
      padding: 20px 30px;
      border-radius: 8px;
      box-shadow: 0 4px 12px var(--shadow);
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }

    form .column {
      flex: 1 45%;
      margin-right: 20px;
    }

    label {
      display: block;
      margin-bottom: 5px;
      font-weight: 500;
    }

    input, select {
      width: 100%;
      padding: 8px 10px;
      border: 1px solid var(--border);
      border-radius: 4px;
      font-size: 1rem;
      margin-bottom: 15px;
    }

    input[type="submit"] {
      background: var(--primary);
      color: var(--white);
      border: none;
      cursor: pointer;
      font-weight: bold;
      width: 200px;
      margin: 0 auto;
      display: block;
      padding: 10px;
      font-size: 1rem;
    }

    input[type="submit"]:hover {
      background: #3a7fd4;
    }

    p.success {
      color: green;
      text-align: center;
    }

    p.error {
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
    <a href="customer-add.php"><i class="fas fa-user-plus"></i> Add Customer</a>
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
  <h2>Add Employee Details</h2>

  <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
    <div class="column">
      <label for="eid">Employee ID:</label>
      <input type="number" name="eid" required>

      <label for="efname">First Name:</label>
      <input type="text" name="efname" required>

      <label for="elname">Last Name:</label>
      <input type="text" name="elname" required>

      <label for="ebdate">Date of Birth:</label>
      <input type="date" name="ebdate" required>

      <label for="eage">Age:</label>
      <input type="number" name="eage" required>

      <label for="esex">Gender:</label>
      <select name="esex" required>
        <option value="">Select</option>
        <option>Female</option>
        <option>Male</option>
        <option>Other</option>
      </select>
    </div>

    <div class="column">
      <label for="etype">Employee Type:</label>
      <select name="etype" required>
        <option value="">Select</option>
        <option>Pharmacist</option>
        <option>Manager</option>
      </select>

      <label for="ejdate">Date of Joining:</label>
      <input type="date" name="ejdate" required>

      <label for="esal">Salary:</label>
      <input type="number" step="0.01" name="esal" required>

      <label for="ephno">Phone Number:</label>
      <input type="number" name="ephno" required>

      <label for="e_mail">Email ID:</label>
      <input type="email" name="e_mail" required>

      <label for="eadd">Address:</label>
      <input type="text" name="eadd" required>
    </div>

    <input type="submit" name="add" value="Add Employee">
  </form>
</div>

<script>
  const dropdown = document.getElementsByClassName("dropdown-btn");
  for (let i = 0; i < dropdown.length; i++) {
    dropdown[i].addEventListener("click", function () {
      this.classList.toggle("active");
      const content = this.nextElementSibling;
      content.style.display = content.style.display === "block" ? "none" : "block";
    });
  }
</script>

</body>
</html>
