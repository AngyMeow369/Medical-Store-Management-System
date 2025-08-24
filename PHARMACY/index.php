<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediFast Pharmacy | Delivery & Take-Away</title>
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
        
        .service-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            height: 100%;
            border: none;
        }
        
        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }
        
        .service-icon {
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
        
        .service-card:hover .service-icon {
            background: var(--primary);
            color: white;
            transform: rotateY(180deg);
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
        
        .testimonial-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: var(--card-shadow);
            height: 100%;
            position: relative;
            overflow: hidden;
        }
        
        .testimonial-card:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: var(--primary);
        }
        
        .quote-icon {
            font-size: 2rem;
            color: var(--primary-light);
            opacity: 0.5;
            position: absolute;
            top: 20px;
            right: 20px;
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
        
        .counter-section {
            background: linear-gradient(135deg, var(--primary), var(--dark));
            color: white;
            padding: 60px 0;
            position: relative;
            overflow: hidden;
        }
        
        .counter-item {
            text-align: center;
            padding: 20px;
        }
        
        .counter-number {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 10px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 10px;
            display: inline-block;
            min-width: 120px;
        }
        
        .counter-text {
            font-size: 1.1rem;
            opacity: 0.9;
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
        }
        
        .newsletter-input {
            width: 100%;
            padding: 12px 20px;
            border-radius: 50px;
            border: 2px solid rgba(255, 255, 255, 0.2);
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
        
        .map-container {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            height: 300px;
        }
        
        .overlay-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: white;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            z-index: 2;
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.2rem;
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
            <a class="navbar-brand" href="#">
                <i class="fas fa-pills me-2"></i>MediFast
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Home</a>
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
                    <h1 class="hero-title" data-aos="fade-up">Your Health, Delivered.</h1>
                    <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="100">Fast pharmacy delivery to your doorstep or collect in-store at your convenience.</p>
                    <div class="d-flex flex-wrap gap-3" data-aos="fade-up" data-aos-delay="200">
                        <a href="user-login.php" class="btn btn-primary">
                            <i class="fas fa-truck me-2"></i> Home Delivery
                        </a>
                        <a href="user-login.php" class="btn btn-secondary">
                            <i class="fas fa-store me-2"></i> Collect In-Store
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block" data-aos="fade-left" data-aos-delay="300">
                    <img src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Pharmacy Services" class="img-fluid rounded-4 shadow-lg">
                </div>
            </div>
        </div>
    </section>
    <!-- Services Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Our Services</h2>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-card p-4 text-center">
                        <div class="service-icon">
                            <i class="fas fa-truck-medical"></i>
                        </div>
                        <h4>Home Delivery</h4>
                        <p>Get your medications delivered to your home within 2 hours of ordering.</p>
                        <a href="#" class="btn btn-sm btn-outline-primary mt-3">Learn More</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-card p-4 text-center">
                        <div class="service-icon">
                            <i class="fas fa-store"></i>
                        </div>
                        <h4>Take-Away Service</h4>
                        <p>Order online and collect your medications from our store at your convenience.</p>
                        <a href="#" class="btn btn-sm btn-outline-primary mt-3">Learn More</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="service-card p-4 text-center">
                        <div class="service-icon">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <h4>Prescription Service</h4>
                        <p>Upload your prescription online and get your medications prepared.</p>
                        <a href="#" class="btn btn-sm btn-outline-primary mt-3">Learn More</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                    <div class="service-card p-4 text-center">
                        <div class="service-icon">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <h4>Health Advice</h4>
                        <p>Get professional health advice from our qualified pharmacists.</p>
                        <a href="#" class="btn btn-sm btn-outline-primary mt-3">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Counter Section -->
    <section class="counter-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="counter-item" data-aos="fade-up" data-aos-delay="100">
                        <div class="counter-number" data-count="5000">0</div>
                        <div class="counter-text">Prescriptions Filled</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="counter-item" data-aos="fade-up" data-aos-delay="200">
                        <div class="counter-number" data-count="15000">0</div>
                        <div class="counter-text">Products Available</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="counter-item" data-aos="fade-up" data-aos-delay="300">
                        <div class="counter-number" data-count="98">0</div>
                        <div class="counter-text">Satisfaction Rate</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="counter-item" data-aos="fade-up" data-aos-delay="400">
                        <div class="counter-number" data-count="24">0</div>
                        <div class="counter-text">Hours Support</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Testimonials Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">What Our Customers Say</h2>
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="testimonial-card">
                        <div class="quote-icon">
                            <i class="fas fa-quote-left"></i>
                        </div>
                        <p class="mb-4">The delivery service is amazing! I ordered my prescription and it arrived within an hour. Very convenient during this busy time.</p>
                        <div class="d-flex align-items-center">
                            <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Customer" class="testimonial-img me-3">
                            <div>
                                <h6 class="mb-1">Sarah Johnson</h6>
                                <small class="text-muted">Regular Customer</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="testimonial-card">
                        <div class="quote-icon">
                            <i class="fas fa-quote-left"></i>
                        </div>
                        <p class="mb-4">I love the take-away option. I can order online while at work and pick up my medications on my way home. Saves me so much time!</p>
                        <div class="d-flex align-items-center">
                            <img src="https://randomuser.me/api/portraits/men/22.jpg" alt="Customer" class="testimonial-img me-3">
                            <div>
                                <h6 class="mb-1">Michael Chen</h6>
                                <small class="text-muted">New Customer</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="testimonial-card">
                        <div class="quote-icon">
                            <i class="fas fa-quote-left"></i>
                        </div>
                        <p class="mb-4">The pharmacists are always helpful and provide great advice. The delivery is always on time and the packaging is secure.</p>
                        <div class="d-flex align-items-center">
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Customer" class="testimonial-img me-3">
                            <div>
                                <h6 class="mb-1">Emily Rodriguez</h6>
                                <small class="text-muted">Regular Customer</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Map Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
                    <h2 class="section-title">Visit Our Store</h2>
                    <p class="mb-4">Our pharmacy is located in the heart of the city, easily accessible and equipped with all the modern amenities to serve you better.</p>
                    <div class="mb-4">
                        <h5><i class="fas fa-map-marker-alt me-2 text-primary"></i> Our Location</h5>
                        <p>Kanjikuzhi Kottayam<br>Kerala, 686105</p>
                    </div>
                    <div class="mb-4">
                        <h5><i class="fas fa-clock me-2 text-primary"></i> Opening Hours</h5>
                        <p>Monday - Friday: 8:00 AM - 9:00 PM<br>Saturday: 9:00 AM - 6:00 PM<br>Sunday: 10:00 AM - 4:00 PM</p>
                    </div>
                    <div>
                        <h5><i class="fas fa-phone me-2 text-primary"></i> Contact Us</h5>
                        <p>Phone: (123) 456-7890<br>Email: info@medifast.com</p>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="map-container">
                        <img src="https://images.unsplash.com/photo-1519452635265-7b1fbfd0d8e4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80" alt="Pharmacy Location" class="w-100 h-100 object-cover">
                        <div class="overlay-text">
                            <h3>MediFast Pharmacy</h3>
                            <p>Your Health Partner</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Newsletter Section -->
    <section class="py-5 bg-primary text-white">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center" data-aos="fade-up">
                    <h2 class="mb-4">Subscribe to Our Newsletter</h2>
                    <p class="mb-4">Stay updated with our latest products, health tips, and exclusive offers.</p>
                    <form class="newsletter-form">
                        <input type="email" class="newsletter-input" placeholder="Your email address">
                        <button class="newsletter-btn">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
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
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
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
                    <a href="#" class="footer-link">Home Delivery</a>
                    <a href="#" class="footer-link">Take-Away Service</a>
                    <a href="#" class="footer-link">Prescription Service</a>
                    <a href="#" class="footer-link">Health Advice</a>
                    <a href="#" class="footer-link">Vaccination</a>
                </div>
                <div class="col-lg-3 col-md-4">
                    <h5 class="footer-title">Contact Us</h5>
                    <p><i class="fas fa-map-marker-alt me-2"></i> 123 Health Street, Medical City</p>
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
        
        // Counter Animation
        document.addEventListener('DOMContentLoaded', function() {
            const counters = document.querySelectorAll('.counter-number');
            const speed = 200;
            
            counters.forEach(counter => {
                const updateCount = () => {
                    const target = +counter.getAttribute('data-count');
                    const count = +counter.innerText;
                    const increment = target / speed;
                    
                    if (count < target) {
                        counter.innerText = Math.ceil(count + increment);
                        setTimeout(updateCount, 1);
                    } else {
                        counter.innerText = target;
                    }
                };
                
                updateCount();
            });
        });
        
        // Newsletter functionality
        const newsletterForm = document.querySelector('.newsletter-form');
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input').value;
            if (email) {
                alert('Thank you for subscribing!');
                this.querySelector('input').value = '';
            } else {
                alert('Please enter a valid email address');
            }
        });
    </script>
</body>
</html>