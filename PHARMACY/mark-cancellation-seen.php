<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_id'])) {
    $cancelId = intval($_POST['cancel_id']);

    $sql = "UPDATE order_cancellations SET seen_by_pharmacist = 1 WHERE id = $cancelId";

    if ($conn->query($sql) === TRUE) {
        header('Location: pharmacist-cancellations.php');
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>