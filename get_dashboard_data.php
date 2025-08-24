<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pharmacy";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set header for JSON response
header('Content-Type: application/json');

// Get current date
$today = date('Y-m-d');
$yesterday = date('Y-m-d', strtotime('-1 day'));
$lastMonth = date('Y-m-d', strtotime('-1 month'));

// Initialize response array
$response = [
    'totalProducts' => 0,
    'productsChange' => '', // Changed from 'Data not available'
    'ordersToday' => 0,
    'ordersChange' => 'Data not available',
    'lowStockItems' => 0,
    'lowStockChange' => '', // Changed from 'Data not available'
    'revenueToday' => 0,
    'revenueChange' => 'Data not available'
];

// 1. Get total products count (using 'meds' table)
$sql = "SELECT COUNT(*) as count FROM meds";
$result = $conn->query($sql);
if ($result) {
    $row = $result->fetch_assoc();
    $response['totalProducts'] = (int)$row['count'];
}

// 2. Get products change from last month
// Since there's no date_added column, we'll leave it empty
$response['productsChange'] = '';

// 3. Get orders today (using 'order_history' table)
$sql = "SELECT COUNT(*) as count FROM order_history WHERE DATE(order_date) = '$today'";
$result = $conn->query($sql);
if ($result) {
    $row = $result->fetch_assoc();
    $response['ordersToday'] = (int)$row['count'];
}

// 4. Get orders change from yesterday
$sql = "SELECT COUNT(*) as count FROM order_history WHERE DATE(order_date) = '$yesterday'";
$result = $conn->query($sql);
if ($result) {
    $row = $result->fetch_assoc();
    $yesterdayOrders = (int)$row['count'];
    if ($yesterdayOrders > 0) {
        $change = round((($response['ordersToday'] - $yesterdayOrders) / $yesterdayOrders) * 100, 1);
        $direction = $change >= 0 ? '+' : '';
        $response['ordersChange'] = "$direction$change% from yesterday";
    } else {
        $response['ordersChange'] = 'No data from yesterday';
    }
}

// 5. Get low stock items (using 'meds' table, with a reasonable threshold)
$threshold = 20; // Changed to 20 as a more reasonable threshold for a pharmacy
$sql = "SELECT COUNT(*) as count FROM meds WHERE MED_QTY < $threshold";
$result = $conn->query($sql);
if ($result) {
    $row = $result->fetch_assoc();
    $response['lowStockItems'] = (int)$row['count'];
}

// 6. Get new low stock items
// Since there's no date_added column, we'll leave it empty
$response['lowStockChange'] = '';

// 7. Get revenue today (using 'order_history' table)
$sql = "SELECT SUM(total_amount) as total FROM order_history WHERE DATE(order_date) = '$today'";
$result = $conn->query($sql);
if ($result) {
    $row = $result->fetch_assoc();
    $response['revenueToday'] = (float)($row['total'] ?? 0); // Handle NULL case
}

// 8. Get revenue change from yesterday (using 'order_history' table)
$sql = "SELECT SUM(total_amount) as total FROM order_history WHERE DATE(order_date) = '$yesterday'";
$result = $conn->query($sql);
if ($result) {
    $row = $result->fetch_assoc();
    $yesterdayRevenue = (float)($row['total'] ?? 0); // Handle NULL case
    if ($yesterdayRevenue > 0) {
        $change = round((($response['revenueToday'] - $yesterdayRevenue) / $yesterdayRevenue) * 100, 1);
        $direction = $change >= 0 ? '+' : '';
        $response['revenueChange'] = "$direction$change% from yesterday";
    } else {
        $response['revenueChange'] = 'No data from yesterday';
    }
}

// Close connection
$conn->close();

// Return JSON response
echo json_encode($response);
?>
