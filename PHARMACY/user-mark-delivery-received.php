<?php
session_start();
header('Content-Type: application/json');
include "config.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "Not logged in"]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['order_id'])) {
    $user_id = $_SESSION['user_id'];
    $order_id = intval($_POST['order_id']);

    $check = mysqli_query($conn, "
        SELECT * FROM orders 
        WHERE order_id = $order_id 
        AND user_id = $user_id 
        AND status = 'approved' 
        AND (order_type = 'delivery' OR order_type = 'online')
        AND dispatched = 1
        AND order_closed = 0
    ");

    if (mysqli_num_rows($check) === 1) {
        $updated = mysqli_query($conn, "
            UPDATE orders 
            SET delivery_status = 'received' 
            WHERE order_id = $order_id
        ");
        if ($updated) {
            echo json_encode(["success" => true, "message" => "Delivery marked as received"]);
        } else {
            echo json_encode(["success" => false, "error" => "Failed to update order"]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "Invalid order or already received"]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Invalid request"]);
}
