<?php
include "config.php";
// Date filters
$filter = $_GET['filter'] ?? 'today';
switch ($filter) {
    case 'week':
        $where = "DATE(order_date) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
        break;
    case 'month':
        $where = "MONTH(order_date) = MONTH(CURDATE()) AND YEAR(order_date) = YEAR(CURDATE())";
        break;
    case 'year':
        $where = "YEAR(order_date) = YEAR(CURDATE())";
        break;
    default:
        $where = "DATE(order_date) = CURDATE()";
        break;
}
// Fetch from order_history instead of orders
$sql = "SELECT * FROM order_history WHERE $where ORDER BY order_date DESC";
$result = $conn->query($sql);
$total_revenue = 0;
$total_orders = 0;
$cod_count = 0;
$online_count = 0;
$paid_count = 0;
$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
    $total_revenue += $row['total_amount'];
    $total_orders++;
    if (isset($row['payment_method'])) {
    if ($row['payment_method'] === 'COD') $cod_count++;
    if ($row['payment_method'] === 'Online') $online_count++;
}
if (isset($row['payment_status']) && $row['payment_status'] === 'paid') {
    $paid_count++;
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sales Summary</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --secondary: #6366f1;
            --accent: #818cf8;
            --bg: #f8fafc;
            --white: #ffffff;
            --dark: #1e293b;
            --light-gray: #f1f5f9;
            --medium-gray: #64748b;
            --border: #e2e8f0;
            --shadow: rgba(0, 0, 0, 0.08);
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
        }
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--dark);
            line-height: 1.6;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 24px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }
        .header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
        }
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .back-btn:hover {
            background: var(--secondary);
        }
        .filters {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }
        .filter-btn {
            padding: 8px 16px;
            background: var(--white);
            color: var(--primary);
            border: 1px solid var(--border);
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
        }
        .filter-btn:hover, .filter-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }
        .summary-card {
            background: var(--white);
            border-radius: 12px;
            padding: 24px;
            border: 1px solid var(--border);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            text-align: center;
        }
        .summary-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.08);
        }
        .summary-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
        }
        .summary-card.primary::before {
            background-color: var(--primary);
        }
        .summary-card.success::before {
            background-color: var(--success);
        }
        .summary-card.warning::before {
            background-color: var(--warning);
        }
        .summary-card.info::before {
            background-color: var(--info);
        }
        .summary-card .icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 1.25rem;
        }
        .summary-card.primary .icon {
            background-color: rgba(79, 70, 229, 0.1);
            color: var(--primary);
        }
        .summary-card.success .icon {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }
        .summary-card.warning .icon {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }
        .summary-card.info .icon {
            background-color: rgba(59, 130, 246, 0.1);
            color: var(--info);
        }
        .summary-card h3 {
            margin: 0 0 8px;
            font-size: 0.875rem;
            color: var(--medium-gray);
            font-weight: 500;
        }
        .summary-card .value {
            font-size: 1.875rem;
            font-weight: 700;
            margin: 0;
            line-height: 1.2;
        }
        .summary-card.primary .value {
            color: var(--primary);
        }
        .summary-card.success .value {
            color: var(--success);
        }
        .summary-card.warning .value {
            color: var(--warning);
        }
        .summary-card.info .value {
            color: var(--info);
        }
        .table-container {
            background: var(--white);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        .table-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
        }
        .table-header h2 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 16px 24px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }
        th {
            background-color: var(--light-gray);
            color: var(--dark);
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        tr:last-child td {
            border-bottom: none;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: capitalize;
        }
        .status-pending {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }
        .status-completed {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }
        .status-cancelled {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }
        .payment-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .payment-cod {
            background-color: rgba(79, 70, 229, 0.1);
            color: var(--primary);
        }
        .payment-online {
            background-color: rgba(59, 130, 246, 0.1);
            color: var(--info);
        }
        .payment-status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
            margin-left: 8px;
        }
        .payment-status-paid {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }
        .payment-status-unpaid {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }
        @media (max-width: 768px) {
            .container {
                padding: 16px;
            }
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }
            .summary-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }
            .table-container {
                overflow-x: auto;
            }
            th, td {
                padding: 12px 16px;
            }
        }
        @media (max-width: 480px) {
            .summary-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Sales Summary</h1>
            <a href="adminmainpage.php" class="back-btn">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
        
        <div class="filters">
            <a href="?filter=today" class="filter-btn <?php echo $filter == 'today' ? 'active' : ''; ?>">Today</a>
            <a href="?filter=week" class="filter-btn <?php echo $filter == 'week' ? 'active' : ''; ?>">This Week</a>
            <a href="?filter=month" class="filter-btn <?php echo $filter == 'month' ? 'active' : ''; ?>">This Month</a>
            <a href="?filter=year" class="filter-btn <?php echo $filter == 'year' ? 'active' : ''; ?>">This Year</a>
        </div>
        
        <div class="summary-grid">
            <div class="summary-card primary">
                <div class="icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <h3>Total Revenue</h3>
                <p class="value">₹<?= number_format($total_revenue, 2) ?></p>
            </div>
            <div class="summary-card success">
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3>Total Orders</h3>
                <p class="value"><?= $total_orders ?></p>
            </div>
            <div class="summary-card warning">
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3>Paid Orders</h3>
                <p class="value"><?= $paid_count ?></p>
            </div>
            <div class="summary-card info">
                <div class="icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                <h3>Payment Methods</h3>
                <p class="value"><?= $cod_count ?> / <?= $online_count ?></p>
                <p style="font-size: 0.75rem; color: var(--medium-gray); margin-top: 4px;">COD / Online</p>
            </div>
        </div>
        
        <div class="table-container">
            <div class="table-header">
                <h2>Order Details</h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>User ID</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>Payment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?= $order['order_id'] ?></td>
                            <td><?= $order['user_id'] ?></td>
                            <td><?= $order['order_date'] ?></td>
                            <td>
                                <span class="status-badge status-<?= strtolower($order['status']) ?>">
                                    <?= ucfirst($order['status']) ?>
                                </span>
                            </td>
                            <td>₹<?= number_format($order['total_amount'], 2) ?></td>
                            <td>
                                <?php if (isset($order['payment_method'])): ?>
                                    <span class="payment-badge payment-<?= strtolower($order['payment_method']) ?>">
                                        <?= $order['payment_method'] ?>
                                    </span>
                                <?php else: ?>
                                    <span>N/A</span>
                                <?php endif; ?>
                                
                                <?php if (isset($order['payment_status'])): ?>
                                    <span class="payment-status payment-status-<?= strtolower($order['payment_status']) ?>">
                                        <?= ucfirst($order['payment_status']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="payment-status payment-status-unpaid">Unpaid</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
