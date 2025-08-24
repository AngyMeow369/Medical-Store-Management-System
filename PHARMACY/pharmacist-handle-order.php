<?php
session_start();
include "config.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure PHPMailer is loaded

// Ensure logged in
if (!isset($_SESSION['user'])) {
    header("Location: pharmLogin.php");
    exit();
}

$pharmacist_id = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $action = $_POST['action']; // approve or reject
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);
    $action_date = date("Y-m-d H:i:s");

    if (!in_array($action, ['approve', 'reject'])) {
        die("Invalid action.");
    }

    $status = $action === 'approve' ? 'approved' : 'rejected';

    $update = mysqli_query($conn, "
        UPDATE orders
        SET 
            status = '$status',
            pharmacist_id = '$pharmacist_id',
            action_date = '$action_date',
            action_notes = '$notes'
        WHERE order_id = $order_id
    ");

    if ($update) {
        $orderQuery = mysqli_query($conn, "
            SELECT o.*, u.email, u.username 
            FROM orders o 
            JOIN users u ON o.user_id = u.user_id 
            WHERE o.order_id = $order_id AND o.payment_status = 'paid' AND o.email_sent = 0
        ");

        if (mysqli_num_rows($orderQuery) > 0) {
            $order = mysqli_fetch_assoc($orderQuery);
            $user_email = $order['email'];
            $username = $order['username'];
            $payment_method = $order['payment_method'];
            $order_date = $order['order_date'];
            $total_amount = $order['total_amount'];

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

            $mail = new PHPMailer(true);
            $mail->CharSet = 'UTF-8';

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'librarymanagementsystem24@gmail.com';
                $mail->Password = 'ekya ziwu ghaf ndab';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('librarymanagementsystem24@gmail.com', 'Medical Store');
                $mail->addAddress($user_email, $username);
                $mail->isHTML(true);

                if ($status === 'approved') {
                    $mail->Subject = "🧾 Order Confirmation - Order #$order_id";
                    $mail->Body = "
                        <div style='background-color: #e6f9ff; padding: 30px; font-family: Arial, sans-serif; color: #333; border-radius: 10px; max-width: 600px; margin: auto;'>
                            <div style='text-align: center; margin-bottom: 20px;'>
                                <img src='https://cdn-icons-png.flaticon.com/512/2965/2965567.png' width='80' alt='Medical Icon'>
                                <h2 style='color: #0077cc;'>Medical Store - Order Confirmed!</h2>
                            </div>
                            <p>Dear <strong>$username</strong>,</p>
                            <p>We're happy to let you know that your order has been <strong style='color: green;'>approved</strong> and confirmed on <strong>$order_date</strong>.</p>
                            <table style='width: 100%; margin-top: 15px; border-collapse: collapse;'>
                                <tr><td style='padding: 8px;'><strong>🆔 Order ID:</strong></td><td style='padding: 8px;'>$order_id</td></tr>
                                <tr><td style='padding: 8px;'><strong>💳 Payment:</strong></td><td style='padding: 8px;'>$payment_method</td></tr>
                                <tr><td style='padding: 8px;'><strong>💰 Total:</strong></td><td style='padding: 8px;'>₹$total_amount</td></tr>
                            </table>
                            <h4 style='margin-top: 25px;'>🧾 Items Ordered:</h4>
                            <ul style='padding-left: 20px; line-height: 1.6;'>$itemList</ul>
                            <p style='margin-top: 25px;'>Thank you for trusting <strong>Medical Store</strong>. We’ll notify you once your medicines are ready for delivery.</p>
                            <div style='margin-top: 30px; text-align: center;'>
                                <p style='font-size: 13px; color: #777;'>Stay safe and healthy!<br>~ Medical Store Team</p>
                            </div>
                        </div>
                    ";
                } elseif ($status === 'rejected') {
                    $mail->Subject = "❌ Order Rejected - Order #$order_id";
                    $mail->Body = "
                        <div style='background-color: #fff4f4; padding: 30px; font-family: Arial, sans-serif; color: #333; border-radius: 10px; max-width: 600px; margin: auto;'>
                            <div style='text-align: center; margin-bottom: 20px;'>
                                <img src='https://cdn-icons-png.flaticon.com/512/564/564619.png' width='70' alt='Rejected Icon'>
                                <h2 style='color: #cc0000;'>Order Rejected</h2>
                            </div>
                            <p>Dear <strong>$username</strong>,</p>
                            <p>We regret to inform you that your order placed on <strong>$order_date</strong> has been <strong style='color: red;'>rejected</strong> by our pharmacist.</p>
                            <table style='width: 100%; margin-top: 15px; border-collapse: collapse;'>
                                <tr><td style='padding: 8px;'><strong>🆔 Order ID:</strong></td><td style='padding: 8px;'>$order_id</td></tr>
                                <tr><td style='padding: 8px;'><strong>💳 Payment:</strong></td><td style='padding: 8px;'>$payment_method</td></tr>
                                <tr><td style='padding: 8px;'><strong>💰 Amount:</strong></td><td style='padding: 8px;'>₹$total_amount</td></tr>
                            </table>
                            <h4 style='margin-top: 25px;'>🧾 Items You Ordered:</h4>
                            <ul style='padding-left: 20px; line-height: 1.6;'>$itemList</ul>
                            <p style='margin-top: 25px; color: #555;'>If payment was already made online, the refund (if applicable) will be processed shortly.</p>
                            <div style='margin-top: 30px; text-align: center;'>
                                <p style='font-size: 13px; color: #999;'>If you have questions, please contact our support team.<br>~ Medical Store Team</p>
                            </div>
                        </div>
                    ";
                }

                $mail->send();

                // ✅ Update email_sent flag
                mysqli_query($conn, "
                    UPDATE orders SET email_sent = 1 WHERE order_id = $order_id
                ");
            } catch (Exception $e) {
                error_log("Email failed for order $order_id: {$mail->ErrorInfo}");
            }
        }
    }

    header("Location: pharmacist-order.php");
    exit();
} else {
    echo "Invalid request method.";
}
?>
