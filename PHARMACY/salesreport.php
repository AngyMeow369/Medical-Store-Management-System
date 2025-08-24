<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Transaction Reports</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<style>
:root {
  --primary: #17a2b8;
  --secondary: #fd7e14;
  --bg: #f5f6fa;
  --white: #ffffff;
  --dark: #2f3542;
  --shadow: rgba(0,0,0,0.08);
}
body {
  margin: 0;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: var(--bg);
  color: var(--dark);
}
.sidenav {
  height: 100%;
  width: 250px;
  position: fixed;
  top: 0;
  left: 0;
  background: var(--white);
  overflow-x: hidden;
  box-shadow: 2px 0 6px var(--shadow);
  padding-top: 20px;
}
.sidenav h2, .sidenav p {
  text-align: center;
  margin: 0.5rem;
  color: var(--dark);
}
.sidenav a, .sidenav button {
  display: block;
  padding: 0.8rem 2rem;
  font-size: 1rem;
  color: var(--dark);
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
  position: fixed;
  top: 0;
  left: 250px;
  right: 0;
  height: 60px;
  background: var(--white);
  display: flex;
  align-items: center;
  justify-content: flex-end;
  padding: 0 1.5rem;
  box-shadow: 0 2px 6px var(--shadow);
  z-index: 100;
}
.main {
  margin-left: 250px;
  padding: 80px 20px 20px 20px; /* top padding to avoid topnav */
}
.main h2 {
  color: var(--primary);
  text-align: center;
}
form {
  display: inline-block;
  margin: 1rem auto;
  background: var(--white);
  padding: 1rem 2rem;
  border-radius: 8px;
  box-shadow: 0 4px 12px var(--shadow);
}
form p {
  margin: 10px 0;
}
form input[type="submit"] {
  background: var(--primary);
  color: var(--white);
  border: none;
  padding: 8px 16px;
  border-radius: 5px;
  cursor: pointer;
}
form input[type="submit"]:hover {
  background: var(--secondary);
}
table {
  border-collapse: collapse;
  width: 95%;
  margin: 2rem auto;
  background: var(--white);
  box-shadow: 0 4px 12px var(--shadow);
  border-radius: 8px;
  overflow: hidden;
}
th, td {
  padding: 12px 15px;
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

<div class="sidenav">
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
  <a href="admin-orders-view.php"><i class="fas fa-shopping-cart"></i> View New Sale</a>

  <button class="dropdown-btn"><i class="fas fa-chart-line"></i> Reports</button>
  <div class="dropdown-container">
    <a href="stockreport.php"><i class="fas fa-exclamation-triangle"></i> Low Stock</a>
    <a href="expiryreport.php"><i class="fas fa-calendar-times"></i> Soon to Expire</a>
    <a href="admin-sales-summary.php"><i class="fas fa-receipt"></i> Transactions</a>
  </div>
</div>

<div class="topnav">
  <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="main">
  <h2>TRANSACTION REPORTS</h2>

  <div style="text-align:center;">
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
      <p>
        <label for="start">Start Date:</label>
        <input type="date" name="start" required>
      </p>
      <p>
        <label for="end">End Date:</label>
        <input type="date" name="end" required>
      </p>
      <input type="submit" name="submit" value="View Records">
    </form>
  </div>

<?php
include "config.php";
if(isset($_POST['submit'])) {
  $start=$_POST['start'];
  $end=$_POST['end'];

  $res=mysqli_query($conn,"SELECT P_AMT('$start','$end') AS PAMT");
  $pamt = mysqli_fetch_assoc($res)['PAMT'] ?? 0;

  $res=mysqli_query($conn,"SELECT S_AMT('$start','$end') AS SAMT;");
  $samt = mysqli_fetch_assoc($res)['SAMT'] ?? 0;

  $profit = $samt - $pamt;
  $profits = number_format($profit, 2);

  echo '<table>
  <tr><th>Purchase ID</th><th>Supplier ID</th><th>Medicine ID</th><th>Quantity</th><th>Date of Purchase</th><th>Cost (Rs)</th></tr>';
  $sql = "SELECT p_id,sup_id,med_id,p_qty,p_cost,pur_date FROM purchase 
          WHERE pur_date >= '$start' AND pur_date <= '$end';";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $pur_date = date("d-m-Y", strtotime($row['pur_date']));
      echo "<tr><td>{$row['p_id']}</td><td>{$row['sup_id']}</td><td>{$row['med_id']}</td><td>{$row['p_qty']}</td><td>{$pur_date}</td><td>{$row['p_cost']}</td></tr>";
    }
  }
  echo "<tr><td colspan='5'><b>Total</b></td><td>Rs. $pamt</td></tr></table>";

  echo '<table>
  <tr><th>Sale ID</th><th>Customer ID</th><th>Employee ID</th><th>Date</th><th>Sale Amount (Rs)</th></tr>';
  $sql = "SELECT sale_id, c_id, s_date, s_time, total_amt, e_id FROM sales
          WHERE s_date >= '$start' AND s_date <= '$end';";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $s_date = date("d-m-Y", strtotime($row['s_date']));
      echo "<tr><td>{$row['sale_id']}</td><td>{$row['c_id']}</td><td>{$row['e_id']}</td><td>{$s_date}</td><td>{$row['total_amt']}</td></tr>";
    }
  }
  echo "<tr><td colspan='4'><b>Total</b></td><td>Rs. $samt</td></tr></table>";

  echo "<table><tr><td><b>Transaction Profit</b></td><td>Rs. $profits</td></tr></table>";
}
?>
</div>

<script>
var dropdown = document.getElementsByClassName("dropdown-btn");
for (let i = 0; i < dropdown.length; i++) {
  dropdown[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var dropdownContent = this.nextElementSibling;
    dropdownContent.style.display = dropdownContent.style.display === "block" ? "none" : "block";
  });
}
</script>
</body>
</html>
