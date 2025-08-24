<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: pharmLogin.php");
    exit();
}

include "config.php";

// Copy orders to history
// Copy orders to history with all fields
mysqli_query($conn, "
    INSERT INTO order_history (
        order_id, user_id, order_date, status, total_amount, pharmacist_id, 
        action_date, action_notes, latitude, longitude, 
        payment_method, payment_status, payment_id, email_sent
    )
    SELECT 
        order_id, user_id, order_date, status, total_amount, pharmacist_id, 
        action_date, action_notes, latitude, longitude, 
        payment_method, payment_status, payment_id, email_sent
    FROM orders
");


// Copy order items to history
mysqli_query($conn, "
    INSERT INTO order_items_history (order_id, med_id, quantity, unit_price, total_price)
    SELECT order_id, med_id, quantity, unit_price, total_price FROM order_items
");

// Clear orders and order items //i commented it out bruh
mysqli_query($conn, "DELETE FROM order_items");
mysqli_query($conn, "DELETE FROM orders");

// Redirect
header("Location: pharmacist-order.php?deleted=1");
exit();
?>
