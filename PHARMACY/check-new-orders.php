<?php
include "config.php";
session_start();

$result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM orders WHERE status = 'Pending'");
$data = mysqli_fetch_assoc($result);
echo json_encode(['count' => $data['count']]);
?>
