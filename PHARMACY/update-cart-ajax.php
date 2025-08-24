<?php
session_start();
include "config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart_id = intval($_POST['cart_id']);
    $quantity = max(1, intval($_POST['quantity']));
    $user_id = $_SESSION['user_id'];

    $update = mysqli_query($conn, "
        UPDATE cart SET quantity = $quantity 
        WHERE cart_id = $cart_id AND user_id = $user_id
    ");

    echo $update ? "success" : "fail";
}
?>
