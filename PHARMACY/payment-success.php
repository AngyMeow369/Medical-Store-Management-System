<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id']) || !isset($_GET['order_id']) || !isset($_GET['payment_id'])) {
    echo "Missing required data.";
    exit();
}

$order_id = intval($_GET['order_id']);
$user_id = $_SESSION['user_id'];
$payment_id = $_GET['payment_id'];

// ✅ Fetch order details
$order = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT * FROM orders WHERE order_id = $order_id AND user_id = $user_id
"));

if (!$order) {
    echo "❌ Order not found.";
    exit();
}

// ✅ Get existing values safely
$totalAmount = $order['total_amount'];
$latitude = $order['latitude'] ?? '';
$longitude = $order['longitude'] ?? '';

// ✅ Update order as paid with method as 'Online'
$update = mysqli_query($conn, "
    UPDATE orders 
    SET payment_status = 'paid', payment_method = 'Online', payment_id = '$payment_id' 
    WHERE order_id = $order_id AND user_id = $user_id
");

if ($update) {
    echo "<script>
        alert('✅ Payment Successful! Your order is now marked as paid.');
        window.location.href = 'user-orders.php';
    </script>";
} else {
    echo "❌ Failed to update order. MySQL error: " . mysqli_error($conn);
}
?>
