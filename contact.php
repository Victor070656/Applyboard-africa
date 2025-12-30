<?php
$pageTitle = "Contact Us";
$pageDescription = "Get in touch with ApplyBoard Africa Ltd. Contact us for visa consultation, immigration services, and travel assistance.";
include_once "config/config.php";
include_once "partials/header.php";
?>

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

<style>
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

  /* Responsive */
  @media (max-width: 1024px) {
    .contact-grid {
      grid-template-columns: 1fr;
    }

    .locations-grid {
      grid-template-columns: 1fr;
    }
  }

  @media (max-width: 768px) {
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

    .form-row {
      grid-template-columns: 1fr;
    }
  }
</style>

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

          // Resolve Agent from URL parameter or cookie
          $agent_id = 'NULL';
          $ref_code = isset($_GET['ref']) ? $_GET['ref'] : (isset($_COOKIE['sdtravels_ref']) ? $_COOKIE['sdtravels_ref'] : '');

          if ($ref_code) {
            $ref_code = mysqli_real_escape_string($conn, $ref_code);
            $agent_check = mysqli_query($conn, "SELECT `id` FROM `agents` WHERE `agent_code` = '$ref_code' AND `status` = 'verified'");
            if ($agent_check && mysqli_num_rows($agent_check) > 0) {
              $agent_id = mysqli_fetch_assoc($agent_check)['id'];
            }
          }

          $full_message = "Subject: $subject\n\n$message";

          $sql = "INSERT INTO `inquiries` (`name`, `email`, `phone`, `message`, `agent_id`) VALUES ('$name', '$email', '$phone', '$full_message', $agent_id)";

          if (mysqli_query($conn, $sql)) {
            // If inquiry is linked to an agent, notify them
            if ($agent_id != 'NULL') {
              $inquiry_id = mysqli_insert_id($conn);
              mysqli_query($conn, "INSERT INTO `notifications` (`user_id`, `user_type`, `type`, `title`, `message`) 
                                        VALUES ($agent_id, 'agent', 'inquiry_received', 'New Inquiry Received', 'A new inquiry from $name has been submitted via your referral.')");
            }

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
            <textarea name="form_message" class="form-control" rows="6" placeholder="How can we help you?"
              required></textarea>
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
          <p style="margin-bottom: 8px;"><strong>Secretary:</strong><br /><a href="tel:+2349069503394">+234 906 9503
              394</a></p>
          <p style="margin-bottom: 8px;"><strong>Manager:</strong><br /><a href="tel:+2349023297280">+234 902 3297
              280</a></p>
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
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.0228649087003!2d3.872499073870538!3d7.35132861299248!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x10398d001d271eb3%3A0x81ed092bffc4ca30!2sSmile%20Dove%20Nigeria%20Limited!5e0!3m2!1sen!2sng!4v1744215177663!5m2!1sen!2sng"
        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
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

<?php include_once "partials/footer.php"; ?>

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
</body>

</html>