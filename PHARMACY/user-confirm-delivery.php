<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: user-login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$order_id = intval($_GET['order_id']);
$response = $_GET['response']; // 'received' or 'not_received'

// Validate input
if (!in_array($response, ['received', 'not_received'])) {
    die("Invalid response.");
}

// Check if order belongs to user, is approved, dispatched, not yet closed, and of correct type
$orderCheck = mysqli_query($conn, "
    SELECT * FROM orders 
    WHERE order_id = $order_id 
    AND user_id = $user_id 
    AND status = 'approved'
    AND (order_type = 'delivery' OR order_type = 'online')
    AND dispatched = 1
    AND order_closed = 0
");

if (mysqli_num_rows($orderCheck) === 0) {
    die("Unauthorized or invalid order.");
}

// Update delivery status
mysqli_query($conn, "
    UPDATE orders 
    SET delivery_status = '$response' 
    WHERE order_id = $order_id
");

header("Location: user-orders.php?delivery_updated=1");
exit();
?>
