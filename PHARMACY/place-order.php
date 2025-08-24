<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: user-login.php");
    exit();
}

include "config.php";
$user_id = $_SESSION['user_id'];

// ✅ Handle prescription image upload
if (isset($_FILES['prescription']) && $_FILES['prescription']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/';
    $filename = time() . '_' . basename($_FILES['prescription']['name']);
    $targetPath = $uploadDir . $filename;

    if (move_uploaded_file($_FILES['prescription']['tmp_name'], $targetPath)) {
        $prescriptionImage = $targetPath;
    } else {
        $prescriptionImage = null;
    }
} else {
    $prescriptionImage = null;
}

// Fetch cart items
$cartQuery = "
    SELECT c.cart_id, c.quantity, m.MED_ID, m.MED_NAME, m.MED_PRICE 
    FROM cart c
    JOIN meds m ON c.med_id = m.MED_ID
    WHERE c.user_id = $user_id
";
$cartResult = mysqli_query($conn, $cartQuery);

if (mysqli_num_rows($cartResult) == 0) {
    echo "
    <div style='
        max-width: 600px;
        margin: 100px auto;
        text-align: center;
        padding: 40px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        font-family: Arial, sans-serif;
    '>
        <img src='https://cdn-icons-png.flaticon.com/512/102/102661.png' alt='Empty Cart' width='100' style='margin-bottom: 20px;'>
        <h2>Your Cart is Empty</h2>
        <p style='font-size: 16px; color: #555;'>Looks like you haven't added anything to your cart yet.</p>
        <a href='user-medicines.php' style='
            display: inline-block;
            margin-top: 20px;
            background-color: #0077cc;
            color: white;
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        '>🛒 Shop Now</a>
    </div>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Confirm Order</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('payimg.jpg') no-repeat center center fixed;
            background-size: cover;
            padding: 30px;
        }
        .container {
            max-width: 700px;
            margin: auto;
            background: rgba(255, 255, 255, 0.95);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        h2 {
            margin-bottom: 20px;
        }
        #map {
            height: 350px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        select, input[type=submit], input[type=file] {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
            width: 100%;
            box-sizing: border-box;
        }
        input[type=submit] {
            background: #0077cc;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        .error {
            color: red;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Confirm Your Delivery Location</h2>
    <p>Please click on the map or use the search bar to set your delivery location:</p>

    <div id="map"></div>

    <!-- ✅ Updated form with file upload -->
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="latitude" id="latitude" required>
        <input type="hidden" name="longitude" id="longitude" required>

        <label for="payment_method">Select Payment Method:</label>
        <select name="payment_method" id="payment_method" required>
            <option value="">-- Choose --</option>
            <option value="COD">Cash on Delivery</option>
            <option value="Online">Online Payment</option>
        </select>

        <!-- New file input -->
        <label for="prescription">Upload Prescription (optional):</label>
        <input type="file" name="prescription" id="prescription" accept="image/*">

        <input type="submit" name="place_order" value="Place Order">
    </form>

    <?php
    if (isset($_POST['place_order'])) {
        $latitude = mysqli_real_escape_string($conn, $_POST['latitude']);
        $longitude = mysqli_real_escape_string($conn, $_POST['longitude']);
        $paymentMethod = mysqli_real_escape_string($conn, $_POST['payment_method']);

        if (empty($latitude) || empty($longitude) || empty($paymentMethod)) {
            echo "<div class='error'>Please select a location and payment method.</div>";
        } else {
            mysqli_data_seek($cartResult, 0);
            $totalAmount = 0;
            $items = [];

            while ($row = mysqli_fetch_assoc($cartResult)) {
                $subtotal = $row['MED_PRICE'] * $row['quantity'];
                $totalAmount += $subtotal;
                $items[] = [
                    'med_id' => $row['MED_ID'],
                    'quantity' => $row['quantity'],
                    'unit_price' => $row['MED_PRICE'],
                    'total_price' => $subtotal
                ];
            }

            // ✅ Include prescription_image in the insert
            $insertOrder = mysqli_query($conn, "
                INSERT INTO orders (
                    user_id, order_date, status, total_amount,
                    latitude, longitude, payment_method, payment_status, order_type, prescription_image
                ) VALUES (
                    $user_id, NOW(), 'pending', $totalAmount,
                    '$latitude', '$longitude', '$paymentMethod', " . 
                    ($paymentMethod === 'COD' ? "'paid'" : "'unpaid'") . ", 'delivery', " . 
                    ($prescriptionImage ? "'$prescriptionImage'" : "NULL") . "
                )
            ");

            if ($insertOrder) {
                $order_id = mysqli_insert_id($conn);

                foreach ($items as $item) {
                    mysqli_query($conn, "
                        INSERT INTO order_items (order_id, med_id, quantity, unit_price, total_price)
                        VALUES (
                            $order_id,
                            {$item['med_id']},
                            {$item['quantity']},
                            {$item['unit_price']},
                            {$item['total_price']}
                        )
                    ");

                    mysqli_query($conn, "
                        UPDATE meds 
                        SET MED_QTY = MED_QTY - {$item['quantity']}
                        WHERE MED_ID = {$item['med_id']}
                    ");
                }

                mysqli_query($conn, "DELETE FROM cart WHERE user_id = $user_id");

                if ($paymentMethod === "Online") {
                    echo "<script>window.location.href='payment-gateway.php?order_id={$order_id}';</script>";
                } else {
                    echo "<script>alert('✅ Order placed successfully with Cash on Delivery!'); window.location.href='user-orders.php';</script>";
                }
            } else {
                echo "<div class='error'>Error placing order: " . mysqli_error($conn) . "</div>";
            }
        }
    }
    ?>
</div>

<script>
    var map = L.map('map').setView([10.8505, 76.2711], 9);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var marker;

    L.Control.geocoder({
        defaultMarkGeocode: false
    })
    .on('markgeocode', function(e) {
        var latlng = e.geocode.center;
        map.setView(latlng, 15);
        document.getElementById('latitude').value = latlng.lat.toFixed(8);
        document.getElementById('longitude').value = latlng.lng.toFixed(8);

        if (marker) marker.setLatLng(latlng);
        else marker = L.marker(latlng).addTo(map);
    })
    .addTo(map);

    map.on('click', function(e) {
        let lat = e.latlng.lat.toFixed(8);
        let lng = e.latlng.lng.toFixed(8);
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;

        if (marker) marker.setLatLng(e.latlng);
        else marker = L.marker(e.latlng).addTo(map);
    });
</script>
</body>
</html>