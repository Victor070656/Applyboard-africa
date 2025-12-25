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
  <title>ApplyBoard Africa Ltd | Your Gateway to Global Opportunities</title>
  <meta name="description" content="ApplyBoard Africa Ltd - Expert visa processing, study abroad consultation, IELTS coaching, pilgrimage travel, and immigration services." />

  <!-- Favicon -->
  <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon" />

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

  <style>
    /* ============================================
       RESET & BASE STYLES
       ============================================ */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html {
      scroll-behavior: smooth;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      color: #1E293B;
      background: #FFFFFF;
      line-height: 1.6;
      overflow-x: hidden;
    }

    img {
      max-width: 100%;
      height: auto;
    }

    a {
      text-decoration: none;
      color: inherit;
    }

    ul {
      list-style: none;
    }

    /* ============================================
       HEADER STYLES
       ============================================ */
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

    .modern-header .header-container {
      max-width: 1400px;
      margin: 0 auto;
      padding: 16px 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .modern-header .logo img {
      height: 45px;
      object-fit: contain;
    }

    .modern-header .nav-menu {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .modern-header .nav-link {
      padding: 10px 18px;
      color: #1E293B;
      font-weight: 500;
      font-size: 15px;
      text-decoration: none;
      border-radius: 8px;
      transition: all 0.3s ease;
      position: relative;
    }

    .modern-header .nav-link:hover,
    .modern-header .nav-link.active {
      color: #0F4C75;
    }

    .modern-header .nav-link::after {
      content: '';
      position: absolute;
      bottom: 4px;
      left: 50%;
      width: 0;
      height: 2px;
      background: linear-gradient(90deg, #0F4C75, #3282B8);
      transition: all 0.3s ease;
      transform: translateX(-50%);
    }

    .modern-header .nav-link:hover::after,
    .modern-header .nav-link.active::after {
      width: 30px;
    }

    .modern-header .btn-header {
      padding: 12px 28px;
      background: linear-gradient(135deg, #0F4C75, #3282B8);
      color: #FFFFFF !important;
      border: none;
      border-radius: 50px;
      font-weight: 600;
      font-size: 14px;
      text-decoration: none !important;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(15, 76, 117, 0.3);
    }

    .modern-header .btn-header:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(15, 76, 117, 0.4);
    }

    .modern-header .mobile-toggle {
      display: none;
      flex-direction: column;
      gap: 5px;
      padding: 10px;
      cursor: pointer;
    }

    .modern-header .mobile-toggle span {
      width: 25px;
      height: 2px;
      background: #1E293B;
      border-radius: 2px;
      transition: all 0.3s ease;
    }

    /* ============================================
       MOBILE MENU
       ============================================ */
    .mobile-menu-panel {
      position: fixed;
      top: 0;
      right: -100%;
      width: 100%;
      max-width: 400px;
      height: 100vh;
      background: #FFFFFF;
      z-index: 1001;
      padding: 80px 32px 32px;
      transition: right 0.3s ease;
      overflow-y: auto;
    }

    .mobile-menu-panel.active {
      right: 0;
    }

    .mobile-menu-panel .close-btn {
      position: absolute;
      top: 24px;
      right: 24px;
      width: 44px;
      height: 44px;
      background: #F8FAFC;
      border: none;
      border-radius: 50%;
      font-size: 20px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .mobile-menu-panel .mobile-nav {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .mobile-menu-panel .mobile-nav a {
      display: block;
      padding: 16px 20px;
      color: #0F172A;
      font-size: 18px;
      font-weight: 500;
      text-decoration: none;
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    .mobile-menu-panel .mobile-nav a:hover,
    .mobile-menu-panel .mobile-nav a.active {
      background: #F8FAFC;
      color: #0F4C75;
    }

    .mobile-menu-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.5);
      z-index: 1000;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
    }

    .mobile-menu-overlay.active {
      opacity: 1;
      visibility: visible;
    }

    /* ============================================
       HERO SECTION
       ============================================ */
    .modern-hero {
      position: relative;
      min-height: 100vh;
      display: flex;
      align-items: center;
      overflow: hidden;
      padding-top: 80px;
    }

    .modern-hero .hero-bg {
      position: absolute;
      inset: 0;
      z-index: -1;
    }

    .modern-hero .hero-bg img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .modern-hero .hero-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg,
        rgba(15, 76, 117, 0.9) 0%,
        rgba(50, 130, 184, 0.7) 50%,
        rgba(15, 76, 117, 0.85) 100%
      );
    }

    .modern-hero .hero-content {
      position: relative;
      max-width: 1400px;
      margin: 0 auto;
      padding: 60px 24px;
      color: #FFFFFF;
    }

    .modern-hero .hero-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 8px 20px;
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(10px);
      border-radius: 50px;
      font-size: 14px;
      font-weight: 500;
      margin-bottom: 24px;
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .modern-hero .hero-badge .dot {
      width: 8px;
      height: 8px;
      background: #22C55E;
      border-radius: 50%;
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0%, 100% { opacity: 1; transform: scale(1); }
      50% { opacity: 0.7; transform: scale(1.1); }
    }

    .modern-hero h1 {
      font-size: clamp(42px, 6vw, 72px);
      font-weight: 800;
      line-height: 1.1;
      margin-bottom: 24px;
      letter-spacing: -0.02em;
    }

    .modern-hero .hero-subtitle {
      font-size: clamp(18px, 2.5vw, 22px);
      opacity: 0.95;
      margin-bottom: 40px;
      max-width: 600px;
      line-height: 1.6;
    }

    .modern-hero .hero-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 16px;
    }

    .modern-hero .btn-primary {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      padding: 18px 40px;
      background: #D4A853;
      color: #0F172A;
      font-weight: 700;
      font-size: 16px;
      border-radius: 50px;
      text-decoration: none;
      transition: all 0.3s ease;
      box-shadow: 0 8px 30px rgba(212, 168, 83, 0.4);
    }

    .modern-hero .btn-primary:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 40px rgba(212, 168, 83, 0.5);
    }

    .modern-hero .btn-secondary {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      padding: 18px 40px;
      background: transparent;
      color: #FFFFFF;
      font-weight: 600;
      font-size: 16px;
      border: 2px solid rgba(255, 255, 255, 0.3);
      border-radius: 50px;
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .modern-hero .btn-secondary:hover {
      background: rgba(255, 255, 255, 0.1);
      border-color: #FFFFFF;
    }

    .modern-hero .hero-stats {
      display: flex;
      flex-wrap: wrap;
      gap: 40px;
      margin-top: 60px;
      padding-top: 40px;
      border-top: 1px solid rgba(255, 255, 255, 0.2);
    }

    .modern-hero .stat-item {
      text-align: left;
    }

    .modern-hero .stat-number {
      font-size: 42px;
      font-weight: 800;
      line-height: 1;
      margin-bottom: 4px;
      background: linear-gradient(135deg, #D4A853, #E8C97A);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .modern-hero .stat-label {
      font-size: 14px;
      opacity: 0.85;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    /* ============================================
       SECTIONS
       ============================================ */
    .modern-section {
      padding: 100px 0;
      position: relative;
    }

    .modern-section.bg-light {
      background: #F8FAFC;
    }

    .section-header {
      text-align: center;
      max-width: 700px;
      margin: 0 auto 60px;
    }

    .section-header .section-tag {
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
      font-size: clamp(32px, 4vw, 48px);
      font-weight: 800;
      color: #0F172A;
      margin-bottom: 16px;
      letter-spacing: -0.02em;
    }

    .section-header p {
      font-size: 18px;
      color: #64748B;
      line-height: 1.7;
    }

    /* ============================================
       SERVICES GRID
       ============================================ */
    .services-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 32px;
      max-width: 1400px;
      margin: 0 auto;
      padding: 0 24px;
    }

    .service-card {
      background: #FFFFFF;
      border-radius: 24px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      transition: all 0.4s ease;
      position: relative;
    }

    .service-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 16px 48px rgba(0, 0, 0, 0.15);
    }

    .service-card .card-image {
      position: relative;
      height: 240px;
      overflow: hidden;
    }

    .service-card .card-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.6s ease;
    }

    .service-card:hover .card-image img {
      transform: scale(1.1);
    }

    .service-card .card-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(to top, rgba(15, 76, 117, 0.9), transparent);
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .service-card:hover .card-overlay {
      opacity: 1;
    }

    .service-card .card-icon {
      position: absolute;
      bottom: -30px;
      right: 24px;
      width: 70px;
      height: 70px;
      background: #D4A853;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 28px;
      color: #0F172A;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
      transition: all 0.3s ease;
    }

    .service-card:hover .card-icon {
      bottom: 20px;
    }

    .service-card .card-content {
      padding: 40px 28px 28px;
    }

    .service-card .card-content h3 {
      font-size: 22px;
      font-weight: 700;
      color: #0F172A;
      margin-bottom: 12px;
    }

    .service-card .card-content p {
      color: #64748B;
      line-height: 1.7;
      margin-bottom: 20px;
    }

    .service-card .card-link {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      color: #0F4C75;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .service-card .card-link:hover {
      color: #D4A853;
      gap: 12px;
    }

    /* ============================================
       WHY CHOOSE SECTION
       ============================================ */
    .why-choose-section {
      background: linear-gradient(135deg, #0F172A 0%, #0A3655 100%);
      color: #FFFFFF;
      overflow: hidden;
      padding: 100px 0;
    }

    .why-choose-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 80px;
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 24px;
      align-items: center;
    }

    .why-choose-content h2 {
      font-size: clamp(32px, 4vw, 48px);
      font-weight: 800;
      margin-bottom: 24px;
    }

    .why-choose-content > p {
      font-size: 18px;
      opacity: 0.85;
      line-height: 1.8;
      margin-bottom: 40px;
    }

    .feature-list {
      display: flex;
      flex-direction: column;
      gap: 24px;
    }

    .feature-item {
      display: flex;
      align-items: flex-start;
      gap: 16px;
      padding: 20px;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 16px;
      border: 1px solid rgba(255, 255, 255, 0.1);
      transition: all 0.3s ease;
    }

    .feature-item:hover {
      background: rgba(255, 255, 255, 0.08);
      transform: translateX(8px);
    }

    .feature-item .feature-icon {
      width: 50px;
      height: 50px;
      min-width: 50px;
      background: linear-gradient(135deg, #D4A853, #E8C97A);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      color: #0F172A;
    }

    .feature-item .feature-text h4 {
      font-size: 18px;
      font-weight: 700;
      margin-bottom: 4px;
    }

    .feature-item .feature-text p {
      font-size: 14px;
      opacity: 0.75;
      margin: 0;
    }

    .why-choose-image {
      position: relative;
    }

    .why-choose-image .main-image {
      position: relative;
      border-radius: 32px;
      overflow: hidden;
      box-shadow: 0 16px 48px rgba(0, 0, 0, 0.3);
    }

    .why-choose-image .main-image img {
      width: 100%;
      display: block;
    }

    .why-choose-image .floating-card {
      position: absolute;
      bottom: -30px;
      left: -30px;
      background: #FFFFFF;
      padding: 24px 32px;
      border-radius: 24px;
      box-shadow: 0 16px 48px rgba(0, 0, 0, 0.3);
      color: #0F172A;
    }

    .floating-card .floating-value {
      font-size: 48px;
      font-weight: 800;
      background: linear-gradient(135deg, #0F4C75, #3282B8);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      line-height: 1;
    }

    .floating-card .floating-label {
      font-size: 14px;
      color: #64748B;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    /* ============================================
       COUNTRIES SECTION
       ============================================ */
    .countries-section {
      padding: 100px 0;
      background: #F8FAFC;
    }

    .countries-carousel {
      display: flex;
      gap: 24px;
      overflow-x: auto;
      padding: 20px 24px 40px;
      max-width: 1400px;
      margin: 0 auto;
      scroll-snap-type: x mandatory;
      scrollbar-width: none;
    }

    .countries-carousel::-webkit-scrollbar {
      display: none;
    }

    .country-card {
      flex: 0 0 280px;
      scroll-snap-align: start;
      position: relative;
      border-radius: 24px;
      overflow: hidden;
      cursor: pointer;
    }

    .country-card img {
      width: 100%;
      height: 350px;
      object-fit: cover;
      transition: transform 0.5s ease;
    }

    .country-card:hover img {
      transform: scale(1.1);
    }

    .country-card .country-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(to top, rgba(15, 76, 117, 0.9), transparent 60%);
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      padding: 24px;
    }

    .country-card h3 {
      color: #FFFFFF;
      font-size: 24px;
      font-weight: 700;
      margin: 0;
    }

    .country-card .country-count {
      color: #D4A853;
      font-size: 14px;
      font-weight: 500;
    }

    /* ============================================
       STATS SECTION
       ============================================ */
    .stats-section {
      padding: 80px 0;
      background: linear-gradient(135deg, #D4A853 0%, #E8C97A 100%);
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 32px;
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 24px;
    }

    .stat-card {
      text-align: center;
      padding: 24px;
    }

    .stat-card .stat-icon {
      width: 70px;
      height: 70px;
      margin: 0 auto 16px;
      background: rgba(255, 255, 255, 0.3);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 28px;
      color: #0F172A;
    }

    .stat-card .stat-number {
      font-size: 48px;
      font-weight: 800;
      color: #0F172A;
      line-height: 1;
      margin-bottom: 8px;
    }

    .stat-card .stat-label {
      font-size: 15px;
      color: #0F172A;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    /* ============================================
       TEAM SECTION
       ============================================ */
    .team-section {
      padding: 100px 0;
      background: #FFFFFF;
    }

    .team-carousel {
      display: flex;
      gap: 32px;
      overflow-x: auto;
      padding: 20px 24px 40px;
      max-width: 1400px;
      margin: 0 auto;
      scroll-snap-type: x mandatory;
      scrollbar-width: none;
    }

    .team-carousel::-webkit-scrollbar {
      display: none;
    }

    .team-card {
      flex: 0 0 350px;
      scroll-snap-align: start;
      background: #FFFFFF;
      border-radius: 24px;
      overflow: hidden;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
      transition: all 0.4s ease;
    }

    .team-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 16px 48px rgba(0, 0, 0, 0.2);
    }

    .team-card .team-image {
      height: 320px;
      overflow: hidden;
    }

    .team-card .team-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .team-card .team-content {
      padding: 24px;
    }

    .team-card .team-name {
      font-size: 20px;
      font-weight: 700;
      color: #0F172A;
      margin-bottom: 4px;
    }

    .team-card .team-role {
      font-size: 14px;
      color: #D4A853;
      font-weight: 600;
      margin-bottom: 12px;
    }

    .team-card .team-bio {
      font-size: 14px;
      color: #64748B;
      line-height: 1.6;
    }

    /* ============================================
       TESTIMONIALS SECTION
       ============================================ */
    .testimonials-section {
      padding: 100px 0;
      background: #0F172A;
      color: #FFFFFF;
      position: relative;
      overflow: hidden;
    }

    .testimonials-section::before {
      content: '"';
      position: absolute;
      top: 40px;
      left: 50%;
      transform: translateX(-50%);
      font-size: 300px;
      font-family: Georgia, serif;
      color: rgba(255, 255, 255, 0.03);
      line-height: 1;
      pointer-events: none;
    }

    .testimonials-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
      gap: 32px;
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 24px;
      position: relative;
      z-index: 1;
    }

    .testimonial-card {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 24px;
      padding: 40px 32px;
      backdrop-filter: blur(10px);
      transition: all 0.3s ease;
    }

    .testimonial-card:hover {
      background: rgba(255, 255, 255, 0.08);
      transform: translateY(-4px);
    }

    .testimonial-card .testimonial-rating {
      display: flex;
      gap: 4px;
      margin-bottom: 20px;
    }

    .testimonial-card .testimonial-rating .star {
      color: #D4A853;
      font-size: 18px;
    }

    .testimonial-card .testimonial-text {
      font-size: 16px;
      line-height: 1.8;
      opacity: 0.9;
      margin-bottom: 24px;
    }

    .testimonial-card .testimonial-author {
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .testimonial-card .author-avatar {
      width: 50px;
      height: 50px;
      background: linear-gradient(135deg, #D4A853, #E8C97A);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 18px;
      color: #0F172A;
    }

    .testimonial-card .author-info h4 {
      font-size: 16px;
      font-weight: 700;
      margin-bottom: 2px;
    }

    .testimonial-card .author-info span {
      font-size: 13px;
      opacity: 0.7;
    }

    /* ============================================
       CTA SECTION
       ============================================ */
    .cta-section {
      padding: 100px 24px;
      background: linear-gradient(135deg, #0F4C75 0%, #3282B8 100%);
      text-align: center;
      color: #FFFFFF;
    }

    .cta-section h2 {
      font-size: clamp(32px, 4vw, 48px);
      font-weight: 800;
      margin-bottom: 16px;
    }

    .cta-section p {
      font-size: 18px;
      opacity: 0.9;
      margin-bottom: 32px;
      max-width: 600px;
      margin-left: auto;
      margin-right: auto;
    }

    .cta-section .cta-phone {
      display: inline-flex;
      align-items: center;
      gap: 12px;
      padding: 18px 40px;
      background: #FFFFFF;
      color: #0F4C75;
      font-size: 20px;
      font-weight: 700;
      border-radius: 50px;
      text-decoration: none;
      transition: all 0.3s ease;
      box-shadow: 0 16px 48px rgba(0, 0, 0, 0.2);
    }

    .cta-section .cta-phone:hover {
      transform: translateY(-4px);
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    /* ============================================
       FOOTER
       ============================================ */
    .modern-footer {
      background: #0F172A;
      color: #FFFFFF;
      padding-top: 80px;
    }

    .modern-footer .footer-top {
      max-width: 1400px;
      margin: 0 auto;
      padding: 0 24px 60px;
      display: grid;
      grid-template-columns: 2fr 1fr 1fr 1fr;
      gap: 60px;
    }

    .modern-footer .footer-brand .logo {
      margin-bottom: 20px;
      display: inline-block;
    }

    .modern-footer .footer-brand .logo img {
      height: 50px;
    }

    .modern-footer .footer-brand p {
      color: rgba(255, 255, 255, 0.7);
      line-height: 1.8;
      margin-bottom: 24px;
    }

    .modern-footer .social-links {
      display: flex;
      gap: 12px;
    }

    .modern-footer .social-links a {
      width: 44px;
      height: 44px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #FFFFFF;
      transition: all 0.3s ease;
    }

    .modern-footer .social-links a:hover {
      background: #D4A853;
      color: #0F172A;
      transform: translateY(-4px);
    }

    .modern-footer .footer-column h4 {
      font-size: 18px;
      font-weight: 700;
      margin-bottom: 24px;
    }

    .modern-footer .footer-links {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .modern-footer .footer-links a {
      color: rgba(255, 255, 255, 0.7);
      text-decoration: none;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .modern-footer .footer-links a:hover {
      color: #D4A853;
      transform: translateX(4px);
    }

    .modern-footer .footer-contact-item {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      margin-bottom: 20px;
    }

    .modern-footer .footer-contact-item i {
      color: #D4A853;
      font-size: 18px;
      margin-top: 2px;
    }

    .modern-footer .footer-contact-item p {
      color: rgba(255, 255, 255, 0.7);
      margin: 0;
    }

    .modern-footer .footer-contact-item a {
      color: rgba(255, 255, 255, 0.7);
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .modern-footer .footer-contact-item a:hover {
      color: #D4A853;
    }

    .modern-footer .footer-bottom {
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      padding: 24px;
      text-align: center;
    }

    .modern-footer .footer-bottom p {
      color: rgba(255, 255, 255, 0.5);
      margin: 0;
    }

    .modern-footer .footer-bottom a {
      color: #D4A853;
      text-decoration: none;
    }

    /* ============================================
       SCROLL TO TOP
       ============================================ */
    .scroll-top {
      position: fixed;
      bottom: 30px;
      right: 30px;
      width: 50px;
      height: 50px;
      background: #D4A853;
      color: #0F172A;
      border: none;
      border-radius: 50%;
      font-size: 20px;
      cursor: pointer;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
      z-index: 999;
    }

    .scroll-top.visible {
      opacity: 1;
      visibility: visible;
    }

    .scroll-top:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 32px rgba(0, 0, 0, 0.3);
    }

    /* ============================================
       RESPONSIVE STYLES
       ============================================ */
    @media (max-width: 1024px) {
      .modern-header .nav-menu {
        display: none;
      }

      .modern-header .mobile-toggle {
        display: flex;
      }

      .why-choose-grid {
        grid-template-columns: 1fr;
        gap: 60px;
      }

      .why-choose-image {
        order: -1;
      }

      .stats-grid {
        grid-template-columns: repeat(2, 1fr);
      }

      .modern-footer .footer-top {
        grid-template-columns: 1fr 1fr;
        gap: 40px;
      }
    }

    @media (max-width: 768px) {
      .section-header {
        margin-bottom: 40px;
      }

      .services-grid {
        grid-template-columns: 1fr;
        padding: 0 16px;
      }

      .stats-grid {
        grid-template-columns: 1fr;
      }

      .modern-footer .footer-top {
        grid-template-columns: 1fr;
      }

      .testimonials-grid {
        grid-template-columns: 1fr;
      }

      .modern-hero .hero-actions {
        flex-direction: column;
      }

      .modern-hero .hero-actions .btn-primary,
      .modern-hero .hero-actions .btn-secondary {
        width: 100%;
        justify-content: center;
      }

      .modern-hero .hero-stats {
        gap: 24px;
        margin-top: 40px;
      }

      .modern-hero .stat-number {
        font-size: 32px;
      }
    }

    @media (max-width: 480px) {
      .modern-header .header-container {
        padding: 12px 16px;
      }

      .modern-header .logo img {
        height: 35px;
      }

      .section-header h2 {
        font-size: 28px;
      }

      .country-card {
        flex: 0 0 240px;
      }

      .team-card {
        flex: 0 0 280px;
      }

      .scroll-top {
        bottom: 20px;
        right: 20px;
        width: 44px;
        height: 44px;
      }
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
        <a href="./" class="nav-link active">Home</a>
        <a href="about.php" class="nav-link">About</a>
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

  <!-- Mobile Menu Panel -->
  <div class="mobile-menu-overlay" id="mobileOverlay" onclick="toggleMobileMenu()"></div>
  <div class="mobile-menu-panel" id="mobileMenu">
    <button class="close-btn" onclick="toggleMobileMenu()">
      <i class="fas fa-times"></i>
    </button>
    <ul class="mobile-nav">
      <li><a href="./" class="active">Home</a></li>
      <li><a href="about.php">About</a></li>
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

  <!-- Hero Section -->
  <section class="modern-hero">
    <div class="hero-bg">
      <img src="images/main-slider/2.jpg" alt="Background" />
    </div>
    <div class="hero-overlay"></div>

    <div class="hero-content">
      <div class="hero-badge">
        <span class="dot"></span>
        Trusted Immigration Experts
      </div>

      <h1>Your Gateway to<br />Global Opportunities</h1>

      <p class="hero-subtitle">
        Fast, reliable visa processing and immigration services with a 99% success rate.
        Let us help you achieve your dream of studying, working, or living abroad.
      </p>

      <div class="hero-actions">
        <a href="services.php" class="btn-primary">
          <span>Explore Services</span>
          <i class="fas fa-arrow-right"></i>
        </a>
        <a href="contact.php" class="btn-secondary">
          <span>Free Consultation</span>
          <i class="fas fa-phone"></i>
        </a>
      </div>

      <div class="hero-stats">
        <div class="stat-item">
          <div class="stat-number">10+</div>
          <div class="stat-label">Years Experience</div>
        </div>
        <div class="stat-item">
          <div class="stat-number">5000+</div>
          <div class="stat-label">Happy Clients</div>
        </div>
        <div class="stat-item">
          <div class="stat-number">99%</div>
          <div class="stat-label">Success Rate</div>
        </div>
        <div class="stat-item">
          <div class="stat-number">50+</div>
          <div class="stat-label">Countries</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Services Section -->
  <section class="modern-section">
    <div class="section-header">
      <span class="section-tag">
        <i class="fas fa-star"></i> What We Offer
      </span>
      <h2>Comprehensive Visa & Immigration Services</h2>
      <p>From student visas to permanent residency, we provide end-to-end immigration solutions tailored to your unique needs.</p>
    </div>

    <div class="services-grid">
      <!-- Service 1: Study Abroad Consulting -->
      <div class="service-card">
        <div class="card-image">
          <img src="images/travel/01.webp" alt="Study Abroad Consulting" />
          <div class="card-overlay"></div>
        </div>
        <div class="card-icon">
          <i class="fas fa-graduation-cap"></i>
        </div>
        <div class="card-content">
          <h3>Study Abroad Consulting</h3>
          <p>Expert guidance for pursuing education abroad. We help with university selection, admissions, and document preparation.</p>
          <a href="services.php" class="card-link">
            Learn More <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>

      <!-- Service 2: Admissions Support -->
      <div class="service-card">
        <div class="card-image">
          <img src="images/travel/13.jpg" alt="Admissions Support" />
          <div class="card-overlay"></div>
        </div>
        <div class="card-icon">
          <i class="fas fa-university"></i>
        </div>
        <div class="card-content">
          <h3>Admissions Support</h3>
          <p>Direct partnerships with institutions worldwide. Fast-track your admission from application to offer letter.</p>
          <a href="services.php" class="card-link">
            Learn More <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>

      <!-- Service 3: Student & Tourist Visa -->
      <div class="service-card">
        <div class="card-image">
          <img src="images/travel/03.webp" alt="Student & Tourist Visa" />
          <div class="card-overlay"></div>
        </div>
        <div class="card-icon">
          <i class="fas fa-passport"></i>
        </div>
        <div class="card-content">
          <h3>Student & Tourist Visa</h3>
          <p>Comprehensive visa support for students and travelers. Ensure your application meets all requirements.</p>
          <a href="services.php" class="card-link">
            Learn More <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>

      <!-- Service 4: Flights & Hotels -->
      <div class="service-card">
        <div class="card-image">
          <img src="images/travel/new/08.jpeg" alt="Flights & Hotels" />
          <div class="card-overlay"></div>
        </div>
        <div class="card-icon">
          <i class="fas fa-plane-departure"></i>
        </div>
        <div class="card-content">
          <h3>Flights & Hotels</h3>
          <p>Travel with confidence. We secure the best deals on flights and accommodations for your journey.</p>
          <a href="services.php" class="card-link">
            Learn More <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>

      <!-- Service 5: Agent Referral Program -->
      <div class="service-card">
        <div class="card-image">
          <img src="images/resource/news-2.jpg" alt="Agent Referral Program" />
          <div class="card-overlay"></div>
        </div>
        <div class="card-icon">
          <i class="fas fa-handshake"></i>
        </div>
        <div class="card-content">
          <h3>Agent Referral Program</h3>
          <p>Join our network of verified agents. Earn commissions while helping students achieve their dreams.</p>
          <a href="services.php" class="card-link">
            Learn More <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>

      <!-- Service 6: Application Tracking -->
      <div class="service-card">
        <div class="card-image">
          <img src="images/travel/new/02.jpeg" alt="Digital Application Tracking" />
          <div class="card-overlay"></div>
        </div>
        <div class="card-icon">
          <i class="fas fa-laptop-code"></i>
        </div>
        <div class="card-content">
          <h3>Digital Application Tracking</h3>
          <p>Track your application progress in real-time through our secure portal with 24/7 access.</p>
          <a href="services.php" class="card-link">
            Learn More <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Why Choose Us Section -->
  <section class="why-choose-section">
    <div class="why-choose-grid">
      <div class="why-choose-content">
        <span style="display: inline-block; padding: 8px 20px; background: rgba(212, 168, 83, 0.2); color: #D4A853; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; border-radius: 50px; margin-bottom: 20px;">
          <i class="fas fa-check-circle"></i> Why Choose Us
        </span>
        <h2>Your Trusted Partner for Hassle-Free Immigration</h2>
        <p>At ApplyBoard Africa Ltd, we combine expertise, dedication, and personalized service to make your immigration dreams a reality.</p>

        <div class="feature-list">
          <div class="feature-item">
            <div class="feature-icon">
              <i class="fas fa-shield-alt"></i>
            </div>
            <div class="feature-text">
              <h4>99% Success Rate</h4>
              <p>Our proven track record speaks for itself with thousands of successful visa applications.</p>
            </div>
          </div>

          <div class="feature-item">
            <div class="feature-icon">
              <i class="fas fa-clock"></i>
            </div>
            <div class="feature-text">
              <h4>Fast Processing</h4>
              <p>Streamlined processes ensure your application is processed quickly and efficiently.</p>
            </div>
          </div>

          <div class="feature-item">
            <div class="feature-icon">
              <i class="fas fa-user-tie"></i>
            </div>
            <div class="feature-text">
              <h4>Expert Guidance</h4>
              <p>Our experienced consultants provide personalized guidance at every step.</p>
            </div>
          </div>

          <div class="feature-item">
            <div class="feature-icon">
              <i class="fas fa-headset"></i>
            </div>
            <div class="feature-text">
              <h4>24/7 Support</h4>
              <p>We're always available to answer your questions and address your concerns.</p>
            </div>
          </div>
        </div>
      </div>

      <div class="why-choose-image">
        <div class="main-image">
          <img src="images/travel/new/16.jpeg" alt="Immigration Services" />
        </div>
        <div class="floating-card">
          <div class="floating-value">10+</div>
          <div class="floating-label">Years of Excellence</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Countries Section -->
  <section class="countries-section">
    <div class="section-header">
      <span class="section-tag">
        <i class="fas fa-globe"></i> Destinations
      </span>
      <h2>Popular Countries We Support</h2>
      <p>Explore opportunities across the globe with our extensive network and expertise in various immigration pathways.</p>
    </div>

    <div class="countries-carousel">
      <div class="country-card">
        <img src="images/resource/country-1.jpg" alt="Australia" />
        <div class="country-overlay">
          <h3>Australia</h3>
          <span class="country-count">Student, Work & PR Visas</span>
        </div>
      </div>

      <div class="country-card">
        <img src="images/resource/country-2.jpg" alt="United States" />
        <div class="country-overlay">
          <h3>United States</h3>
          <span class="country-count">All Visa Categories</span>
        </div>
      </div>

      <div class="country-card">
        <img src="images/resource/country-3.jpg" alt="Dubai" />
        <div class="country-overlay">
          <h3>Dubai (UAE)</h3>
          <span class="country-count">Tourist & Work Visas</span>
        </div>
      </div>

      <div class="country-card">
        <img src="images/resource/country-4.jpg" alt="United Kingdom" />
        <div class="country-overlay">
          <h3>United Kingdom</h3>
          <span class="country-count">Study & Work Visas</span>
        </div>
      </div>

      <div class="country-card">
        <img src="images/resource/country-5.jpg" alt="India" />
        <div class="country-overlay">
          <h3>India</h3>
          <span class="country-count">Tourist & Business Visas</span>
        </div>
      </div>
    </div>
  </section>

  <!-- Stats Section -->
  <section class="stats-section">
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon">
          <i class="fas fa-passport"></i>
        </div>
        <div class="stat-number">10+</div>
        <div class="stat-label">Visa Categories</div>
      </div>

      <div class="stat-card">
        <div class="stat-icon">
          <i class="fas fa-users"></i>
        </div>
        <div class="stat-number">5000+</div>
        <div class="stat-label">Happy Clients</div>
      </div>

      <div class="stat-card">
        <div class="stat-icon">
          <i class="fas fa-file-alt"></i>
        </div>
        <div class="stat-number">2000+</div>
        <div class="stat-label">Visas Processed</div>
      </div>

      <div class="stat-card">
        <div class="stat-icon">
          <i class="fas fa-trophy"></i>
        </div>
        <div class="stat-number">99%</div>
        <div class="stat-label">Success Rate</div>
      </div>
    </div>
  </section>


  <!-- Testimonials Section -->
  <section class="testimonials-section">
    <div class="section-header">
      <span style="display: inline-block; padding: 8px 20px; background: rgba(255, 255, 255, 0.1); color: #D4A853; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; border-radius: 50px; margin-bottom: 20px;">
        <i class="fas fa-quote-left"></i> Client Stories
      </span>
      <h2 style="color: #FFFFFF;">What Our Clients Say</h2>
      <p style="color: rgba(255, 255, 255, 0.7);">Don't just take our word for it. Here's what our satisfied clients have to say about their experience with us.</p>
    </div>

    <div class="testimonials-grid">
      <?php
      $getTest = mysqli_query($conn, "SELECT * FROM `testimonials` ORDER BY `created_at` DESC LIMIT 6");
      if (mysqli_num_rows($getTest) > 0) {
        while ($test = mysqli_fetch_assoc($getTest)) {
          $initials = strtoupper(substr($test['name'], 0, 2));
          ?>
          <div class="testimonial-card">
            <div class="testimonial-rating">
              <span class="star"><i class="fas fa-star"></i></span>
              <span class="star"><i class="fas fa-star"></i></span>
              <span class="star"><i class="fas fa-star"></i></span>
              <span class="star"><i class="fas fa-star"></i></span>
              <span class="star"><i class="fas fa-star"></i></span>
            </div>
            <p class="testimonial-text">"<?php echo htmlspecialchars($test['message']); ?>"</p>
            <div class="testimonial-author">
              <div class="author-avatar"><?php echo $initials; ?></div>
              <div class="author-info">
                <h4><?php echo htmlspecialchars($test['name']); ?></h4>
                <span><?php echo htmlspecialchars($test['position']); ?></span>
              </div>
            </div>
          </div>
          <?php
        }
      } else {
        ?>
        <div class="testimonial-card">
          <div class="testimonial-rating">
            <span class="star"><i class="fas fa-star"></i></span>
            <span class="star"><i class="fas fa-star"></i></span>
            <span class="star"><i class="fas fa-star"></i></span>
            <span class="star"><i class="fas fa-star"></i></span>
            <span class="star"><i class="fas fa-star"></i></span>
          </div>
          <p class="testimonial-text">"ApplyBoard Africa made my dream of studying in Canada a reality. Their team was professional, responsive, and guided me through every step. I got my visa approved in just 3 weeks!"</p>
          <div class="testimonial-author">
            <div class="author-avatar">AC</div>
            <div class="author-info">
              <h4>Amara Chukwu</h4>
              <span>Student Visa Client</span>
            </div>
          </div>
        </div>

        <div class="testimonial-card">
          <div class="testimonial-rating">
            <span class="star"><i class="fas fa-star"></i></span>
            <span class="star"><i class="fas fa-star"></i></span>
            <span class="star"><i class="fas fa-star"></i></span>
            <span class="star"><i class="fas fa-star"></i></span>
            <span class="star"><i class="fas fa-star"></i></span>
          </div>
          <p class="testimonial-text">"Excellent service from start to finish. The team helped me secure a work visa for the UK with minimal stress. Their expertise and attention to detail is unmatched."</p>
          <div class="testimonial-author">
            <div class="author-avatar">OA</div>
            <div class="author-info">
              <h4>Oluwaseun Adeyemi</h4>
              <span>Work Visa Client</span>
            </div>
          </div>
        </div>

        <div class="testimonial-card">
          <div class="testimonial-rating">
            <span class="star"><i class="fas fa-star"></i></span>
            <span class="star"><i class="fas fa-star"></i></span>
            <span class="star"><i class="fas fa-star"></i></span>
            <span class="star"><i class="fas fa-star"></i></span>
            <span class="star"><i class="fas fa-star"></i></span>
          </div>
          <p class="testimonial-text">"Our family pilgrimage to Jerusalem was beautifully organized. ApplyBoard Africa handled everything from flights to accommodations. A truly spiritual and memorable experience."</p>
          <div class="testimonial-author">
            <div class="author-avatar">FE</div>
            <div class="author-info">
              <h4>Fatima Ekong</h4>
              <span>Pilgrimage Travel Client</span>
            </div>
          </div>
        </div>
        <?php
      }
      ?>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="cta-section">
    <h2>Ready to Start Your Journey?</h2>
    <p>Contact us today for a free consultation and let our experts guide you through your visa application process.</p>
    <a href="tel:+2349069503394" class="cta-phone">
      <i class="fas fa-phone-alt"></i>
      +234 906 9503 394
    </a>
  </section>

  <!-- Modern Footer -->
  <footer class="modern-footer">
    <div class="footer-top">
      <div class="footer-brand">
        <a href="./" class="logo">
          <img src="images/logo-2.png" alt="ApplyBoard Africa Ltd" />
        </a>
        <p>ApplyBoard Africa Ltd is your trusted partner for hassle-free visa processing, study abroad consultation, and immigration services. With over 10 years of experience and a 99% success rate, we're here to help you achieve your global dreams.</p>
        <div class="social-links">
          <a href="https://facebook.com" target="_blank"><i class="fab fa-facebook-f"></i></a>
          <a href="https://twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
          <a href="https://instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
          <a href="https://linkedin.com" target="_blank"><i class="fab fa-linkedin-in"></i></a>
        </div>

        <div class="newsletter-box" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 16px; padding: 24px; margin-top: 24px;">
          <h5 style="font-size: 16px; font-weight: 600; margin-bottom: 16px;"><i class="fas fa-envelope"></i> Subscribe to Newsletter</h5>
          <form method="post" class="newsletter-form" style="display: flex; gap: 8px;">
            <input type="email" name="email" placeholder="Enter your email" required style="flex: 1; padding: 12px 16px; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 8px; color: #FFFFFF; font-size: 14px;" />
            <button type="submit" name="subscribe" style="padding: 12px 24px; background: #D4A853; color: #0F172A; border: none; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; transition: all 0.3s ease;">
              <i class="fas fa-paper-plane"></i>
            </button>
          </form>
          <?php
          if (isset($_POST["subscribe"])) {
            $email = mysqli_real_escape_string($conn, $_POST["email"]);
            $insert = mysqli_query($conn, "INSERT INTO `newsletters` (`email`) VALUES ('$email')");
            if ($insert) {
              echo "<script>alert('Successfully subscribed to our newsletter!'); window.location.href='index.php';</script>";
            }
          }
          ?>
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

  <!-- Scroll To Top -->
  <button class="scroll-top" id="scrollTop" onclick="scrollToTop()">
    <i class="fas fa-arrow-up"></i>
  </button>

  <!-- Start of Glassix WhatsApp Widget -->
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
  <!-- End of Glassix WhatsApp Widget -->

  <script>
    // Mobile Menu Toggle
    function toggleMobileMenu() {
      const menu = document.getElementById('mobileMenu');
      const overlay = document.getElementById('mobileOverlay');
      menu.classList.toggle('active');
      overlay.classList.toggle('active');
      document.body.style.overflow = menu.classList.contains('active') ? 'hidden' : '';
    }

    // Scroll To Top
    const scrollTopBtn = document.getElementById('scrollTop');
    window.addEventListener('scroll', () => {
      if (window.pageYOffset > 300) {
        scrollTopBtn.classList.add('visible');
      } else {
        scrollTopBtn.classList.remove('visible');
      }
    });

    function scrollToTop() {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Header Scroll Effect
    const header = document.querySelector('.modern-header');
    let lastScroll = 0;

    window.addEventListener('scroll', () => {
      const currentScroll = window.pageYOffset;

      if (currentScroll > 100) {
        header.style.boxShadow = '0 4px 24px rgba(0, 0, 0, 0.1)';
      } else {
        header.style.boxShadow = 'none';
      }

      lastScroll = currentScroll;
    });

    // Smooth reveal animations on scroll
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
        }
      });
    }, observerOptions);

    document.querySelectorAll('.service-card, .team-card, .feature-item, .country-card').forEach(el => {
      el.style.opacity = '0';
      el.style.transform = 'translateY(30px)';
      el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
      observer.observe(el);
    });
  </script>
</body>
</html>
