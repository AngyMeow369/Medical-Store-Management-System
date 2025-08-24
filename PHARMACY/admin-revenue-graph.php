<?php
include "config.php";

// Fetch revenue data (last 7 days)
$labels = [];
$data = [];

$sql = "
    SELECT DATE(order_date) as date, SUM(total_amount) as revenue
    FROM order_history
    WHERE order_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
      AND payment_status = 'paid'
    GROUP BY DATE(order_date)
    ORDER BY DATE(order_date)
";
$result = $conn->query($sql);

// Store revenue and label
while ($row = $result->fetch_assoc()) {
    $labels[] = $row['date'];
    $data[] = (float) $row['revenue'];
}

// Calculate projection (simple average)
$average = count($data) > 0 ? array_sum($data) / count($data) : 0;
$projection_days = 3;
$projection_labels = [];
$projection_data = [];

for ($i = 1; $i <= $projection_days; $i++) {
    $future_date = date('Y-m-d', strtotime("+$i day"));
    $projection_labels[] = $future_date;
    $projection_data[] = round($average, 2); // same avg value for simplicity
}

// Merge with original data
$full_labels = array_merge($labels, $projection_labels);
$projection_data = array_merge(array_fill(0, count($data), null), $projection_data); // align with real data
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Revenue Projection</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f7f9fc;
            padding: 40px;
            margin: 0;
        }
        h2 {
            color: #007bff;
            text-align: center;
        }
        .chart-container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .back-btn {
            display: inline-block;
            margin: 20px auto;
            text-align: center;
            background: #007bff;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: bold;
        }
        .back-btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<a href="adminmainpage.php" class="back-btn">← Back to Dashboard</a>

<div class="chart-container">
    <h2>📈 Revenue & Projection (Last 7 Days + 3 Days Forecast)</h2>
    <canvas id="revenueChart" width="400" height="200"></canvas>
</div>

<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');

    const revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($full_labels) ?>,
            datasets: [
                {
                    label: 'Actual Revenue (₹)',
                    data: <?= json_encode($data) ?>,
                    borderColor: 'rgba(0, 123, 255, 1)',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    fill: false,
                    tension: 0.3,
                    pointRadius: 4
                },
                {
                    label: 'Projection (₹)',
                    data: <?= json_encode($projection_data) ?>,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderDash: [5, 5],
                    backgroundColor: 'rgba(255, 99, 132, 0.1)',
                    fill: false,
                    tension: 0.3,
                    pointRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => '₹' + value
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        font: {
                            weight: 'bold'
                        }
                    }
                }
            }
        }
    });
</script>

</body>
</html>
