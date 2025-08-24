<?php
session_start();
include "config.php";

$ename = "";
if (isset($_SESSION['user'])) {
    $sql = "SELECT E_FNAME FROM EMPLOYEE WHERE E_ID='{$_SESSION['user']}'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_row();
        $ename = $row[0];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="nav2.css">
<link rel="stylesheet" type="text/css" href="form3.css">
<link rel="stylesheet" type="text/css" href="table2.css">
<title>New Sales</title>
</head>
<body>
<div class="sidenav">
    <h2 style="font-family:Arial; color:white; text-align:center;"> Medical Store Management System </h2>
    <p style="margin-top:-20px;color:white;line-height:1;font-size:12px;text-align:center">Developed by</p>
    <a href="pharmmainpage.php">Dashboard</a>
    <a href="pharm-inventory.php">View Inventory</a>
    <a href="pharm-pos1.php">Add New Sale</a>
    <button class="dropdown-btn">Customers<i class="down"></i></button>
    <div class="dropdown-container">
        <a href="pharm-customer.php">Add New Customer</a>
        <a href="pharm-customer-view.php">View Customers</a>
    </div>
</div>
<div class="topnav">
    <a href="PharmLogout.php">Logout (signed in as <?php echo htmlspecialchars($ename); ?>)</a>
</div>
<center><div class="head"><h2>POINT OF SALE</h2></div></center>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
    <center>
    <select id="cName" name="cName">
        <option value="0" selected>*Select Customer ID (only once for a customer's sales)</option>
        <?php
        $qry1 = "SELECT c_fname FROM customer";
        $result1 = $conn->query($qry1);
        if ($result1 && $result1->num_rows > 0) {
            while ($row1 = $result1->fetch_assoc()) {
                echo "<option value='" . $row1["c_fname"] . "'>" . $row1["c_fname"] . "</option>";
            }
        }
        ?>
    </select>
    &nbsp;&nbsp;
    <input type="submit" name="custadd" value="Add to Proceed">
</form>
<?php
if (isset($_POST['custadd']) && isset($_POST['cid']) && $_POST['cid'] != "0") {
    $cid = $_POST['cid'];
    $qry2 = "INSERT INTO sales(c_id,e_id) VALUES ('$cid','{$_SESSION['user']}')";
    $conn->query($qry2);
}
?>
<form method="post">
    <select id="med" name="med">
        <option value="0" selected>Select Medicine</option>
        <?php
        $qry3 = "SELECT med_name FROM meds";
        $result3 = $conn->query($qry3);
        if ($result3 && $result3->num_rows > 0) {
            while ($row4 = $result3->fetch_assoc()) {
                echo "<option value='" . $row4["med_name"] . "'>" . $row4["med_name"] . "</option>";
            }
        }
        ?>
    </select>
    &nbsp;&nbsp;
    <input type="submit" name="search" value="Search">
</form><br><br><br>
<?php
if (isset($_POST['search']) && !empty($_POST['med']) && $_POST['med'] != "0") {
    $med = $_POST['med'];
    $qry4 = "SELECT * FROM meds WHERE med_name='$med'";
    $result4 = $conn->query($qry4);
    if ($result4 && $result4->num_rows > 0) {
        $row4 = $result4->fetch_row();
?>
<div class="one row" style="margin-right:160px;">
<form method="post">
    <div class="column">
        <label for="medid">Medicine ID:</label>
        <input type="number" name="medid" value="<?php echo htmlspecialchars($row4[0]); ?>" readonly><br><br>
        <label for="mdname">Medicine Name:</label>
        <input type="text" name="mdname" value="<?php echo htmlspecialchars($row4[1]); ?>" readonly><br><br>
    </div>
    <div class="column">
        <label for="mcat">Category:</label>
        <input type="text" name="mcat" value="<?php echo htmlspecialchars($row4[3]); ?>" readonly><br><br>
        <label for="mloc">Location:</label>
        <input type="text" name="mloc" value="<?php echo htmlspecialchars($row4[5]); ?>" readonly><br><br>
    </div>
    <div class="column">
        <label for="mqty">Quantity Available:</label>
        <input type="number" name="mqty" value="<?php echo htmlspecialchars($row4[2]); ?>" readonly><br><br>
        <label for="mprice">Price of One Unit:</label>
        <input type="number" name="mprice" value="<?php echo htmlspecialchars($row4[4]); ?>" readonly><br><br>
    </div>
    <label for="mcqty">Quantity Required:</label>
    <input type="number" name="mcqty" required min="1">
    &nbsp;&nbsp;&nbsp;
    <input type="submit" name="add" value="Add Medicine">
</form>
</div>
<?php
    } else {
        echo "<p style='color:red;'>⚠️ No medicine found. Please select a valid medicine and try again.</p>";
    }
}
?>
<?php
if (isset($_POST['add'])) {
    $qry5 = "SELECT sale_id FROM sales ORDER BY sale_id DESC LIMIT 1";
    $result5 = $conn->query($qry5);
    $sid = ($result5 && $result5->num_rows > 0) ? $result5->fetch_row()[0] : 0;

    $mid = (int)$_POST['medid'];
    $aqty = (int)$_POST['mqty'];
    $qty = (int)$_POST['mcqty'];
    $unit_price = (float)$_POST['mprice'];

    if ($qty > $aqty || $qty <= 0) {
        echo "<p style='color:red;'>QUANTITY INVALID! Requested: $qty, Available: $aqty</p>";
    } else {
        $price = $unit_price * $qty;
        $qry6 = "INSERT INTO sales_items(sale_id, med_id, sale_qty, tot_price) VALUES($sid, $mid, $qty, $price)";
        if ($conn->query($qry6)) {
            echo "<br><br><center><a class='button1 view-btn' href='pharm-pos2.php?sid=$sid'>View Order</a></center>";
        } else {
            echo "<p style='color:red;'>Error adding medicine to sale.</p>";
        }
    }
}
?>
<script>
var dropdown = document.getElementsByClassName("dropdown-btn");
for (let i = 0; i < dropdown.length; i++) {
    dropdown[i].addEventListener("click", function () {
        this.classList.toggle("active");
        var dropdownContent = this.nextElementSibling;
        dropdownContent.style.display = dropdownContent.style.display === "block" ? "none" : "block";
    });
}
</script>
</body>
</html>
