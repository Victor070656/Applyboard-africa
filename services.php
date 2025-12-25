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
  <title>Our Services | ApplyBoard Africa Ltd</title>
  <meta name="description" content="Explore our comprehensive visa processing, study abroad consultation, IELTS coaching, and immigration services." />

  <!-- Favicon -->
  <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon" />
  <link rel="icon" href="images/favicon.png" type="image/x-icon" />

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

  <style>
    /* Reset & Base */
    *, *::before, *::after {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html {
      scroll-behavior: smooth;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      font-size: 16px;
      line-height: 1.7;
      color: #334155;
      background: #ffffff;
      overflow-x: hidden;
    }

    /* Colors */
    :root {
      --primary: #0F4C75;
      --primary-dark: #0A3655;
      --secondary: #3282B8;
      --gold: #D4A853;
      --gold-light: #E8C97A;
      --white: #ffffff;
      --off-white: #F8FAFC;
      --light-gray: #E2E8F0;
      --medium-gray: #64748B;
      --darker: #1E293B;
    }

    img {
      max-width: 100%;
      height: auto;
      display: block;
    }

    a {
      text-decoration: none;
      color: inherit;
      transition: all 0.3s ease;
    }

    ul {
      list-style: none;
    }

    /* Modern Header */
    .modern-header {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1000;
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border-bottom: 1px solid rgba(0, 0, 0, 0.05);
      transition: all 0.3s ease;
    }

    .modern-header.scrolled {
      box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    }

    .header-container {
      max-width: 1400px;
      margin: 0 auto;
      padding: 0 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      height: 80px;
    }

    .modern-header .logo {
      display: flex;
      align-items: center;
    }

    .modern-header .logo img {
      height: 50px;
      width: auto;
    }

    .nav-menu {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .nav-link {
      padding: 10px 20px;
      font-size: 15px;
      font-weight: 500;
      color: #334155;
      border-radius: 10px;
      transition: all 0.3s ease;
      position: relative;
    }

    .nav-link::after {
      content: '';
      position: absolute;
      bottom: 4px;
      left: 50%;
      transform: translateX(-50%) scaleX(0);
      width: 30px;
      height: 2px;
      background: #D4A853;
      transition: transform 0.3s ease;
    }

    .nav-link:hover::after,
    .nav-link.active::after {
      transform: translateX(-50%) scaleX(1);
    }

    .nav-link:hover,
    .nav-link.active {
      color: #0F4C75;
    }

    .btn-header {
      padding: 12px 28px;
      background: linear-gradient(135deg, #0F4C75, #3282B8);
      color: #ffffff !important;
      font-weight: 600;
      font-size: 14px;
      border-radius: 50px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(15, 76, 117, 0.3);
    }

    .btn-header:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(15, 76, 117, 0.4);
    }

    .btn-header::after {
      display: none;
    }

    .mobile-toggle {
      display: none;
      flex-direction: column;
      gap: 5px;
      cursor: pointer;
      padding: 8px;
    }

    .mobile-toggle span {
      width: 28px;
      height: 2px;
      background: #0F4C75;
      border-radius: 2px;
      transition: all 0.3s ease;
    }

    /* Mobile Menu */
    .mobile-menu-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.5);
      z-index: 1001;
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .mobile-menu-overlay.active {
      display: block;
      opacity: 1;
    }

    .mobile-menu-panel {
      position: fixed;
      top: 0;
      right: -320px;
      width: 320px;
      height: 100vh;
      background: #ffffff;
      z-index: 1002;
      transition: right 0.3s ease;
      padding: 80px 24px 24px;
      overflow-y: auto;
    }

    .mobile-menu-panel.active {
      right: 0;
    }

    .close-btn {
      position: absolute;
      top: 20px;
      right: 20px;
      width: 40px;
      height: 40px;
      border: none;
      background: #F8FAFC;
      border-radius: 50%;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      color: #334155;
      transition: all 0.3s ease;
    }

    .close-btn:hover {
      background: #E2E8F0;
    }

    .mobile-nav {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .mobile-nav li a {
      display: block;
      padding: 16px 20px;
      font-size: 16px;
      font-weight: 500;
      color: #334155;
      border-radius: 12px;
      transition: all 0.3s ease;
    }

    .mobile-nav li a:hover,
    .mobile-nav li a.active {
      background: #F8FAFC;
      color: #0F4C75;
    }

    /* Page Hero */
    .page-hero {
      position: relative;
      min-height: 50vh;
      display: flex;
      align-items: center;
      padding-top: 80px;
    }

    .page-hero .hero-bg {
      position: absolute;
      inset: 0;
      z-index: -1;
    }

    .page-hero .hero-bg img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .page-hero .hero-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, rgba(15, 76, 117, 0.92) 0%, rgba(50, 130, 184, 0.85) 100%);
    }

    .page-hero .hero-content {
      position: relative;
      max-width: 1200px;
      margin: 0 auto;
      padding: 60px 24px;
      text-align: center;
      color: #ffffff;
    }

    .page-hero .hero-subtitle {
      color: #E8C97A;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 3px;
      font-size: 14px;
      margin-bottom: 16px;
    }

    .page-hero h1 {
      font-size: clamp(36px, 5vw, 56px);
      font-weight: 800;
      margin-bottom: 20px;
      line-height: 1.1;
    }

    .page-hero p {
      font-size: 18px;
      opacity: 0.9;
      max-width: 600px;
      margin: 0 auto;
    }

    .page-hero .breadcrumb {
      display: flex;
      justify-content: center;
      gap: 8px;
      margin-top: 24px;
      font-size: 14px;
    }

    .page-hero .breadcrumb a {
      color: #E8C97A;
    }

    .page-hero .breadcrumb span {
      color: rgba(255, 255, 255, 0.7);
    }

    /* Modern Section */
    .modern-section {
      padding: 100px 0;
    }

    .modern-section.bg-light {
      background: #F8FAFC;
    }

    .modern-section.bg-dark {
      background: #0A3655;
      color: #ffffff;
    }

    .modern-section.bg-dark h2,
    .modern-section.bg-dark p {
      color: #ffffff;
    }

    .section-header {
      text-align: center;
      margin-bottom: 60px;
      max-width: 800px;
      margin-left: auto;
      margin-right: auto;
    }

    .section-tag {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 8px 20px;
      background: linear-gradient(135deg, rgba(15, 76, 117, 0.1), rgba(50, 130, 184, 0.1));
      color: #0F4C75;
      font-size: 13px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
      border-radius: 50px;
      margin-bottom: 20px;
    }

    .section-header h2 {
      font-size: clamp(28px, 4vw, 42px);
      font-weight: 800;
      color: #0A3655;
      margin-bottom: 16px;
      line-height: 1.2;
    }

    .section-header p {
      font-size: 17px;
      color: #64748B;
      line-height: 1.7;
    }

    /* Service Detail Card */
    .service-detail-card {
      background: #ffffff;
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
      transition: all 0.3s ease;
      height: 100%;
      border: 1px solid rgba(0, 0, 0, 0.04);
    }

    .service-detail-card:hover {
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
      transform: translateY(-5px);
    }

    .service-detail-card .card-icon {
      width: 70px;
      height: 70px;
      background: linear-gradient(135deg, #0F4C75, #3282B8);
      border-radius: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 28px;
      color: #ffffff;
      margin-bottom: 24px;
    }

    .service-detail-card:nth-child(even) .card-icon {
      background: linear-gradient(135deg, #D4A853, #E8C97A);
    }

    .service-detail-card:nth-child(even) .card-icon i {
      color: #0A3655;
    }

    .service-detail-card h3 {
      font-size: 22px;
      font-weight: 700;
      color: #0A3655;
      margin-bottom: 16px;
    }

    .service-detail-card p {
      color: #64748B;
      line-height: 1.8;
      margin-bottom: 24px;
    }

    .service-detail-card .features-list {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .service-detail-card .features-list li {
      display: flex;
      align-items: center;
      gap: 12px;
      color: #64748B;
      font-size: 15px;
      padding: 8px 0;
    }

    .service-detail-card .features-list li i {
      color: #D4A853;
      font-size: 14px;
    }

    /* Services Grid */
    .services-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 32px;
    }

    /* Process Section */
    .process-section {
      padding: 100px 0;
      background: #F8FAFC;
    }

    .process-steps {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 32px;
    }

    .process-step {
      text-align: center;
      padding: 32px 20px;
    }

    .process-step .step-number {
      width: 60px;
      height: 60px;
      background: linear-gradient(135deg, #0F4C75, #3282B8);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
      font-weight: 800;
      color: #ffffff;
      margin: 0 auto 20px;
      box-shadow: 0 8px 25px rgba(15, 76, 117, 0.3);
    }

    .process-step h4 {
      font-size: 18px;
      font-weight: 700;
      color: #0A3655;
      margin-bottom: 12px;
    }

    .process-step p {
      color: #64748B;
      line-height: 1.6;
      font-size: 14px;
    }

    /* Video Gallery */
    .video-gallery-section {
      padding: 80px 0 100px;
    }

    .video-gallery {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 20px;
    }

    .video-card {
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    }

    .video-card video {
      width: 100%;
      height: 200px;
      object-fit: cover;
      display: block;
    }

    /* FAQ Section */
    .faq-section {
      padding: 100px 0;
      background: #F8FAFC;
    }

    .faq-container {
      max-width: 900px;
      margin: 0 auto;
      background: #ffffff;
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    }

    .faq-item {
      padding: 20px 0;
      border-bottom: 1px solid #E2E8F0;
    }

    .faq-item:last-child {
      border-bottom: none;
    }

    .faq-item summary {
      font-size: 17px;
      font-weight: 600;
      color: #0A3655;
      list-style: none;
      display: flex;
      justify-content: space-between;
      align-items: center;
      cursor: pointer;
      padding-right: 30px;
      position: relative;
    }

    .faq-item summary::-webkit-details-marker {
      display: none;
    }

    .faq-item summary::after {
      content: '\f078';
      font-family: 'Font Awesome 6 Free';
      font-weight: 900;
      position: absolute;
      right: 0;
      color: #D4A853;
      transition: transform 0.3s ease;
    }

    .faq-item details[open] summary::after {
      transform: rotate(180deg);
    }

    .faq-item p {
      margin-top: 16px;
      color: #64748B;
      line-height: 1.7;
      padding-left: 20px;
      border-left: 3px solid #D4A853;
    }

    /* CTA Section */
    .cta-section {
      padding: 100px 24px;
      background: linear-gradient(135deg, #0F4C75 0%, #3282B8 50%, #0A3655 100%);
      text-align: center;
      color: #ffffff;
    }

    .cta-section h2 {
      font-size: clamp(28px, 4vw, 42px);
      font-weight: 800;
      margin-bottom: 16px;
    }

    .cta-section p {
      font-size: 18px;
      opacity: 0.9;
      max-width: 700px;
      margin: 0 auto 40px;
    }

    .cta-buttons {
      display: flex;
      gap: 16px;
      justify-content: center;
      flex-wrap: wrap;
    }

    .cta-btn-primary {
      padding: 18px 40px;
      background: #ffffff;
      color: #0F4C75 !important;
      font-weight: 700;
      font-size: 16px;
      border-radius: 50px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
      transition: all 0.3s ease;
    }

    .cta-btn-primary:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 35px rgba(0, 0, 0, 0.3);
    }

    .cta-btn-secondary {
      padding: 18px 40px;
      background: transparent;
      color: #ffffff !important;
      font-weight: 700;
      font-size: 16px;
      border: 2px solid #ffffff;
      border-radius: 50px;
      transition: all 0.3s ease;
    }

    .cta-btn-secondary:hover {
      background: #ffffff;
      color: #0F4C75 !important;
    }

    /* Modern Footer */
    .modern-footer {
      background: #0A3655;
      color: #ffffff;
    }

    .footer-top {
      max-width: 1400px;
      margin: 0 auto;
      padding: 80px 24px 40px;
      display: grid;
      grid-template-columns: 1.5fr 1fr 1fr 1.2fr;
      gap: 40px;
    }

    .footer-brand .logo {
      display: inline-flex;
      margin-bottom: 20px;
    }

    .footer-brand .logo img {
      height: 50px;
    }

    .footer-brand p {
      color: rgba(255, 255, 255, 0.75);
      line-height: 1.8;
      margin-bottom: 24px;
      font-size: 15px;
    }

    .social-links {
      display: flex;
      gap: 12px;
    }

    .social-links a {
      width: 40px;
      height: 40px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      transition: all 0.3s ease;
    }

    .social-links a:hover {
      background: #D4A853;
      transform: translateY(-3px);
    }

    .footer-column h4 {
      font-size: 18px;
      font-weight: 700;
      margin-bottom: 24px;
      color: #ffffff;
    }

    .footer-links {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .footer-links li a {
      display: flex;
      align-items: center;
      gap: 10px;
      color: rgba(255, 255, 255, 0.7);
      font-size: 15px;
      transition: all 0.3s ease;
    }

    .footer-links li a:hover {
      color: #E8C97A;
      padding-left: 5px;
    }

    .footer-links li a i {
      font-size: 12px;
      color: #D4A853;
    }

    .footer-contact-item {
      display: flex;
      gap: 16px;
      margin-bottom: 16px;
    }

    .footer-contact-item i {
      width: 40px;
      height: 40px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      color: #E8C97A;
      flex-shrink: 0;
    }

    .footer-contact-item p {
      color: rgba(255, 255, 255, 0.75);
      font-size: 15px;
      line-height: 1.6;
    }

    .footer-contact-item p a {
      color: rgba(255, 255, 255, 0.75);
    }

    .footer-contact-item p a:hover {
      color: #E8C97A;
    }

    .footer-bottom {
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      padding: 24px;
      text-align: center;
    }

    .footer-bottom p {
      color: rgba(255, 255, 255, 0.6);
      font-size: 14px;
    }

    .footer-bottom p a {
      color: #E8C97A;
    }

    /* Scroll To Top */
    .scroll-top {
      position: fixed;
      bottom: 30px;
      right: 30px;
      width: 50px;
      height: 50px;
      background: linear-gradient(135deg, #0F4C75, #3282B8);
      border: none;
      border-radius: 50%;
      color: #ffffff;
      font-size: 18px;
      cursor: pointer;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
      z-index: 999;
      box-shadow: 0 5px 20px rgba(15, 76, 117, 0.4);
    }

    .scroll-top.visible {
      opacity: 1;
      visibility: visible;
    }

    .scroll-top:hover {
      transform: translateY(-5px);
    }

    /* Responsive Styles */
    @media (max-width: 1024px) {
      .services-grid {
        grid-template-columns: 1fr;
      }

      .process-steps {
        grid-template-columns: repeat(2, 1fr);
      }

      .video-gallery {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 768px) {
      .nav-menu {
        display: none;
      }

      .mobile-toggle {
        display: flex;
      }

      .modern-header .logo img {
        height: 40px;
      }

      .header-container {
        height: 70px;
      }

      .page-hero {
        min-height: 40vh;
        padding-top: 70px;
      }

      .page-hero h1 {
        font-size: 32px;
      }

      .modern-section {
        padding: 60px 0;
      }

      .section-header {
        margin-bottom: 40px;
      }

      .footer-top {
        grid-template-columns: 1fr;
        gap: 32px;
        padding: 50px 24px 30px;
      }

      .process-steps {
        grid-template-columns: 1fr;
        gap: 24px;
      }

      .video-gallery {
        grid-template-columns: 1fr;
      }

      .service-detail-card {
        padding: 32px 24px;
      }

      .faq-container {
        padding: 24px;
      }

      .cta-section {
        padding: 60px 24px;
      }

      .cta-buttons {
        flex-direction: column;
        align-items: stretch;
      }

      .cta-btn-primary,
      .cta-btn-secondary {
        text-align: center;
      }

      .scroll-top {
        bottom: 20px;
        right: 20px;
        width: 45px;
        height: 45px;
      }
    }
  </style>
</head>

<body>
  <!-- Modern Header -->
  <header class="modern-header" id="mainHeader">
    <div class="header-container">
      <a href="./" class="logo">
        <img src="images/logo-3.png" alt="ApplyBoard Africa Ltd" />
      </a>

      <nav class="nav-menu">
        <a href="./" class="nav-link">Home</a>
        <a href="about.php" class="nav-link">About</a>
        <a href="services.php" class="nav-link active">Services</a>
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

  <!-- Mobile Menu Panel -->
  <div class="mobile-menu-overlay" id="mobileOverlay" onclick="toggleMobileMenu()"></div>
  <div class="mobile-menu-panel" id="mobileMenu">
    <button class="close-btn" onclick="toggleMobileMenu()">
      <i class="fas fa-times"></i>
    </button>
    <ul class="mobile-nav">
      <li><a href="./">Home</a></li>
      <li><a href="about.php">About</a></li>
      <li><a href="services.php" class="active">Services</a></li>
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
    <div class="hero-bg">
      <img src="images/main-slider/2.jpg" alt="" />
    </div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
      <p class="hero-subtitle">What We Offer</p>
      <h1>Our Services</h1>
      <p>Comprehensive immigration and travel services tailored to your unique needs.</p>
      <div class="breadcrumb">
        <a href="./">Home</a>
        <span>/</span>
        <span>Services</span>
      </div>
    </div>
  </section>

  <!-- Services Section -->
  <section class="modern-section">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 24px;">
      <div class="section-header">
        <span class="section-tag">
          <i class="fas fa-star"></i> Our Services
        </span>
        <h2>Our Services</h2>
        <p>Comprehensive education and travel solutions tailored to your unique needs.</p>
      </div>

      <div class="services-grid">
        <!-- Service 1: Study Abroad Consulting -->
        <div class="service-detail-card">
          <div class="card-icon">
            <i class="fas fa-graduation-cap"></i>
          </div>
          <h3>Study Abroad Consulting</h3>
          <p>Pursue your education abroad with our expert guidance. We help you navigate university selection, admission requirements, and application processes across top destinations.</p>
          <ul class="features-list">
            <li><i class="fas fa-check-circle"></i> University Selection Guidance</li>
            <li><i class="fas fa-check-circle"></i> Application Support</li>
            <li><i class="fas fa-check-circle"></i> Scholarship Assistance</li>
            <li><i class="fas fa-check-circle"></i> Document Preparation</li>
          </ul>
        </div>

        <!-- Service 2: Admissions Support & Partner Processing -->
        <div class="service-detail-card">
          <div class="card-icon">
            <i class="fas fa-university"></i>
          </div>
          <h3>Admissions Support & Partner Processing</h3>
          <p>Direct partnerships with institutions worldwide streamline your admission process. We handle everything from application to offer letter.</p>
          <ul class="features-list">
            <li><i class="fas fa-check-circle"></i> Partner University Network</li>
            <li><i class="fas fa-check-circle"></i> Fast-Track Admissions</li>
            <li><i class="fas fa-check-circle"></i> Offer Letter Processing</li>
            <li><i class="fas fa-check-circle"></i> Acceptance Coordination</li>
          </ul>
        </div>

        <!-- Service 3: Student & Tourist Visa Assistance -->
        <div class="service-detail-card">
          <div class="card-icon">
            <i class="fas fa-passport"></i>
          </div>
          <h3>Student & Tourist Visa Assistance</h3>
          <p>Comprehensive visa support for students and travelers. Our team ensures your application meets all requirements for higher approval chances.</p>
          <ul class="features-list">
            <li><i class="fas fa-check-circle"></i> Student Visa Processing</li>
            <li><i class="fas fa-check-circle"></i> Tourist Visa Applications</li>
            <li><i class="fas fa-check-circle"></i> Document Review</li>
            <li><i class="fas fa-check-circle"></i> Interview Preparation</li>
          </ul>
        </div>

        <!-- Service 4: Flights & Hotel Reservations -->
        <div class="service-detail-card">
          <div class="card-icon">
            <i class="fas fa-plane-departure"></i>
          </div>
          <h3>Flights & Hotel Reservations</h3>
          <p>Travel with confidence using our booking services. We secure the best deals on flights and accommodations for your journey.</p>
          <ul class="features-list">
            <li><i class="fas fa-check-circle"></i> International Flight Booking</li>
            <li><i class="fas fa-check-circle"></i> Hotel Reservations</li>
            <li><i class="fas fa-check-circle"></i> Airport Pickup Services</li>
            <li><i class="fas fa-check-circle"></i> Competitive Pricing</li>
          </ul>
        </div>

        <!-- Service 5: Travel Planning & Relocation Guidance -->
        <div class="service-detail-card">
          <div class="card-icon">
            <i class="fas fa-route"></i>
          </div>
          <h3>Travel Planning & Relocation Guidance</h3>
          <p>Smooth transition to your new destination. We provide comprehensive travel planning and relocation support for students and travelers.</p>
          <ul class="features-list">
            <li><i class="fas fa-check-circle"></i> Pre-Departure Briefing</li>
            <li><i class="fas fa-check-circle"></i> Accommodation Assistance</li>
            <li><i class="fas fa-check-circle"></i> Airport Reception</li>
            <li><i class="fas fa-check-circle"></i> Settlement Support</li>
          </ul>
        </div>

        <!-- Service 6: Agent Referral System -->
        <div class="service-detail-card">
          <div class="card-icon">
            <i class="fas fa-handshake"></i>
          </div>
          <h3>Agent Referral Program</h3>
          <p>Join our network of verified agents and earn commissions while helping students achieve their dreams. Transparent tracking and reliable payments.</p>
          <ul class="features-list">
            <li><i class="fas fa-check-circle"></i> Verified Agent Network</li>
            <li><i class="fas fa-check-circle"></i> Referral Tracking</li>
            <li><i class="fas fa-check-circle"></i> Commission Payments</li>
            <li><i class="fas fa-check-circle"></i> Performance Dashboard</li>
          </ul>
        </div>

        <!-- Service 7: Digital Application Tracking Platform -->
        <div class="service-detail-card">
          <div class="card-icon">
            <i class="fas fa-laptop-code"></i>
          </div>
          <h3>Digital Application Tracking</h3>
          <p>Track your application progress in real-time through our secure portal. Stay updated at every stage of your journey.</p>
          <ul class="features-list">
            <li><i class="fas fa-check-circle"></i> Real-Time Status Updates</li>
            <li><i class="fas fa-check-circle"></i> Document Upload Center</li>
            <li><i class="fas fa-check-circle"></i> Direct Messaging</li>
            <li><i class="fas fa-check-circle"></i> 24/7 Access</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- Process Section -->
  <section class="process-section">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 24px;">
      <div class="section-header">
        <span class="section-tag">
          <i class="fas fa-cogs"></i> How It Works
        </span>
        <h2>Our Simple Process</h2>
        <p>We've made the visa application process straightforward and stress-free.</p>
      </div>

      <div class="process-steps">
        <div class="process-step">
          <div class="step-number">1</div>
          <h4>Consultation</h4>
          <p>Free initial consultation to understand your needs and recommend the best visa options.</p>
        </div>
        <div class="process-step">
          <div class="step-number">2</div>
          <h4>Documentation</h4>
          <p>Our team guides you through preparing all required documents accurately.</p>
        </div>
        <div class="process-step">
          <div class="step-number">3</div>
          <h4>Application</h4>
          <p>We submit your application and track its progress throughout the process.</p>
        </div>
        <div class="process-step">
          <div class="step-number">4</div>
          <h4>Approval</h4>
          <p>Receive your visa and prepare for your journey with our pre-departure support.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Video Gallery -->
  <section class="video-gallery-section">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 24px;">
      <div class="section-header">
        <span class="section-tag">
          <i class="fas fa-video"></i> Gallery
        </span>
        <h2>Video Gallery</h2>
        <p>Watch our success stories and learn more about our services.</p>
      </div>
    </div>
    <div class="video-gallery">
      <div class="video-card">
        <video controls>
          <source src="images/travel/new/others/01.mp4" type="video/mp4">
          Your browser does not support the video tag.
        </video>
      </div>
      <div class="video-card">
        <video controls>
          <source src="images/travel/new/others/02.mp4" type="video/mp4">
          Your browser does not support the video tag.
        </video>
      </div>
      <div class="video-card">
        <video controls>
          <source src="images/travel/new/others/03.mp4" type="video/mp4">
          Your browser does not support the video tag.
        </video>
      </div>
      <div class="video-card">
        <video controls>
          <source src="images/travel/new/others/04.mp4" type="video/mp4">
          Your browser does not support the video tag.
        </video>
      </div>
    </div>
  </section>

  <!-- FAQ Section -->
  <section class="faq-section">
    <div class="section-header">
      <span class="section-tag">
        <i class="fas fa-question-circle"></i> FAQs
      </span>
      <h2>Frequently Asked Questions</h2>
      <p>Find answers to common questions about our services.</p>
    </div>

    <div class="faq-container">
      <details class="faq-item">
        <summary>What services does ApplyBoard Africa Ltd offer?</summary>
        <p>We provide study abroad consulting, admissions support, student and tourist visa assistance, flight and hotel reservations, travel planning, and digital application tracking.</p>
      </details>
      <details class="faq-item">
        <summary>How do I get started with study abroad consulting?</summary>
        <p>Contact us for a free consultation. Our experts will assess your profile, recommend suitable universities and programs, and guide you through the entire application process.</p>
      </details>
      <details class="faq-item">
        <summary>How long does visa processing take?</summary>
        <p>Visa processing times vary by country. On average: Student Visa: 3-6 weeks, Tourist Visa: 2-4 weeks. We ensure your application is complete for faster processing.</p>
      </details>
      <details class="faq-item">
        <summary>Can I become an agent for ApplyBoard Africa?</summary>
        <p>Yes! We welcome verified agents to join our referral program. Register on our platform, complete verification, and start earning commissions while helping students achieve their dreams.</p>
      </details>
      <details class="faq-item">
        <summary>How does the application tracking platform work?</summary>
        <p>Once you register, you get access to a secure portal where you can track your application status in real-time, upload documents, and communicate directly with our team.</p>
      </details>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="cta-section">
    <h2>Ready to Get Started?</h2>
    <p>Contact us today for a free consultation and let our experts guide you through your visa application process.</p>
    <div class="cta-buttons">
      <a href="contact.php" class="cta-btn-primary">
        Get Free Consultation <i class="fas fa-arrow-right" style="margin-left: 8px;"></i>
      </a>
      <a href="tel:+2349069503394" class="cta-btn-secondary">
        <i class="fas fa-phone-alt" style="margin-right: 8px;"></i> +234 906 9503 394
      </a>
    </div>
  </section>

  <!-- Modern Footer -->
  <footer class="modern-footer">
    <div class="footer-top">
      <div class="footer-brand">
        <a href="./" class="logo">
          <img src="images/logo-2.png" alt="ApplyBoard Africa Ltd" />
        </a>
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
          <li><a href="about.php"><i class="fas fa-chevron-right"></i> About Us</a></li>
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

  <!-- Scroll To Top -->
  <button class="scroll-top" id="scrollTop" onclick="scrollToTop()">
    <i class="fas fa-arrow-up"></i>
  </button>

  <script>
    function toggleMobileMenu() {
      const menu = document.getElementById('mobileMenu');
      const overlay = document.getElementById('mobileOverlay');
      menu.classList.toggle('active');
      overlay.classList.toggle('active');
      document.body.style.overflow = menu.classList.contains('active') ? 'hidden' : '';
    }

    // Header scroll effect
    const header = document.getElementById('mainHeader');
    window.addEventListener('scroll', () => {
      if (window.scrollY > 50) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }
    });

    // Scroll to top button
    const scrollTopBtn = document.getElementById('scrollTop');
    window.addEventListener('scroll', () => {
      if (window.scrollY > 300) {
        scrollTopBtn.classList.add('visible');
      } else {
        scrollTopBtn.classList.remove('visible');
      }
    });

    function scrollToTop() {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
  </script>
</body>
</html>
