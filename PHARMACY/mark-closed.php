<?php
include "config.php";
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: pharmLogin.php");
    exit();
}

if (isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $update = mysqli_query($conn, "UPDATE orders SET order_closed = 1 WHERE order_id = $order_id");
}
header("Location: pharmacist-order.php");
exit();
