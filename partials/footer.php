<!-- Footer -->
<footer class="site-footer">
  <div class="container">
    <div class="footer-grid">
      <!-- Brand Column -->
      <div class="footer-brand">
        <a href="./" class="site-logo">
          <img src="images/logo-2.png" alt="ApplyBoard Africa" style="height: 48px; border-radius: 5px;" />
        </a>
        <p>ApplyBoard Africa Ltd is your trusted partner for seamless visa processing, study abroad consultation, and
          immigration services. Let us help you achieve your global dreams.</p>
        <div class="footer-social">
          <a href="https://facebook.com/applyboardafrica" target="_blank" aria-label="Facebook">
            <i class="fab fa-facebook-f"></i>
          </a>
          <a href="https://twitter.com/applyboardafrica" target="_blank" aria-label="Twitter">
            <i class="fab fa-twitter"></i>
          </a>
          <a href="https://instagram.com/applyboardafrica" target="_blank" aria-label="Instagram">
            <i class="fab fa-instagram"></i>
          </a>
          <a href="https://linkedin.com/company/applyboardafrica" target="_blank" aria-label="LinkedIn">
            <i class="fab fa-linkedin-in"></i>
          </a>
          <a href="https://wa.me/2347063459820" target="_blank" aria-label="WhatsApp">
            <i class="fab fa-whatsapp"></i>
          </a>
        </div>
      </div>

      <!-- Quick Links -->
      <div class="footer-column">
        <h4>Quick Links</h4>
        <nav class="footer-links">
          <a href="./">Home</a>
          <a href="about.php">About Us</a>
          <a href="services.php">Our Services</a>
          <a href="agents.php">Become an Agent</a>
          <a href="contact.php">Contact Us</a>
        </nav>
      </div>

      <!-- Services -->
      <div class="footer-column">
        <h4>Our Services</h4>
        <nav class="footer-links">
          <a href="services.php#study-abroad">Study Abroad</a>
          <a href="services.php#visa-processing">Visa Processing</a>
          <a href="services.php#travel-booking">Travel Booking</a>
          <a href="services.php#pilgrimage">Pilgrimage Tours</a>
          <a href="services.php#ielts">IELTS Preparation</a>
        </nav>
      </div>

      <!-- Contact Info -->
      <div class="footer-column">
        <h4>Contact Us</h4>
        <div class="footer-contact-item">
          <i class="fas fa-map-marker-alt"></i>
          <span>13 Akinwunmi Street,<br />Egbe, Lagos</span>
        </div>
        <div class="footer-contact-item">
          <i class="fas fa-phone-alt"></i>
          <span>+234 706 345 9820</span>
        </div>
        <div class="footer-contact-item">
          <i class="fas fa-envelope"></i>
          <span>info@applyboardafrica.com<br />support@applyboardafrica.com</span>
        </div>
        <div class="footer-contact-item">
          <i class="fas fa-clock"></i>
          <span>Mon - Fri: 9:00 AM - 6:00 PM<br />Sat: 10:00 AM - 4:00 PM</span>
        </div>
      </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
      <p class="footer-copyright">
        &copy; <?= date('Y') ?> ApplyBoard Africa Ltd. All rights reserved.
      </p>
      <div class="footer-legal">
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Service</a>
        <a href="#">Cookie Policy</a>
      </div>
    </div>
  </div>
</footer>

<!-- Scroll to Top Button -->
<button class="scroll-to-top" id="scrollToTop" aria-label="Scroll to top">
  <i class="fas fa-chevron-up"></i>
</button>

<a href="tel:+2342013306393" class="pressone-call-btn">
  <img src="images/phone.png" alt="">
</a>

<style>
  .pressone-call-btn {
    position: fixed;
    bottom: 100px;
    right: 30px;
    z-index: 100;
  }

  .pressone-call-btn img {
    width: 50px;
    height: 50px;
    border-radius: 5px;
    filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
  }

  .scroll-to-top {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, var(--primary-700), var(--primary-500));
    color: var(--white);
    border: none;
    border-radius: var(--radius-full);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    cursor: pointer;
    opacity: 0;
    visibility: hidden;
    transform: translateY(20px);
    transition: var(--transition-base);
    box-shadow: 0 4px 20px rgba(15, 76, 117, 0.4);
    z-index: 99;
  }

  .scroll-to-top.visible {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
  }

  .scroll-to-top:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(15, 76, 117, 0.5);
  }
</style>

<script>
  // Scroll to Top Button
  const scrollToTopBtn = document.getElementById('scrollToTop');

  window.addEventListener('scroll', () => {
    if (window.scrollY > 500) {
      scrollToTopBtn.classList.add('visible');
    } else {
      scrollToTopBtn.classList.remove('visible');
    }
  });

  scrollToTopBtn.addEventListener('click', () => {
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });
</script>
</body>

</html>