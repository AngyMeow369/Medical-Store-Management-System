<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: user-login.php");
    exit();
}

include "config.php";

if (isset($_GET['med_id'])) {
    $user_id = $_SESSION['user_id'];
    $med_id = intval($_GET['med_id']);

    // Check if already wishlisted
    $check = mysqli_query($conn, "SELECT * FROM wishlist WHERE user_id = $user_id AND med_id = $med_id");
    if (mysqli_num_rows($check) > 0) {
        // Already wishlisted, redirect back
        header("Location: user-medicines.php?message=already_wishlisted");
        exit();
    }

    // Insert into wishlist
    $insert = mysqli_query($conn, "INSERT INTO wishlist (user_id, med_id) VALUES ($user_id, $med_id)");
    
    if ($insert) {
        header("Location: user-medicines.php?message=wishlisted_success");
    } else {
        header("Location: user-medicines.php?message=wishlist_error");
    }
} else {
    header("Location: user-medicines.php");
    exit();
}
?>
