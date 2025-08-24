<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: user-login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username'] ?? '');
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $phone = mysqli_real_escape_string($conn, $_POST['phone'] ?? '');
    $address = mysqli_real_escape_string($conn, $_POST['address'] ?? '');
    $new_password = $_POST['password'] ?? '';
    $profile_picture = null;

    if (!empty($_FILES['profile_picture']['tmp_name'])) {
        $profile_picture = addslashes(file_get_contents($_FILES['profile_picture']['tmp_name']));
    }

    $updateQuery = "UPDATE users SET 
        username = '$username', 
        email = '$email', 
        phone = '$phone', 
        address = '$address'";

    if (!empty($new_password)) {
        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
        $updateQuery .= ", password = '$hashedPassword'";
    }

    if ($profile_picture) {
        $updateQuery .= ", profile_picture = '$profile_picture'";
    }

    $updateQuery .= " WHERE user_id = $user_id";

    if (mysqli_query($conn, $updateQuery)) {
        $message = "Profile updated successfully!";
    } else {
        $message = "Error updating profile: " . mysqli_error($conn);
    }
}

$userQuery = mysqli_query($conn, "SELECT * FROM users WHERE user_id = $user_id");
$user = mysqli_fetch_assoc($userQuery);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f2f5; padding: 30px; }
        .container { max-width: 600px; margin: auto; background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.1); }
        h2 { text-align: center; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { font-weight: bold; display: block; margin-bottom: 5px; }
        input[type="text"], input[type="email"], input[type="password"], textarea {
            width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;
        }
        .preview {
            text-align: center; margin-bottom: 15px;
        }
        .preview img {
            max-width: 120px; height: auto; border-radius: 50%; border: 3px solid #0077cc;
        }
        .btn { background: #0077cc; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
        .btn:hover { background: #005fa3; }
        .success { color: green; text-align: center; margin-bottom: 10px; }
        .error { color: red; text-align: center; margin-bottom: 10px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Profile</h2>
    <?php if (!empty($message)): ?>
        <p class="<?php echo strpos($message, 'success') !== false ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </p>
    <?php endif; ?>
    <div style="text-align: center; margin-bottom: 20px;">
    <a href="user-dashboard.php" style="display: inline-block; background-color: #6c757d; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none;">⬅️ Back to Dashboard</a>
</div>

    <form method="POST" enctype="multipart/form-data">
        <div class="preview">
            <?php if (!empty($user['profile_picture'])): ?>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($user['profile_picture']); ?>" alt="Profile Picture">
            <?php else: ?>
                <img src="default-avatar.png" alt="No Profile Picture">
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Change Profile Picture:</label>
            <input type="file" name="profile_picture" accept="image/*" />
        </div>

        <div class="form-group">
            <label>Username:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>

        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>

        <div class="form-group">
            <label>Phone:</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
        </div>

        <div class="form-group">
            <label>Address:</label>
            <textarea name="address" rows="3"><?php echo htmlspecialchars($user['address']); ?></textarea>
        </div>

        <div class="form-group">
            <label>New Password (optional):</label>
            <input type="password" name="password" placeholder="Leave blank to keep existing">
        </div>

        <div style="text-align: center;">
            <button type="submit" class="btn">Update Profile</button>
        </div>
    </form>
</div>

</body>
</html>
