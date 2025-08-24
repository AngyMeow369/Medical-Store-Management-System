<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: user-login.php");
    exit();
}

include "config.php";
$user_id = $_SESSION['user_id'];

if (!isset($_GET['cart_id']) || !is_numeric($_GET['cart_id'])) {
    die("Invalid request");
}

$cart_id = intval($_GET['cart_id']);

// Make sure this cart item belongs to the logged-in user
mysqli_query($conn, "DELETE FROM cart WHERE cart_id = $cart_id AND user_id = $user_id");

header("Location: user-cart.php");
exit();
?>
