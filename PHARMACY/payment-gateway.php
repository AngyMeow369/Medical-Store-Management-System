<?php
session_start();
include "config.php"; //Razorpay IMPORTANT FILE

if (!isset($_SESSION['user_id']) || !isset($_GET['order_id'])) {
    echo "Invalid session or order.";
    exit();
}

$order_id = $_GET['order_id'];
$user_id = $_SESSION['user_id'];

// Fetch order details
$order = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM orders WHERE order_id = $order_id AND user_id = $user_id"));

if (!$order) {
    echo "Order not found.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pay with Razorpay</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body style="text-align:center; font-family:sans-serif; padding-top:80px;">

    <h2>Pay ₹<?= number_format($order['total_amount'], 2) ?> for Order #<?= $order_id ?></h2>
    <button id="rzp-button" style="
        background: #0077cc;
        color: white;
        padding: 12px 25px;
        font-size: 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    ">Pay Now</button>

    <script>
    var options = {
        "key": "", // Replace with your Razorpay test key
        "amount": <?= $order['total_amount'] * 100 ?>, // in paise
        "currency": "INR",
        "name": "Medical Store",
        "description": "Order #<?= $order_id ?>",
        "handler": function (response){
        alert("✅ Payment successful!\nPayment ID: " + response.razorpay_payment_id);
        window.location.href = "payment-success.php?order_id=<?= $order_id ?>&payment_id=" + response.razorpay_payment_id;
    },
        "prefill": {
            "name": "<?= $_SESSION['username'] ?? 'User' ?>",
            "email": "test@example.com",
            "contact": "9000000000"
        },
        "theme": {
            "color": "#0077cc"
        }
    };
    var rzp1 = new Razorpay(options);
    document.getElementById('rzp-button').onclick = function(e){
        rzp1.open();
        e.preventDefault();
    }
    </script>
</body>
</html>
