<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: user-login.php");
    exit();
}

// ✅ Handle prescription image upload
if (isset($_FILES['prescription']) && $_FILES['prescription']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/';
    $filename = time() . '_' . basename($_FILES['prescription']['name']);
    $targetPath = $uploadDir . $filename;

    if (move_uploaded_file($_FILES['prescription']['tmp_name'], $targetPath)) {
        $prescriptionImage = $targetPath;
    } else {
        $prescriptionImage = null;
    }
} else {
    $prescriptionImage = null;
}

$user_id = $_SESSION['user_id'];

// Fetch cart items
$cartQuery = mysqli_query($conn, "
    SELECT c.*, m.MED_PRICE 
    FROM cart c 
    JOIN meds m ON c.med_id = m.MED_ID 
    WHERE c.user_id = $user_id
");

if (mysqli_num_rows($cartQuery) == 0) {
    echo "<script>alert('Your cart is empty.'); window.location.href='user-dashboard.php';</script>";
    exit();
}

// Calculate total amount
$total = 0;
$cartItems = [];
while ($row = mysqli_fetch_assoc($cartQuery)) {
    $subtotal = $row['quantity'] * $row['MED_PRICE'];
    $total += $subtotal;
    $cartItems[] = $row;
}

// ✅ Insert into orders table with optional prescription_image
$orderInsert = mysqli_query($conn, "
    INSERT INTO orders (user_id, order_date, status, total_amount, payment_status, order_type, prescription_image) 
    VALUES (
        $user_id, NOW(), 'pending', $total, 'unpaid', 'pickup', " . 
        ($prescriptionImage ? "'$prescriptionImage'" : "NULL") . "
    )
");

if (!$orderInsert) {
    echo "<script>alert('❌ Failed to create order.'); window.location.href='cart.php';</script>";
    exit();
}

$order_id = mysqli_insert_id($conn); // Get generated order_id

// Insert items into order_items table
foreach ($cartItems as $item) {
    $med_id = $item['med_id'];
    $qty = $item['quantity'];
    $unit_price = $item['MED_PRICE'];
    $total_price = $qty * $unit_price;

    mysqli_query($conn, "
        INSERT INTO order_items (order_id, med_id, quantity, unit_price, total_price) 
        VALUES ($order_id, $med_id, $qty, $unit_price, $total_price)
    ");
}

// Clear user's cart
mysqli_query($conn, "DELETE FROM cart WHERE user_id = $user_id");

// Redirect to payment-method.php
header("Location: payment-method.php?order_id=$order_id");
exit();
?>
