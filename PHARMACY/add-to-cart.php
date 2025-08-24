<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: user-login.php");
    exit();
}

include "config.php";
$user_id = $_SESSION['user_id'];

if (!isset($_GET['med_id']) || !is_numeric($_GET['med_id'])) {
    die("Invalid request");
}

$med_id = intval($_GET['med_id']);

// Check if medicine exists and has stock
$checkMed = mysqli_query($conn, "SELECT * FROM meds WHERE MED_ID = $med_id AND MED_QTY > 0");
if (mysqli_num_rows($checkMed) == 0) {
    die("Medicine not available or out of stock");
}

// Check if already in cart
$checkCart = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = $user_id AND med_id = $med_id");

if (mysqli_num_rows($checkCart) > 0) {
    mysqli_query($conn, "
        UPDATE cart 
        SET quantity = quantity + 1, added_at = NOW() 
        WHERE user_id = $user_id AND med_id = $med_id
    ");
} else {
    mysqli_query($conn, "
        INSERT INTO cart (user_id, med_id, quantity, added_at) 
        VALUES ($user_id, $med_id, 1, NOW())
    ");
}

// Redirect to medicine page with success message
header("Location: user-medicines.php?added=1");
exit();
?>
