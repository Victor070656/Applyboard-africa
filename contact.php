<?php
$pageTitle = "Contact Us";
$pageDescription = "Get in touch with SD Travels for visa processing, study abroad consultation, and immigration services. We're here to help.";
include_once "config/config.php";

$successMessage = '';
$errorMessage = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_submit'])) {
  $name = sanitize($_POST['name'] ?? '');
  $email = sanitize($_POST['email'] ?? '');
  $phone = sanitize($_POST['phone'] ?? '');
  $subject = sanitize($_POST['subject'] ?? '');
  $message = sanitize($_POST['message'] ?? '');
  $service = sanitize($_POST['service'] ?? '');

  // Convert empty service to NULL for ENUM column
  $service = !empty($service) ? $service : null;

  // Basic validation
  if (empty($name) || empty($email) || empty($message)) {
    $errorMessage = 'Please fill in all required fields.';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errorMessage = 'Please enter a valid email address.';
  } else {
    // Store inquiry in database
    $stmt = $conn->prepare("INSERT INTO inquiries (name, email, phone, service_type, message, status, created_at) VALUES (?, ?, ?, ?, ?, 'new', NOW())");
    $stmt->bind_param("sssss", $name, $email, $phone, $service, $message);

    if ($stmt->execute()) {
      $successMessage = 'Thank you for your message! Our team will get back to you within 24 hours.';

      // Send notification email (if configured)
      // You can add email functionality here
    } else {
      $errorMessage = 'Sorry, there was an error sending your message. Please try again.';
    }
    $stmt->close();
  }
}

include_once "partials/header.php";
?>

<!-- Page Hero -->
<section class="page-hero">
  <div class="hero-bg">
    <img src="images/main-slider/3.jpg" alt="Contact SD Travels" />
  </div>
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <p class="hero-subtitle">Get In Touch</p>
    <h1>Contact Us</h1>
    <p>Have questions? We're here to help you with all your travel and immigration needs.</p>
    <div class="breadcrumb">
      <a href="./">Home</a>
      <span>/</span>
      <span>Contact</span>
    </div>
  </div>
</section>

<!-- Contact Info Cards -->
<section class="section section-white contact-info-section">
  <div class="container">
    <div class="contact-info-grid">
      <div class="contact-info-card">
        <div class="info-icon">
          <i class="fas fa-map-marker-alt"></i>
        </div>
        <h4>Visit Our Office</h4>
        <p>123 Business District<br>Lagos, Nigeria</p>
      </div>

      <div class="contact-info-card">
        <div class="info-icon gold">
          <i class="fas fa-phone-alt"></i>
        </div>
        <h4>Call Us</h4>
        <p>+234 801 234 5678<br>+234 901 234 5678</p>
      </div>

      <div class="contact-info-card">
        <div class="info-icon">
          <i class="fas fa-envelope"></i>
        </div>
        <h4>Email Us</h4>
        <p>info@sdtravels.com<br>support@sdtravels.com</p>
      </div>

      <div class="contact-info-card">
        <div class="info-icon gold">
          <i class="fas fa-clock"></i>
        </div>
        <h4>Working Hours</h4>
        <p>Mon - Fri: 9:00 AM - 6:00 PM<br>Saturday: 10:00 AM - 4:00 PM</p>
      </div>
    </div>
  </div>
</section>

<!-- Contact Form Section -->
<section class="section section-light">
  <div class="container">
    <div class="contact-wrapper">
      <div class="contact-form-section">
        <div class="section-header">
          <p class="section-subtitle">Send Us a Message</p>
          <h2 class="section-title">We'd Love to Hear From You</h2>
          <p class="section-description">
            Fill out the form below and our team will get back to you within 24 hours.
          </p>
        </div>

        <?php if ($successMessage): ?>
          <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?php echo $successMessage; ?>
          </div>
        <?php endif; ?>

        <?php if ($errorMessage): ?>
          <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo $errorMessage; ?>
          </div>
        <?php endif; ?>

        <form method="POST" action="" class="contact-form">
          <div class="form-row">
            <div class="form-group">
              <label for="name">Full Name <span class="required">*</span></label>
              <input type="text" id="name" name="name" placeholder="Enter your full name" required
                value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
            </div>

            <div class="form-group">
              <label for="email">Email Address <span class="required">*</span></label>
              <input type="email" id="email" name="email" placeholder="Enter your email" required
                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="phone">Phone Number</label>
              <input type="tel" id="phone" name="phone" placeholder="Enter your phone number"
                value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
            </div>

            <div class="form-group">
              <label for="service">Service Interested In</label>
              <select id="service" name="service">
                <option value="">Select a service</option>
                <option value="study_abroad" <?php echo (isset($_POST['service']) && $_POST['service'] == 'study_abroad') ? 'selected' : ''; ?>>Study Abroad</option>
                <option value="visa_student" <?php echo (isset($_POST['service']) && $_POST['service'] == 'visa_student') ? 'selected' : ''; ?>>Student Visa</option>
                <option value="visa_tourist" <?php echo (isset($_POST['service']) && $_POST['service'] == 'visa_tourist') ? 'selected' : ''; ?>>Tourist Visa</option>
                <option value="visa_family" <?php echo (isset($_POST['service']) && $_POST['service'] == 'visa_family') ? 'selected' : ''; ?>>Family Visa</option>
                <option value="travel_booking" <?php echo (isset($_POST['service']) && $_POST['service'] == 'travel_booking') ? 'selected' : ''; ?>>Travel Booking</option>
                <option value="pilgrimage" <?php echo (isset($_POST['service']) && $_POST['service'] == 'pilgrimage') ? 'selected' : ''; ?>>Hajj & Umrah</option>
                <option value="other" <?php echo (isset($_POST['service']) && $_POST['service'] == 'other') ? 'selected' : ''; ?>>Other</option>
              </select>
            </div>
          </div>

          <div class="form-group full-width">
            <label for="message">Your Message <span class="required">*</span></label>
            <textarea id="message" name="message" rows="6" placeholder="Tell us how we can help you..."
              required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
          </div>

          <button type="submit" name="contact_submit" class="btn btn-primary btn-lg">
            <i class="fas fa-paper-plane"></i> Send Message
          </button>
        </form>
      </div>

      <div class="contact-sidebar">
        <div class="sidebar-card">
          <h4><i class="fas fa-headset"></i> Need Immediate Help?</h4>
          <p>Our customer support team is available to assist you with urgent inquiries.</p>
          <a href="tel:+2348012345678" class="btn btn-gold btn-block">
            <i class="fas fa-phone"></i> Call Now
          </a>
        </div>

        <div class="sidebar-card">
          <h4><i class="fab fa-whatsapp"></i> Chat on WhatsApp</h4>
          <p>Get instant responses to your questions via WhatsApp.</p>
          <a href="https://wa.me/2348012345678" target="_blank" class="btn btn-success btn-block">
            <i class="fab fa-whatsapp"></i> Start Chat
          </a>
        </div>

        <div class="sidebar-card social-card">
          <h4>Follow Us</h4>
          <p>Stay updated with our latest news and travel tips.</p>
          <div class="social-links-large">
            <a href="#" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="#" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="#" target="_blank" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
            <a href="#" target="_blank" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Map Section -->
<section class="map-section">
  <div class="map-container">
    <iframe
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d253682.63451968866!2d3.1191421!3d6.5483!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x103b8b2ae68280c1%3A0xdc9e87a367c3d9cb!2sLagos%2C%20Nigeria!5e0!3m2!1sen!2sus!4v1699999999999!5m2!1sen!2sus"
      width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
      referrerpolicy="no-referrer-when-downgrade">
    </iframe>
  </div>
</section>

<!-- Quick Links CTA -->
<section class="section section-white">
  <div class="container">
    <div class="quick-links-grid">
      <div class="quick-link-card">
        <div class="ql-icon">
          <i class="fas fa-question-circle"></i>
        </div>
        <h4>FAQs</h4>
        <p>Find answers to frequently asked questions about our services.</p>
        <a href="services.php#faq" class="link-arrow">View FAQs <i class="fas fa-arrow-right"></i></a>
      </div>

      <div class="quick-link-card">
        <div class="ql-icon">
          <i class="fas fa-file-alt"></i>
        </div>
        <h4>Apply Online</h4>
        <p>Start your visa or immigration application online today.</p>
        <a href="platform.php" class="link-arrow">Start Application <i class="fas fa-arrow-right"></i></a>
      </div>

      <div class="quick-link-card">
        <div class="ql-icon">
          <i class="fas fa-user-tie"></i>
        </div>
        <h4>Become an Agent</h4>
        <p>Join our network and earn commissions on referrals.</p>
        <a href="agents.php" class="link-arrow">Learn More <i class="fas fa-arrow-right"></i></a>
      </div>
    </div>
  </div>
</section>

<style>
  /* Contact Info Section */
  .contact-info-section {
    margin-top: -80px;
    position: relative;
    z-index: 10;
    padding-bottom: 0;
  }

  .contact-info-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
  }

  .contact-info-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 32px 24px;
    text-align: center;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border: 1px solid rgba(0, 0, 0, 0.04);
  }

  .contact-info-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12);
  }

  .info-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: #ffffff;
    margin: 0 auto 20px;
  }

  .info-icon.gold {
    background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 100%);
    color: var(--primary-dark);
  }

  .contact-info-card h4 {
    font-size: 18px;
    font-weight: 700;
    color: var(--primary-dark);
    margin-bottom: 12px;
  }

  .contact-info-card p {
    color: var(--text-secondary);
    line-height: 1.7;
    margin: 0;
    font-size: 15px;
  }

  /* Contact Wrapper */
  .contact-wrapper {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 48px;
    align-items: start;
  }

  /* Contact Form */
  .contact-form-section .section-header {
    text-align: left;
    margin-bottom: 32px;
  }

  .contact-form {
    background: #ffffff;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
  }

  .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-bottom: 24px;
  }

  .form-group {
    display: flex;
    flex-direction: column;
  }

  .form-group.full-width {
    margin-bottom: 24px;
  }

  .form-group label {
    font-size: 14px;
    font-weight: 600;
    color: var(--primary-dark);
    margin-bottom: 8px;
  }

  .form-group label .required {
    color: #ef4444;
  }

  .form-group input,
  .form-group select,
  .form-group textarea {
    padding: 14px 18px;
    border: 2px solid #E2E8F0;
    border-radius: 10px;
    font-size: 15px;
    color: var(--text-primary);
    transition: all 0.3s ease;
    background: #ffffff;
  }

  .form-group input:focus,
  .form-group select:focus,
  .form-group textarea:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(15, 76, 117, 0.1);
  }

  .form-group input::placeholder,
  .form-group textarea::placeholder {
    color: #94A3B8;
  }

  .form-group select {
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%2364748B' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6,9 12,15 18,9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    background-size: 18px;
    padding-right: 48px;
  }

  .form-group textarea {
    resize: vertical;
    min-height: 140px;
  }

  /* Alert Messages */
  .alert {
    padding: 16px 20px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;
    font-weight: 500;
  }

  .alert i {
    font-size: 20px;
  }

  .alert-success {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
  }

  .alert-error {
    background: #fef2f2;
    color: #991b1b;
    border: 1px solid #fecaca;
  }

  /* Contact Sidebar */
  .contact-sidebar {
    display: flex;
    flex-direction: column;
    gap: 24px;
    position: sticky;
    top: 120px;
  }

  .sidebar-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 28px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    border: 1px solid rgba(0, 0, 0, 0.04);
  }

  .sidebar-card h4 {
    font-size: 17px;
    font-weight: 700;
    color: var(--primary-dark);
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .sidebar-card h4 i {
    color: var(--gold);
  }

  .sidebar-card p {
    color: var(--text-secondary);
    font-size: 14px;
    line-height: 1.7;
    margin-bottom: 16px;
  }

  .btn-block {
    width: 100%;
    text-align: center;
  }

  .btn-success {
    background: #25D366;
    color: #ffffff;
  }

  .btn-success:hover {
    background: #1fb855;
  }

  /* Social Links Large */
  .social-links-large {
    display: flex;
    gap: 12px;
  }

  .social-links-large a {
    width: 44px;
    height: 44px;
    background: var(--bg-light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    font-size: 18px;
    transition: all 0.3s ease;
  }

  .social-links-large a:hover {
    background: var(--primary);
    color: #ffffff;
    transform: translateY(-3px);
  }

  /* Map Section */
  .map-section {
    background: var(--bg-light);
  }

  .map-container {
    position: relative;
    overflow: hidden;
  }

  .map-container iframe {
    display: block;
    filter: grayscale(20%);
  }

  /* Quick Links Grid */
  .quick-links-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 32px;
  }

  .quick-link-card {
    text-align: center;
    padding: 40px 32px;
    background: var(--bg-light);
    border-radius: 16px;
    transition: all 0.3s ease;
  }

  .quick-link-card:hover {
    background: #ffffff;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
  }

  .ql-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: #ffffff;
    margin: 0 auto 20px;
  }

  .quick-link-card:nth-child(2) .ql-icon {
    background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 100%);
    color: var(--primary-dark);
  }

  .quick-link-card h4 {
    font-size: 20px;
    font-weight: 700;
    color: var(--primary-dark);
    margin-bottom: 12px;
  }

  .quick-link-card p {
    color: var(--text-secondary);
    line-height: 1.7;
    margin-bottom: 20px;
  }

  .link-arrow {
    color: var(--primary);
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
  }

  .link-arrow:hover {
    color: var(--gold);
    gap: 12px;
  }

  /* Responsive */
  @media (max-width: 1200px) {
    .contact-wrapper {
      grid-template-columns: 1fr 320px;
      gap: 32px;
    }
  }

  @media (max-width: 991px) {
    .contact-info-grid {
      grid-template-columns: repeat(2, 1fr);
    }

    .contact-wrapper {
      grid-template-columns: 1fr;
    }

    .contact-sidebar {
      position: static;
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
    }

    .quick-links-grid {
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
    }
  }

  @media (max-width: 768px) {
    .contact-info-section {
      margin-top: -60px;
    }

    .contact-info-grid {
      grid-template-columns: 1fr;
      gap: 16px;
    }

    .contact-info-card {
      display: flex;
      align-items: center;
      text-align: left;
      gap: 20px;
      padding: 24px;
    }

    .info-icon {
      margin: 0;
      flex-shrink: 0;
    }

    .form-row {
      grid-template-columns: 1fr;
      gap: 20px;
    }

    .contact-form {
      padding: 28px;
    }

    .contact-sidebar {
      grid-template-columns: 1fr;
    }

    .quick-links-grid {
      grid-template-columns: 1fr;
      gap: 16px;
    }

    .quick-link-card {
      padding: 28px 24px;
    }
  }
</style>

<?php include_once "partials/footer.php"; ?>