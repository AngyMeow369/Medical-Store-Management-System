<?php
session_start();
include "config.php";

if (isset($_SESSION['user_id']) && isset($_POST['latitude']) && isset($_POST['longitude'])) {
    $user_id = $_SESSION['user_id'];
    $lat = floatval($_POST['latitude']);
    $lng = floatval($_POST['longitude']);

    $stmt = mysqli_prepare($conn, "INSERT INTO user_locations (user_id, latitude, longitude) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "idd", $user_id, $lat, $lng);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "Location saved successfully!";
    } else {
        echo "Error saving location!";
    }
    mysqli_stmt_close($stmt);
} else {
    echo "Invalid data or not logged in!";
}
?>
