<?php
// user-profile.php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: user-login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT username, email, phone, address, profile_picture FROM users WHERE user_id = $user_id");

if (!$query || mysqli_num_rows($query) === 0) {
    echo "<p>User not found.</p>";
    exit();
}

$user = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Profile</title>
    <style>
        body { font-family: Arial; background: #f5f7fa; margin: 0; padding: 20px; }
        .profile-container { max-width: 600px; margin: auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 0 10px rgba(0,0,0,0.05); }
        .profile-pic { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid #007bff; }
        .field { margin: 15px 0; }
        .label { font-weight: bold; color: #333; }
        .value { margin-top: 5px; color: #555; }
        .btn { display: inline-block; background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="profile-container">
        <div style="text-align: center; margin-bottom: 20px;">
    <a href="user-dashboard.php" style="display: inline-block; background-color: #6c757d; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none;">⬅️ Back to Dashboard</a>
</div>
        <h2>Your Profile</h2>
        <div class="field">
            <?php if ($user['profile_picture']) {
                echo '<img class="profile-pic" src="data:image/jpeg;base64,' . base64_encode($user['profile_picture']) . '" />';
            } else {
                echo '<img class="profile-pic" src="default-avatar.png" />';
            } ?>
        </div>
        <div class="field"><div class="label">Username:</div><div class="value"><?php echo htmlspecialchars($user['username']); ?></div></div>
        <div class="field"><div class="label">Email:</div><div class="value"><?php echo htmlspecialchars($user['email']); ?></div></div>
        <div class="field"><div class="label">Phone:</div><div class="value"><?php echo htmlspecialchars($user['phone']); ?></div></div>
        <div class="field"><div class="label">Address:</div><div class="value"><?php echo htmlspecialchars($user['address']); ?></div></div>

        <a class="btn" href="edit-user-profile.php">Edit Profile</a>
    </div>
</body>
</html>
