  <!-- Font Awesome for footer icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

  <style>
    /* ============================================
       FOOTER STYLES
       ============================================ */
    .modern-footer {
      background: #0F172A;
      color: #FFFFFF;
      padding-top: 60px;
    }

    .modern-footer .footer-top {
      max-width: 1400px;
      margin: 0 auto;
      padding: 0 24px 40px;
      display: grid;
      grid-template-columns: 2fr 1fr 1fr 1fr;
      gap: 40px;
    }

    .modern-footer .footer-brand .logo {
      margin-bottom: 16px;
      display: inline-block;
    }

    .modern-footer .footer-brand .logo img {
      height: 45px;
    }

    .modern-footer .footer-brand p {
      color: rgba(255, 255, 255, 0.7);
      line-height: 1.7;
      margin-bottom: 20px;
      font-size: 14px;
    }

    .modern-footer .social-links {
      display: flex;
      gap: 10px;
    }

    .modern-footer .social-links a {
      width: 40px;
      height: 40px;
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

    .modern-footer .newsletter-box {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 12px;
      padding: 20px;
      margin-top: 20px;
    }

    .modern-footer .newsletter-box h5 {
      font-size: 15px;
      font-weight: 600;
      margin-bottom: 12px;
    }

    .modern-footer .newsletter-form {
      display: flex;
      gap: 8px;
    }

    .modern-footer .newsletter-form input {
      flex: 1;
      padding: 10px 14px;
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 8px;
      color: #FFFFFF;
      font-size: 14px;
    }

    .modern-footer .newsletter-form input::placeholder {
      color: rgba(255, 255, 255, 0.5);
    }

    .modern-footer .newsletter-form button {
      padding: 10px 20px;
      background: #D4A853;
      color: #0F172A;
      border: none;
      border-radius: 8px;
      font-weight: 600;
      font-size: 14px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .modern-footer .newsletter-form button:hover {
      background: #E8C97A;
    }

    .modern-footer .footer-column h4 {
      font-size: 16px;
      font-weight: 700;
      margin-bottom: 20px;
    }

    .modern-footer .footer-links {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .modern-footer .footer-links a {
      color: rgba(255, 255, 255, 0.7);
      text-decoration: none;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 14px;
    }

    .modern-footer .footer-links a:hover {
      color: #D4A853;
      transform: translateX(4px);
    }

    .modern-footer .footer-contact-item {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      margin-bottom: 16px;
    }

    .modern-footer .footer-contact-item i {
      color: #D4A853;
      font-size: 16px;
      margin-top: 2px;
    }

    .modern-footer .footer-contact-item p {
      color: rgba(255, 255, 255, 0.7);
      margin: 0;
      font-size: 14px;
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
      padding: 20px 24px;
      text-align: center;
    }

    .modern-footer .footer-bottom p {
      color: rgba(255, 255, 255, 0.5);
      margin: 0;
      font-size: 14px;
    }

    .modern-footer .footer-bottom a {
      color: #D4A853;
      text-decoration: none;
    }

    /* Scroll To Top */
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
      font-size: 18px;
      cursor: pointer;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
      z-index: 999;
    }

    .scroll-top.visible {
      opacity: 1;
      visibility: visible;
    }

    .scroll-top:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
    }

    /* Responsive Footer */
    @media (max-width: 1024px) {
      .modern-footer .footer-top {
        grid-template-columns: 1fr 1fr;
        gap: 32px;
      }
    }

    @media (max-width: 768px) {
      .modern-footer .footer-top {
        grid-template-columns: 1fr;
      }

      .scroll-top {
        bottom: 20px;
        right: 20px;
        width: 44px;
        height: 44px;
      }
    }
  </style>

  <!-- Modern Footer -->
  <footer class="modern-footer">
    <div class="footer-top">
      <div class="footer-brand">
        <a href="./" class="logo">
          <img src="images/logo-2.png" alt="ApplyBoard Africa Ltd" />
        </a>
        <p>ApplyBoard Africa Ltd is your trusted partner for hassle-free visa processing, study abroad consultation, and immigration services. We help you achieve your global dreams.</p>
        <div class="social-links">
          <a href="https://facebook.com" target="_blank"><i class="fab fa-facebook-f"></i></a>
          <a href="https://twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
          <a href="https://instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
          <a href="https://linkedin.com" target="_blank"><i class="fab fa-linkedin-in"></i></a>
        </div>

        <div class="newsletter-box">
          <h5><i class="fas fa-envelope"></i> Newsletter</h5>
          <form method="post" class="newsletter-form">
            <input type="email" name="email" placeholder="Your email" required />
            <button type="submit" name="subscribe">
              <i class="fas fa-paper-plane"></i>
            </button>
          </form>
          <?php
          if (isset($_POST["subscribe"])) {
              $email = mysqli_real_escape_string($conn, $_POST["email"]);
              $insert = mysqli_query($conn, "INSERT INTO `newsletters` (`email`) VALUES ('$email')");
              if ($insert) {
                  echo "<script>alert('Successfully subscribed!'); window.location.href='" . basename($_SERVER['PHP_SELF']) . "';</script>";
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
          <li><a href="services.php"><i class="fas fa-chevron-right"></i> Study Abroad</a></li>
          <li><a href="services.php"><i class="fas fa-chevron-right"></i> Student Visa</a></li>
          <li><a href="services.php"><i class="fas fa-chevron-right"></i> Tourist Visa</a></li>
          <li><a href="services.php"><i class="fas fa-chevron-right"></i> Flight Booking</a></li>
          <li><a href="services.php"><i class="fas fa-chevron-right"></i> Agent Program</a></li>
          <li><a href="services.php"><i class="fas fa-chevron-right"></i> Tracking</a></li>
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
          <p>Mon - Sat: 8AM - 6:30PM</p>
        </div>
      </div>
    </div>

    <div class="footer-bottom">
      <p>&copy; <?= date('Y') ?> <a href="./">ApplyBoard Africa Ltd</a>. All Rights Reserved.</p>
    </div>
  </footer>

  <!-- Scroll To Top -->
  <button class="scroll-top" id="scrollTop" onclick="scrollToTop()">
    <i class="fas fa-arrow-up"></i>
  </button>

  <script>
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
  </script>
