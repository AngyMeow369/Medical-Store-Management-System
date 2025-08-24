<?php
session_start();
include "config.php"; // ensure this contains $conn

header('Content-Type: application/json');

// 🔐 1. Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$user_id = intval($_SESSION['user_id']);
$order_id = intval($_POST['order_id'] ?? 0);
$reason = trim($_POST['reason'] ?? '');

// 📊 2. Validate input
if ($order_id <= 0 || $reason === '') {
    echo json_encode(['success' => false, 'error' => 'Missing or invalid order ID or reason.']);
    exit;
}

// 🔍 3. Check if the order exists and is pending for this user
$checkQuery = mysqli_query($conn, "
    SELECT * FROM orders 
    WHERE order_id = $order_id 
      AND user_id = $user_id 
      AND status = 'pending'
");

if (!$checkQuery || mysqli_num_rows($checkQuery) === 0) {
    echo json_encode(['success' => false, 'error' => 'Order not found or not cancellable.']);
    exit;
}

// 📝 4. Insert cancellation reason (record in order_cancellations table)
$escaped_reason = mysqli_real_escape_string($conn, $reason);
$logCancel = mysqli_query($conn, "
    INSERT INTO order_cancellations (order_id, user_id, cancellation_reason, cancelled_at, seen_by_pharmacist)
    VALUES ($order_id, $user_id, '$escaped_reason', NOW(), 0)
");

// 🔄 5. Update order status to cancelled
$updateOrder = mysqli_query($conn, "
    UPDATE orders 
    SET status = 'cancelled' 
    WHERE order_id = $order_id
");

if ($logCancel && $updateOrder) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Database error while cancelling.']);
}
?>