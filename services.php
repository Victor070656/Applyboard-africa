<?php
$pageTitle = "Our Services";
$pageDescription = "Explore our comprehensive visa processing, study abroad consultation, IELTS coaching, and immigration services.";
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

<style>
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

  /* Responsive */
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
  }
</style>

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
<section class="modern-section bg-light">
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
<section class="modern-section bg-light">
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

<?php include_once "partials/footer.php"; ?>
</body>
</html>
