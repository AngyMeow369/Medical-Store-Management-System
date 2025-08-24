<?php
include "config.php";

header('Content-Type: application/json');

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'day';
$data = [];

switch ($filter) {
    case 'day':
        $query = "
            SELECT DATE(order_date) AS label, SUM(total_amount) AS total
            FROM order_history
            GROUP BY DATE(order_date)
            ORDER BY DATE(order_date) ASC
        ";
        break;

    case 'week':
        $query = "
            SELECT CONCAT(YEAR(order_date), '-W', WEEK(order_date)) AS label, SUM(total_amount) AS total
            FROM order_history
            GROUP BY YEAR(order_date), WEEK(order_date)
            ORDER BY YEAR(order_date), WEEK(order_date)
        ";
        break;

    case 'year':
        $query = "
            SELECT YEAR(order_date) AS label, SUM(total_amount) AS total
            FROM order_history
            GROUP BY YEAR(order_date)
            ORDER BY YEAR(order_date)
        ";
        break;

    default:
        http_response_code(400);
        echo json_encode(["error" => "Invalid filter value"]);
        exit();
}

$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = [
            'label' => $row['label'],
            'total' => round($row['total'], 2)
        ];
    }
}

echo json_encode($data);
