<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription Service | MediFast Pharmacy</title>
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
        
        .hero-section {
            background: linear-gradient(135deg, rgba(0, 119, 182, 0.9), rgba(0, 180, 216, 0.85)), 
                        url('https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }
        
        .hero-shape {
            position: absolute;
            bottom: -50px;
            right: -50px;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            z-index: 1;
        }
        
        .hero-shape-2 {
            position: absolute;
            top: -80px;
            left: -80px;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            z-index: 1;
        }
        
        .hero-title {
            font-weight: 700;
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 2;
        }
        
        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            opacity: 0.9;
            position: relative;
            z-index: 2;
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 50px;
            transition: var(--transition);
            box-shadow: 0 5px 15px rgba(0, 119, 182, 0.3);
        }
        
        .btn-primary:hover {
            background-color: var(--dark);
            border-color: var(--dark);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(3, 4, 94, 0.4);
        }
        
        .btn-secondary {
            background-color: var(--secondary);
            border-color: var(--secondary);
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 50px;
            transition: var(--transition);
            box-shadow: 0 5px 15px rgba(72, 202, 228, 0.3);
        }
        
        .btn-secondary:hover {
            background-color: var(--accent);
            border-color: var(--accent);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 180, 216, 0.4);
        }
        
        .section-title {
            position: relative;
            margin-bottom: 3rem;
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
        
        .prescription-info {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: var(--card-shadow);
        }
        
        .process-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: var(--card-shadow);
            height: 100%;
            transition: var(--transition);
            text-align: center;
        }
        
        .process-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }
        
        .process-icon {
            width: 80px;
            height: 80px;
            background: var(--light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2rem;
            color: var(--primary);
            transition: var(--transition);
        }
        
        .process-card:hover .process-icon {
            background: var(--primary);
            color: white;
            transform: rotateY(180deg);
        }
        
        .process-number {
            position: absolute;
            top: -15px;
            left: -15px;
            width: 40px;
            height: 40px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
            box-shadow: 0 5px 15px rgba(0, 119, 182, 0.3);
        }
        
        .faq-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: var(--card-shadow);
            margin-bottom: 20px;
            transition: var(--transition);
        }
        
        .faq-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }
        
        .faq-question {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 15px;
            font-size: 1.2rem;
        }
        
        .faq-answer {
            color: var(--gray);
        }
        
        .floating-action-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 5px 20px rgba(0, 119, 182, 0.4);
            transition: var(--transition);
            z-index: 1000;
        }
        
        .floating-action-btn:hover {
            transform: translateY(-5px) rotate(15deg);
            box-shadow: 0 8px 30px rgba(0, 119, 182, 0.6);
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
        
        .social-icon-footer {
            font-size: 1.5rem;
            margin-right: 15px;
            color: rgba(255, 255, 255, 0.7);
            transition: var(--transition);
        }
        
        .social-icon-footer:hover {
            color: white;
            transform: translateY(-5px);
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.2rem;
            }
            
            .prescription-info {
                padding: 25px;
            }
            
            .floating-action-btn {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
                bottom: 20px;
                right: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-pills me-2"></i>MediFast
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="services.php">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="products.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="prescription.php">Prescriptions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about-us.php">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-primary" href="user-login.php">Login Now</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-shape"></div>
        <div class="hero-shape-2"></div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="hero-title" data-aos="fade-up">Prescription Service</h1>
                    <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="100">Safe and convenient prescription fulfillment for your medication needs</p>
                    <div class="d-flex flex-wrap gap-3" data-aos="fade-up" data-aos-delay="200">
                        <a href="products.php" class="btn btn-primary">
                            <i class="fas fa-shopping-cart me-2"></i>Order Medicines
                        </a>
                        <a href="#process" class="btn btn-secondary">
                            <i class="fas fa-info-circle me-2"></i>How It Works
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block" data-aos="fade-left" data-aos-delay="300">
                    <img src="https://images.unsplash.com/photo-1587854692152-cbe660dbde88?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Prescription Service" class="img-fluid rounded-4 shadow-lg">
                </div>
            </div>
        </div>
    </section>
    
    <!-- Prescription Information Section -->
    <section class="py-5" id="prescription-info">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <h2 class="section-title" data-aos="fade-up">How Our Prescription Service Works</h2>
                    <div class="prescription-info" data-aos="fade-up" data-aos-delay="100">
                        <div class="row g-4">
                            <div class="col-12">
                                <h4>Prescription-Only Medications</h4>
                                <p>At MediFast Pharmacy, we prioritize your health and safety. Certain medications, such as antibiotics and other prescription drugs, require a valid prescription from a licensed medical practitioner. This ensures that you receive the appropriate medication for your condition.</p>
                            </div>
                            <div class="col-12">
                                <h4>Simple Prescription Process</h4>
                                <p>When you order prescription medications through our website or mobile app, you'll have the option to upload your prescription during checkout. Our licensed pharmacists will carefully verify your prescription to ensure its validity and appropriateness before processing your order.</p>
                            </div>
                            <div class="col-12">
                                <h4>Professional Verification</h4>
                                <p>Our team of experienced pharmacists reviews all prescriptions to check for accuracy, dosage, potential interactions, and other important factors. If any questions arise, we'll contact you directly to clarify details before proceeding with your order.</p>
                            </div>
                            <div class="col-12">
                                <h4>Convenient Fulfillment</h4>
                                <p>Once your prescription is verified, we'll prepare your medication with the utmost care and precision. You can choose to have your prescription delivered to your doorstep or collect it from our pharmacy at your convenience.</p>
                            </div>
                            <div class="col-12 text-center mt-4">
                                <a href="products.php" class="btn btn-primary px-5">
                                    <i class="fas fa-shopping-cart me-2"></i>Browse Medicines
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Process Section -->
    <section class="py-5 bg-light" id="process">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Our Prescription Process</h2>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="process-card position-relative">
                        <div class="process-number">1</div>
                        <div class="process-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h4>Order Medication</h4>
                        <p>Select your required medication and proceed to checkout.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="process-card position-relative">
                        <div class="process-number">2</div>
                        <div class="process-icon">
                            <i class="fas fa-file-upload"></i>
                        </div>
                        <h4>Upload Prescription</h4>
                        <p>Upload a clear photo or scan of your prescription during checkout.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="process-card position-relative">
                        <div class="process-number">3</div>
                        <div class="process-icon">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <h4>Verification</h4>
                        <p>Our licensed pharmacists verify your prescription and contact you if needed.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                    <div class="process-card position-relative">
                        <div class="process-number">4</div>
                        <div class="process-icon">
                            <i class="fas fa-truck"></i>
                        </div>
                        <h4>Delivery</h4>
                        <p>Your medication is delivered to your doorstep or ready for collection.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- FAQ Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Frequently Asked Questions</h2>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="faq-card" data-aos="fade-up" data-aos-delay="100">
                        <h4 class="faq-question">How long does it take to process a prescription?</h4>
                        <p class="faq-answer">Most prescriptions are processed within 2-4 hours during business hours. For complex prescriptions or those requiring special ordering, it may take up to 24 hours. You'll receive a notification when your prescription is ready for delivery or collection.</p>
                    </div>
                    <div class="faq-card" data-aos="fade-up" data-aos-delay="200">
                        <h4 class="faq-question">Which medications require a prescription?</h4>
                        <p class="faq-answer">Medications such as antibiotics, certain pain relievers, and other controlled substances require a valid prescription. Our website clearly indicates which products require a prescription during the ordering process.</p>
                    </div>
                    <div class="faq-card" data-aos="fade-up" data-aos-delay="300">
                        <h4 class="faq-question">Do you accept insurance for prescription medications?</h4>
                        <p class="faq-answer">Yes, we accept most major insurance plans. Please provide your insurance information when submitting your prescription, and our team will verify your coverage and process any applicable co-pays.</p>
                    </div>
                    <div class="faq-card" data-aos="fade-up" data-aos-delay="400">
                        <h4 class="faq-question">What if I need to speak with a pharmacist about my prescription?</h4>
                        <p class="faq-answer">Our pharmacists are available to answer any questions you may have about your prescription. You can request a consultation during the submission process or call our pharmacy directly during business hours.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5 class="footer-title">MediFast Pharmacy</h5>
                    <p>Your trusted partner for all your healthcare needs. We provide fast, reliable pharmacy services with both delivery and take-away options.</p>
                    <div class="mt-4">
                        <a href="#" class="social-icon-footer"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon-footer"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon-footer"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon-footer"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h5 class="footer-title">Quick Links</h5>
                    <a href="index.php" class="footer-link">Home</a>
                    <a href="services.php" class="footer-link">Services</a>
                    <a href="products.php" class="footer-link">Products</a>
                    <a href="about-us.php" class="footer-link">About Us</a>
                    <a href="contact.php" class="footer-link">Contact</a>
                </div>
                <div class="col-lg-3 col-md-4 mb-4 mb-md-0">
                    <h5 class="footer-title">Services</h5>
                    <a href="services.php" class="footer-link">Home Delivery</a>
                    <a href="services.php" class="footer-link">Take-Away Service</a>
                    <a href="prescription.php" class="footer-link">Prescription Service</a>
                    <a href="services.php" class="footer-link">Health Advice</a>
                    <a href="services.php" class="footer-link">Vaccination</a>
                </div>
                <div class="col-lg-3 col-md-4">
                    <h5 class="footer-title">Contact Us</h5>
                    <p><i class="fas fa-map-marker-alt me-2"></i> Kanjikuzhi Kottayam<br>Kerala, 686105</p>
                    <p><i class="fas fa-phone me-2"></i> (123) 456-7890</p>
                    <p><i class="fas fa-envelope me-2"></i> info@medifast.com</p>
                    <p><i class="fas fa-clock me-2"></i> Mon-Sat: 8AM-9PM, Sun: 9AM-6PM</p>
                </div>
            </div>
            <hr class="mt-4 mb-4" style="border-color: rgba(255,255,255,0.1);">
            <div class="text-center">
                <p class="mb-0">&copy; 2023 MediFast Pharmacy. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <!-- Floating Action Button -->
    <a href="#" class="floating-action-btn" data-aos="zoom-in" data-aos-delay="500">
        <i class="fas fa-phone-alt"></i>
    </a>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true
        });
    </script>
</body>
</html>