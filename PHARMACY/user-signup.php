<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>MediFast Pharmacy | Signup</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    :root {
      --primary: #0077b6;
      --accent: #00b4d8;
      --light: #caf0f8;
      --gray: #6c757d;
      --danger: #d00000;
      --success: #38b000;
      --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      --transition: all 0.3s ease-in-out;
    }
    
    body {
      font-family: 'Roboto', sans-serif;
      background: linear-gradient(135deg, #f0f8ff 0%, #e1f5fe 100%);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    
    .navbar {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
    }
    
    .navbar-brand {
      font-weight: 700;
      color: var(--primary);
      font-size: 1.8rem;
      display: flex;
      align-items: center;
    }
    
    .nav-link {
      font-weight: 500;
      position: relative;
      margin: 0 10px;
      transition: var(--transition);
    }
    
    .nav-link:after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      bottom: -2px;
      left: 0;
      background-color: var(--primary);
      transition: var(--transition);
    }
    
    .nav-link:hover:after {
      width: 100%;
    }
    
    .signup-container {
      max-width: 500px;
      margin: 60px auto;
      background: white;
      padding: 40px;
      border-radius: 20px;
      box-shadow: var(--card-shadow);
      position: relative;
      overflow: hidden;
      z-index: 1;
    }
    
    .signup-container:before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 5px;
      background: linear-gradient(90deg, var(--primary), var(--accent));
    }
    
    h2 {
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      color: var(--primary);
      text-align: center;
      margin-bottom: 30px;
      position: relative;
    }
    
    h2:after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 50px;
      height: 3px;
      background: linear-gradient(90deg, var(--primary), var(--accent));
      border-radius: 3px;
    }
    
    .form-label {
      font-weight: 500;
      color: var(--gray);
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 8px;
    }
    
    .form-control {
      border-radius: 12px;
      padding: 12px 20px;
      border: 1.5px solid rgba(0,0,0,0.1);
      transition: var(--transition);
      background-color: #f8f9fa;
      font-size: 0.95rem;
    }
    
    .form-control:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 0.2rem rgba(0,119,182,0.25);
      background-color: white;
    }
    
    .input-group-text {
      background-color: var(--primary);
      color: white;
      border: none;
      border-radius: 12px 0 0 12px;
      padding: 0 15px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: var(--transition);
    }
    
    .input-group {
      margin-bottom: 20px;
    }
    
    .input-group .form-control {
      border-radius: 0 12px 12px 0;
    }
    
    .btn-primary {
      background-color: var(--primary);
      border: none;
      font-weight: 600;
      padding: 14px;
      border-radius: 50px;
      width: 100%;
      transition: var(--transition);
      font-size: 1.05rem;
      letter-spacing: 0.5px;
      box-shadow: 0 4px 15px rgba(0, 119, 182, 0.3);
      position: relative;
      overflow: hidden;
    }
    
    .btn-primary:hover {
      background-color: #005fa3;
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(0, 119, 182, 0.4);
    }
    
    .btn-primary:before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: 0.5s;
    }
    
    .btn-primary:hover:before {
      left: 100%;
    }
    
    .message {
      text-align: center;
      color: var(--success);
      font-weight: 500;
      margin-top: 20px;
      padding: 12px;
      border-radius: 10px;
      background-color: rgba(56, 176, 0, 0.1);
      border-left: 4px solid var(--success);
      animation: fadeIn 0.5s ease-in-out;
    }
    
    .error {
      text-align: center;
      color: var(--danger);
      font-weight: 500;
      margin-top: 20px;
      padding: 12px;
      border-radius: 10px;
      background-color: rgba(208, 0, 0, 0.1);
      border-left: 4px solid var(--danger);
      animation: fadeIn 0.5s ease-in-out;
    }
    
    .login-link {
      text-align: center;
      margin-top: 25px;
      color: var(--gray);
      font-size: 0.95rem;
    }
    
    .login-link a {
      color: var(--primary);
      font-weight: 500;
      text-decoration: none;
      transition: var(--transition);
    }
    
    .login-link a:hover {
      color: var(--accent);
      text-decoration: underline;
    }
    
    .features {
      display: flex;
      justify-content: space-around;
      margin: 40px 0 20px;
      flex-wrap: wrap;
    }
    
    .feature {
      text-align: center;
      padding: 15px;
      flex: 1;
      min-width: 120px;
      max-width: 150px;
    }
    
    .feature i {
      font-size: 2rem;
      color: var(--primary);
      margin-bottom: 10px;
    }
    
    .feature p {
      font-size: 0.85rem;
      color: var(--gray);
      margin: 0;
    }
    
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    @media (max-width: 576px) {
      .signup-container {
        margin: 30px 15px;
        padding: 30px 20px;
      }
      
      .features {
        margin: 20px 0;
      }
      
      .feature {
        min-width: 100px;
      }
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container">
      <a class="navbar-brand" href="#">
        <i class="fas fa-pills me-2"></i>MediFast Pharmacy
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="homepage.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="index.html">Services</a></li>
          <li class="nav-item"><a class="nav-link" href="index.html">Products</a></li>
          <li class="nav-item"><a class="nav-link" href="index.html">Prescriptions</a></li>
          <li class="nav-item"><a class="nav-link" href="index.html">About Us</a></li>
          <li class="nav-item"><a class="nav-link" href="index.html">Contact</a></li>
        </ul>
      </div>
    </div>
  </nav>
  
  <div class="container">
    <div class="signup-container">
      <h2>Create Your Account</h2>
      
      <div class="features">
        <div class="feature">
          <i class="fas fa-shield-alt"></i>
          <p>Secure</p>
        </div>
        <div class="feature">
          <i class="fas fa-truck"></i>
          <p>Fast Delivery</p>
        </div>
        <div class="feature">
          <i class="fas fa-prescription-bottle-alt"></i>
          <p>Quality Meds</p>
        </div>
      </div>
      
      <form method="post" action="" id="signupForm">
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-user"></i></span>
          <input type="text" name="username" class="form-control" placeholder="Username" required>
        </div>
        
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
          <input type="email" name="email" class="form-control" placeholder="Email Address" required>
        </div>
        
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-phone"></i></span>
          <input type="text" name="phone" class="form-control" placeholder="Phone Number" required pattern="[0-9]{10}" title="Enter 10-digit number">
        </div>
        
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
          <input type="text" name="address" class="form-control" placeholder="Your Address" required>
        </div>
        
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-lock"></i></span>
          <input type="password" name="password" class="form-control" placeholder="Password" required id="password">
        </div>
        
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-lock"></i></span>
          <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required id="confirm_password">
        </div>
        
        <button type="submit" name="signup" class="btn btn-primary">
          <i class="fas fa-user-plus me-2"></i>Register Account
        </button>
      </form>
      
      <div class="login-link">
        Already have an account? <a href="user-login.php">Sign in here</a>
      </div>
      
      <?php
      include "config.php";


        if (isset($_POST['signup'])) {
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $phone = mysqli_real_escape_string($conn, $_POST['phone']);
            $address = mysqli_real_escape_string($conn, $_POST['address']);
            $password = mysqli_real_escape_string($conn, $_POST['password']);
            $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
            $role = 'user';
            $created_at = date('Y-m-d H:i:s');

            // Check if passwords match
            if ($password !== $confirm_password) {
                echo "<div class='error'>Passwords do not match!</div>";
            } else {
                $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' OR email='$email'");
                if (mysqli_num_rows($check) > 0) {
                    echo "<div class='error'>Username or Email already exists!</div>";
                } else {
                    // ✅ Hash the password securely
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    $insert = mysqli_query($conn, "INSERT INTO users (username, password, email, phone, address, role, created_at)
                        VALUES ('$username', '$hashed_password', '$email', '$phone', '$address', '$role', '$created_at')");

                    if ($insert) {
                        echo "<div class='message'>Signup successful! <a href='user-login.php'>Click here to login</a></div>";
                    } else {
                        echo "<div class='error'>Error during signup!</div>";
                    }
                }
            }
        }
        ?>

    </div>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
