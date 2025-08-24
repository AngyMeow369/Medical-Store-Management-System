<?php
include "config.php";
session_start();

$sql = "SELECT E_FNAME FROM EMPLOYEE WHERE E_ID='$_SESSION[user]'";
$result = $conn->query($sql);
$row = $result->fetch_row();
$ename = $row[0];
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
    <h2 style="font-family:Arial; color:white; text-align:center;">Medical Store Management System</h2>
    <p style="margin-top:-20px;color:white;line-height:1;font-size:12px;text-align:center">Developed by, Abhishek Sharma, 2021</p>
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
    <a href="logout.php">Logout (signed in as <?php echo $ename; ?>)</a>
</div>

<center>
    <div class="head">
        <h2>SALES INVOICE</h2>
    </div>
</center>

<table align='right' id='table1'>
    <tr>
        <th>Medicine ID</th>
        <th>Medicine Name</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Total Price</th>
        <th>Action</th>
    </tr>

<?php
$sid = isset($_GET['sid']) ? $_GET['sid'] : null;

if (empty($sid)) {
    $sql = "SHOW TABLE STATUS LIKE 'sales'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $sid = $row['Auto_increment'] - 1;
}

if (!empty($sid)) {
    $qry1 = "SELECT med_id, sale_qty, tot_price FROM sales_items WHERE sale_id = $sid";
    $result1 = $conn->query($qry1);
    $sum = 0;

    if ($result1 && $result1->num_rows > 0) {
        while ($row1 = $result1->fetch_assoc()) {
            $medid = $row1["med_id"];
            $qry2 = "SELECT med_name, med_price FROM meds WHERE med_id = $medid";
            $result2 = $conn->query($qry2);
            $row2 = $result2 ? $result2->fetch_row() : null;

            echo "<tr>";
            echo "<td>" . $row1["med_id"] . "</td>";
            echo "<td>" . ($row2 ? $row2[0] : "Unknown") . "</td>";
            echo "<td>" . $row1["sale_qty"] . "</td>";
            echo "<td>" . ($row2 ? $row2[1] : "N/A") . "</td>";
            echo "<td>" . $row1["tot_price"] . "</td>";
            echo "<td align=center>";
            echo "<a class='button1 del-btn' href='pharm-pos-delete.php?mid=" . $row1['med_id'] . "&slid=" . $sid . "'>Delete</a>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6' style='text-align:center;'>No items found for this sale.</td></tr>";
    }
}
?>
</table>

<div class="one" style="background-color:white;">
    <form method="post">
        <a class='button1 view-btn' href='pharm-pos1.php?sid=<?php echo $sid; ?>'>Go Back to Sales Page</a>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type='submit' name='custadd' value='Complete Order'><br>
    </form>
</div>

<?php
if (isset($_POST['custadd'])) {
    mysqli_query($conn, "SET @p0=$sid;");
    $res = mysqli_query($conn, "CALL TOTAL_AMT(@p0, @p1);") or die(mysqli_error($conn));
    $res = mysqli_query($conn, "SELECT @p1 AS TOTAL;");

    if ($res) {
        $row = mysqli_fetch_assoc($res);
        $tot = $row['TOTAL'];

        echo "<table align='right' id='table1'>
            <tr style='background-color: #f2f2f2;'>
                <td>Total</td>
                <td>$tot</td>
            </tr>
        </table>";
    }
}
?>

<script>
var dropdown = document.getElementsByClassName("dropdown-btn");
for (let i = 0; i < dropdown.length; i++) {
    dropdown[i].addEventListener("click", function () {
        this.classList.toggle("active");
        var dropdownContent = this.nextElementSibling;
        if (dropdownContent.style.display === "block") {
            dropdownContent.style.display = "none";
        } else {
            dropdownContent.style.display = "block";
        }
    });
}
</script>

</body>
</html>
