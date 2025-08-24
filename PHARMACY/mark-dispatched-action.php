<?php
session_start();
include "config.php";

if (!isset($_SESSION['user'])) {
    header("Location: pharmLogin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = intval($_POST['order_id']);

    // Update delivery_status to dispatched
    $query = "UPDATE orders SET delivery_status = 'dispatched', dispatched = 1 WHERE order_id = $order_id";
    mysqli_query($conn, $query);
}

header("Location: pharmacist-order.php");
exit();
?>
