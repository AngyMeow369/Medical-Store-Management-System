<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | MediFast Pharmacy</title>
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
        
        .about-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            height: 100%;
            border: none;
            padding: 30px;
        }
        
        .about-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }
        
        .about-icon {
            width: 80px;
            height: 80px;
            background: var(--light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 2rem;
            color: var(--primary);
            transition: var(--transition);
        }
        
        .about-card:hover .about-icon {
            background: var(--primary);
            color: white;
            transform: rotateY(180deg);
        }
        
        .timeline {
            position: relative;
            padding: 20px 0;
        }
        
        .timeline:before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            width: 2px;
            height: 100%;
            background: var(--primary-light);
            transform: translateX(-50%);
        }
        
        .timeline-item {
            margin-bottom: 50px;
            position: relative;
        }
        
        .timeline-item:nth-child(odd) .timeline-content {
            margin-left: 0;
            margin-right: 50%;
            padding-right: 30px;
            text-align: right;
        }
        
        .timeline-item:nth-child(even) .timeline-content {
            margin-left: 50%;
            margin-right: 0;
            padding-left: 30px;
        }
        
        .timeline-dot {
            position: absolute;
            top: 0;
            left: 50%;
            width: 20px;
            height: 20px;
            background: var(--primary);
            border-radius: 50%;
            transform: translateX(-50%);
            border: 4px solid white;
            box-shadow: 0 0 0 2px var(--primary-light);
        }
        
        .timeline-content {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: var(--card-shadow);
        }
        
        .timeline-date {
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 5px;
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
            
            .timeline:before {
                left: 30px;
            }
            
            .timeline-item:nth-child(odd) .timeline-content,
            .timeline-item:nth-child(even) .timeline-content {
                margin-left: 60px;
                margin-right: 0;
                padding-left: 30px;
                padding-right: 20px;
                text-align: left;
            }
            
            .timeline-dot {
                left: 30px;
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
                        <a class="nav-link" href="priscription.php">Prescriptions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="about-us.php">About Us</a>
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
                    <h1 class="hero-title" data-aos="fade-up">About MediFast Pharmacy</h1>
                    <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="100">Your trusted healthcare partner for over 15 years</p>
                    <div class="d-flex flex-wrap gap-3" data-aos="fade-up" data-aos-delay="200">
                        <a href="#about-content" class="btn btn-primary">
                            <i class="fas fa-info-circle me-2"></i>Our Story
                        </a>
                        <a href="#values" class="btn btn-secondary">
                            <i class="fas fa-heart me-2"></i>Our Values
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block" data-aos="fade-left" data-aos-delay="300">
                    <img src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="About MediFast" class="img-fluid rounded-4 shadow-lg">
                </div>
            </div>
        </div>
    </section>
    
    <!-- About Content Section -->
    <section class="py-5" id="about-content">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
                    <h2 class="section-title">Our Mission</h2>
                    <p class="mb-4">At MediFast Pharmacy, our mission is to provide accessible, affordable, and high-quality healthcare services to our community. We believe that everyone deserves access to the medications and health products they need to live their best lives.</p>
                    <p class="mb-4">Since our establishment in 2008, we've been committed to offering personalized care, expert advice, and convenient services that put our customers' health and well-being first.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <div class="about-icon">
                            <i class="fas fa-award"></i>
                        </div>
                        <div>
                            <h5>Excellence in Healthcare</h5>
                            <p>We maintain the highest standards in all our services and products.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <h2 class="section-title">Our Vision</h2>
                    <p class="mb-4">Our vision is to be the leading pharmacy service provider in the region, known for our innovation, customer-centric approach, and commitment to community health.</p>
                    <p class="mb-4">We aim to continuously expand our services and embrace new technologies to make healthcare more accessible and convenient for everyone.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <div class="about-icon">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <div>
                            <h5>Community Health</h5>
                            <p>We actively participate in community health initiatives and education programs.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Values Section -->
    <section class="py-5 bg-light" id="values">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Our Core Values</h2>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="about-card text-center">
                        <div class="about-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <h4>Integrity</h4>
                        <p>We operate with honesty and transparency in all our interactions.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="about-card text-center">
                        <div class="about-icon">
                            <i class="fas fa-hand-holding-heart"></i>
                        </div>
                        <h4>Compassion</h4>
                        <p>We provide care with empathy and understanding for our customers' needs.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="about-card text-center">
                        <div class="about-icon">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <h4>Innovation</h4>
                        <p>We continuously seek new ways to improve our services and customer experience.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                    <div class="about-card text-center">
                        <div class="about-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4>Community</h4>
                        <p>We're committed to supporting the health and well-being of our community.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Timeline Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Our Journey</h2>
            <div class="timeline">
                <div class="timeline-item" data-aos="fade-up" data-aos-delay="100">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <div class="timeline-date">2008</div>
                        <h4>Establishment</h4>
                        <p>MediFast Pharmacy was founded with a single store and a vision to provide accessible healthcare.</p>
                    </div>
                </div>
                <div class="timeline-item" data-aos="fade-up" data-aos-delay="200">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <div class="timeline-date">2012</div>
                        <h4>Expansion</h4>
                        <p>We opened our second location and introduced home delivery services.</p>
                    </div>
                </div>
                <div class="timeline-item" data-aos="fade-up" data-aos-delay="300">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <div class="timeline-date">2016</div>
                        <h4>Digital Transformation</h4>
                        <p>Launched our online platform for prescription refills and product ordering.</p>
                    </div>
                </div>
                <div class="timeline-item" data-aos="fade-up" data-aos-delay="400">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <div class="timeline-date">2020</div>
                        <h4>Community Support</h4>
                        <p>Expanded our services to support community health during challenging times.</p>
                    </div>
                </div>
                <div class="timeline-item" data-aos="fade-up" data-aos-delay="500">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <div class="timeline-date">Today</div>
                        <h4>Continued Growth</h4>
                        <p>Now serving thousands of customers with multiple locations and comprehensive services.</p>
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