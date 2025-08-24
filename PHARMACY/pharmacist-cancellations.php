<?php
session_start();
include 'config.php';
// Redirect if not logged in
if (!isset($_SESSION['user'])) {
    header('Location: pharmmainpage.php');
    exit();
}
// Fetch unseen order cancellations
$sql = "SELECT oc.*, o.order_date, o.total_amount, u.username
        FROM order_cancellations oc
        JOIN orders o ON oc.order_id = o.order_id
        JOIN users u ON oc.user_id = u.user_id
        WHERE oc.seen_by_pharmacist = 0
        ORDER BY oc.cancelled_at DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Cancellations - Pharmacist Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #0d9488;
            --primary-light: #14b8a6;
            --primary-dark: #0f766e;
            --secondary: #0891b2;
            --accent: #06b6d4;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #1e293b;
            --gray: #64748b;
            --light-gray: #e2e8f0;
            --light: #f8fafc;
            --white: #ffffff;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light);
            color: var(--dark);
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
            flex-wrap: wrap;
            gap: 1.5rem;
        }
        
        .header-content h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 0.25rem;
        }
        
        .header-content p {
            color: var(--gray);
            font-size: 1rem;
        }
        
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background-color: var(--white);
            color: var(--primary);
            font-weight: 500;
            border-radius: 0.5rem;
            text-decoration: none;
            box-shadow: var(--shadow);
            transition: all 0.2s ease;
            border: 1px solid var(--light-gray);
        }
        
        .back-btn:hover {
            background-color: var(--primary);
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }
        
        .stat-card {
            background-color: var(--white);
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            border-left: 4px solid var(--primary);
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }
        
        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .stat-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.5rem;
            background-color: rgba(13, 148, 136, 0.1);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
        }
        
        .stat-label {
            color: var(--gray);
            font-size: 0.875rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        /* Main Content */
        .content-card {
            background-color: var(--white);
            border-radius: 0.75rem;
            box-shadow: var(--shadow);
            overflow: hidden;
        }
        
        .content-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--light-gray);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .content-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .content-title i {
            color: var(--primary);
        }
        
        .badge {
            background-color: var(--danger);
            color: var(--white);
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
        }
        
        .content-body {
            padding: 1.5rem;
        }
        
        /* Table */
        .table-wrapper {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th {
            text-align: left;
            padding: 0.75rem 1rem;
            font-weight: 600;
            color: var(--gray);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 2px solid var(--light-gray);
            background-color: var(--light);
        }
        
        td {
            padding: 1rem;
            border-bottom: 1px solid var(--light-gray);
        }
        
        tr:hover {
            background-color: rgba(13, 148, 136, 0.02);
        }
        
        .order-id {
            font-weight: 600;
            color: var(--primary);
        }
        
        .user-info {
            display: flex;
            flex-direction: column;
        }
        
        .username {
            font-weight: 500;
            color: var(--dark);
        }
        
        .user-id-text {
            font-size: 0.875rem;
            color: var(--gray);
        }
        
        .reason-text {
            max-width: 250px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .date-text {
            color: var(--gray);
            font-size: 0.875rem;
        }
        
        .amount {
            font-weight: 600;
            color: var(--success);
        }
        
        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background-color: var(--primary);
            color: var(--white);
            font-weight: 500;
            border: none;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.875rem;
        }
        
        .btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: var(--shadow);
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
        }
        
        .empty-icon {
            font-size: 3rem;
            color: var(--primary-light);
            margin-bottom: 1rem;
        }
        
        .empty-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        
        .empty-description {
            color: var(--gray);
            max-width: 500px;
            margin: 0 auto;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            
            .header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .content-header {
                padding: 1rem;
            }
            
            .content-body {
                padding: 1rem;
            }
            
            th, td {
                padding: 0.75rem 0.5rem;
                font-size: 0.875rem;
            }
            
            .reason-text {
                max-width: 150px;
            }
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .stat-card {
            animation: fadeIn 0.5s ease forwards;
        }
        
        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        
        .content-card {
            animation: fadeIn 0.5s ease forwards 0.4s;
            opacity: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-content">
                <h1>Order Cancellations</h1>
                <p>Review and manage customer cancellation requests</p>
            </div>
            <a href="pharmmainpage.php" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Dashboard</span>
            </a>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
                <div class="stat-value"><?= $result->num_rows ?></div>
                <div class="stat-label">New Cancellations</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                </div>
                <div class="stat-value"><?= date('M d') ?></div>
                <div class="stat-label">Today</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stat-value">
                    <?php
                    $userCountQuery = "SELECT COUNT(DISTINCT user_id) as count FROM order_cancellations WHERE seen_by_pharmacist = 0";
                    $userCountResult = $conn->query($userCountQuery);
                    $userCount = $userCountResult->fetch_assoc()['count'];
                    echo $userCount;
                    ?>
                </div>
                <div class="stat-label">Affected Users</div>
            </div>
        </div>
        
        <div class="content-card">
            <div class="content-header">
                <div class="content-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    Cancellation Requests
                    <?php if ($result->num_rows > 0): ?>
                        <span class="badge"><?= $result->num_rows ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="content-body">
                <?php if ($result->num_rows > 0): ?>
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>User</th>
                                    <th>Reason</th>
                                    <th>Cancelled At</th>
                                    <th>Order Date</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td>
                                            <div class="order-id">#<?= $row['order_id'] ?></div>
                                        </td>
                                        <td>
                                            <div class="user-info">
                                                <div class="username"><?= htmlspecialchars($row['username']) ?></div>
                                                <div class="user-id-text">ID: <?= $row['user_id'] ?></div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="reason-text" title="<?= htmlspecialchars($row['cancellation_reason']) ?>">
                                                <?= htmlspecialchars($row['cancellation_reason']) ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="date-text"><?= date('M d, Y H:i', strtotime($row['cancelled_at'])) ?></div>
                                        </td>
                                        <td>
                                            <div class="date-text"><?= date('M d, Y', strtotime($row['order_date'])) ?></div>
                                        </td>
                                        <td>
                                            <div class="amount">₹<?= number_format($row['total_amount'], 2) ?></div>
                                        </td>
                                        <td>
                                            <form method="POST" action="mark-cancellation-seen.php" style="display: inline;">
                                                <input type="hidden" name="cancel_id" value="<?= $row['id'] ?>">
                                                <button type="submit" class="btn">
                                                    <i class="fas fa-check"></i>
                                                    Mark Seen
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h3 class="empty-title">No New Cancellations</h3>
                        <p class="empty-description">
                            All cancellation requests have been reviewed. You're all caught up with customer notifications.
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>