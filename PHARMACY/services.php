<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services | MediFast Pharmacy</title>
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
        
        .service-detail {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            height: 100%;
            border: none;
        }
        
        .service-detail:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }
        
        .service-img {
            height: 200px;
            object-fit: cover;
            transition: var(--transition);
        }
        
        .service-detail:hover .service-img {
            transform: scale(1.05);
        }
        
        .process-step {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: var(--card-shadow);
            position: relative;
            transition: var(--transition);
            height: 100%;
        }
        
        .process-step:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
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
        
        .process-icon {
            width: 70px;
            height: 70px;
            background: var(--light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 1.8rem;
            color: var(--primary);
            transition: var(--transition);
        }
        
        .process-step:hover .process-icon {
            background: var(--primary);
            color: white;
            transform: rotateY(180deg);
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
                        <a class="nav-link active" href="services.php">Services</a>
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
                    <h1 class="hero-title" data-aos="fade-up">Our Services</h1>
                    <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="100">Comprehensive healthcare services for your needs</p>
                    <div class="d-flex flex-wrap gap-3" data-aos="fade-up" data-aos-delay="200">
                        <a href="#delivery" class="btn btn-primary">
                            <i class="fas fa-truck me-2"></i>Home Delivery
                        </a>
                        <a href="#takeaway" class="btn btn-secondary">
                            <i class="fas fa-store me-2"></i>Take-Away Service
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block" data-aos="fade-left" data-aos-delay="300">
                    <img src="https://images.unsplash.com/photo-1587854692152-cbe660dbde88?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Our Services" class="img-fluid rounded-4 shadow-lg">
                </div>
            </div>
        </div>
    </section>
    
    <!-- Services Overview Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Our Healthcare Services</h2>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-card p-4 text-center">
                        <div class="service-icon">
                            <i class="fas fa-truck-medical"></i>
                        </div>
                        <h4>Home Delivery</h4>
                        <p>Get your medications delivered to your home within 2 hours of ordering.</p>
                        <a href="#delivery" class="btn btn-sm btn-outline-primary mt-3">Learn More</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-card p-4 text-center">
                        <div class="service-icon">
                            <i class="fas fa-store"></i>
                        </div>
                        <h4>Take-Away Service</h4>
                        <p>Order online and collect your medications from our store at your convenience.</p>
                        <a href="#takeaway" class="btn btn-sm btn-outline-primary mt-3">Learn More</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="service-card p-4 text-center">
                        <div class="service-icon">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <h4>Prescription Service</h4>
                        <p>Upload your prescription online and get your medications prepared.</p>
                        <a href="#prescription" class="btn btn-sm btn-outline-primary mt-3">Learn More</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                    <div class="service-card p-4 text-center">
                        <div class="service-icon">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <h4>Health Advice</h4>
                        <p>Get professional health advice from our qualified pharmacists.</p>
                        <a href="#advice" class="btn btn-sm btn-outline-primary mt-3">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Home Delivery Service Section -->
    <section class="py-5 bg-light" id="delivery">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
                    <h2 class="section-title">Home Delivery Service</h2>
                    <p class="mb-4">Our home delivery service brings your medications right to your doorstep. With our fast and reliable delivery system, you can get your prescriptions without leaving the comfort of your home.</p>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-3"><i class="fas fa-check-circle text-primary me-2"></i> Fast delivery within 2 hours</li>
                        <li class="mb-3"><i class="fas fa-check-circle text-primary me-2"></i> Contactless delivery options</li>
                        <li class="mb-3"><i class="fas fa-check-circle text-primary me-2"></i> Real-time delivery tracking</li>
                        <li class="mb-3"><i class="fas fa-check-circle text-primary me-2"></i> Secure medication packaging</li>
                    </ul>
                    <a href="#" class="btn btn-primary">Schedule Delivery</a>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="service-detail">
                        <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="service-img w-100" alt="Home Delivery">
                        <div class="p-4">
                            <h4>How Our Delivery Works</h4>
                            <ol>
                                <li>Place your order online or by phone</li>
                                <li>Our pharmacists prepare your medications</li>
                                <li>Track your delivery in real-time</li>
                                <li>Receive your medications at your doorstep</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Take-Away Service Section -->
    <section class="py-5" id="takeaway">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0" data-aos="fade-left">
                    <h2 class="section-title">Take-Away Service</h2>
                    <p class="mb-4">Our take-away service allows you to order your medications online and pick them up at your convenience. Skip the wait and have your medications ready when you arrive.</p>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-3"><i class="fas fa-check-circle text-primary me-2"></i> Order online for quick pickup</li>
                        <li class="mb-3"><i class="fas fa-check-circle text-primary me-2"></i> Dedicated pickup counter</li>
                        <li class="mb-3"><i class="fas fa-check-circle text-primary me-2"></i> SMS notifications when ready</li>
                        <li class="mb-3"><i class="fas fa-check-circle text-primary me-2"></i> Flexible pickup times</li>
                    </ul>
                    <a href="#" class="btn btn-primary">Order Now</a>
                </div>
                <div class="col-lg-6 order-lg-1" data-aos="fade-right">
                    <div class="service-detail">
                        <img src="https://images.unsplash.com/photo-1584362917165-526a968579e8?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="service-img w-100" alt="Take-Away Service">
                        <div class="p-4">
                            <h4>Pickup Process</h4>
                            <ol>
                                <li>Place your order through our website or app</li>
                                <li>Receive confirmation with pickup time</li>
                                <li>Visit our store at your scheduled time</li>
                                <li>Collect your medications from the pickup counter</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Prescription Service Section -->
    <section class="py-5 bg-light" id="prescription">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
                    <h2 class="section-title">Prescription Service</h2>
                    <p class="mb-4">Our prescription service makes it easy to get your medications filled. Upload your prescription online, and our pharmacists will take care of the rest.</p>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-3"><i class="fas fa-check-circle text-primary me-2"></i> Upload prescriptions online</li>
                        <li class="mb-3"><i class="fas fa-check-circle text-primary me-2"></i> Automatic refill reminders</li>
                        <li class="mb-3"><i class="fas fa-check-circle text-primary me-2"></i> Insurance processing</li>
                        <li class="mb-3"><i class="fas fa-check-circle text-primary me-2"></i> Medication counseling</li>
                    </ul>
                    <a href="#" class="btn btn-primary">Upload Prescription</a>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="service-detail">
                        <img src="https://images.unsplash.com/photo-1587854692152-cbe660dbde88?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="service-img w-100" alt="Prescription Service">
                        <div class="p-4">
                            <h4>Prescription Process</h4>
                            <ol>
                                <li>Upload your prescription through our secure portal</li>
                                <li>Our pharmacists review and verify your prescription</li>
                                <li>We prepare your medications with care</li>
                                <li>Choose delivery or in-store pickup</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Health Advice Section -->
    <section class="py-5" id="advice">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0" data-aos="fade-left">
                    <h2 class="section-title">Health Advice</h2>
                    <p class="mb-4">Our qualified pharmacists are available to provide professional health advice and answer your medication questions. Get the guidance you need to manage your health effectively.</p>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-3"><i class="fas fa-check-circle text-primary me-2"></i> One-on-one consultations</li>
                        <li class="mb-3"><i class="fas fa-check-circle text-primary me-2"></i> Medication reviews</li>
                        <li class="mb-3"><i class="fas fa-check-circle text-primary me-2"></i> Health screenings</li>
                        <li class="mb-3"><i class="fas fa-check-circle text-primary me-2"></i> Wellness advice</li>
                    </ul>
                    <a href="#" class="btn btn-primary">Book Consultation</a>
                </div>
                <div class="col-lg-6 order-lg-1" data-aos="fade-right">
                    <div class="service-detail">
                        <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="service-img w-100" alt="Health Advice">
                        <div class="p-4">
                            <h4>Our Health Services</h4>
                            <ul>
                                <li>Medication counseling and management</li>
                                <li>Health condition management advice</li>
                                <li>Vaccination services</li>
                                <li>Health screenings and checks</li>
                                <li>Wellness and lifestyle guidance</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Process Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Our Service Process</h2>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="process-step text-center">
                        <div class="process-number">1</div>
                        <div class="process-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <h4>Contact Us</h4>
                        <p>Reach out to us through our website, app, or by phone to inquire about our services.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="process-step text-center">
                        <div class="process-number">2</div>
                        <div class="process-icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <h4>Provide Details</h4>
                        <p>Share your prescription details or service requirements with our team.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="process-step text-center">
                        <div class="process-number">3</div>
                        <div class="process-icon">
                            <i class="fas fa-pills"></i>
                        </div>
                        <h4>Processing</h4>
                        <p>Our pharmacists prepare your medications or arrange your requested service.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                    <div class="process-step text-center">
                        <div class="process-number">4</div>
                        <div class="process-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h4>Delivery/Pickup</h4>
                        <p>Receive your medications at your doorstep or collect them from our store.</p>
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
                        <p class="mb-4">The home delivery service is a lifesaver! I can get my medications without leaving home, especially important during bad weather or when I'm not feeling well.</p>
                        <div class="d-flex align-items-center">
                            <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Customer" class="rounded-circle me-3" width="50" height="50">
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
                        <p class="mb-4">The take-away service is so convenient. I order on my lunch break and pick up on my way home. No waiting in line!</p>
                        <div class="d-flex align-items-center">
                            <img src="https://randomuser.me/api/portraits/men/22.jpg" alt="Customer" class="rounded-circle me-3" width="50" height="50">
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
                        <p class="mb-4">The health advice I received from the pharmacist was incredibly helpful. They took time to explain my medications and answer all my questions.</p>
                        <div class="d-flex align-items-center">
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Customer" class="rounded-circle me-3" width="50" height="50">
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
                    <a href="#" class="footer-link">Services</a>
                    <a href="#" class="footer-link">Products</a>
                    <a href="#" class="footer-link">About Us</a>
                    <a href="#" class="footer-link">Contact</a>
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