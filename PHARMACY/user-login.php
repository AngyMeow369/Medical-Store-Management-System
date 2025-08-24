<?php
session_start();
include 'config.php';
$error = "";
$success = "";
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password']; // Don't escape passwords; hash functions need exact value
    if ($username !== "" && $password !== "") {
        // Fetch the user by username only
        $sql = "SELECT * FROM users WHERE username='$username'";
        $result = mysqli_query($conn, $sql);
        if ($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);
            // Verify password hash
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                
                // Set success message
                $success = "Login successful! Redirecting to dashboard...";
                
                // We'll redirect using JavaScript after showing the success message
            } else {
                $error = "Invalid username or password!";
            }
        } else {
            $error = "Invalid username or password!";
        }
    } else {
        $error = "All fields are required!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>MediFast Pharmacy | Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Google Fonts & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">
  <style>
        :root {
            --primary: #0077b6;
            --primary-light: #90e0ef;
            --secondary: #48cae4;
            --accent: #00b4d8;
            --dark: #03045e;
            --light: #caf0f8;
            --gray: #6c757d;
            --success: #38b000;
            --warning: #ffbe0b;
            --danger: #d00000;
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            overflow-x: hidden;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
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
        
        .section-title {
            position: relative;
            margin-bottom: 2rem;
            font-weight: 700;
            color: var(--dark);
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 0;
            width: 80px;
            height: 4px;
            background-color: var(--accent);
            border-radius: 2px;
        }
        
        .login-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            height: 100%;
            border: none;
        }
        
        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }
        
        .login-icon {
            width: 80px;
            height: 80px;
            background: var(--light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 2.5rem;
            color: var(--primary);
            transition: var(--transition);
        }
        
        .login-btn {
            background-color:#0087b6;
            color: white;
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 50px;
            transition: var(--transition);
            box-shadow: 0 5px 15px rgba(0, 119, 182, 0.3);
            width: 100%;
        }
        
        .login-btn:hover {
            background-color:#0077b6;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(3, 4, 94, 0.4);
        }
        
        .login-input {
            width: 100%;
            padding: 12px 20px;
            border-radius: 10px;
            border: 2px solid rgba(0, 119, 182, 0.2);
            font-size: 1rem;
            transition: var(--transition);
            margin-bottom: 20px;
        }
        
        .login-input:focus {
            border-color: var(--primary);
            box-shadow: 0 5px 20px rgba(0, 119, 182, 0.2);
            outline: none;
        }
        
        .login-links {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            font-size: 0.9rem;
        }
        
        .login-link {
            color: var(--primary);
            text-decoration: none;
            transition: var(--transition);
        }
        
        .login-link:hover {
            color: var(--dark);
            text-decoration: underline;
        }
        
        .login-divider {
            display: flex;
            align-items: center;
            margin: 30px 0;
        }
        
        .login-divider-line {
            flex-grow: 1;
            height: 1px;
            background-color: rgba(0, 0, 0, 0.1);
        }
        
        .login-divider-text {
            padding: 0 15px;
            color: var(--gray);
            font-size: 0.9rem;
        }
        
        .social-login-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 500;
            margin-bottom: 10px;
            transition: var(--transition);
            border: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        .social-login-btn i {
            margin-right: 10px;
        }
        
        .facebook-btn {
            color: #3b5998;
        }
        
        .google-btn {
            color: #db4437;
        }
        
        .twitter-btn {
            color: #1da1f2;
        }
        
        .footer {
            background-color: var(--dark);
            color: white;
            padding: 60px 0 30px;
        }
        
        .footer-title {
            font-weight: 700;
            margin-bottom: 1.5rem;
            font-size: 1.4rem;
            position: relative;
            padding-bottom: 15px;
        }
        
        .footer-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background-color: var(--accent);
        }
        
        .footer-link {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: var(--transition);
            display: block;
            margin-bottom: 0.8rem;
        }
        
        .footer-link:hover {
            color: white;
            transform: translateX(5px);
        }
        
        .social-icon {
            font-size: 1.5rem;
            margin-right: 15px;
            color: rgba(255, 255, 255, 0.7);
            transition: var(--transition);
        }
        
        .social-icon:hover {
            color: white;
            transform: translateY(-5px);
        }
        
        .newsletter-form {
            position: relative;
            margin-top: 20px;
        }
        
        .newsletter-input {
            width: 100%;
            padding: 12px 20px;
            border-radius: 50px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transition: var(--transition);
        }
        
        .newsletter-input:focus {
            border-color: var(--accent);
            background: rgba(255, 255, 255, 0.15);
            outline: none;
        }
        
        .newsletter-btn {
            position: absolute;
            right: 5px;
            top: 5px;
            bottom: 5px;
            width: 40px;
            border-radius: 50px;
            background: var(--accent);
            color: white;
            border: none;
            transition: var(--transition);
        }
        
        .newsletter-btn:hover {
            background: var(--primary);
        }
        
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 3000;
        }
        
        .toast {
            background-color: white;
            color: #333;
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
            max-width: 350px;
            opacity: 0;
            transform: translateX(50px);
            transition: all 0.3s ease;
        }
        
        .toast.show {
            opacity: 1;
            transform: translateX(0);
        }
        
        .toast-success {
            border-left: 4px solid var(--success);
        }
        
        .toast-error {
            border-left: 4px solid var(--danger);
        }
        
        .toast-close {
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            color: var(--gray);
        }
        
        .toast-close:hover {
            color: var(--dark);
        }
        
        .toast-icon {
            margin-right: 15px;
            font-size: 1.5rem;
        }
        
        .toast-success .toast-icon {
            color: var(--success);
        }
        
        .toast-error .toast-icon {
            color: var(--danger);
        }
        
        .login-image-container {
            position: relative;
            height: 100%;
            overflow: hidden;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
        }
        
        .login-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }
        
        .login-image-container:hover img {
            transform: scale(1.05);
        }
        
        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0, 119, 182, 0.7), rgba(0, 180, 216, 0.7));
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .image-overlay i {
            font-size: 3rem;
            margin-bottom: 15px;
        }
        
        .image-overlay h3 {
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 10px;
        }
        
        .image-overlay p {
            font-size: 1.1rem;
            opacity: 0.9;
            max-width: 80%;
        }
        
        /* Centered Success Modal */
        .success-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .success-modal.show {
            opacity: 1;
            visibility: visible;
        }
        
        .success-modal-content {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 90%;
            text-align: center;
            padding: 40px;
            transform: scale(0.8);
            transition: transform 0.3s ease;
        }
        
        .success-modal.show .success-modal-content {
            transform: scale(1);
        }
        
        .success-icon-circle {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--success), #2e8b0a);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            position: relative;
        }
        
        .success-icon-circle::after {
            content: '';
            position: absolute;
            top: -4px;
            left: -4px;
            right: -4px;
            bottom: -4px;
            border: 2px solid var(--success);
            border-radius: 50%;
            opacity: 0;
            animation: ripple 1.5s ease-out infinite;
        }
        
        @keyframes ripple {
            0% {
                transform: scale(0.8);
                opacity: 0.8;
            }
            100% {
                transform: scale(1.5);
                opacity: 0;
            }
        }
        
        .success-icon {
            font-size: 36px;
            color: white;
        }
        
        .success-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 12px;
        }
        
        .success-message {
            color: var(--gray);
            margin-bottom: 24px;
            font-size: 16px;
        }
        
        .progress-container {
            margin-bottom: 16px;
        }
        
        .progress-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
            color: var(--gray);
        }
        
        .progress-bar-container {
            height: 8px;
            background-color: var(--light-gray);
            border-radius: 4px;
            overflow: hidden;
        }
        
        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--success), #2e8b0a);
            border-radius: 4px;
            width: 100%;
            animation: progressShrink 3s linear forwards;
        }
        
        @keyframes progressShrink {
            from { width: 100%; }
            to { width: 0%; }
        }
        
        .success-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 24px;
        }
        
        .success-countdown {
            font-size: 14px;
            color: var(--gray);
        }
        
        .success-countdown span {
            font-weight: 600;
            color: var(--primary);
        }
        
        .success-skip-btn {
            background: none;
            border: none;
            color: var(--primary);
            font-weight: 500;
            cursor: pointer;
            font-size: 14px;
            transition: color 0.2s;
        }
        
        .success-skip-btn:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }
    </style>
</head>
<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container">
      <a class="navbar-brand" href="#">
        <i class="fas fa-pills me-2"></i>Medical Store
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="services.php">Services</a></li>
          <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>
          <li class="nav-item"><a class="nav-link" href="priscription.php">Prescriptions</a></li>
          <li class="nav-item"><a class="nav-link" href="about-us.php">About Us</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        </ul>
      </div>
    </div>
  </nav>
  
  <!-- Centered Success Modal -->
  <?php if (!empty($success)) { ?>
    <div class="success-modal show" id="successModal">
      <div class="success-modal-content">
        <div class="success-icon-circle">
          <i class="fas fa-check success-icon"></i>
        </div>
        <h3 class="success-title">Login Successful!</h3>
        <p class="success-message"><?php echo $success; ?></p>
        
        <div class="progress-container">
          <div class="progress-label">
            <span>Redirecting to dashboard</span>
            <span id="progressPercent">100%</span>
          </div>
          <div class="progress-bar-container">
            <div class="progress-bar-fill" id="progressBar"></div>
          </div>
        </div>
        
        <div class="success-footer">
          <div class="success-countdown">
            Redirecting in <span id="countdown">3</span> seconds
          </div>
          <button class="success-skip-btn" onclick="skipRedirect()">Skip</button>
        </div>
      </div>
    </div>
  <?php } ?>
  
  <!-- Login Section -->
  <section class="py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-10">
          <div class="login-card">
            <div class="row g-0">
              <div class="col-lg-6" data-aos="fade-right">
                <div class="login-image-container">
                  <img src="https://images.unsplash.com/photo-1583483448420-5c0a3e4f0a7a?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Pharmacy Login">
                  <div class="image-overlay">
                    <i class="fas fa-user-lock"></i>
                    <h3>Welcome Back!</h3>
                    <p>Log in to access your pharmacy account and manage your orders</p>
                  </div>
                </div>
              </div>
              <div class="col-lg-6" data-aos="fade-left">
                <div class="p-4 p-md-5">
                  <div class="text-center mb-4">
                    <div class="login-icon mx-auto" data-aos="zoom-in" data-aos-delay="100">
                      <i class="fas fa-user-shield"></i>
                    </div>
                    <h2 class="section-title" data-aos="fade-up">Account Login</h2>
                    <p class="mb-4" data-aos="fade-up" data-aos-delay="100">Please enter your credentials to access your account.</p>
                  </div>
                  <form id="loginForm" method="post" action="">
                    <div class="mb-3">
                      <label for="username" class="form-label">Username</label>
                      <input type="text" id="username" name="username" class="login-input" placeholder="Enter your username" required>
                    </div>
                    <div class="mb-3">
                      <label for="password" class="form-label">Password</label>
                      <input type="password" id="password" name="password" class="login-input" placeholder="Enter your password" required>
                    </div>
                    <!-- Error message just below password -->
                    <?php if (!empty($error)) { ?>
                      <div class="toast toast-error show" style="margin-top: -10px; margin-bottom: 20px;">
                        <i class="toast-icon fas fa-exclamation-circle"></i>
                        <div><?php echo $error; ?></div>
                        <button class="toast-close">&times;</button>
                      </div>
                    <?php } ?>
                    <div class="mb-3 form-check">
                      <input class="form-check-input" type="checkbox" id="rememberMe" name="rememberMe">
                      <label class="form-check-label" for="rememberMe">Remember me</label>
                    </div>
                    <button type="submit" name="login" class="login-btn">
                      <i class="fas fa-sign-in-alt me-2"></i> Login
                    </button>
                    <div class="login-divider">
                      <div class="login-divider-line"></div>
                      <div class="login-divider-text">OR</div>
                      <div class="login-divider-line"></div>
                    </div>
                    
                    <div class="login-links">
                      <a href="user-passwordreset.php" class="login-link">Forgot Password?</a>
                      <a href="user-signup.php" class="login-link">Create Account</a>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <div class="row">
        <!-- Footer sections unchanged -->
      </div>
      <hr class="mt-4 mb-4" style="border-color: rgba(255,255,255,0.1);">
      <div class="text-center">
        <p class="mb-0">&copy; 2025 Pharmacy. All rights reserved.</p>
      </div>
    </div>
  </footer>
  <!-- Toast JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <script>
    AOS.init({ duration: 1000, once: true });
    document.addEventListener('DOMContentLoaded', function() {
      const toastCloseButtons = document.querySelectorAll('.toast-close');
      toastCloseButtons.forEach(button => {
        button.addEventListener('click', function() {
          const toast = this.parentElement;
          toast.classList.remove('show');
          setTimeout(() => toast.remove(), 300);
        });
      });
      
      // Handle success modal
      const successModal = document.getElementById('successModal');
      if (successModal) {
        let countdown = 3;
        const countdownElement = document.getElementById('countdown');
        const progressPercent = document.getElementById('progressPercent');
        
        // Update countdown and progress every 100ms
        const interval = setInterval(() => {
          countdown -= 0.1;
          countdownElement.textContent = Math.ceil(countdown);
          progressPercent.textContent = Math.round((countdown / 3) * 100) + '%';
          
          if (countdown <= 0) {
            clearInterval(interval);
            window.location.href = "user-dashboard.php";
          }
        }, 100);
      }
    });
    
    function skipRedirect() {
      window.location.href = "user-dashboard.php";
    }
  </script>
</body>
</html>