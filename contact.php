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
  <title>Contact Us | ApplyBoard Africa Ltd</title>
  <meta name="description" content="Get in touch with ApplyBoard Africa Ltd. Contact us for visa consultation, immigration services, and travel assistance." />

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

    /* Contact Form Wrapper */
    .contact-form-wrapper {
      background: #ffffff;
      border-radius: 20px;
      padding: 48px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    }

    .contact-form-wrapper h3 {
      font-size: 24px;
      font-weight: 700;
      color: #0A3655;
      margin-bottom: 32px;
    }

    .form-group {
      margin-bottom: 24px;
    }

    .form-group label {
      display: block;
      font-weight: 600;
      color: #0A3655;
      margin-bottom: 8px;
      font-size: 14px;
    }

    .form-control {
      width: 100%;
      padding: 16px 20px;
      border: 2px solid #E2E8F0;
      border-radius: 12px;
      font-size: 16px;
      font-family: inherit;
      transition: all 0.3s ease;
      background: #F8FAFC;
      color: #334155;
    }

    .form-control:focus {
      outline: none;
      border-color: #0F4C75;
      background: #ffffff;
      box-shadow: 0 0 0 4px rgba(15, 76, 117, 0.1);
    }

    .form-control::placeholder {
      color: #94A3B8;
    }

    textarea.form-control {
      resize: vertical;
      min-height: 150px;
    }

    .btn-submit {
      padding: 18px 40px;
      background: linear-gradient(135deg, #0F4C75, #3282B8);
      color: #ffffff;
      border: none;
      border-radius: 12px;
      font-weight: 700;
      font-size: 16px;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(15, 76, 117, 0.3);
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(15, 76, 117, 0.4);
    }

    .btn-reset {
      padding: 18px 40px;
      background: transparent;
      color: #64748B;
      border: 2px solid #E2E8F0;
      border-radius: 12px;
      font-weight: 600;
      font-size: 16px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .btn-reset:hover {
      border-color: #0F4C75;
      color: #0F4C75;
    }

    /* Contact Info Card */
    .contact-info-card {
      background: #ffffff;
      border-radius: 20px;
      padding: 32px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
      transition: all 0.3s ease;
      height: 100%;
    }

    .contact-info-card:hover {
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
      transform: translateY(-5px);
    }

    .contact-info-card .icon {
      width: 60px;
      height: 60px;
      background: linear-gradient(135deg, #0F4C75, #3282B8);
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      color: #ffffff;
      margin-bottom: 20px;
    }

    .contact-info-card:nth-child(2) .icon {
      background: linear-gradient(135deg, #D4A853, #E8C97A);
    }

    .contact-info-card:nth-child(2) .icon i {
      color: #0A3655;
    }

    .contact-info-card h4 {
      font-size: 18px;
      font-weight: 700;
      color: #0A3655;
      margin-bottom: 16px;
    }

    .contact-info-card p {
      color: #64748B;
      line-height: 1.6;
      margin: 0;
    }

    .contact-info-card a {
      color: #0F4C75;
      transition: color 0.3s ease;
    }

    .contact-info-card a:hover {
      color: #D4A853;
    }

    /* Location Card */
    .location-card {
      background: #ffffff;
      border-radius: 20px;
      padding: 32px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
      transition: all 0.3s ease;
    }

    .location-card:hover {
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
      transform: translateY(-5px);
    }

    .location-header {
      display: flex;
      align-items: center;
      gap: 16px;
      margin-bottom: 20px;
    }

    .location-header .icon {
      width: 50px;
      height: 50px;
      background: linear-gradient(135deg, #0F4C75, #3282B8);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      color: #ffffff;
    }

    .location-header .icon.gold {
      background: linear-gradient(135deg, #D4A853, #E8C97A);
    }

    .location-header .icon.gold i {
      color: #0A3655;
    }

    .location-info h4 {
      font-size: 18px;
      font-weight: 700;
      color: #0A3655;
      margin: 0;
    }

    .location-info p {
      color: #D4A853;
      font-size: 14px;
      margin: 4px 0 0;
    }

    .location-card p {
      color: #64748B;
      line-height: 1.7;
      margin-bottom: 16px;
    }

    .location-card .direction-link {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      color: #0F4C75;
      font-weight: 600;
    }

    .location-card .direction-link:hover {
      color: #D4A853;
    }

    /* Map Wrapper */
    .map-wrapper {
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    }

    .map-wrapper iframe {
      width: 100%;
      height: 400px;
      border: none;
      display: block;
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

    .social-icons {
      display: flex;
      gap: 16px;
      justify-content: center;
      flex-wrap: wrap;
    }

    .social-icons a {
      width: 54px;
      height: 54px;
      background: #ffffff;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .social-icons a:hover {
      transform: translateY(-5px);
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

    /* Alert Messages */
    .alert-success {
      background: #D1FAE5;
      color: #065F46;
      padding: 16px 20px;
      border-radius: 12px;
      margin-bottom: 24px;
      border: 1px solid #A7F3D0;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .alert-error {
      background: #FEE2E2;
      color: #991B1B;
      padding: 16px 20px;
      border-radius: 12px;
      margin-bottom: 24px;
      border: 1px solid #FECACA;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    /* Contact Grid */
    .contact-grid {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 48px;
      align-items: start;
    }

    .contact-info-sidebar {
      display: flex;
      flex-direction: column;
      gap: 24px;
    }

    .locations-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 32px;
    }

    /* Responsive Styles */
    @media (max-width: 1024px) {
      .contact-grid {
        grid-template-columns: 1fr;
      }

      .locations-grid {
        grid-template-columns: 1fr;
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

      .contact-form-wrapper {
        padding: 32px 24px;
      }

      .contact-info-card {
        padding: 24px;
      }

      .location-card {
        padding: 24px;
      }

      .map-wrapper iframe {
        height: 300px;
      }

      .cta-section {
        padding: 60px 24px;
      }

      .social-icons {
        gap: 12px;
      }

      .social-icons a {
        width: 48px;
        height: 48px;
        font-size: 20px;
      }

      .scroll-top {
        bottom: 20px;
        right: 20px;
        width: 45px;
        height: 45px;
      }

      .contact-form-wrapper .form-row {
        grid-template-columns: 1fr;
      }
    }

    .form-row {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 20px;
    }

    .button-row {
      display: flex;
      gap: 16px;
      flex-wrap: wrap;
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
        <a href="services.php" class="nav-link">Services</a>
        <a href="platform.php" class="nav-link">Platform</a>
        <a href="agents.php" class="nav-link">Agents</a>
        <a href="contact.php" class="nav-link active">Contact</a>
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
      <li><a href="services.php">Services</a></li>
      <li><a href="platform.php">Platform</a></li>
      <li><a href="agents.php">Agents</a></li>
      <li><a href="contact.php" class="active">Contact</a></li>
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
      <p class="hero-subtitle">Get In Touch</p>
      <h1>Contact Us</h1>
      <p>Have questions or need assistance? Reach out to us and let's start your journey together.</p>
      <div class="breadcrumb">
        <a href="./">Home</a>
        <span>/</span>
        <span>Contact</span>
      </div>
    </div>
  </section>

  <!-- Contact Section -->
  <section class="modern-section">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 24px;">
      <div class="section-header">
        <span class="section-tag">
          <i class="fas fa-paper-plane"></i> Contact
        </span>
        <h2>Get In Touch With Us</h2>
        <p>We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
      </div>

      <div class="contact-grid">
        <!-- Contact Form -->
        <div class="contact-form-wrapper">
          <h3>Send Us a Message</h3>

          <?php
          if (isset($_POST['submit_inquiry'])) {
              $name = mysqli_real_escape_string($conn, $_POST['form_name']);
              $email = mysqli_real_escape_string($conn, $_POST['form_email']);
              $phone = mysqli_real_escape_string($conn, $_POST['form_phone']);
              $subject = mysqli_real_escape_string($conn, $_POST['form_subject']);
              $message = mysqli_real_escape_string($conn, $_POST['form_message']);

              // Resolve Agent
              $agent_id = 'NULL';
              $ref_code = isset($_GET['ref']) ? $_GET['ref'] : (isset($_COOKIE['sdtravels_ref']) ? $_COOKIE['sdtravels_ref'] : '');

              if ($ref_code) {
                  $ref_code = mysqli_real_escape_string($conn, $ref_code);
                  $agent_check = mysqli_query($conn, "SELECT `id` FROM `agents` WHERE `agent_code` = '$ref_code'");
                  if ($agent_check && mysqli_num_rows($agent_check) > 0) {
                      $agent_id = mysqli_fetch_assoc($agent_check)['id'];
                  }
              }

              $full_message = "Subject: $subject\n\n$message";

              $sql = "INSERT INTO `inquiries` (`name`, `email`, `phone`, `message`, `agent_id`) VALUES ('$name', '$email', '$phone', '$full_message', $agent_id)";

              if (mysqli_query($conn, $sql)) {
                  echo "<div class='alert-success'>
                          <i class='fas fa-check-circle'></i>
                          <span>Thank you! Your message has been sent successfully. We'll get back to you soon.</span>
                        </div>";
              } else {
                  echo "<div class='alert-error'>
                          <i class='fas fa-exclamation-circle'></i>
                          <span>Error sending message. Please try again.</span>
                        </div>";
              }
          }
          ?>

          <form method="post" action="">
            <div class="form-row">
              <div class="form-group">
                <label>Full Name *</label>
                <input type="text" name="form_name" class="form-control" placeholder="Enter your full name" required />
              </div>
              <div class="form-group">
                <label>Email Address *</label>
                <input type="email" name="form_email" class="form-control" placeholder="Enter your email" required />
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Phone Number</label>
                <input type="tel" name="form_phone" class="form-control" placeholder="Enter your phone number" />
              </div>
              <div class="form-group">
                <label>Subject *</label>
                <input type="text" name="form_subject" class="form-control" placeholder="Enter subject" required />
              </div>
            </div>
            <div class="form-group">
              <label>Message *</label>
              <textarea name="form_message" class="form-control" rows="6" placeholder="How can we help you?" required></textarea>
            </div>
            <div class="button-row">
              <button type="submit" name="submit_inquiry" class="btn-submit">
                <i class="fas fa-paper-plane"></i> Send Message
              </button>
              <button type="reset" class="btn-reset">
                Reset Form
              </button>
            </div>
          </form>
        </div>

        <!-- Contact Info -->
        <div class="contact-info-sidebar">
          <div class="contact-info-card">
            <div class="icon">
              <i class="fas fa-phone-alt"></i>
            </div>
            <h4>Phone Numbers</h4>
            <p style="margin-bottom: 8px;"><strong>Secretary:</strong><br /><a href="tel:+2349069503394">+234 906 9503 394</a></p>
            <p style="margin-bottom: 8px;"><strong>Manager:</strong><br /><a href="tel:+2349023297280">+234 902 3297 280</a></p>
            <p><strong>MD:</strong><br /><a href="tel:+2348145450396">+234 814 5450 396</a></p>
          </div>

          <div class="contact-info-card">
            <div class="icon">
              <i class="fas fa-envelope"></i>
            </div>
            <h4>Email Address</h4>
            <p><a href="mailto:info@smiledovetravels.com">info@smiledovetravels.com</a></p>
            <p><a href="mailto:info@applyboardafrica.com">info@applyboardafrica.com</a></p>
          </div>

          <div class="contact-info-card">
            <div class="icon">
              <i class="fas fa-clock"></i>
            </div>
            <h4>Working Hours</h4>
            <p><strong>Monday - Saturday:</strong><br />8:00 AM - 6:30 PM</p>
            <p><strong>Sunday:</strong><br />Closed</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Locations Section -->
  <section class="modern-section bg-light">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 24px;">
      <div class="section-header">
        <span class="section-tag">
          <i class="fas fa-map-marker-alt"></i> Locations
        </span>
        <h2>Our Offices</h2>
        <p>Visit us at any of our office locations for in-person consultation.</p>
      </div>

      <div class="locations-grid">
        <div class="location-card">
          <div class="location-header">
            <div class="icon">
              <i class="fas fa-building"></i>
            </div>
            <div class="location-info">
              <h4>Ibadan Office</h4>
              <p>Head Office</p>
            </div>
          </div>
          <p>
            4 Animashaun Street, OPP Teju Hospital, Ajegbe Ring Road, Ibadan, Oyo State, Nigeria.
          </p>
          <a href="https://maps.google.com" target="_blank" class="direction-link">
            <i class="fas fa-directions"></i> Get Directions
          </a>
        </div>

        <div class="location-card">
          <div class="location-header">
            <div class="icon gold">
              <i class="fas fa-building"></i>
            </div>
            <div class="location-info">
              <h4>Akure Office</h4>
              <p>Branch Office</p>
            </div>
          </div>
          <p>
            Properties Plaza, Beside Bank of Industry, First Bank Junction, Alagbaka, Akure, Ondo State, Nigeria.
          </p>
          <a href="https://maps.google.com" target="_blank" class="direction-link">
            <i class="fas fa-directions"></i> Get Directions
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Map Section -->
  <section class="modern-section">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 24px;">
      <div class="section-header">
        <span class="section-tag">
          <i class="fas fa-map"></i> Find Us
        </span>
        <h2>Location Map</h2>
        <p>Locate our head office on the map below.</p>
      </div>
    </div>
    <div style="max-width: 1400px; margin: 0 auto; padding: 0 24px;">
      <div class="map-wrapper">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.0228649087003!2d3.872499073870538!3d7.35132861299248!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x10398d001d271eb3%3A0x81ed092bffc4ca30!2sSmile%20Dove%20Nigeria%20Limited!5e0!3m2!1sen!2sng!4v1744215177663!5m2!1sen!2sng" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
    </div>
  </section>

  <!-- Social Connect -->
  <section class="cta-section">
    <h2>Connect With Us</h2>
    <p>Follow us on social media for updates, tips, and immigration news.</p>
    <div class="social-icons">
      <a href="https://facebook.com" target="_blank" style="color: #1877F2;">
        <i class="fab fa-facebook-f"></i>
      </a>
      <a href="https://twitter.com" target="_blank" style="color: #1DA1F2;">
        <i class="fab fa-twitter"></i>
      </a>
      <a href="https://instagram.com" target="_blank" style="color: #E4405F;">
        <i class="fab fa-instagram"></i>
      </a>
      <a href="https://wa.me/2349069503394" target="_blank" style="color: #25D366;">
        <i class="fab fa-whatsapp"></i>
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

  <!-- WhatsApp Widget -->
  <script>
    var glassixWidgetOptions = {
      "numbers": [
        {
          "number": "09069503394",
          "name": "Customer Support",
          "subtitle": "Contact us 24/7"
        }
      ],
      "left": false,
      "ltr": true,
      "popupText": "Need help?\nChat with us on WhatsApp",
      "title": "Hi There!",
      "subTitle": "Click to start a conversation"
    };
    !function (t) {
      var e = function () {
        window.requirejs && !window.whatsAppWidgetClient && (requirejs.config({ paths: { GlassixWhatsAppWidgetClient: "https://cdn.glassix.com/clients/whatsapp.widget.1.2.min.js" } }),
          require(["GlassixWhatsAppWidgetClient"], function (t) { window.whatsAppWidgetClient = new t(window.glassixWidgetOptions), whatsAppWidgetClient.attach() })),
          window.GlassixWhatsAppWidgetClient && "function" == typeof window.GlassixWhatsAppWidgetClient ? (window.whatsAppWidgetClient = new GlassixWhatsAppWidgetClient(t), whatsAppWidgetClient.attach()) : i()
      }, i = function () {
        a.onload = e, a.src = "https://cdn.glassix.net/clients/whatsapp.widget.1.2.min.js", s && s.parentElement && s.parentElement.removeChild(s), n.parentNode.insertBefore(a, n)
      }, n = document.getElementsByTagName("script")[0], s = document.createElement("script"); s.async = !0, s.type = "text/javascript", s.crossorigin = "anonymous", s.id = "glassix-whatsapp-widget-script"; var a = s.cloneNode();
      s.onload = e, s.src = "https://cdn.glassix.com/clients/whatsapp.widget.1.2.min.js", !document.getElementById(s.id) && document.body && (n.parentNode.insertBefore(s, n), s.onerror = i)
    }(glassixWidgetOptions);
  </script>

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
