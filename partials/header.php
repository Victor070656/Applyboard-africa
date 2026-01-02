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

// Set page title if not defined
if (!isset($pageTitle)) {
  $pageTitle = "ApplyBoard Africa Ltd";
}
if (!isset($pageDescription)) {
  $pageDescription = "Your trusted partner for visa processing, study abroad consultation, and immigration services.";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($pageTitle) ?> | ApplyBoard Africa Ltd</title>
  <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>" />

  <!-- Favicon -->
  <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon" />
  <link rel="apple-touch-icon" href="images/favicon.png" />

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@400;500;600;700;800;900&display=swap"
    rel="stylesheet" />

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

  <!-- Theme CSS -->
  <link rel="stylesheet" href="css/theme.css" />
</head>

<body>

  <!-- Preloader -->
  <div class="preloader" id="preloader">
    <div class="preloader-spinner"></div>
  </div>

  <!-- Header -->
  <header class="site-header" id="siteHeader">
    <div class="container">
      <div class="header-inner">
        <!-- Logo -->
        <a href="./" class="site-logo">
          <img src="images/logo-2.png" style="border-radius: 5px;" alt="ApplyBoard Africa" />
        </a>

        <!-- Main Navigation -->
        <nav class="main-nav">
          <a href="./" class="nav-link <?= $currentPage === 'index.php' ? 'active' : '' ?>">Home</a>
          <a href="about.php" class="nav-link <?= $currentPage === 'about.php' ? 'active' : '' ?>">About</a>
          <a href="services.php" class="nav-link <?= $currentPage === 'services.php' ? 'active' : '' ?>">Services</a>
          <a href="agents.php" class="nav-link <?= $currentPage === 'agents.php' ? 'active' : '' ?>">Agents</a>
          <a href="contact.php" class="nav-link <?= $currentPage === 'contact.php' ? 'active' : '' ?>">Contact</a>
        </nav>

        <!-- Header Actions -->
        <div class="header-actions">
          <?php if ($loggedIn): ?>
            <a href="user/" class="btn btn-primary">
              <i class="fas fa-user"></i>
              <span>Dashboard</span>
            </a>
          <?php else: ?>
            <a href="user/login.php" class="btn btn-ghost">Sign In</a>
            <a href="platform.php" class="btn btn-primary">Get Started</a>
          <?php endif; ?>

          <!-- Mobile Toggle -->
          <button class="mobile-toggle" id="mobileToggle" aria-label="Toggle menu">
            <span></span>
            <span></span>
            <span></span>
          </button>
        </div>
      </div>
    </div>
  </header>

  <!-- Mobile Menu Overlay -->
  <div class="mobile-menu-overlay" id="mobileOverlay"></div>

  <!-- Mobile Menu -->
  <div class="mobile-menu" id="mobileMenu">
    <button class="mobile-menu-close" id="mobileClose">
      <i class="fas fa-times"></i>
    </button>

    <nav class="mobile-nav">
      <a href="./" class="<?= $currentPage === 'index.php' ? 'active' : '' ?>">Home</a>
      <a href="about.php" class="<?= $currentPage === 'about.php' ? 'active' : '' ?>">About Us</a>
      <a href="services.php" class="<?= $currentPage === 'services.php' ? 'active' : '' ?>">Our Services</a>
      <a href="agents.php" class="<?= $currentPage === 'agents.php' ? 'active' : '' ?>">Become an Agent</a>
      <a href="contact.php" class="<?= $currentPage === 'contact.php' ? 'active' : '' ?>">Contact Us</a>
    </nav>

    <div class="mobile-menu-actions">
      <?php if ($loggedIn): ?>
        <a href="user/" class="btn btn-primary btn-lg" style="width: 100%;">
          <i class="fas fa-user"></i>
          <span>My Dashboard</span>
        </a>
      <?php else: ?>
        <a href="user/login.php" class="btn btn-outline btn-lg" style="width: 100%;">Sign In</a>
        <a href="platform.php" class="btn btn-primary btn-lg" style="width: 100%;">Get Started</a>
      <?php endif; ?>
    </div>
  </div>

  <script>
    // Header Scroll Effect
    const header = document.getElementById('siteHeader');
    window.addEventListener('scroll', () => {
      if (window.scrollY > 50) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }
    });

    // Mobile Menu
    const mobileToggle = document.getElementById('mobileToggle');
    const mobileClose = document.getElementById('mobileClose');
    const mobileOverlay = document.getElementById('mobileOverlay');
    const mobileMenu = document.getElementById('mobileMenu');

    function openMobileMenu() {
      mobileMenu.classList.add('active');
      mobileOverlay.classList.add('active');
      document.body.style.overflow = 'hidden';
    }

    function closeMobileMenu() {
      mobileMenu.classList.remove('active');
      mobileOverlay.classList.remove('active');
      document.body.style.overflow = '';
    }

    mobileToggle.addEventListener('click', openMobileMenu);
    mobileClose.addEventListener('click', closeMobileMenu);
    mobileOverlay.addEventListener('click', closeMobileMenu);

    // Preloader
    window.addEventListener('load', () => {
      document.getElementById('preloader').classList.add('hidden');
    });
  </script>