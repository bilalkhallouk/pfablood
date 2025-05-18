<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LifeFlow - Blood Donation Network</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #e63946;
            --primary-dark: #e63946;
            --secondary: #1d3557;
            --light: #f1faee;
            --accent: #a8dadc;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            color: #333;
        }
        
        .hero-gradient {
            background: linear-gradient(135deg, rgba(230,57,70,0.95) 0%, rgba(29,53,87,0.95) 100%);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.8rem;
        }
        
        .nav-link {
            font-weight: 500;
            position: relative;
        }
        
        .nav-link:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: var(--primary);
            transition: width 0.3s ease;
        }
        
        .nav-link:hover:after {
            width: 100%;
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        
        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(245, 235, 235, 0.1);
        }
        
        .blood-drop {
            width: 80px;
            height: 80px;
            background-color: var(--primary);
            border-radius: 50% 50% 50% 50%/60% 60% 40% 40%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            margin: 0 auto 20px;
        }
        
        .stats-item {
            position: relative;
            padding: 30px;
            border-radius: 15px;
            overflow: hidden;
            z-index: 1;
        }
        
        .stats-item:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(230,57,70,0.9) 0%, rgba(168,218,220,0.9) 100%);
            z-index: -1;
            opacity: 0.8;
        }
        
        .stats-item:nth-child(2):before {
            background: linear-gradient(135deg, rgba(29,53,87,0.9) 0%, rgba(168,218,220,0.9) 100%);
        }
        
        .stats-item:nth-child(3):before {
            background: linear-gradient(135deg, rgba(241,250,238,0.9) 0%, rgba(230,57,70,0.9) 100%);
        }
        
        .testimonial-card {
            border-left: 4px solid var(--primary);
        }
        
        .footer {
            background-color: var(--secondary);
            color: white;
        }
        
        .footer a {
            color: var(--accent);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer a:hover {
            color: white;
        }
        
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        
        .animate-on-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        .donation-process {
            position: relative;
        }
        
        .process-line {
            position: absolute;
            height: 100%;
            width: 4px;
            background: linear-gradient(to bottom, var(--primary), var(--accent));
            left: 50%;
            transform: translateX(-50%);
            z-index: 0;
        }
        
        @media (max-width: 768px) {
            .process-line {
                left: 30px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand text-danger" href="#">
                <i class="fas fa-tint me-2"></i>LifeFlow
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#process">Process</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#donors">For Donors</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#patients">For Patients</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-outline-danger" href="/login">Login</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-danger" href="/register">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-gradient text-white py-5" style="padding-top: 80px !important;">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0 animate-on-scroll">
                    <h1 class="display-4 fw-bold mb-4">Your Blood Can Save Lives</h1>
                    <p class="lead mb-4">Join our network of blood donors and help patients in need. Every donation matters and can make the difference between life and death.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="/register" class="btn btn-light btn-lg px-4">Become a Donor</a>
                        <a href="#request-blood" class="btn btn-outline-light btn-lg px-4">Request Blood</a>
                    </div>
                </div>
                <div class="col-lg-6 animate-on-scroll">
                    <img src="https://images.unsplash.com/photo-1579684385127-1ef15d508118?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=880&q=80" 
                         alt="Blood donation" 
                         class="img-fluid rounded-4 shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="bg-light py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="stats-item text-center text-white p-4 h-100 animate-on-scroll">
                        <h3 class="display-4 fw-bold">10,000+</h3>
                        <p class="mb-0">Lives Saved</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-item text-center text-white p-4 h-100 animate-on-scroll">
                        <h3 class="display-4 fw-bold">5,000+</h3>
                        <p class="mb-0">Active Donors</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-item text-center text-white p-4 h-100 animate-on-scroll">
                        <h3 class="display-4 fw-bold">24/7</h3>
                        <p class="mb-0">Emergency Support</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0 animate-on-scroll">
                    <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" 
                         alt="About blood donation" 
                         class="img-fluid rounded-4 shadow">
                </div>
                <div class="col-lg-6 animate-on-scroll">
                    <span class="text-danger fw-bold">ABOUT US</span>
                    <h2 class="fw-bold mb-4">Connecting Donors With Patients In Need</h2>
                    <p class="mb-4">LifeFlow is a revolutionary platform that bridges the gap between blood donors and patients who desperately need blood transfusions. Our mission is to ensure no patient dies due to lack of blood.</p>
                    
                    <div class="d-flex mb-4">
                        <div class="me-4">
                            <div class="blood-drop">
                                <i class="fas fa-heartbeat"></i>
                            </div>
                            <h5 class="text-center">Save Lives</h5>
                        </div>
                        <div class="me-4">
                            <div class="blood-drop">
                                <i class="fas fa-users"></i>
                            </div>
                            <h5 class="text-center">Community</h5>
                        </div>
                        <div>
                            <div class="blood-drop">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <h5 class="text-center">Quick Response</h5>
                        </div>
                    </div>
                    
                    <a href="#how-it-works" class="btn btn-danger px-4">Learn More</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section id="process" class="py-5 bg-light">
        <div class="container py-5">
            <div class="text-center mb-5 animate-on-scroll">
                <span class="text-danger fw-bold">DONATION PROCESS</span>
                <h2 class="fw-bold mb-3">How It Works</h2>
                <p class="lead mx-auto" style="max-width: 700px;">Our simple 4-step process makes blood donation and request seamless and efficient</p>
            </div>
            
            <div class="row donation-process">
                <div class="process-line d-none d-md-block"></div>
                
                <div class="col-md-6 mb-5 animate-on-scroll">
                    <div class="card feature-card h-100 border-0 shadow-sm ms-md-auto" style="max-width: 500px;">
                        <div class="card-body p-4">
                            <div class="d-flex">
                                <div class="me-4">
                                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <h4 class="mb-0">1</h4>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="mb-3">Register Your Profile</h4>
                                    <p class="mb-0">Create an account as either a donor or patient. Provide your basic information and blood type.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-5 animate-on-scroll">
                    <div class="card feature-card h-100 border-0 shadow-sm" style="max-width: 500px;">
                        <div class="card-body p-4">
                            <div class="d-flex">
                                <div class="me-4">
                                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <h4 class="mb-0">2</h4>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="mb-3">Find a Match</h4>
                                    <p class="mb-0">Our system automatically matches patients with compatible donors based on blood type and location.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-5 animate-on-scroll">
                    <div class="card feature-card h-100 border-0 shadow-sm ms-md-auto" style="max-width: 500px;">
                        <div class="card-body p-4">
                            <div class="d-flex">
                                <div class="me-4">
                                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <h4 class="mb-0">3</h4>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="mb-3">Schedule Donation</h4>
                                    <p class="mb-0">Coordinate with the donor/patient to schedule the donation at a nearby blood bank or hospital.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-5 animate-on-scroll">
                    <div class="card feature-card h-100 border-0 shadow-sm" style="max-width: 500px;">
                        <div class="card-body p-4">
                            <div class="d-flex">
                                <div class="me-4">
                                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <h4 class="mb-0">4</h4>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="mb-3">Save a Life</h4>
                                    <p class="mb-0">Complete the donation process and receive notifications about how your blood helped save lives.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Blood Types Section -->
    <section class="py-5">
        <div class="container py-5">
            <div class="text-center mb-5 animate-on-scroll">
                <span class="text-danger fw-bold">BLOOD TYPES</span>
                <h2 class="fw-bold mb-3">Blood Type Compatibility</h2>
                <p class="lead mx-auto" style="max-width: 700px;">Understanding blood type compatibility is crucial for successful transfusions</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-3 animate-on-scroll">
                    <div class="card feature-card h-100 border-0 shadow-sm">
                        <div class="card-body p-4 text-center">
                            <div class="blood-type-icon mb-3">
                                <div class="blood-drop bg-danger mx-auto">
                                    <span class="fw-bold">A+</span>
                                </div>
                            </div>
                            <h5 class="mb-3">Type A+</h5>
                            <p class="mb-0">Can receive from: A+, A-, O+, O-</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3 animate-on-scroll">
                    <div class="card feature-card h-100 border-0 shadow-sm">
                        <div class="card-body p-4 text-center">
                            <div class="blood-type-icon mb-3">
                                <div class="blood-drop bg-danger mx-auto">
                                    <span class="fw-bold">B+</span>
                                </div>
                            </div>
                            <h5 class="mb-3">Type B+</h5>
                            <p class="mb-0">Can receive from: B+, B-, O+, O-</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3 animate-on-scroll">
                    <div class="card feature-card h-100 border-0 shadow-sm">
                        <div class="card-body p-4 text-center">
                            <div class="blood-type-icon mb-3">
                                <div class="blood-drop bg-danger mx-auto">
                                    <span class="fw-bold">AB+</span>
                                </div>
                            </div>
                            <h5 class="mb-3">Type AB+</h5>
                            <p class="mb-0">Universal recipient (can receive from all types)</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3 animate-on-scroll">
                    <div class="card feature-card h-100 border-0 shadow-sm">
                        <div class="card-body p-4 text-center">
                            <div class="blood-type-icon mb-3">
                                <div class="blood-drop bg-danger mx-auto">
                                    <span class="fw-bold">O+</span>
                                </div>
                            </div>
                            <h5 class="mb-3">Type O+</h5>
                            <p class="mb-0">Can receive from: O+, O-</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-5 bg-light">
        <div class="container py-5">
            <div class="text-center mb-5 animate-on-scroll">
                <span class="text-danger fw-bold">TESTIMONIALS</span>
                <h2 class="fw-bold mb-3">What People Say</h2>
                <p class="lead mx-auto" style="max-width: 700px;">Stories from donors and recipients who have been part of our community</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 animate-on-scroll">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="mb-4">
                                <img src="https://randomuser.me/api/portraits/women/32.jpg" 
                                     alt="User" 
                                     class="rounded-circle me-3" 
                                     width="60">
                                <div class="d-inline-block">
                                    <h5 class="mb-0">Sarah Johnson</h5>
                                    <small class="text-muted">Blood Donor</small>
                                </div>
                            </div>
                            <div class="testimonial-card ps-3">
                                <p class="mb-0">"I've donated blood 5 times through LifeFlow. The process is so easy and knowing I've helped save lives is incredibly rewarding."</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 animate-on-scroll">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="mb-4">
                                <img src="https://randomuser.me/api/portraits/men/45.jpg" 
                                     alt="User" 
                                     class="rounded-circle me-3" 
                                     width="60">
                                <div class="d-inline-block">
                                    <h5 class="mb-0">Michael Chen</h5>
                                    <small class="text-muted">Patient's Father</small>
                                </div>
                            </div>
                            <div class="testimonial-card ps-3">
                                <p class="mb-0">"When my daughter needed emergency surgery, LifeFlow connected us with 3 matching donors within hours. They saved her life."</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 animate-on-scroll">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="mb-4">
                                <img src="https://randomuser.me/api/portraits/women/68.jpg" 
                                     alt="User" 
                                     class="rounded-circle me-3" 
                                     width="60">
                                <div class="d-inline-block">
                                    <h5 class="mb-0">Dr. Amina Kourouma</h5>
                                    <small class="text-muted">Hospital Administrator</small>
                                </div>
                            </div>
                            <div class="testimonial-card ps-3">
                                <p class="mb-0">"LifeFlow has revolutionized how we source blood for our patients. The response time and donor availability have improved dramatically."</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section id="request-blood" class="py-5 text-white hero-gradient">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center animate-on-scroll">
                    <h2 class="fw-bold mb-4">Ready to Make a Difference?</h2>
                    <p class="lead mb-5">Whether you want to donate blood or need blood for yourself or a loved one, our community is here to help.</p>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="/register?role=donor" class="btn btn-light btn-lg px-4">Become a Donor</a>
                        <a href="/register?role=patient" class="btn btn-outline-light btn-lg px-4">Request Blood</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer py-5">
        <div class="container py-4">
            <div class="row g-4">
                <div class="col-lg-4 animate-on-scroll">
                    <h3 class="text-white mb-4">
                        <i class="fas fa-tint me-2"></i>LifeFlow
                    </h3>
                    <p>Connecting blood donors with patients in need to save lives and build healthier communities.</p>
                    <div class="d-flex gap-3 mt-4">
                        <a href="#"><i class="fab fa-facebook-f fa-lg"></i></a>
                        <a href="#"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in fa-lg"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-4 animate-on-scroll">
                    <h5 class="text-white mb-4">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#home">Home</a></li>
                        <li class="mb-2"><a href="#about">About</a></li>
                        <li class="mb-2"><a href="#process">Process</a></li>
                        <li class="mb-2"><a href="#donors">For Donors</a></li>
                        <li class="mb-2"><a href="#patients">For Patients</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-4 animate-on-scroll">
                    <h5 class="text-white mb-4">Contact Us</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> 123 Health St, Medical City</li>
                        <li class="mb-2"><i class="fas fa-phone me-2"></i> +1 (555) 123-4567</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i> info@lifeflow.org</li>
                        <li class="mb-2"><i class="fas fa-clock me-2"></i> 24/7 Emergency Support</li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-4 animate-on-scroll">
                    <h5 class="text-white mb-4">Newsletter</h5>
                    <p>Subscribe to our newsletter for updates and blood donation campaigns.</p>
                    <form class="mt-3">
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" placeholder="Your Email">
                            <button class="btn btn-danger" type="button">Subscribe</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <hr class="my-4 bg-light opacity-10">
            
            <div class="row">
                <div class="col-md-6 text-center text-md-start animate-on-scroll">
                    <p class="mb-0">&copy; 2023 LifeFlow. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end animate-on-scroll">
                    <p class="mb-0">Made with <i class="fas fa-heart text-danger"></i> to save lives</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation on scroll
        function animateOnScroll() {
            const elements = document.querySelectorAll('.animate-on-scroll');
            
            elements.forEach(element => {
                const elementPosition = element.getBoundingClientRect().top;
                const windowHeight = window.innerHeight;
                
                if (elementPosition < windowHeight - 100) {
                    element.classList.add('visible');
                }
            });
        }
        
        // Run on load and scroll
        window.addEventListener('load', animateOnScroll);
        window.addEventListener('scroll', animateOnScroll);
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        // Navbar background change on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('shadow');
                navbar.classList.add('bg-white');
                navbar.classList.remove('bg-transparent');
            } else {
                navbar.classList.remove('shadow');
                navbar.classList.remove('bg-white');
                navbar.classList.add('bg-transparent');
            }
        });
    </script>
</body>
</html>