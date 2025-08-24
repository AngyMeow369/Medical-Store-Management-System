<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products | MediFast Pharmacy</title>
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
        
        .product-card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            background: white;
            border: none;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        
        .product-icon {
            width: 60px;
            height: 60px;
            background: var(--light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 1.8rem;
            color: var(--primary);
        }
        
        .badge-special {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: var(--accent);
            color: white;
            font-weight: 600;
            padding: 8px 15px;
            border-radius: 50px;
            z-index: 1;
            box-shadow: 0 5px 15px rgba(0, 180, 216, 0.3);
        }
        
        .category-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            height: 100%;
            border: none;
            cursor: pointer;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: white;
        }
        
        .category-card:hover .category-icon {
            color: white;
            background: rgba(255, 255, 255, 0.2);
        }
        
        .category-icon {
            width: 70px;
            height: 70px;
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
        
        .filter-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: var(--card-shadow);
            margin-bottom: 30px;
        }
        
        .filter-title {
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--dark);
        }
        
        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .price-range {
            margin-top: 20px;
        }
        
        .price-range input {
            width: 100%;
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
        
        .product-description {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        
        .product-details {
            margin-bottom: 15px;
        }
        
        .product-benefits {
            list-style-type: none;
            padding: 0;
            margin-bottom: 15px;
        }
        
        .product-benefits li {
            margin-bottom: 5px;
            display: flex;
            align-items: center;
        }
        
        .product-benefits li:before {
            content: '\f00c';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            color: var(--success);
            margin-right: 10px;
        }
        
        .prescription-required {
            font-size: 0.85rem;
            color: var(--danger);
            font-weight: 500;
            margin-top: 5px;
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
                        <a class="nav-link" href="services.php">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="products.php">Products</a>
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
                    <h1 class="hero-title" data-aos="fade-up">Our Products</h1>
                    <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="100">Wide range of healthcare products for your needs</p>
                    <div class="d-flex flex-wrap gap-3" data-aos="fade-up" data-aos-delay="200">
                        <a href="#categories" class="btn btn-primary">
                            <i class="fas fa-th-large me-2"></i>Browse Categories
                        </a>
                        <a href="#featured" class="btn btn-secondary">
                            <i class="fas fa-star me-2"></i>Featured Products
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block" data-aos="fade-left" data-aos-delay="300">
                    <div class="text-center">
                        <i class="fas fa-pills" style="font-size: 15rem; opacity: 0.7;"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Categories Section -->
    <section class="py-5" id="categories">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Product Categories</h2>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="100">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-capsules"></i>
                        </div>
                        <h4>Vitamins & Supplements</h4>
                        <p class="mb-0">Essential nutrients for your health</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="200">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-tablets"></i>
                        </div>
                        <h4>Pain Relief</h4>
                        <p class="mb-0">Effective solutions for pain management</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="300">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-first-aid"></i>
                        </div>
                        <h4>First Aid</h4>
                        <p class="mb-0">Essential supplies for emergencies</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="400">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-hand-sparkles"></i>
                        </div>
                        <h4>Personal Care</h4>
                        <p class="mb-0">Products for your daily hygiene</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Filter Section -->
    <section class="py-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="filter-section" data-aos="fade-right">
                        <h5 class="filter-title">Filter Products</h5>
                        
                        <div class="mb-4">
                            <h6>Categories</h6>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="cat1">
                                <label class="form-check-label" for="cat1">
                                    Vitamins & Supplements
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="cat2">
                                <label class="form-check-label" for="cat2">
                                    Pain Relief
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="cat3">
                                <label class="form-check-label" for="cat3">
                                    First Aid
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="cat4">
                                <label class="form-check-label" for="cat4">
                                    Personal Care
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h6>Price Range</h6>
                            <div class="price-range">
                                <input type="range" class="form-range" min="0" max="100" id="priceRange">
                                <div class="d-flex justify-content-between">
                                    <span>$0</span>
                                    <span id="priceValue">$50</span>
                                    <span>$100+</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h6>Availability</h6>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="inStock">
                                <label class="form-check-label" for="inStock">
                                    In Stock
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="onSale">
                                <label class="form-check-label" for="onSale">
                                    On Sale
                                </label>
                            </div>
                        </div>
                        
                        <button class="btn btn-primary w-100">Apply Filters</button>
                    </div>
                </div>
                
                <div class="col-lg-9">
                    <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-left">
                        <h3>All Products</h3>
                        <div class="d-flex align-items-center">
                            <span class="me-2">Sort by:</span>
                            <select class="form-select form-select-sm" style="width: auto;">
                                <option selected>Popularity</option>
                                <option value="1">Price: Low to High</option>
                                <option value="2">Price: High to Low</option>
                                <option value="3">Newest First</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row g-4" id="featured">
                        <div class="col-md-6 col-lg-4" data-aos="zoom-in" data-aos-delay="100">
                            <div class="product-card position-relative">
                                <span class="badge-special">New</span>
                                <div class="p-4 product-description">
                                    <div class="product-icon">
                                        <i class="fas fa-capsules"></i>
                                    </div>
                                    <h5>Vitamin C Supplements</h5>
                                    <div class="product-details">
                                        <p>Immune support formula with high potency vitamin C to boost your natural defenses.</p>
                                        <ul class="product-benefits">
                                            <li>Strengthens immune system</li>
                                            <li>Powerful antioxidant</li>
                                            <li>Supports collagen production</li>
                                        </ul>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                        <span class="fw-bold">$12.99</span>
                                        <button class="btn btn-sm btn-primary">
                                            <i class="fas fa-cart-plus me-1"></i> Add
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4" data-aos="zoom-in" data-aos-delay="200">
                            <div class="product-card position-relative">
                                <div class="p-4 product-description">
                                    <div class="product-icon">
                                        <i class="fas fa-tablets"></i>
                                    </div>
                                    <h5>Pain Relief Tablets</h5>
                                    <div class="product-details">
                                        <p>Fast-acting formula for effective relief from headaches, muscle aches, and minor pain.</p>
                                        <ul class="product-benefits">
                                            <li>Fast pain relief</li>
                                            <li>Reduces inflammation</li>
                                            <li>Non-drowsy formula</li>
                                        </ul>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                        <span class="fw-bold">$8.49</span>
                                        <button class="btn btn-sm btn-primary">
                                            <i class="fas fa-cart-plus me-1"></i> Add
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4" data-aos="zoom-in" data-aos-delay="300">
                            <div class="product-card position-relative">
                                <span class="badge-special">Sale</span>
                                <div class="p-4 product-description">
                                    <div class="product-icon">
                                        <i class="fas fa-hand-sparkles"></i>
                                    </div>
                                    <h5>Hand Sanitizer</h5>
                                    <div class="product-details">
                                        <p>Alcohol-based formula that kills 99.9% of germs without drying your skin.</p>
                                        <ul class="product-benefits">
                                            <li>Kills 99.9% of germs</li>
                                            <li>Moisturizing formula</li>
                                            <li>Convenient travel size</li>
                                        </ul>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                        <span class="fw-bold">$5.99</span>
                                        <button class="btn btn-sm btn-primary">
                                            <i class="fas fa-cart-plus me-1"></i> Add
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4" data-aos="zoom-in" data-aos-delay="400">
                            <div class="product-card position-relative">
                                <div class="p-4 product-description">
                                    <div class="product-icon">
                                        <i class="fas fa-first-aid"></i>
                                    </div>
                                    <h5>First Aid Kit</h5>
                                    <div class="product-details">
                                        <p>Complete home kit with essential supplies for minor injuries and emergencies.</p>
                                        <ul class="product-benefits">
                                            <li>100+ essential items</li>
                                            <li>Compact and organized</li>
                                            <li>Includes first aid guide</li>
                                        </ul>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                        <span class="fw-bold">$24.99</span>
                                        <button class="btn btn-sm btn-primary">
                                            <i class="fas fa-cart-plus me-1"></i> Add
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4" data-aos="zoom-in" data-aos-delay="500">
                            <div class="product-card position-relative">
                                <div class="p-4 product-description">
                                    <div class="product-icon">
                                        <i class="fas fa-capsules"></i>
                                    </div>
                                    <h5>Omega-3 Fish Oil</h5>
                                    <div class="product-details">
                                        <p>High-quality fish oil for heart health, brain function, and overall wellness.</p>
                                        <ul class="product-benefits">
                                            <li>Supports heart health</li>
                                            <li>Promotes brain function</li>
                                            <li>Molecularly distilled</li>
                                        </ul>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                        <span class="fw-bold">$18.99</span>
                                        <button class="btn btn-sm btn-primary">
                                            <i class="fas fa-cart-plus me-1"></i> Add
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4" data-aos="zoom-in" data-aos-delay="600">
                            <div class="product-card position-relative">
                                <span class="badge-special">Popular</span>
                                <div class="p-4 product-description">
                                    <div class="product-icon">
                                        <i class="fas fa-tablets"></i>
                                    </div>
                                    <h5>Antibiotics</h5>
                                    <div class="product-details">
                                        <p>Prescription antibiotics for treating bacterial infections effectively.</p>
                                        <ul class="product-benefits">
                                            <li>Effective against bacteria</li>
                                            <li>Various formulations available</li>
                                            <li>Complete treatment course</li>
                                        </ul>
                                        <div class="prescription-required">
                                            <i class="fas fa-prescription-bottle me-1"></i> Prescription required
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                        <span class="fw-bold">Price varies</span>
                                        <button class="btn btn-sm btn-primary">
                                            <i class="fas fa-cart-plus me-1"></i> Add
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4" data-aos="fade-up">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1">Previous</a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Next</a>
                                </li>
                            </ul>
                        </nav>
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
                    <a href="priscription.php" class="footer-link">Prescription Service</a>
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
        
        // Price Range Slider
        const priceRange = document.getElementById('priceRange');
        const priceValue = document.getElementById('priceValue');
        
        priceRange.addEventListener('input', function() {
            priceValue.textContent = '$' + this.value;
        });
        
        // Add to cart functionality
        const addToCartButtons = document.querySelectorAll('.btn-sm.btn-primary');
        addToCartButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productName = this.closest('.product-card').querySelector('h5').textContent;
                
                // Check if prescription is required
                const prescriptionRequired = this.closest('.product-card').querySelector('.prescription-required');
                
                if (prescriptionRequired) {
                    alert(`${productName} requires a prescription. Please upload your prescription during checkout.`);
                } else {
                    alert(`${productName} has been added to your cart!`);
                }
            });
        });
    </script>
</body>
</html>