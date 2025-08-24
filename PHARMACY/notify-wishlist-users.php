<?php
include "config.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // PHPMailer

$query = mysqli_query($conn, "
    SELECT 
        w.id AS wishlist_id,
        w.med_id,
        u.email,
        u.username,
        m.med_name
    FROM wishlist w
    JOIN users u ON w.user_id = u.user_id
    JOIN meds m ON w.med_id = m.med_id
    WHERE w.notified = 0 AND m.med_qty > 0
");

$notified = 0;

while ($row = mysqli_fetch_assoc($query)) {
    $email = $row['email'];
    $username = $row['username'];
    $med_name = $row['med_name'];
    $wishlist_id = $row['wishlist_id'];

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
        $mail->addAddress($email, $username);
        $mail->isHTML(true);

        $mail->Subject = "📦 '$med_name' is now back in stock!";
        $mail->Body = "
            <div style='background-color: #f3faff; padding: 30px; font-family: Arial, sans-serif; color: #333; border-radius: 10px; max-width: 600px; margin: auto;'>
                <div style='text-align: center; margin-bottom: 20px;'>
                    <img src='https://cdn-icons-png.flaticon.com/512/3437/3437361.png' width='80' alt='Medicine Icon'>
                    <h2 style='color: #0077cc;'>Medicine Now Available</h2>
                </div>
                <p>Hi <strong>$username</strong>,</p>
                <p>Great news! The medicine you were waiting for — <strong>$med_name</strong> — is now <span style='color: green; font-weight: bold;'>available</span> in our store.</p>
                <p><a href='https://yourwebsite.com/user-medicines.php' style='display: inline-block; padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 6px;'>Buy Now</a></p>
                <p style='margin-top: 20px;'>Thank you for using <strong>Medical Store</strong>. Stay healthy!</p>
                <div style='margin-top: 30px; text-align: center; font-size: 12px; color: #777;'>This is an automated message. Do not reply.</div>
            </div>
        ";

        $mail->send();

        // mark as notified
        mysqli_query($conn, "UPDATE wishlist SET notified = 1 WHERE id = $wishlist_id");
        $notified++;
    } catch (Exception $e) {
        error_log("Wishlist email failed: " . $mail->ErrorInfo);
    }
}

echo "✅ Wishlist notifications sent: $notified";
?>
