<?php
// Check if logged in
if (!isset($loggedIn)) {
    $loggedIn = false;
    if (!empty($_SESSION["sdtravels_user"])) {
        $loggedIn = true;
        $uid = $_SESSION["sdtravels_user"];
    }
}

// Get current page for active link highlighting
$currentPage = basename($_SERVER['PHP_SELF']);
?>
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
       PAGE HERO (for inner pages)
       ============================================ */
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
      background: linear-gradient(135deg,
        rgba(15, 76, 117, 0.95) 0%,
        rgba(50, 130, 184, 0.85) 100%
      );
    }

    .page-hero .hero-content {
      position: relative;
      max-width: 1200px;
      margin: 0 auto;
      padding: 60px 24px;
      text-align: center;
      color: #FFFFFF;
    }

    .page-hero .hero-subtitle {
      color: #D4A853;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 2px;
      margin-bottom: 16px;
    }

    .page-hero h1 {
      font-size: clamp(36px, 5vw, 56px);
      font-weight: 800;
      margin-bottom: 20px;
      line-height: 1.1;
    }

    .page-hero .hero-desc {
      font-size: 18px;
      opacity: 0.9;
      max-width: 700px;
      margin: 0 auto;
    }

    /* ============================================
       BREADCRUMB
       ============================================ */
    .breadcrumb {
      background: #F8FAFC;
      padding: 16px 0;
    }

    .breadcrumb .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 24px;
    }

    .breadcrumb p {
      color: #64748B;
      font-size: 14px;
    }

    .breadcrumb a {
      color: #0F4C75;
      text-decoration: none;
    }

    /* ============================================
       BASE SECTIONS
       ============================================ */
    .modern-section {
      padding: 80px 0;
      position: relative;
    }

    .modern-section.bg-light {
      background: #F8FAFC;
    }

    .modern-section.bg-dark {
      background: #0F172A;
      color: #FFFFFF;
    }

    .section-header {
      text-align: center;
      max-width: 700px;
      margin: 0 auto 50px;
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
      font-size: clamp(28px, 4vw, 42px);
      font-weight: 800;
      color: #0F172A;
      margin-bottom: 16px;
      letter-spacing: -0.02em;
    }

    .section-header p {
      font-size: 17px;
      color: #64748B;
      line-height: 1.7;
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
    }

    @media (max-width: 768px) {
      .page-hero {
        min-height: 40vh;
        padding-top: 70px;
      }

      .page-hero h1 {
        font-size: clamp(28px, 6vw, 42px);
      }

      .modern-section {
        padding: 60px 0;
      }
    }

    @media (max-width: 480px) {
      .modern-header .header-container {
        padding: 12px 16px;
      }

      .modern-header .logo img {
        height: 35px;
      }
    }
  </style>

  <!-- Modern Header -->
  <header class="modern-header">
    <div class="header-container">
      <a href="./" class="logo">
        <img src="images/logo-3.png" alt="ApplyBoard Africa Ltd" />
      </a>

      <nav class="nav-menu">
        <a href="./" class="nav-link <?= $currentPage === 'index.php' ? 'active' : '' ?>">Home</a>
        <a href="about.php" class="nav-link <?= $currentPage === 'about.php' ? 'active' : '' ?>">About</a>
        <a href="services.php" class="nav-link <?= $currentPage === 'services.php' ? 'active' : '' ?>">Services</a>
        <a href="platform.php" class="nav-link <?= $currentPage === 'platform.php' ? 'active' : '' ?>">Platform</a>
        <a href="agents.php" class="nav-link <?= $currentPage === 'agents.php' ? 'active' : '' ?>">Agents</a>
        <a href="contact.php" class="nav-link <?= $currentPage === 'contact.php' ? 'active' : '' ?>">Contact</a>
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
      <li><a href="./" <?= $currentPage === 'index.php' ? 'class="active"' : '' ?>>Home</a></li>
      <li><a href="about.php" <?= $currentPage === 'about.php' ? 'class="active"' : '' ?>>About</a></li>
      <li><a href="services.php" <?= $currentPage === 'services.php' ? 'class="active"' : '' ?>>Services</a></li>
      <li><a href="platform.php" <?= $currentPage === 'platform.php' ? 'class="active"' : '' ?>>Platform</a></li>
      <li><a href="agents.php" <?= $currentPage === 'agents.php' ? 'class="active"' : '' ?>>Agents</a></li>
      <li><a href="contact.php" <?= $currentPage === 'contact.php' ? 'class="active"' : '' ?>>Contact</a></li>
      <?php if ($loggedIn): ?>
        <li><a href="user" class="btn-header">Dashboard</a></li>
      <?php else: ?>
        <li><a href="user/login.php" class="btn-header">Login</a></li>
      <?php endif; ?>
    </ul>
  </div>

  <script>
    // Mobile Menu Toggle
    function toggleMobileMenu() {
      const menu = document.getElementById('mobileMenu');
      const overlay = document.getElementById('mobileOverlay');
      menu.classList.toggle('active');
      overlay.classList.toggle('active');
      document.body.style.overflow = menu.classList.contains('active') ? 'hidden' : '';
    }

    // Header Scroll Effect
    const header = document.querySelector('.modern-header');
    window.addEventListener('scroll', () => {
      if (window.pageYOffset > 100) {
        header.style.boxShadow = '0 4px 24px rgba(0, 0, 0, 0.1)';
      } else {
        header.style.boxShadow = 'none';
      }
    });
  </script>
