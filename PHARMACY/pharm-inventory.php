<?php
include "config.php";
session_start();

$ename = "Unknown";
if (isset($_SESSION['user'])) {
    $sql1 = "SELECT E_FNAME FROM EMPLOYEE WHERE E_ID='{$_SESSION['user']}'";
    $result1 = $conn->query($sql1);
    if ($result1 && $result1->num_rows > 0) {
        $row1 = $result1->fetch_row();
        $ename = $row1[0];
    }
}

if (isset($_POST['search']) && !empty(trim($_POST['valuetosearch']))) {
    $search = mysqli_real_escape_string($conn, trim($_POST['valuetosearch']));
    
    if (is_numeric($search)) {
        // If input is numeric, search by med_id
        $query = "SELECT med_id as medid, med_name as medname, med_qty as medqty, category as medcategory, med_price as medprice, location_rack as medlocation 
                  FROM meds 
                  WHERE med_id = '$search'";
    } else {
        // Otherwise, search by medicine name
        $query = "SELECT med_id as medid, med_name as medname, med_qty as medqty, category as medcategory, med_price as medprice, location_rack as medlocation 
                  FROM meds 
                  WHERE med_name LIKE '%$search%'";
    }
    $search_result = mysqli_query($conn, $query) or die(mysqli_error($conn));
} else {
    $query = "SELECT med_id as medid, med_name as medname, med_qty as medqty, category as medcategory, med_price as medprice, location_rack as medlocation FROM meds";
    $search_result = mysqli_query($conn, $query) or die(mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Medicine Inventory</title>
<style>
:root {
    --primary: #6c5ce7;
    --secondary: #00cec9;
    --card-bg: rgba(255,255,255,0.85);
    --text-color: #2d3436;
    --navbar-bg: #ecf0f1;
    --sidebar-bg: #2f3542;
}
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #f5f6fa, #dcdde1);
    color: var(--text-color);
    min-height: 100vh;
}

/* Navbar */
.navbar {
    position: fixed;
    top: 0; left: 0; right: 0;
    background: var(--navbar-bg);
    color: var(--text-color);
    padding: 15px 25px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    z-index: 200;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.navbar .menu-icon {
    font-size: 28px;
    cursor: pointer;
    transition: transform 0.3s;
    color: var(--text-color);
}
.navbar .menu-icon:hover {
    transform: scale(1.2);
}
.navbar a {
    color: var(--text-color);
    text-decoration: none;
    font-weight: 600;
}

/* Sidebar */
.sidenav {
    height: 100%;
    width: 0;
    position: fixed;
    z-index: 250;
    top: 0; left: 0;
    background: var(--sidebar-bg);
    overflow-x: hidden;
    transition: 0.4s;
    padding-top: 80px;
    color: #fff;
}
.sidenav a, .sidenav button.dropdown-btn {
    padding: 15px 40px;
    font-size: 18px;
    color: #ecf0f1;
    display: block;
    border: none;
    background: none;
    text-align: left;
    cursor: pointer;
    transition: background 0.3s, padding-left 0.3s;
}
.sidenav a:hover, .sidenav button:hover {
    background: rgba(255,255,255,0.1);
    border-radius: 8px;
    padding-left: 50px;
}
.dropdown-container {
    display: none;
    background: rgba(255,255,255,0.08);
    border-left: 3px solid var(--secondary);
    margin-left: 20px;
    border-radius: 8px;
}
.dropdown-container a {
    padding-left: 60px;
    color: #ecf0f1;
}

/* Overlay */
#overlay {
    position: fixed;
    display: none;
    width: 100%; height: 100%;
    top: 0; left: 0;
    background: rgba(0,0,0,0.5);
    z-index: 100;
}

/* Main content */
.main {
    margin-top: 90px;
    padding: 30px;
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
}

/* Headline */
.head h2 {
    text-align: center;
    font-size: 38px;
    margin-bottom: 30px;
}

/* Card container */
.inventory-card {
    background: var(--card-bg);
    border-radius: 15px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    padding: 30px;
}

/* Search form */
.inventory-card form {
    display: flex;
    justify-content: center;
    margin-bottom: 25px;
}
.inventory-card input[type="text"] {
    padding: 12px 20px;
    width: 55%;
    border-radius: 30px;
    border: 1px solid #ccc;
    outline: none;
    margin-right: 15px;
}
.inventory-card input[type="submit"] {
    padding: 12px 30px;
    border-radius: 30px;
    border: none;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: #fff;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.4s, transform 0.3s;
}
.inventory-card input[type="submit"]:hover {
    background: linear-gradient(135deg, var(--secondary), var(--primary));
    transform: scale(1.05);
}

/* Table */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}
table th, table td {
    padding: 15px;
    text-align: center;
    border-bottom: 1px solid rgba(0,0,0,0.1);
}
table th {
    background: rgba(0,0,0,0.07);
}
table tr:hover {
    background: rgba(0,0,0,0.05);
}
</style>
</head>
<body>

<div class="navbar">
    <span class="menu-icon" onclick="openNav()">&#9776;</span>
    <a href="logout.php">Logout (signed in as <?php echo htmlspecialchars($ename); ?>)</a>
</div>

<div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" onclick="closeNav()" style="font-size:28px;position:absolute;top:15px;right:25px;">&times;</a>
    <a href="pharmmainpage.php">Dashboard</a>
    <a href="pharm-inventory.php">View Inventory</a>
    <!-- <a href="pharm-pos1.php">Add New Sale</a>
    <button class="dropdown-btn">Customers</button>
    <div class="dropdown-container">
        <a href="pharm-customer.php">Add New Customer</a>
        <a href="pharm-customer-view.php">View Customers</a> -->
    </div>
</div>
<div id="overlay" onclick="closeNav()"></div>

<div class="main">
    <div class="head"><h2>MEDICINE INVENTORY</h2></div>
    <div class="inventory-card">
        <form method="post">
            <input type="text" name="valuetosearch" placeholder="Search by medicine name or ID...">
            <input type="submit" name="search" value="Search">
        </form>
        <table>
            <tr>
                <th>Medicine ID</th>
                <th>Medicine Name</th>
                <th>Quantity Available</th>
                <th>Category</th>
                <th>Price</th>
                <th>Location in Store</th>
            </tr>
            <?php
            if ($search_result && $search_result->num_rows > 0) {
                while($row = $search_result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["medid"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["medname"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["medqty"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["medcategory"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["medprice"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["medlocation"]) . "</td>";
                    echo "</tr>";
                }
            }
            ?>
        </table>
    </div>
</div>

<script>
function openNav() {
    document.getElementById("mySidenav").style.width = "260px";
    document.getElementById("overlay").style.display = "block";
}
function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
    document.getElementById("overlay").style.display = "none";
}
var dropdown = document.getElementsByClassName("dropdown-btn");
for (var i = 0; i < dropdown.length; i++) {
    dropdown[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var dropdownContent = this.nextElementSibling;
        dropdownContent.style.display = dropdownContent.style.display === "block" ? "none" : "block";
    });
}
</script>
</body>
</html>
