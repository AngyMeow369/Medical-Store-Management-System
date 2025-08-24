<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: user-login.php");
    exit();
}

include "config.php";
$user_id = $_SESSION['user_id'];

// Fetch username
$userQuery = mysqli_query($conn, "SELECT username, profile_picture FROM users WHERE user_id = $user_id");
$userData = mysqli_fetch_assoc($userQuery);

// Fetch cart items
$query = "
    SELECT c.cart_id, c.quantity, m.MED_NAME, m.MED_PRICE, m.MED_ID, m.CATEGORY
    FROM cart c
    JOIN meds m ON c.med_id = m.MED_ID
    WHERE c.user_id = $user_id
";

$result = mysqli_query($conn, $query);
$total = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
               --primary: #0ea5e9;
            --primary-light: #38bdf8;
            --secondary: #8b5cf6;
            --dark: #1e293b;
            --gray: #64748b;
            --light-gray: #e2e8f0;
            --danger: #ef4444;
            --success: #10b981;
            --warning: #f59e0b;
            --background: #f8fafc;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background);
            color: var(--dark);
            line-height: 1.6;
        }
        
        .navbar {
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            padding: 1rem 2rem;
            color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .navbar-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }
        
        .navbar-links a {
            color: rgba(12, 0, 99, 0.55);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .navbar-links a:hover {
            opacity: 0.8;
        }
        
        .profile-dropdown {
            position: relative;
            display: inline-block;
        }
        
        .profile-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .profile-icon:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        
        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            width: 220px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.3s ease;
            z-index: 100;
            overflow: hidden;
        }
        
        .profile-dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(5px);
        }
        
        .dropdown-header {
            padding: 1rem;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .dropdown-header-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .dropdown-header-text {
            flex: 1;
        }
        
        .dropdown-header-text h4 {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .dropdown-header-text p {
            font-size: 0.75rem;
            opacity: 0.9;
        }
        
        .dropdown-item {
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--dark);
            text-decoration: none;
            transition: all 0.2s ease;
            border-bottom: 1px solid var(--light-gray);
        }
        
        .dropdown-item:last-child {
            border-bottom: none;
        }
        
        .dropdown-item:hover {
            background: var(--light-gray);
            color: var(--primary);
            padding-left: 1.25rem;
        }
        
        .dropdown-item i {
            width: 20px;
            text-align: center;
            color: var(--primary);
        }
        
        .dropdown-divider {
            height: 1px;
            background: var(--light-gray);
            margin: 0.5rem 0;
        }
        
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .cart-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        
        .cart-header {
            background: var(--light-gray);
            padding: 1.5rem;
            border-bottom: 1px solid var(--light-gray);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .cart-header h2 {
            color: var(--primary);
            font-weight: 600;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .cart-icon {
            font-size: 1.5rem;
            color: var(--primary);
        }
        
        .cart-count {
            background: var(--primary);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .cart-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .cart-table th {
            padding: 1.25rem 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--gray);
            border-bottom: 1px solid var(--light-gray);
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .cart-table td {
            padding: 1.5rem 1rem;
            border-bottom: 1px solid var(--light-gray);
            vertical-align: middle;
        }
        
        .cart-table tr:last-child td {
            border-bottom: none;
        }
        
        .cart-table tr:hover {
            background-color: rgba(14, 165, 233, 0.03);
        }
        
        .product-info {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }
        
        .product-image {
            width: 70px;
            height: 70px;
            border-radius: 12px;
            object-fit: cover;
            background-color: var(--light-gray);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray);
            font-size: 1.5rem;
        }
        
        .product-details h4 {
            font-weight: 600;
            margin-bottom: 0.25rem;
            font-size: 1rem;
        }
        
        .product-details p {
            color: var(--gray);
            font-size: 0.875rem;
        }
        
        .price {
            font-weight: 600;
            color: var(--dark);
            font-size: 1rem;
        }
        
        .quantity-control {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .quantity-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: 1px solid var(--light-gray);
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .quantity-btn:hover {
            background: var(--light-gray);
            color: var(--primary);
        }
        
        .quantity-input {
            width: 60px;
            height: 40px;
            border: 1px solid var(--light-gray);
            border-radius: 8px;
            text-align: center;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .quantity-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
        }
        
        .subtotal {
            font-weight: 600;
            color: var(--primary);
            font-size: 1rem;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            font-size: 0.875rem;
        }
        
        .btn-danger {
            background-color: var(--danger);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #dc2626;
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: white;
            padding: 1rem 2rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.2);
        }
        
        .btn-primary:hover {
            background-color: #0284c7;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(14, 165, 233, 0.25);
        }
        
        .cart-footer {
            background: var(--light-gray);
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid var(--light-gray);
        }
        
        .cart-total {
            display: flex;
            flex-direction: column;
        }
        
        .cart-total-label {
            font-size: 0.875rem;
            color: var(--gray);
            margin-bottom: 0.25rem;
        }
        
        .cart-total-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary);
        }
        
        .empty-cart {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--gray);
        }
        
        .empty-cart i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--light-gray);
        }
        
        .empty-cart h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }
        
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: var(--success);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }
        
        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.25rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .badge-primary {
            background: rgba(14, 165, 233, 0.1);
            color: var(--primary);
        }
        
        .badge-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }
        
        .badge-warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }
        
        @media (max-width: 768px) {
            .cart-table {
                display: block;
                overflow-x: auto;
            }
            
            .product-info {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .cart-footer {
                flex-direction: column;
                gap: 1rem;
            }
            
            .navbar {
                flex-direction: column;
                gap: 1rem;
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-brand">
            <i class="fas fa-pills"></i>
            <span>Medical store</span>
        </div>
        <div class="navbar-links">
            <span>Welcome, <?php echo htmlspecialchars($userData['username']); ?></span>
            <a href="user-dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
            <a href="user-logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            
            <!-- Profile Dropdown -->
            <div class="profile-dropdown">
                <div class="profile-icon">
            <?php if (!empty($userData['profile_picture'])): ?>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($userData['profile_picture']); ?>" alt="Profile Picture" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
            <?php else: ?>
                <i class="fas fa-user"></i>
            <?php endif; ?>
            </div>

                <div class="dropdown-menu">
                    <div class="dropdown-header">
                        <div class="dropdown-header-icon">
    <?php if (!empty($userData['profile_picture'])): ?>
        <img src="data:image/jpeg;base64,<?php echo base64_encode($userData['profile_picture']); ?>" alt="Profile Picture" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
    <?php else: ?>
        <i class="fas fa-user"></i>
    <?php endif; ?>
</div>

                        <div class="dropdown-header-text">
                            <h4><?php echo htmlspecialchars($userData['username']); ?></h4>
                            <p>Manage your account</p>
                        </div>
                    </div>
                    <a href="user-profile.php" class="dropdown-item">
                        <i class="fas fa-user-circle"></i>
                        <span>My Profile</span>
                    </a>
                    <a href="user-order-history.php" class="dropdown-item">
                        <i class="fas fa-shopping-bag"></i>
                        <span>My Orders</span>
                    </a>
                    <a href="user-wishlist.php" class="dropdown-item">
                        <i class="fas fa-heart"></i>
                        <span>Wishlist</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="user-logout.php" class="dropdown-item">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    
    <div class="container">
        <div class="cart-container">
            <div class="cart-header">
                <h2>
                    <i class="fas fa-shopping-cart cart-icon"></i>
                    Your Shopping Cart
                </h2>
                <div class="cart-count"><?php echo mysqli_num_rows($result); ?> items</div>
            </div>
            
            <?php if (mysqli_num_rows($result) > 0): ?>
                <div class="table-responsive">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): 
                                $subtotal = $row['MED_PRICE'] * $row['quantity'];
                                $total += $subtotal;
                                
                                // Determine icon based on medicine category
                                $icon = 'fa-pills'; // Default icon
                                if (isset($row['CATEGORY'])) {
                                    switch (strtolower($row['CATEGORY'])) {
                                        case 'tablet':
                                            $icon = 'fa-tablets';
                                            break;
                                        case 'syrup':
                                            $icon = 'fa-prescription-bottle';
                                            break;
                                        case 'injection':
                                            $icon = 'fa-syringe';
                                            break;
                                        case 'capsule':
                                            $icon = 'fa-capsules';
                                            break;
                                        case 'ointment':
                                            $icon = 'fa-pump-soap';
                                            break;
                                        case 'drops':
                                            $icon = 'fa-eye-dropper';
                                            break;
                                        case 'inhaler':
                                            $icon = 'fa-lungs';
                                            break;
                                    }
                                }
                            ?>
                            <tr>
                                <td>
                                    <div class="product-info">
                                        <div class="product-image">
                                            <i class="fas <?php echo $icon; ?>"></i>
                                        </div>
                                        <div class="product-details">
                                            <h4><?php echo htmlspecialchars($row['MED_NAME']); ?></h4>
                                            <p class="badge badge-primary">ID: <?php echo $row['MED_ID']; ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="price">₹<?php echo number_format($row['MED_PRICE'], 2); ?></td>
                                <td>
                                    <div class="quantity-control">
                                        <div class="quantity-btn minus-btn">
                                            <i class="fas fa-minus"></i>
                                        </div>
                                        <input 
                                            type="number" 
                                            class="quantity-input" 
                                            data-cart-id="<?php echo $row['cart_id']; ?>" 
                                            data-price="<?php echo $row['MED_PRICE']; ?>" 
                                            value="<?php echo $row['quantity']; ?>" 
                                            min="1"
                                        >
                                        <div class="quantity-btn plus-btn">
                                            <i class="fas fa-plus"></i>
                                        </div>
                                    </div>
                                </td>
                                <td class="subtotal" id="subtotal-<?php echo $row['cart_id']; ?>">
                                    ₹<?php echo number_format($subtotal, 2); ?>
                                </td>
                                <td>
                                    <a href="remove-from-cart.php?cart_id=<?php echo $row['cart_id']; ?>" 
                                       class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Remove
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="cart-footer">
                    <div class="cart-total">
                        <span class="cart-total-label">Total Amount</span>
                        <span class="cart-total-value" id="grand-total">₹<?php echo number_format($total, 2); ?></span>
                    </div>
                    <button id="choose-method-btn" class="btn btn-primary">
    <i class="fas fa-shopping-cart"></i> Place Order
</button>

<!-- Modal for order method -->
<div id="order-method-modal" style="display: none; position: fixed; inset: 0; background-color: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 2rem; border-radius: 12px; text-align: center;">
        <h3>Choose Order Method</h3>
        <p>Please select how you'd like to receive your order.</p>

        <!-- Prescription Upload (shared by both forms) -->
        <form id="prescription-form" style="margin-top: 1rem;" enctype="multipart/form-data">
            <label for="prescription" style="display:block; margin-bottom: 0.5rem; font-weight: 500;">Upload Prescription (optional):</label>
            <input type="file" name="prescription" id="prescription" accept="image/*" required style="margin-bottom: 1rem;">
        </form>

        <div>
            <!-- Shared Prescription uploaded via JS -->
            <form id="order-online-form" action="place-order.php" method="POST" enctype="multipart/form-data" style="display:inline;">
                <input type="hidden" name="method" value="online">
                <button type="submit" class="btn btn-primary" style="margin: 0.5rem;">Order Online</button>
            </form>

            <form id="order-collect-form" action="collect-order.php" method="POST" enctype="multipart/form-data" style="display:inline;">
                <input type="hidden" name="method" value="collect">
                <button type="submit" class="btn btn-warning" style="margin: 0.5rem;">Collect by Yourself</button>
            </form>
        </div>

        <button onclick="document.getElementById('order-method-modal').style.display='none'" class="btn btn-danger" style="margin-top: 1rem;">Cancel</button>
    </div>
</div>

                </div>
            <?php else: ?>
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <h3>Your cart is empty</h3>
                    <p>Looks like you haven't added any medicines to your cart yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div id="toast" class="toast">
        <i class="fas fa-check-circle"></i>
        <span id="toast-message"></span>
    </div>
    
    <script>
    // Attach logic to each quantity input
    document.querySelectorAll('.quantity-input').forEach(input => {
        const minusBtn = input.previousElementSibling;
        const plusBtn = input.nextElementSibling;

        // Handle minus click
        minusBtn.addEventListener('click', () => {
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
                input.dispatchEvent(new Event('change'));
            }
        });

        // Handle plus click
        plusBtn.addEventListener('click', () => {
            input.value = parseInt(input.value) + 1;
            input.dispatchEvent(new Event('change'));
        });

        // On quantity change
        input.addEventListener('change', function () {
            const cartId = this.dataset.cartId;
            const price = parseFloat(this.dataset.price);
            const newQty = parseInt(this.value);

            if (newQty < 1) {
                showToast("Quantity must be at least 1");
                this.value = 1;
                return;
            }

            // Update subtotal visually
            const subtotal = price * newQty;
            document.getElementById('subtotal-' + cartId).textContent = '₹' + subtotal.toFixed(2);

            // Send AJAX to backend
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'update-cart-ajax.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send(`cart_id=${cartId}&quantity=${newQty}`);

            // Recalculate grand total
            let newTotal = 0;
            document.querySelectorAll('.quantity-input').forEach(input => {
                const qty = parseInt(input.value);
                const price = parseFloat(input.dataset.price);
                newTotal += qty * price;
            });
            document.getElementById('grand-total').textContent = '₹' + newTotal.toFixed(2);
        });
    });

    // ✅ Move toast function outside the loop
    function showToast(message) {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');

        toastMessage.textContent = message;
        toast.classList.add('show');

        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

    document.getElementById('choose-method-btn').addEventListener('click', () => {
    document.getElementById('order-method-modal').style.display = 'flex';
});

// Copy prescription input into both forms on submission
document.getElementById('order-online-form').addEventListener('submit', function (e) {
    const fileInput = document.getElementById('prescription');
    if (fileInput.files.length > 0) {
        const clone = fileInput.cloneNode();
        clone.name = "prescription";
        this.appendChild(clone);
    }
});

document.getElementById('order-collect-form').addEventListener('submit', function (e) {
    const fileInput = document.getElementById('prescription');
    if (fileInput.files.length > 0) {
        const clone = fileInput.cloneNode();
        clone.name = "prescription";
        this.appendChild(clone);
    }
});


</script>

</body>
</html>
