<?php
use PHPMailer\PHPMailer\PHPMailer; //BACKUPP
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Composer autoload

include "config.php";

// Fetch only approved and paid orders where email hasn't been sent yet
$orderQuery = mysqli_query($conn, "
    SELECT o.*, u.email, u.username
    FROM orders o
    JOIN users u ON o.user_id = u.user_id
    WHERE o.payment_status = 'paid' 
      AND o.status = 'approved' 
      AND o.email_sent = 0
");

while ($order = mysqli_fetch_assoc($orderQuery)) {
    $order_id = $order['order_id'];
    $user_email = $order['email'];
    $username = $order['username'];
    $payment_method = $order['payment_method'];
    $order_date = $order['order_date'];
    $total_amount = $order['total_amount'];

    // Get order items
    $itemsQuery = mysqli_query($conn, "
        SELECT oi.quantity, oi.total_price, m.med_name
        FROM order_items oi
        JOIN meds m ON oi.med_id = m.med_id
        WHERE oi.order_id = $order_id
    ");

    $itemList = "";
    while ($item = mysqli_fetch_assoc($itemsQuery)) {
        $itemList .= "<li>{$item['med_name']} - Qty: {$item['quantity']} - ₹{$item['total_price']}</li>";
    }

    // Setup PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'librarymanagementsystem24@gmail.com'; // ⚠️ your Gmail
        $mail->Password = 'ekya ziwu ghaf ndab'; // ⚠️ App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('librarymanagementsystem24@gmail.com', 'Medical Store');
        $mail->addAddress($user_email, $username);

        $mail->isHTML(true);
        $mail->Subject = "🧾 Order Confirmation - Order #$order_id";
        $mail->Body = "
            <h2>Thank you, $username!</h2>
            <p>Your order has been successfully placed and approved on <strong>$order_date</strong>.</p>
            <p><strong>Order ID:</strong> $order_id</p>
            <p><strong>Payment Method:</strong> $payment_method</p>
            <p><strong>Total Amount:</strong> ₹$total_amount</p>
            <p><strong>Items Ordered:</strong></p>
            <ul>$itemList</ul>
            <p>We will notify you once it's out for delivery. Thank you for shopping with us!</p>
        ";

        $mail->send();

        // Mark email as sent
        mysqli_query($conn, "
            UPDATE orders SET email_sent = 1 WHERE order_id = $order_id
        ");
    } catch (Exception $e) {
        error_log("Email sending failed for order $order_id: {$mail->ErrorInfo}");
    }
}
?>
