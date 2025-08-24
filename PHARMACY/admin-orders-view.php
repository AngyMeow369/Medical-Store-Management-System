<?php
session_start();
include "config.php";
// Fetch orders from history with user and pharmacist info
$ordersQuery = mysqli_query($conn, "
    SELECT 
        oh.*, 
        u.username AS customer_name, 
        p.E_USERNAME AS pharmacist_name
    FROM order_history oh
    JOIN users u ON oh.user_id = u.user_id
    LEFT JOIN pharmlogin p ON oh.pharmacist_id = p.E_ID
    ORDER BY oh.order_date DESC
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Orders Overview</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --secondary: #6366f1;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #1e293b;
            --light: #f8fafc;
            --gray: #64748b;
            --light-gray: #e2e8f0;
            --white: #ffffff;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
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

        .navbar {
            background-color: var(--white);
            color: var(--dark);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar h1::before {
            content: "📊";
            font-size: 1.75rem;
        }

        .nav-icons {
            display: flex;
            gap: 2rem;
        }

        .nav-icons a {
            color: var(--dark);
            text-align: center;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: all 0.2s ease;
            padding: 0.5rem;
            border-radius: 0.5rem;
        }

        .nav-icons a:hover {
            color: var(--primary);
            background-color: rgba(79, 70, 229, 0.1);
        }

        .nav-icons i {
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            margin: 1.5rem 2rem;
            padding: 0.75rem 1.5rem;
            background-color: var(--primary);
            color: var(--white);
            text-decoration: none;
            border-radius: 0.5rem;
            font-weight: 600;
            box-shadow: var(--shadow);
            transition: all 0.2s ease;
            gap: 0.5rem;
        }

        .back-button:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .container {
            padding: 0 2rem 2rem;
            max-width: 1200px;
            margin: auto;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-header h2 {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .page-header p {
            color: var(--gray);
            margin-top: 0.5rem;
        }

        .order-card {
            background-color: var(--white);
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            border-left: 4px solid var(--primary);
        }

        .order-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .order-id {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark);
        }

        .order-date {
            color: var(--gray);
            font-size: 0.875rem;
        }

        .order-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .order-info {
            display: flex;
            flex-direction: column;
        }

        .order-info-label {
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--gray);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.25rem;
        }

        .order-info-value {
            font-weight: 500;
            color: var(--dark);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            align-self: flex-start;
        }

        .pending {
            background-color: rgba(245, 158, 11, 0.15);
            color: var(--warning);
        }

        .approved {
            background-color: rgba(16, 185, 129, 0.15);
            color: var(--success);
        }

        .rejected {
            background-color: rgba(239, 68, 68, 0.15);
            color: var(--danger);
        }

        .toggle-btn {
            background: none;
            border: none;
            color: var(--primary);
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            padding: 0.5rem 0;
            margin-top: 0.5rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .toggle-btn:hover {
            color: var(--primary-dark);
        }

        .toggle-btn i {
            transition: transform 0.2s ease;
        }

        .toggle-btn.active i {
            transform: rotate(180deg);
        }

        .details {
            display: none;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid var(--light-gray);
        }

        .details-section {
            margin-bottom: 1rem;
        }

        .details-section:last-child {
            margin-bottom: 0;
        }

        .details-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .item-list {
            padding-left: 1.5rem;
            margin: 0.5rem 0;
        }

        .item-list li {
            margin-bottom: 0.5rem;
            color: var(--dark);
        }

        .map-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .map-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .no-orders {
            text-align: center;
            padding: 3rem;
            color: var(--gray);
        }

        .no-orders i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--light-gray);
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 1rem;
                padding: 1rem;
            }

            .nav-icons {
                width: 100%;
                justify-content: space-around;
            }

            .container {
                padding: 0 1rem 1rem;
            }

            .order-grid {
                grid-template-columns: 1fr;
            }

            .order-header {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>
</head>
<body>
<div class="navbar">
    <h1>Admin Dashboard</h1>
    <div class="nav-icons">
        <a href="admin-sales-summary.php">
            <i class="fas fa-file-invoice-dollar"></i>
            Sales Summary
        </a>
        <a href="admin-revenue-graph.php">
            <i class="fas fa-chart-line"></i>
            Revenue Chart
        </a>
    </div>
</div>

<a href="adminmainpage.php" class="back-button">
    <i class="fas fa-arrow-left"></i>
    Back to Dashboard
</a>

<div class="container">
    <div class="page-header">
        <h2>📦 All Orders Overview</h2>
        <p>View and manage all order history</p>
    </div>

    <?php
    if (mysqli_num_rows($ordersQuery) === 0) {
        echo "<div class='no-orders'>
            <i class='fas fa-box-open'></i>
            <p>No orders found in history.</p>
        </div>";
    } else {
        while ($order = mysqli_fetch_assoc($ordersQuery)) {
            $status = strtolower(trim($order['status']));
            $badgeClass = in_array($status, ['approved', 'rejected']) ? $status : 'pending';
            $pharmacist = $order['pharmacist_name'] ?? 'Not assigned';
            
            $statusIcon = '';
            if ($badgeClass === 'approved') {
                $statusIcon = '✅';
            } elseif ($badgeClass === 'rejected') {
                $statusIcon = '❌';
            } else {
                $statusIcon = '⏳';
            }
            
            echo "<div class='order-card'>
                <div class='order-header'>
                    <div>
                        <div class='order-id'>Order #{$order['order_id']}</div>
                        <div class='order-date'>{$order['order_date']}</div>
                    </div>
                    <div>
                        <span class='status-badge {$badgeClass}'>{$statusIcon} {$order['status']}</span>
                    </div>
                </div>
                <div class='order-grid'>
                    <div class='order-info'>
                        <span class='order-info-label'>Customer</span>
                        <span class='order-info-value'>" . htmlspecialchars($order['customer_name']) . "</span>
                    </div>
                    <div class='order-info'>
                        <span class='order-info-label'>Pharmacist</span>
                        <span class='order-info-value'>" . htmlspecialchars($pharmacist) . "</span>
                    </div>
                    <div class='order-info'>
                        <span class='order-info-label'>Total Amount</span>
                        <span class='order-info-value'>₹{$order['total_amount']}</span>
                    </div>
                </div>
                <button class='toggle-btn' onclick='toggleDetails(this)'>
                    View Details <i class='fas fa-chevron-down'></i>
                </button>
                <div class='details'>";
            
            // Location (if available)
            if (!empty($order['latitude']) && !empty($order['longitude'])) {
                echo "<div class='details-section'>
                    <div class='details-title'>
                        <i class='fas fa-map-marker-alt'></i> Location
                    </div>
                    <a href='https://www.google.com/maps?q={$order['latitude']},{$order['longitude']}' target='_blank' class='map-link'>
                        🗺️ View on Map
                    </a>
                </div>";
            }
            
            // Pharmacist Notes
            if (!empty($order['action_notes'])) {
                echo "<div class='details-section'>
                    <div class='details-title'>
                        <i class='fas fa-sticky-note'></i> Pharmacist Notes
                    </div>
                    <div>" . htmlspecialchars($order['action_notes']) . "</div>
                </div>";
            }
            
            // Fetch and display ordered items
            $itemsResult = mysqli_query($conn, "
                SELECT oih.*, m.med_name 
                FROM order_items_history oih
                JOIN meds m ON oih.med_id = m.med_id
                WHERE oih.order_id = {$order['order_id']}
            ");
            if (mysqli_num_rows($itemsResult) > 0) {
                echo "<div class='details-section'>
                    <div class='details-title'>
                        <i class='fas fa-pills'></i> Ordered Items
                    </div>
                    <ul class='item-list'>";
                while ($item = mysqli_fetch_assoc($itemsResult)) {
                    echo "<li>" . htmlspecialchars($item['med_name']) . 
                         " - Qty: {$item['quantity']} - ₹{$item['total_price']}</li>";
                }
                echo "</ul></div>";
            }
            echo "</div></div>";
        }
    }
    ?>
</div>

<script>
function toggleDetails(btn) {
    const details = btn.nextElementSibling;
    const isOpen = details.style.display === 'block';
    details.style.display = isOpen ? 'none' : 'block';
    btn.classList.toggle('active');
    btn.innerHTML = isOpen ? 
        'View Details <i class="fas fa-chevron-down"></i>' : 
        'Hide Details <i class="fas fa-chevron-up"></i>';
}
</script>
</body>
</html>