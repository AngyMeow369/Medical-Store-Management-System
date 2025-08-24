<?php
session_start();
include "config.php";

if (!isset($_SESSION['user']) || !isset($_POST['order_id'])) {
    header("Location: pharmLogin.php");
    exit();
}

$order_id = intval($_POST['order_id']);

$update = mysqli_query($conn, "
    UPDATE orders SET ready_for_pickup = 1 WHERE order_id = $order_id
");

if ($update) {
    echo "<script>
        alert('✅ Order marked as ready for collection.');
        window.location.href = 'pharmacist-order.php';
    </script>";
} else {
    echo "❌ Failed to update order.";
}
?>
