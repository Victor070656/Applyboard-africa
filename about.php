<?php
include_once "config/config.php";
$loggedIn = false;
if (!empty($_SESSION["sdtravels_user"])) {
  $loggedIn = true;
  $uid = $_SESSION["sdtravels_user"];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>About Us | ApplyBoard Africa Ltd</title>
  <meta name="description" content="Learn about ApplyBoard Africa Ltd - Your trusted partner for visa processing, study abroad consultation, and immigration services." />

  <!-- Favicon -->
  <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon" />

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

  <style>
    /* RESET & BASE */
    * { margin: 0; padding: 0; box-sizing: border-box; }
    html { scroll-behavior: smooth; }
    body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; color: #1E293B; background: #FFFFFF; line-height: 1.6; overflow-x: hidden; }
    img { max-width: 100%; height: auto; }
    a { text-decoration: none; color: inherit; }
    ul { list-style: none; }

    /* HEADER */
    .modern-header { position: fixed; top: 0; left: 0; right: 0; z-index: 1000; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border-bottom: 1px solid rgba(0, 0, 0, 0.05); transition: all 0.3s ease; }
    .modern-header .header-container { max-width: 1400px; margin: 0 auto; padding: 16px 24px; display: flex; align-items: center; justify-content: space-between; }
    .modern-header .logo img { height: 45px; object-fit: contain; }
    .modern-header .nav-menu { display: flex; align-items: center; gap: 8px; }
    .modern-header .nav-link { padding: 10px 18px; color: #1E293B; font-weight: 500; font-size: 15px; text-decoration: none; border-radius: 8px; transition: all 0.3s ease; position: relative; }
    .modern-header .nav-link:hover, .modern-header .nav-link.active { color: #0F4C75; }
    .modern-header .nav-link::after { content: ''; position: absolute; bottom: 4px; left: 50%; width: 0; height: 2px; background: linear-gradient(90deg, #0F4C75, #3282B8); transition: all 0.3s ease; transform: translateX(-50%); }
    .modern-header .nav-link:hover::after, .modern-header .nav-link.active::after { width: 30px; }
    .modern-header .btn-header { padding: 12px 28px; background: linear-gradient(135deg, #0F4C75, #3282B8); color: #FFFFFF !important; border: none; border-radius: 50px; font-weight: 600; font-size: 14px; text-decoration: none !important; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(15, 76, 117, 0.3); }
    .modern-header .btn-header:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(15, 76, 117, 0.4); }
    .modern-header .mobile-toggle { display: none; flex-direction: column; gap: 5px; padding: 10px; cursor: pointer; }
    .modern-header .mobile-toggle span { width: 25px; height: 2px; background: #1E293B; border-radius: 2px; transition: all 0.3s ease; }

    /* MOBILE MENU */
    .mobile-menu-panel { position: fixed; top: 0; right: -100%; width: 100%; max-width: 400px; height: 100vh; background: #FFFFFF; z-index: 1001; padding: 80px 32px 32px; transition: right 0.3s ease; overflow-y: auto; }
    .mobile-menu-panel.active { right: 0; }
    .mobile-menu-panel .close-btn { position: absolute; top: 24px; right: 24px; width: 44px; height: 44px; background: #F8FAFC; border: none; border-radius: 50%; font-size: 20px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
    .mobile-menu-panel .mobile-nav { list-style: none; display: flex; flex-direction: column; gap: 8px; }
    .mobile-menu-panel .mobile-nav a { display: block; padding: 16px 20px; color: #0F172A; font-size: 18px; font-weight: 500; text-decoration: none; border-radius: 8px; transition: all 0.3s ease; }
    .mobile-menu-panel .mobile-nav a:hover, .mobile-menu-panel .mobile-nav a.active { background: #F8FAFC; color: #0F4C75; }
    .mobile-menu-overlay { position: fixed; inset: 0; background: rgba(0, 0, 0, 0.5); z-index: 1000; opacity: 0; visibility: hidden; transition: all 0.3s ease; }
    .mobile-menu-overlay.active { opacity: 1; visibility: visible; }

    /* PAGE HERO */
    .page-hero { position: relative; min-height: 60vh; display: flex; align-items: center; padding-top: 80px; }
    .page-hero .hero-bg { position: absolute; inset: 0; z-index: -1; }
    .page-hero .hero-bg img { width: 100%; height: 100%; object-fit: cover; }
    .page-hero .hero-overlay { position: absolute; inset: 0; background: linear-gradient(135deg, rgba(15, 76, 117, 0.95) 0%, rgba(50, 130, 184, 0.85) 100%); }
    .page-hero .hero-content { position: relative; max-width: 1200px; margin: 0 auto; padding: 60px 24px; text-align: center; color: #FFFFFF; }
    .page-hero .hero-subtitle { color: #D4A853; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 16px; }
    .page-hero h1 { font-size: clamp(42px, 6vw, 64px); font-weight: 800; margin-bottom: 24px; line-height: 1.1; }
    .page-hero .hero-desc { font-size: 18px; opacity: 0.9; max-width: 700px; margin: 0 auto; }

    /* BREADCRUMB */
    .breadcrumb { background: #F8FAFC; padding: 20px 0; }
    .breadcrumb .container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
    .breadcrumb p { color: #64748B; font-size: 14px; }
    .breadcrumb a { color: #0F4C75; text-decoration: none; }

    /* SECTIONS */
    .modern-section { padding: 100px 0; position: relative; }
    .modern-section.bg-light { background: #F8FAFC; }
    .section-header { text-align: center; max-width: 700px; margin: 0 auto 60px; }
    .section-header .section-tag { display: inline-flex; align-items: center; gap: 8px; padding: 8px 20px; background: linear-gradient(135deg, rgba(15, 76, 117, 0.1), rgba(50, 130, 184, 0.1)); color: #0F4C75; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; border-radius: 50px; margin-bottom: 20px; }
    .section-header h2 { font-size: clamp(32px, 4vw, 42px); font-weight: 800; color: #0F172A; margin-bottom: 16px; letter-spacing: -0.02em; }
    .section-header p { font-size: 18px; color: #64748B; line-height: 1.7; }

    /* STORY SECTION */
    .story-section { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
    .story-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: center; }
    .story-content .badge { display: inline-block; padding: 8px 20px; background: linear-gradient(135deg, rgba(15, 76, 117, 0.1), rgba(50, 130, 184, 0.1)); color: #0F4C75; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; border-radius: 50px; margin-bottom: 20px; }
    .story-content h2 { font-size: clamp(32px, 4vw, 42px); font-weight: 800; color: #0F172A; margin-bottom: 24px; line-height: 1.2; }
    .story-content > p { color: #64748B; line-height: 1.8; margin-bottom: 20px; }
    .story-stats { display: flex; gap: 40px; flex-wrap: wrap; }
    .story-stat h3 { font-size: 42px; font-weight: 800; background: linear-gradient(135deg, #0F4C75, #3282B8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; line-height: 1; }
    .story-stat p { color: #64748B; font-size: 14px; text-transform: uppercase; letter-spacing: 1px; margin-top: 8px; }
    .story-image { position: relative; }
    .story-image img { width: 100%; border-radius: 32px; box-shadow: 0 16px 48px rgba(0, 0, 0, 0.15); }
    .story-image .floating-badge { position: absolute; bottom: -30px; left: -30px; background: #FFFFFF; padding: 24px 32px; border-radius: 24px; box-shadow: 0 16px 48px rgba(0, 0, 0, 0.15); }
    .floating-badge .badge-icon { width: 60px; height: 60px; background: linear-gradient(135deg, #D4A853, #E8C97A); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 24px; color: #0F172A; margin-bottom: 12px; }

    /* MISSION VISION */
    .mission-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 32px; max-width: 1200px; margin: 0 auto; padding: 0 24px; }
    .mission-card { background: #FFFFFF; padding: 48px; border-radius: 24px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); transition: all 0.3s ease; }
    .mission-card:hover { box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12); }
    .mission-card .card-icon { width: 70px; height: 70px; background: linear-gradient(135deg, #0F4C75, #3282B8); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 28px; color: #FFFFFF; margin-bottom: 24px; }
    .mission-card:nth-child(2) .card-icon { background: linear-gradient(135deg, #D4A853, #E8C97A); color: #0F172A; }
    .mission-card h3 { font-size: 26px; font-weight: 700; color: #0F172A; margin-bottom: 16px; }
    .mission-card p { color: #64748B; line-height: 1.8; }

    /* VALUES */
    .values-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 32px; max-width: 1200px; margin: 0 auto; padding: 0 24px; }
    .value-card { text-align: center; padding: 32px 20px; background: #FFFFFF; border-radius: 24px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); transition: all 0.3s ease; }
    .value-card:hover { box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12); transform: translateY(-4px); }
    .value-card .value-icon { width: 60px; height: 60px; background: linear-gradient(135deg, #0F4C75, #3282B8); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; color: #FFFFFF; margin: 0 auto 20px; }
    .value-card:nth-child(even) .value-icon { background: linear-gradient(135deg, #D4A853, #E8C97A); color: #0F172A; }
    .value-card h4 { font-size: 18px; font-weight: 700; color: #0F172A; margin-bottom: 12px; }
    .value-card p { font-size: 14px; color: #64748B; line-height: 1.6; }

    /* TEAM */
    .team-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px; max-width: 1200px; margin: 0 auto; padding: 0 24px; }
    .team-card { background: #FFFFFF; border-radius: 24px; overflow: hidden; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12); transition: all 0.4s ease; }
    .team-card:hover { transform: translateY(-8px); box-shadow: 0 16px 48px rgba(0, 0, 0, 0.2); }
    .team-card .team-image { height: 320px; overflow: hidden; }
    .team-card .team-image img { width: 100%; height: 100%; object-fit: cover; }
    .team-card .team-content { padding: 24px; }
    .team-card .team-name { font-size: 20px; font-weight: 700; color: #0F172A; margin-bottom: 4px; }
    .team-card .team-role { font-size: 14px; color: #D4A853; font-weight: 600; margin-bottom: 12px; }
    .team-card .team-bio { font-size: 14px; color: #64748B; line-height: 1.6; }

    /* CTA */
    .cta-section { padding: 100px 24px; background: linear-gradient(135deg, #0F4C75 0%, #3282B8 100%); text-align: center; color: #FFFFFF; }
    .cta-section h2 { font-size: clamp(32px, 4vw, 48px); font-weight: 800; margin-bottom: 16px; }
    .cta-section p { font-size: 18px; opacity: 0.9; margin-bottom: 32px; max-width: 600px; margin-left: auto; margin-right: auto; }
    .cta-section .cta-btn { display: inline-flex; align-items: center; gap: 12px; padding: 18px 40px; background: #FFFFFF; color: #0F4C75; font-weight: 700; font-size: 16px; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 8px 25px rgba(0,0,0,0.2); }
    .cta-section .cta-btn:hover { transform: translateY(-4px); box-shadow: 0 12px 35px rgba(0,0,0,0.3); }
    .cta-section .cta-btn-sec { display: inline-flex; align-items: center; gap: 12px; padding: 18px 40px; background: transparent; color: #FFFFFF; font-weight: 700; font-size: 16px; border: 2px solid #FFFFFF; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; margin-left: 16px; }
    .cta-section .cta-btn-sec:hover { background: rgba(255,255,255,0.1); }

    /* FOOTER */
    .modern-footer { background: #0F172A; color: #FFFFFF; padding-top: 80px; }
    .modern-footer .footer-top { max-width: 1400px; margin: 0 auto; padding: 0 24px 60px; display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 60px; }
    .modern-footer .footer-brand .logo { margin-bottom: 20px; display: inline-block; }
    .modern-footer .footer-brand .logo img { height: 50px; }
    .modern-footer .footer-brand p { color: rgba(255, 255, 255, 0.7); line-height: 1.8; margin-bottom: 24px; }
    .modern-footer .social-links { display: flex; gap: 12px; }
    .modern-footer .social-links a { width: 44px; height: 44px; background: rgba(255, 255, 255, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #FFFFFF; transition: all 0.3s ease; }
    .modern-footer .social-links a:hover { background: #D4A853; color: #0F172A; transform: translateY(-4px); }
    .modern-footer .footer-column h4 { font-size: 18px; font-weight: 700; margin-bottom: 24px; }
    .modern-footer .footer-links { list-style: none; display: flex; flex-direction: column; gap: 12px; }
    .modern-footer .footer-links a { color: rgba(255, 255, 255, 0.7); text-decoration: none; transition: all 0.3s ease; display: flex; align-items: center; gap: 8px; }
    .modern-footer .footer-links a:hover { color: #D4A853; transform: translateX(4px); }
    .modern-footer .footer-contact-item { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 20px; }
    .modern-footer .footer-contact-item i { color: #D4A853; font-size: 18px; margin-top: 2px; }
    .modern-footer .footer-contact-item p { color: rgba(255, 255, 255, 0.7); margin: 0; }
    .modern-footer .footer-contact-item a { color: rgba(255, 255, 255, 0.7); text-decoration: none; transition: color 0.3s ease; }
    .modern-footer .footer-contact-item a:hover { color: #D4A853; }
    .modern-footer .footer-bottom { border-top: 1px solid rgba(255, 255, 255, 0.1); padding: 24px; text-align: center; }
    .modern-footer .footer-bottom p { color: rgba(255, 255, 255, 0.5); margin: 0; }
    .modern-footer .footer-bottom a { color: #D4A853; text-decoration: none; }

    /* SCROLL TOP */
    .scroll-top { position: fixed; bottom: 30px; right: 30px; width: 50px; height: 50px; background: #D4A853; color: #0F172A; border: none; border-radius: 50%; font-size: 20px; cursor: pointer; opacity: 0; visibility: hidden; transition: all 0.3s ease; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2); z-index: 999; }
    .scroll-top.visible { opacity: 1; visibility: visible; }
    .scroll-top:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0, 0, 0, 0.3); }

    /* RESPONSIVE */
    @media (max-width: 1024px) {
      .modern-header .nav-menu { display: none; }
      .modern-header .mobile-toggle { display: flex; }
      .story-grid { grid-template-columns: 1fr; gap: 60px; }
      .story-image { order: -1; }
      .mission-grid { grid-template-columns: 1fr; }
      .modern-footer .footer-top { grid-template-columns: 1fr 1fr; gap: 40px; }
      .values-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 768px) {
      .team-grid { grid-template-columns: 1fr; }
      .modern-footer .footer-top { grid-template-columns: 1fr; }
      .values-grid { grid-template-columns: 1fr; }
      .story-stats { gap: 24px; }
      .story-stat h3 { font-size: 32px; }
    }
    @media (max-width: 480px) {
      .modern-header .header-container { padding: 12px 16px; }
      .modern-header .logo img { height: 35px; }
      .section-header h2 { font-size: 28px; }
      .scroll-top { bottom: 20px; right: 20px; width: 44px; height: 44px; }
    }
  </style>
</head>

<body>
  <!-- Modern Header -->
  <header class="modern-header">
    <div class="header-container">
      <a href="./" class="logo">
        <img src="images/logo-3.png" alt="ApplyBoard Africa Ltd" />
      </a>
      <nav class="nav-menu">
        <a href="./" class="nav-link">Home</a>
        <a href="about.php" class="nav-link active">About</a>
        <a href="services.php" class="nav-link">Services</a>
        <a href="platform.php" class="nav-link">Platform</a>
        <a href="agents.php" class="nav-link">Agents</a>
        <a href="contact.php" class="nav-link">Contact</a>
        <?php if ($loggedIn): ?>
          <a href="user" class="btn-header">Dashboard</a>
        <?php else: ?>
          <a href="user/login.php" class="btn-header">Login</a>
        <?php endif; ?>
      </nav>
      <div class="mobile-toggle" onclick="toggleMobileMenu()">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </header>

  <!-- Mobile Menu -->
  <div class="mobile-menu-overlay" id="mobileOverlay" onclick="toggleMobileMenu()"></div>
  <div class="mobile-menu-panel" id="mobileMenu">
    <button class="close-btn" onclick="toggleMobileMenu()"><i class="fas fa-times"></i></button>
    <ul class="mobile-nav">
      <li><a href="./">Home</a></li>
      <li><a href="about.php" class="active">About</a></li>
      <li><a href="services.php">Services</a></li>
      <li><a href="platform.php">Platform</a></li>
      <li><a href="agents.php">Agents</a></li>
      <li><a href="contact.php">Contact</a></li>
      <?php if ($loggedIn): ?>
        <li><a href="user" class="btn-header">Dashboard</a></li>
      <?php else: ?>
        <li><a href="user/login.php" class="btn-header">Login</a></li>
      <?php endif; ?>
    </ul>
  </div>

  <!-- Page Hero -->
  <section class="page-hero">
    <div class="hero-bg"><img src="images/main-slider/2.jpg" alt="" /></div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
      <p class="hero-subtitle">Get To Know Us</p>
      <h1>About ApplyBoard Africa</h1>
      <p class="hero-desc">Your trusted partner for hassle-free visa processing, study abroad consultation, and immigration services.</p>
    </div>
  </section>

  <!-- Breadcrumb -->
  <section class="breadcrumb">
    <div class="container">
      <p><a href="./">Home</a> <span style="margin: 0 8px;">/</span> <span style="color: #D4A853;">About Us</span></p>
    </div>
  </section>

  <!-- Story Section -->
  <section class="modern-section">
    <div class="story-section">
      <div class="story-grid">
        <div class="story-content">
          <span class="badge"><i class="fas fa-heart"></i> Our Story</span>
          <h2>Your Trusted Partner in Travel and Education</h2>
          <p>ApplyBoard Africa Ltd is a leading travel and immigration consultancy dedicated to providing seamless travel and educational consulting services. Whether you are looking to study abroad, explore new destinations, or require visa assistance, we are here to guide you every step of the way.</p>
          <p>Our mission is to empower individuals to achieve their global dreams through transparent, reliable, and personalized support. With over 10 years of experience and a 99% success rate, we have helped thousands of clients successfully relocate, study, and work abroad.</p>
          <div class="story-stats">
            <div class="story-stat">
              <h3>10+</h3>
              <p>Years Experience</p>
            </div>
            <div class="story-stat">
              <h3>5000+</h3>
              <p>Happy Clients</p>
            </div>
            <div class="story-stat">
              <h3>99%</h3>
              <p>Success Rate</p>
            </div>
          </div>
        </div>
        <div class="story-image">
          <img src="images/resource/about-1.jpg" alt="About Us" />
          <div class="floating-badge">
            <div class="badge-icon"><i class="fas fa-award"></i></div>
            <div>
              <p style="font-size: 14px; color: #64748B; margin: 0;">Trusted Agency</p>
              <p style="font-size: 18px; font-weight: 700; color: #0F172A; margin: 4px 0 0;">Certified Experts</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Mission & Vision -->
  <section class="modern-section bg-light">
    <div class="section-header">
      <span class="section-tag"><i class="fas fa-star"></i> What Drives Us</span>
      <h2>Our Mission & Vision</h2>
      <p>The foundation of our commitment to excellence in immigration services.</p>
    </div>
    <div class="mission-grid">
      <div class="mission-card">
        <div class="card-icon"><i class="fas fa-bullseye"></i></div>
        <h3>Our Mission</h3>
        <p>To provide accessible, transparent, and reliable immigration services that empower individuals to achieve their dreams of living, studying, and working abroad. We are committed to delivering personalized support that ensures every client's journey is smooth and successful.</p>
      </div>
      <div class="mission-card">
        <div class="card-icon"><i class="fas fa-eye"></i></div>
        <h3>Our Vision</h3>
        <p>To be Africa's leading immigration consultancy, recognized for excellence, integrity, and innovation. We envision a world where borders are no longer barriers to education, career growth, and family reunification.</p>
      </div>
    </div>
  </section>

  <!-- Core Values -->
  <section class="modern-section">
    <div class="section-header">
      <span class="section-tag"><i class="fas fa-gem"></i> Our Values</span>
      <h2>Core Values That Guide Us</h2>
    </div>
    <div class="values-grid">
      <div class="value-card">
        <div class="value-icon"><i class="fas fa-shield-alt"></i></div>
        <h4>Integrity</h4>
        <p>We maintain honesty and transparency in all our dealings.</p>
      </div>
      <div class="value-card">
        <div class="value-icon"><i class="fas fa-award"></i></div>
        <h4>Excellence</h4>
        <p>We deliver the highest quality service in everything we do.</p>
      </div>
      <div class="value-card">
        <div class="value-icon"><i class="fas fa-users"></i></div>
        <h4>Client Focus</h4>
        <p>Our clients' success is our top priority.</p>
      </div>
      <div class="value-card">
        <div class="value-icon"><i class="fas fa-lightbulb"></i></div>
        <h4>Innovation</h4>
        <p>We continuously improve and embrace new solutions.</p>
      </div>
    </div>
  </section>

  <!-- Team -->
  <section class="modern-section bg-light">
    <div class="section-header">
      <span class="section-tag"><i class="fas fa-user-friends"></i> Our Team</span>
      <h2>Leadership Team</h2>
      <p>Meet the experienced professionals dedicated to making your dreams a reality.</p>
    </div>
    <div class="team-grid">
      <div class="team-card">
        <div class="team-image"><img src="images/travel/new/staff/07.jpeg" alt="CEO" /></div>
        <div class="team-content">
          <h3 class="team-name">Oluwasesan Marcus Debo</h3>
          <p class="team-role">Chief Executive Officer</p>
        </div>
      </div>
      <div class="team-card">
        <div class="team-image"><img src="images/travel/new/staff/05.jpeg" alt="MD" /></div>
        <div class="team-content">
          <h3 class="team-name">Oluwasesan Omolola</h3>
          <p class="team-role">Managing Director</p>
        </div>
      </div>
      <div class="team-card">
        <div class="team-image"><img src="images/travel/new/staff/03.jpeg" alt="Manager" /></div>
        <div class="team-content">
          <h3 class="team-name">Adabiri Deborah</h3>
          <p class="team-role">Operations Manager</p>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="cta-section">
    <h2>Ready to Start Your Journey?</h2>
    <p>Contact us today for a free consultation and let our experts guide you through your visa application process.</p>
    <div>
      <a href="contact.php" class="cta-btn">Get Started <i class="fas fa-arrow-right" style="margin-left: 8px;"></i></a>
      <a href="tel:+2349069503394" class="cta-btn-sec"><i class="fas fa-phone-alt" style="margin-right: 8px;"></i> +234 906 9503 394</a>
    </div>
  </section>

  <!-- Footer -->
  <footer class="modern-footer">
    <div class="footer-top">
      <div class="footer-brand">
        <a href="./" class="logo"><img src="images/logo-2.png" alt="ApplyBoard Africa Ltd" /></a>
        <p>ApplyBoard Africa Ltd is your trusted partner for hassle-free visa processing, study abroad consultation, and immigration services.</p>
        <div class="social-links">
          <a href="https://facebook.com" target="_blank"><i class="fab fa-facebook-f"></i></a>
          <a href="https://twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
          <a href="https://instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
          <a href="https://linkedin.com" target="_blank"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>
      <div class="footer-column">
        <h4>Quick Links</h4>
        <ul class="footer-links">
          <li><a href="./"><i class="fas fa-chevron-right"></i> Home</a></li>
          <li><a href="about.php"><i class="fas fa-chevron-right"></i> About</a></li>
          <li><a href="services.php"><i class="fas fa-chevron-right"></i> Services</a></li>
          <li><a href="platform.php"><i class="fas fa-chevron-right"></i> Platform</a></li>
          <li><a href="agents.php"><i class="fas fa-chevron-right"></i> Agents</a></li>
          <li><a href="contact.php"><i class="fas fa-chevron-right"></i> Contact</a></li>
        </ul>
      </div>
      <div class="footer-column">
        <h4>Our Services</h4>
        <ul class="footer-links">
          <li><a href="services.php"><i class="fas fa-chevron-right"></i> Study Abroad Consulting</a></li>
          <li><a href="services.php"><i class="fas fa-chevron-right"></i> Admissions Support</a></li>
          <li><a href="services.php"><i class="fas fa-chevron-right"></i> Student & Tourist Visa</a></li>
          <li><a href="services.php"><i class="fas fa-chevron-right"></i> Flight & Hotel Booking</a></li>
          <li><a href="services.php"><i class="fas fa-chevron-right"></i> Agent Referral Program</a></li>
          <li><a href="services.php"><i class="fas fa-chevron-right"></i> Application Tracking</a></li>
        </ul>
      </div>
      <div class="footer-column">
        <h4>Contact Us</h4>
        <div class="footer-contact-item">
          <i class="fas fa-map-marker-alt"></i>
          <p>Ibadan, Oyo State, Nigeria</p>
        </div>
        <div class="footer-contact-item">
          <i class="fas fa-phone-alt"></i>
          <p><a href="tel:+2349069503394">+234 906 9503 394</a></p>
        </div>
        <div class="footer-contact-item">
          <i class="fas fa-envelope"></i>
          <p><a href="mailto:info@applyboardafrica.com">info@applyboardafrica.com</a></p>
        </div>
        <div class="footer-contact-item">
          <i class="fas fa-clock"></i>
          <p>Mon - Sat: 8:00 AM - 6:30 PM</p>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; <?php echo date('Y'); ?> <a href="./">ApplyBoard Africa Ltd</a>. All Rights Reserved.</p>
    </div>
  </footer>

  <button class="scroll-top" id="scrollTop" onclick="scrollToTop()"><i class="fas fa-arrow-up"></i></button>

  <script>
    function toggleMobileMenu() {
      document.getElementById('mobileMenu').classList.toggle('active');
      document.getElementById('mobileOverlay').classList.toggle('active');
      document.body.style.overflow = document.getElementById('mobileMenu').classList.contains('active') ? 'hidden' : '';
    }
    const scrollTopBtn = document.getElementById('scrollTop');
    window.addEventListener('scroll', () => {
      scrollTopBtn.classList.toggle('visible', window.pageYOffset > 300);
    });
    function scrollToTop() { window.scrollTo({ top: 0, behavior: 'smooth' }); }
  </script>
</body>
</html>
