<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id']) || !isset($_GET['order_id'])) {
    header("Location: user-login.php");
    exit();
}

$order_id = intval($_GET['order_id']);
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_payment'])) {
    $payment_method = $_POST['payment_method'];

    if ($payment_method == 'COD') {
        // ✅ Update payment_status to 'paid' for COD
        $update = mysqli_query($conn, "
            UPDATE orders 
            SET payment_status = 'paid', payment_method = 'COD' 
            WHERE order_id = $order_id AND user_id = $user_id
        ");

        if ($update) {
            echo "<script>
                alert('✅ Order placed successfully with COD.');
                window.location.href = 'user-orders.php';
            </script>";
            exit();
        } else {
            echo "❌ Failed to update order. " . mysqli_error($conn);
            exit();
        }

    } elseif ($payment_method == 'Online') {
        // ✅ Redirect to Razorpay payment
        header("Location: payment-gateway.php?order_id=$order_id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Select Payment Method</title>
    <style>
        body { font-family: Arial; background: #f0f0f0; padding: 30px; }
        .container {
            background: white; padding: 20px; max-width: 500px; margin: auto;
            border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 { text-align: center; }
        label { display: block; margin: 15px 0; font-size: 16px; }
        button {
            padding: 10px 20px; background: #0077cc; color: white; border: none;
            border-radius: 5px; font-size: 16px; cursor: pointer; display: block; margin: auto;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Select Payment Method</h2>
    <form method="POST">
        <label>
            <input type="radio" name="payment_method" value="COD" checked> Cash on Delivery
        </label>
        <label>
            <input type="radio" name="payment_method" value="Online"> Online Payment
        </label>
        <button type="submit" name="confirm_payment">Confirm and Place Order</button>
    </form>
</div>
</body>
</html>
