<?php
ob_start();
include "config.php";
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Notify users with wishlist when medicine is restocked
function notifyUsersForMedicine($med_id) {
    global $conn;

    $med_id = intval($med_id);
    $medRes = mysqli_query($conn, "SELECT MED_NAME, MED_QTY FROM meds WHERE MED_ID = $med_id");
    $med = mysqli_fetch_assoc($medRes);

    if (!$med || $med['MED_QTY'] <= 0) return;

    $med_name = $med['MED_NAME'];

    $wishlistQuery = mysqli_query($conn, "
        SELECT w.id, u.email, u.username
        FROM wishlist w
        JOIN users u ON w.user_id = u.user_id
        WHERE w.med_id = $med_id AND w.notified = 0
    ");

    while ($row = mysqli_fetch_assoc($wishlistQuery)) {
        $email = $row['email'];
        $username = $row['username'];
        $wishlist_id = $row['id'];

        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'librarymanagementsystem24@gmail.com';
            $mail->Password = 'ekya ziwu ghaf ndab';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('librarymanagementsystem24@gmail.com', 'Medical Store');
            $mail->addAddress($email, $username);
            $mail->isHTML(true);
            $mail->Subject = "🩺 '$med_name' is back in stock!";

            $mail->Body = "
                <div style='background-color:#f0fdf4; padding:20px; border-radius:10px; font-family:sans-serif; color:#333; max-width:600px; margin:auto;'>
                    <h2 style='color:#10B981;'>Medicine Available Notification</h2>
                    <p>Hi <strong>$username</strong>,</p>
                    <p>The medicine you wishlisted, <strong>$med_name</strong>, is now <span style='color:green;font-weight:bold;'>back in stock</span>.</p>
                    <p>Visit your dashboard to place an order before it runs out!</p>
                    <div style='margin-top:30px; text-align:center;'>
                        <a href='http://yourdomain.com/user-dashboard.php' style='padding:10px 20px; background:#10B981; color:#fff; text-decoration:none; border-radius:6px;'>Go to Dashboard</a>
                    </div>
                    <p style='margin-top:40px; font-size:13px; color:#888;'>~ Medical Store Team</p>
                </div>
            ";

            $mail->send();

            // Mark as notified
            mysqli_query($conn, "DELETE FROM wishlist WHERE id = $wishlist_id");

        } catch (Exception $e) {
            error_log("Wishlist Email Error (med_id: $med_id): " . $mail->ErrorInfo);
        }
    }
}

// Fetch medicine details
$row = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $qry1 = "SELECT * FROM meds WHERE med_id = '$id'";
    $result = $conn->query($qry1);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_row();
    } else {
        echo "<script>alert('Invalid Medicine ID or not found'); window.location.href='inventory-view.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('No medicine ID provided'); window.location.href='inventory-view.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Update Medicine</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body { font-family: Arial, sans-serif; margin: 0; background: #f9f9f9; }
    .topnav { background: #17a2b8; color: #fff; padding: 1rem; display: flex; justify-content: space-between; }
    .form-container { max-width: 700px; margin: 2rem auto; background: #fff; padding: 2rem; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    input, label { display: block; width: 100%; margin-bottom: 10px; }
    input[type="submit"] { background: #17a2b8; color: white; border: none; padding: 10px; border-radius: 5px; cursor: pointer; }
    input[type="submit"]:hover { background: #138496; }
  </style>
</head>
<body>

<div class="topnav">
  <span><i class="fas fa-capsules"></i> Update Medicine</span>
  <a href="logout.php" style="color: white; text-decoration: none;">Logout</a>
</div>

<div class="form-container">
  <form action="<?= $_SERVER['PHP_SELF'] ?>?id=<?= $_GET['id'] ?>" method="post">
    <label for="medid">Medicine ID:</label>
    <input type="number" name="medid" value="<?= $row[0] ?>" readonly>
    <label for="medname">Medicine Name:</label>
    <input type="text" name="medname" value="<?= $row[1] ?>">
    <label for="qty">Quantity:</label>
    <input type="number" name="qty" value="<?= $row[2] ?>">
    <label for="cat">Category:</label>
    <input type="text" name="cat" value="<?= $row[3] ?>">
    <label for="sp">Price:</label>
    <input type="number" step="0.01" name="sp" value="<?= $row[4] ?>">
    <label for="loc">Location:</label>
    <input type="text" name="loc" value="<?= $row[5] ?>">
    <input type="submit" name="update" value="Update">
  </form>

<?php
if (isset($_POST['update'])) {
    $id = $_POST['medid'];
    $name = $_POST['medname'];
    $qty = $_POST['qty'];
    $cat = $_POST['cat'];
    $price = $_POST['sp'];
    $lcn = $_POST['loc'];

    // Check old quantity first
    $oldQtyRes = mysqli_query($conn, "SELECT med_qty FROM meds WHERE med_id = '$id'");
    $oldQtyRow = mysqli_fetch_assoc($oldQtyRes);
    $oldQty = $oldQtyRow ? (int)$oldQtyRow['med_qty'] : 0;

    $sql = "UPDATE meds SET med_name='$name', med_qty='$qty', category='$cat', med_price='$price', location_rack='$lcn' WHERE med_id='$id'";
    if ($conn->query($sql)) {
        if ($oldQty == 0 && $qty > 0) {
            notifyUsersForMedicine($id);
        }
        header("Location: inventory-view.php");
        exit();
    } else {
        echo "<p style='color:red;'>Error updating medicine.</p>";
    }
}
?>
</div>
<?php ob_end_flush(); ?>
</body>
</html>
