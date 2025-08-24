<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: pharmLogin.php");
    exit();
}

include "config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = intval($_POST['order_id']);

    // First, verify the order exists and is eligible to be closed
    $checkQuery = mysqli_query($conn, "
        SELECT * FROM orders 
        WHERE order_id = $order_id 
          AND status = 'approved' 
          AND order_type = 'delivery' 
          AND delivery_status = 'received'
    ");

    if (mysqli_num_rows($checkQuery) > 0) {
        // Update order status to closed
        $update = mysqli_query($conn, "
            UPDATE orders 
            SET status = 'closed' 
            WHERE order_id = $order_id
        ");

        if ($update) {
            header("Location: pharmacist-order.php?closed=1");
            exit();
        } else {
            header("Location: pharmacist-order.php?error=1");
            exit();
        }
    } else {
        // Invalid or ineligible order
        header("Location: pharmacist-order.php?invalid=1");
        exit();
    }
} else {
    header("Location: pharmacist-order.php");
    exit();
}
?>
