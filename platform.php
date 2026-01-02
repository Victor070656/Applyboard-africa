<?php
$pageTitle = "Get Started";
$pageDescription = "Start your journey with ApplyBoard Africa. Choose your service and begin your study abroad, visa, or travel application today.";
include_once "config/config.php";
include_once "partials/header.php";

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$userType = $_SESSION['user_type'] ?? null;
?>

<!-- Page Hero -->
<section class="page-hero page-hero-short">
  <div class="hero-bg">
    <img src="images/main-slider/3.jpg" alt="Get Started with ApplyBoard Africa" />
  </div>
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <p class="hero-subtitle">Begin Your Journey</p>
    <h1>Get Started</h1>
    <p>Select a service below to start your application or register to access our client portal.</p>
    <div class="breadcrumb">
      <a href="./">Home</a>
      <span>/</span>
      <span>Get Started</span>
    </div>
  </div>
</section>

<!-- Service Selection -->
<section class="section section-white">
  <div class="container">
    <div class="section-header text-center">
      <p class="section-subtitle">Choose Your Path</p>
      <h2 class="section-title">What Would You Like to Do?</h2>
      <p class="section-description">
        Select a service category to get started. Our team will guide you through every step of the process.
      </p>
    </div>

    <div class="service-selection-grid">
      <!-- Visa Processing -->
      <a href="<?php echo $isLoggedIn ? 'user/new_application.php?type=visa' : 'user/register.php?redirect=new_application&type=visa'; ?>"
        class="service-select-card">
        <div class="card-icon">
          <i class="fas fa-passport"></i>
        </div>
        <h3>Visa Processing</h3>
        <p>Apply for tourist, business, student, or work visas to destinations worldwide.</p>
        <ul class="card-features">
          <li><i class="fas fa-check"></i> Expert documentation support</li>
          <li><i class="fas fa-check"></i> High success rate</li>
          <li><i class="fas fa-check"></i> Multiple country options</li>
        </ul>
        <span class="card-cta">Start Application <i class="fas fa-arrow-right"></i></span>
      </a>

      <!-- Study Abroad -->
      <a href="<?php echo $isLoggedIn ? 'user/new_application.php?type=study' : 'user/register.php?redirect=new_application&type=study'; ?>"
        class="service-select-card featured">
        <div class="featured-badge">Popular</div>
        <div class="card-icon">
          <i class="fas fa-graduation-cap"></i>
        </div>
        <h3>Study Abroad</h3>
        <p>Get admission to top universities and colleges in the UK, Canada, USA, and more.</p>
        <ul class="card-features">
          <li><i class="fas fa-check"></i> University matching</li>
          <li><i class="fas fa-check"></i> Scholarship guidance</li>
          <li><i class="fas fa-check"></i> Complete visa support</li>
        </ul>
        <span class="card-cta">Explore Options <i class="fas fa-arrow-right"></i></span>
      </a>

      <!-- Hajj & Umrah -->
      <a href="<?php echo $isLoggedIn ? 'user/new_application.php?type=hajj' : 'user/register.php?redirect=new_application&type=hajj'; ?>"
        class="service-select-card">
        <div class="card-icon">
          <i class="fas fa-kaaba"></i>
        </div>
        <h3>Hajj & Umrah</h3>
        <p>Book your pilgrimage package with trusted accommodations and guided tours.</p>
        <ul class="card-features">
          <li><i class="fas fa-check"></i> All-inclusive packages</li>
          <li><i class="fas fa-check"></i> Hotels near Haram</li>
          <li><i class="fas fa-check"></i> Experienced guides</li>
        </ul>
        <span class="card-cta">View Packages <i class="fas fa-arrow-right"></i></span>
      </a>

      <!-- IELTS Coaching -->
      <a href="contact.php?service=ielts" class="service-select-card">
        <div class="card-icon">
          <i class="fas fa-language"></i>
        </div>
        <h3>IELTS Coaching</h3>
        <p>Prepare for your IELTS exam with expert trainers and proven study materials.</p>
        <ul class="card-features">
          <li><i class="fas fa-check"></i> Certified instructors</li>
          <li><i class="fas fa-check"></i> Practice tests included</li>
          <li><i class="fas fa-check"></i> Flexible schedules</li>
        </ul>
        <span class="card-cta">Enroll Now <i class="fas fa-arrow-right"></i></span>
      </a>

      <!-- Flight Booking -->
      <a href="contact.php?service=flight" class="service-select-card">
        <div class="card-icon">
          <i class="fas fa-plane"></i>
        </div>
        <h3>Flight Booking</h3>
        <p>Get the best deals on domestic and international flights with our expert team.</p>
        <ul class="card-features">
          <li><i class="fas fa-check"></i> Competitive prices</li>
          <li><i class="fas fa-check"></i> Multiple airlines</li>
          <li><i class="fas fa-check"></i> 24/7 support</li>
        </ul>
        <span class="card-cta">Book Flight <i class="fas fa-arrow-right"></i></span>
      </a>

      <!-- Immigration -->
      <a href="contact.php?service=immigration" class="service-select-card">
        <div class="card-icon">
          <i class="fas fa-globe-americas"></i>
        </div>
        <h3>Immigration Services</h3>
        <p>Get expert guidance for permanent residency, citizenship, and work permits.</p>
        <ul class="card-features">
          <li><i class="fas fa-check"></i> PR applications</li>
          <li><i class="fas fa-check"></i> Family sponsorship</li>
          <li><i class="fas fa-check"></i> Expert consultation</li>
        </ul>
        <span class="card-cta">Get Consultation <i class="fas fa-arrow-right"></i></span>
      </a>
    </div>
  </div>
</section>

<!-- Already Have Account -->
<section class="section section-light">
  <div class="container">
    <div class="account-options">
      <div class="account-card">
        <div class="account-icon">
          <i class="fas fa-user"></i>
        </div>
        <h3>Already a Client?</h3>
        <p>Log in to your account to track applications, upload documents, and make payments.</p>
        <a href="user/login.php" class="btn btn-primary">
          <i class="fas fa-sign-in-alt"></i> Client Login
        </a>
      </div>

      <div class="account-card">
        <div class="account-icon gold">
          <i class="fas fa-user-tie"></i>
        </div>
        <h3>Are You an Agent?</h3>
        <p>Access your agent dashboard to manage referrals, track commissions, and more.</p>
        <a href="agent/login.php" class="btn btn-gold">
          <i class="fas fa-sign-in-alt"></i> Agent Login
        </a>
      </div>

      <div class="account-card">
        <div class="account-icon">
          <i class="fas fa-user-shield"></i>
        </div>
        <h3>Staff Member?</h3>
        <p>Log in to the management portal to handle cases and support clients.</p>
        <a href="manager/login.php" class="btn btn-outline">
          <i class="fas fa-sign-in-alt"></i> Staff Login
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Referral Code Section -->
<section class="section section-white">
  <div class="container">
    <div class="referral-section">
      <div class="referral-content">
        <p class="section-subtitle">Have a Referral Code?</p>
        <h2 class="section-title">Register with Your Agent's Code</h2>
        <p>
          If you were referred by one of our agents, enter their referral code when registering to receive personalized
          support throughout your application process.
        </p>
        <a href="user/register.php" class="btn btn-primary btn-lg">
          <i class="fas fa-user-plus"></i> Register Now
        </a>
      </div>
      <div class="referral-image">
        <img src="images/travel/05.jpg" alt="Agent Referral" />
      </div>
    </div>
  </div>
</section>

<!-- Need Help Section -->
<section class="section section-primary">
  <div class="container">
    <div class="help-banner">
      <div class="help-content">
        <h2>Not Sure Where to Start?</h2>
        <p>Our team is here to help you choose the right service for your needs. Get a free consultation today.</p>
      </div>
      <div class="help-actions">
        <a href="contact.php" class="btn btn-gold btn-lg">
          <i class="fas fa-envelope"></i> Contact Us
        </a>
        <a href="tel:+2348012345678" class="btn btn-outline-white btn-lg">
          <i class="fas fa-phone"></i> Call Now
        </a>
      </div>
    </div>
  </div>
</section>

<style>
  /* Short Page Hero */
  .page-hero-short {
    min-height: 350px;
  }

  .page-hero-short .hero-content h1 {
    font-size: clamp(32px, 5vw, 48px);
  }

  /* Service Selection Grid */
  .service-selection-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 28px;
  }

  .service-select-card {
    background: #ffffff;
    border-radius: 20px;
    padding: 36px 28px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    transition: all 0.4s ease;
    border: 2px solid transparent;
    text-decoration: none;
    display: block;
    position: relative;
    overflow: hidden;
  }

  .service-select-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12);
    border-color: var(--primary);
  }

  .service-select-card.featured {
    border-color: var(--gold);
  }

  .featured-badge {
    position: absolute;
    top: 20px;
    right: -30px;
    background: var(--gold);
    color: var(--primary-dark);
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    padding: 6px 40px;
    transform: rotate(45deg);
  }

  .service-select-card .card-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: #ffffff;
    margin-bottom: 24px;
    transition: all 0.3s ease;
  }

  .service-select-card:hover .card-icon {
    transform: scale(1.1);
  }

  .service-select-card:nth-child(2) .card-icon,
  .service-select-card:nth-child(4) .card-icon,
  .service-select-card:nth-child(6) .card-icon {
    background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 100%);
    color: var(--primary-dark);
  }

  .service-select-card h3 {
    font-size: 22px;
    font-weight: 700;
    color: var(--primary-dark);
    margin-bottom: 12px;
  }

  .service-select-card p {
    color: var(--text-secondary);
    line-height: 1.7;
    margin-bottom: 20px;
    font-size: 15px;
  }

  .card-features {
    list-style: none;
    padding: 0;
    margin: 0 0 24px;
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  .card-features li {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    color: var(--text-secondary);
  }

  .card-features li i {
    color: var(--gold);
    font-size: 12px;
  }

  .card-cta {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--primary);
    font-weight: 600;
    font-size: 15px;
    transition: all 0.3s ease;
  }

  .service-select-card:hover .card-cta {
    color: var(--gold);
    gap: 12px;
  }

  /* Account Options */
  .account-options {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 28px;
  }

  .account-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 40px 32px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
    border: 1px solid rgba(0, 0, 0, 0.04);
    transition: all 0.3s ease;
  }

  .account-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
  }

  .account-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: #ffffff;
    margin: 0 auto 24px;
  }

  .account-icon.gold {
    background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 100%);
    color: var(--primary-dark);
  }

  .account-card h3 {
    font-size: 20px;
    font-weight: 700;
    color: var(--primary-dark);
    margin-bottom: 12px;
  }

  .account-card p {
    color: var(--text-secondary);
    line-height: 1.7;
    margin-bottom: 24px;
    font-size: 15px;
  }

  /* Referral Section */
  .referral-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
  }

  .referral-content p {
    color: var(--text-secondary);
    line-height: 1.8;
    margin-bottom: 28px;
  }

  .referral-image {
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
  }

  .referral-image img {
    width: 100%;
    height: 400px;
    object-fit: cover;
    display: block;
  }

  /* Primary Section */
  .section-primary {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 50%, var(--primary-dark) 100%);
    position: relative;
    overflow: hidden;
  }

  .section-primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('images/pattern/pattern.png') repeat;
    opacity: 0.05;
  }

  .help-banner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 40px;
    position: relative;
    z-index: 1;
  }

  .help-content h2 {
    font-size: clamp(24px, 3vw, 32px);
    font-weight: 800;
    color: #ffffff;
    margin-bottom: 12px;
  }

  .help-content p {
    color: rgba(255, 255, 255, 0.9);
    font-size: 17px;
    max-width: 500px;
  }

  .help-actions {
    display: flex;
    gap: 16px;
    flex-shrink: 0;
  }

  .btn-outline-white {
    background: transparent;
    color: #ffffff;
    border: 2px solid #ffffff;
  }

  .btn-outline-white:hover {
    background: #ffffff;
    color: var(--primary);
  }

  /* Responsive */
  @media (max-width: 1200px) {
    .service-selection-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }

  @media (max-width: 991px) {
    .account-options {
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
    }

    .referral-section {
      grid-template-columns: 1fr;
      gap: 40px;
    }

    .referral-image {
      order: -1;
    }

    .referral-image img {
      height: 300px;
    }

    .help-banner {
      flex-direction: column;
      text-align: center;
    }

    .help-content p {
      max-width: none;
    }
  }

  @media (max-width: 768px) {
    .service-selection-grid {
      grid-template-columns: 1fr;
    }

    .account-options {
      grid-template-columns: 1fr;
    }

    .account-card {
      padding: 32px 24px;
    }

    .help-actions {
      flex-direction: column;
      width: 100%;
      max-width: 280px;
    }

    .help-actions .btn {
      width: 100%;
    }
  }

  @media (max-width: 576px) {
    .service-select-card {
      padding: 28px 24px;
    }

    .featured-badge {
      right: -35px;
      padding: 4px 40px;
      font-size: 10px;
    }
  }
</style>

<?php include_once "partials/footer.php"; ?>