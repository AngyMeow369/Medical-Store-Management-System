<?php
include "config.php";
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: user-login.php");
    exit();
}

if (isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $user_id = $_SESSION['user_id'];

    // Ensure the order belongs to this user
    $check = mysqli_query($conn, "SELECT * FROM orders WHERE order_id = $order_id AND user_id = $user_id");
    if (mysqli_num_rows($check) === 1) {
        mysqli_query($conn, "UPDATE orders SET delivery_status = 'received' WHERE order_id = $order_id");
    }
}
header("Location: user-orders.php");
exit();
