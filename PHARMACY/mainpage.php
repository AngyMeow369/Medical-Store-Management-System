<?php
session_start();
include "config.php";

$showSuccess = false;
$error = "";

if (isset($_POST['submit'])) {
  $uname = mysqli_real_escape_string($conn, $_POST['uname']);
  $password = mysqli_real_escape_string($conn, $_POST['pwd']);

  if ($uname != "" && $password != "") {
    $sql = "SELECT * FROM admin WHERE a_username='$uname' AND a_password='$password'";
    $result = $conn->query($sql);
    $row = $result->fetch_row();

    if ($row) {
      $_SESSION['user'] = $uname;
      $_SESSION['login_success'] = true;
      header("Location: " . $_SERVER['PHP_SELF']);
      exit();
    } else {
      $error = "Invalid username or password!";
    }
  }
}

if (isset($_SESSION['login_success'])) {
  $showSuccess = true;
  unset($_SESSION['login_success']);
}

if (isset($_POST['psubmit'])) {
  header("location:pharmLogin.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Pharmacia - Admin Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Google Fonts & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

  <style>
    * {
      margin: 0;
      padding: 0;
      font-family: 'Outfit', sans-serif;
      box-sizing: border-box;
    }

    body {
      height: 100vh;
      background: linear-gradient(to right, #f4fcff, #e2f7f9);
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-container {
      background: #ffffff;
      border-radius: 14px;
      box-shadow: 0 8px 25px rgba(0, 180, 216, 0.15);
      padding: 45px 40px;
      max-width: 400px;
      width: 90%;
      text-align: center;
      border-top: 5px solid #00bcd4;
      position: relative;
    }

    .logo {
      font-size: 42px;
      color: #00bcd4;
      margin-bottom: 15px;
    }

    .login-container h1 {
      font-size: 26px;
      margin-bottom: 5px;
      color: #004d66;
    }

    .login-container p {
      font-size: 14px;
      color: #555;
      margin-bottom: 30px;
    }

    .textbox {
      width: 100%;
      padding: 12px 15px;
      margin-bottom: 18px;
      background: #f6fbfc;
      border: 1px solid #c0e4ea;
      border-radius: 8px;
      font-size: 15px;
      color: #333;
      transition: all 0.3s;
    }

    .textbox:focus {
      background: #ffffff;
      border-color: #00bcd4;
      outline: none;
    }

    input[type="submit"] {
      width: 100%;
      padding: 12px;
      background: #00bcd4;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 15px;
      cursor: pointer;
      transition: background 0.3s ease;
      margin-top: 8px;
    }

    input[type="submit"]:hover {
      background: #0097a7;
    }

    .pharmacist-link {
      text-align: center;
      margin-top: 12px;
    }

    .link-button {
      background: none;
      border: none;
      color: #00bcd4;
      font-size: 15px;
      cursor: pointer;
      text-decoration: underline;
      padding: 0;
      transition: color 0.3s;
    }

    .link-button:hover {
      color: #008ca1;
    }

    .error {
      color: #d32f2f;
      font-size: 14px;
      margin-top: 12px;
    }

    .footer {
      text-align: center;
      font-size: 12px;
      color: #888;
      margin-top: 30px;
    }

    /* Success Modal */
    .modal {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.35);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
    }

    .modal-content {
      background: #ffffff;
      padding: 30px;
      border-radius: 14px;
      box-shadow: 0 6px 20px rgba(0, 180, 216, 0.2);
      text-align: center;
      max-width: 320px;
      animation: fadeIn 0.4s ease;
    }

    .modal-content h2 {
      color: #00bcd4;
      margin-bottom: 10px;
      font-size: 24px;
    }

    .modal-content p {
      color: #444;
      font-size: 15px;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 480px) {
      .login-container {
        padding: 30px 20px;
      }
    }
  </style>
</head>
<body>

  <div class="login-container">
    <div class="logo"><i class="fas fa-user-shield"></i></div>
    <h1>Admin Login</h1>
    <p>Administrator Control Panel</p>

    <form method="post" action="">
      <input type="text" class="textbox" name="uname" placeholder="Username" required />
      <input type="password" class="textbox" name="pwd" placeholder="Password" required />
      <input type="submit" value="Login as Admin" name="submit" />

      <div class="pharmacist-link">
        <button type="submit" name="psubmit" class="link-button">→ Switch to Pharmacist Login</button>
      </div>

      <?php if (!empty($error)): ?>
        <div class="error"><?= $error ?></div>
      <?php endif; ?>
    </form>

    <div class="footer">
      &copy; 2025 Pharmacia. Trusted by healthcare.
    </div>
  </div>

  <?php if ($showSuccess): ?>
    <div class="modal" id="successModal">
      <div class="modal-content">
        <h2>Login Successful</h2>
        <p>Welcome back, Admin! Redirecting...</p>
      </div>
    </div>

    <script>
      setTimeout(function () {
        window.location.href = "adminmainpage.php";
      }, 2000); // Redirect after 2 seconds
    </script>
  <?php endif; ?>

</body>
</html>