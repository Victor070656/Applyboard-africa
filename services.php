<?php
$pageTitle = "Our Services";
$pageDescription = "Explore our comprehensive visa processing, study abroad consultation, IELTS coaching, and immigration services at SD Travels.";
include_once "config/config.php";
include_once "partials/header.php";
?>

<!-- Page Hero -->
<section class="page-hero">
  <div class="hero-bg">
    <img src="images/main-slider/2.jpg" alt="SD Travels Services" />
  </div>
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <p class="hero-subtitle">What We Offer</p>
    <h1>Our Services</h1>
    <p>Comprehensive immigration and travel services designed to make your global journey seamless.</p>
    <div class="breadcrumb">
      <a href="./">Home</a>
      <span>/</span>
      <span>Services</span>
    </div>
  </div>
</section>

<!-- Services Overview -->
<section class="section section-white">
  <div class="container">
    <div class="section-header text-center">
      <p class="section-subtitle">Expert Solutions</p>
      <h2 class="section-title">Services Tailored to Your Needs</h2>
      <p class="section-description">
        From visa applications to study abroad programs, we provide end-to-end support for all your international travel and immigration requirements.
      </p>
    </div>
    
    <div class="services-detail-grid">
      <!-- Visa Processing -->
      <div class="service-detail-card">
        <div class="card-icon">
          <i class="fas fa-passport"></i>
        </div>
        <h3>Visa Processing</h3>
        <p>
          Our expert team handles all types of visa applications with precision and care, ensuring the highest approval rates for our clients.
        </p>
        <ul class="features-list">
          <li><i class="fas fa-check"></i> Tourist & Visitor Visas</li>
          <li><i class="fas fa-check"></i> Business & Work Visas</li>
          <li><i class="fas fa-check"></i> Student Visas (F1, M1)</li>
          <li><i class="fas fa-check"></i> Family & Spouse Visas</li>
          <li><i class="fas fa-check"></i> Transit & Schengen Visas</li>
          <li><i class="fas fa-check"></i> Document Preparation & Review</li>
        </ul>
        <a href="platform.php" class="btn btn-primary btn-sm" style="margin-top: 24px;">Apply Now</a>
      </div>
      
      <!-- Study Abroad -->
      <div class="service-detail-card">
        <div class="card-icon gold">
          <i class="fas fa-graduation-cap"></i>
        </div>
        <h3>Study Abroad</h3>
        <p>
          Unlock world-class education opportunities with our comprehensive study abroad consultation and application support services.
        </p>
        <ul class="features-list">
          <li><i class="fas fa-check"></i> University Selection & Matching</li>
          <li><i class="fas fa-check"></i> Admission Application Support</li>
          <li><i class="fas fa-check"></i> Scholarship Guidance</li>
          <li><i class="fas fa-check"></i> Student Visa Processing</li>
          <li><i class="fas fa-check"></i> Pre-Departure Orientation</li>
          <li><i class="fas fa-check"></i> Accommodation Assistance</li>
        </ul>
        <a href="platform.php" class="btn btn-outline btn-sm" style="margin-top: 24px;">Learn More</a>
      </div>
      
      <!-- IELTS Coaching -->
      <div class="service-detail-card">
        <div class="card-icon">
          <i class="fas fa-language"></i>
        </div>
        <h3>IELTS Coaching</h3>
        <p>
          Achieve your target band score with our proven IELTS preparation program led by certified instructors and personalized study plans.
        </p>
        <ul class="features-list">
          <li><i class="fas fa-check"></i> Expert Certified Trainers</li>
          <li><i class="fas fa-check"></i> Practice Tests & Mock Exams</li>
          <li><i class="fas fa-check"></i> Speaking & Writing Focus</li>
          <li><i class="fas fa-check"></i> Flexible Class Schedules</li>
          <li><i class="fas fa-check"></i> Study Materials Provided</li>
          <li><i class="fas fa-check"></i> One-on-One Coaching Available</li>
        </ul>
        <a href="contact.php" class="btn btn-primary btn-sm" style="margin-top: 24px;">Enroll Now</a>
      </div>
      
      <!-- Immigration Services -->
      <div class="service-detail-card">
        <div class="card-icon gold">
          <i class="fas fa-globe-americas"></i>
        </div>
        <h3>Immigration Services</h3>
        <p>
          Navigate complex immigration processes with confidence. Our specialists guide you through every step of your relocation journey.
        </p>
        <ul class="features-list">
          <li><i class="fas fa-check"></i> Permanent Residency Applications</li>
          <li><i class="fas fa-check"></i> Citizenship & Naturalization</li>
          <li><i class="fas fa-check"></i> Work Permit Processing</li>
          <li><i class="fas fa-check"></i> Family Reunification</li>
          <li><i class="fas fa-check"></i> Immigration Consultation</li>
          <li><i class="fas fa-check"></i> Appeal & Review Services</li>
        </ul>
        <a href="contact.php" class="btn btn-outline btn-sm" style="margin-top: 24px;">Get Consultation</a>
      </div>
      
      <!-- Hajj & Umrah -->
      <div class="service-detail-card">
        <div class="card-icon">
          <i class="fas fa-kaaba"></i>
        </div>
        <h3>Hajj & Umrah Packages</h3>
        <p>
          Embark on your spiritual journey with our carefully curated Hajj and Umrah packages designed for a blessed and comfortable experience.
        </p>
        <ul class="features-list">
          <li><i class="fas fa-check"></i> Premium & Economy Packages</li>
          <li><i class="fas fa-check"></i> Hotel Near Haram</li>
          <li><i class="fas fa-check"></i> Visa Processing Included</li>
          <li><i class="fas fa-check"></i> Experienced Tour Guides</li>
          <li><i class="fas fa-check"></i> Ground Transportation</li>
          <li><i class="fas fa-check"></i> Group & Private Tours</li>
        </ul>
        <a href="platform.php" class="btn btn-primary btn-sm" style="margin-top: 24px;">View Packages</a>
      </div>
      
      <!-- Flight Booking -->
      <div class="service-detail-card">
        <div class="card-icon gold">
          <i class="fas fa-plane"></i>
        </div>
        <h3>Flight Booking</h3>
        <p>
          Get the best deals on domestic and international flights with our extensive airline partnerships and expert booking assistance.
        </p>
        <ul class="features-list">
          <li><i class="fas fa-check"></i> Competitive Airfare Prices</li>
          <li><i class="fas fa-check"></i> Multiple Airline Options</li>
          <li><i class="fas fa-check"></i> Group Booking Discounts</li>
          <li><i class="fas fa-check"></i> Last-Minute Bookings</li>
          <li><i class="fas fa-check"></i> Flight Change Assistance</li>
          <li><i class="fas fa-check"></i> 24/7 Travel Support</li>
        </ul>
        <a href="contact.php" class="btn btn-outline btn-sm" style="margin-top: 24px;">Book Flight</a>
      </div>
    </div>
  </div>
</section>

<!-- How It Works -->
<section class="section section-light">
  <div class="container">
    <div class="section-header text-center">
      <p class="section-subtitle">Simple Process</p>
      <h2 class="section-title">How It Works</h2>
      <p class="section-description">
        Our streamlined process makes it easy to get started on your journey. Here's how we help you every step of the way.
      </p>
    </div>
    
    <div class="process-timeline">
      <div class="process-step">
        <div class="step-number">1</div>
        <h4>Free Consultation</h4>
        <p>Schedule a free consultation to discuss your travel goals, requirements, and eligibility with our expert advisors.</p>
      </div>
      
      <div class="process-step">
        <div class="step-number">2</div>
        <h4>Document Collection</h4>
        <p>We provide a comprehensive checklist and assist you in gathering and organizing all required documents.</p>
      </div>
      
      <div class="process-step">
        <div class="step-number">3</div>
        <h4>Application Processing</h4>
        <p>Our team meticulously prepares and submits your application, ensuring accuracy and completeness.</p>
      </div>
      
      <div class="process-step">
        <div class="step-number">4</div>
        <h4>Success & Support</h4>
        <p>Receive your approval and continued support for your journey. We're with you from start to destination.</p>
      </div>
    </div>
  </div>
</section>

<!-- Destinations Section -->
<section class="section section-white">
  <div class="container">
    <div class="section-header text-center">
      <p class="section-subtitle">Popular Destinations</p>
      <h2 class="section-title">Countries We Serve</h2>
      <p class="section-description">
        We process visa applications for over 50 countries worldwide. Here are some of our most popular destinations.
      </p>
    </div>
    
    <div class="destinations-grid">
      <div class="destination-card">
        <div class="destination-flag">🇺🇸</div>
        <h4>United States</h4>
        <p>Tourist, Business, Student Visas</p>
      </div>
      
      <div class="destination-card">
        <div class="destination-flag">🇬🇧</div>
        <h4>United Kingdom</h4>
        <p>Visitor, Work, Study Visas</p>
      </div>
      
      <div class="destination-card">
        <div class="destination-flag">🇨🇦</div>
        <h4>Canada</h4>
        <p>Immigration, Study, Work Permits</p>
      </div>
      
      <div class="destination-card">
        <div class="destination-flag">🇦🇺</div>
        <h4>Australia</h4>
        <p>Skilled Migration, Student Visas</p>
      </div>
      
      <div class="destination-card">
        <div class="destination-flag">🇩🇪</div>
        <h4>Germany</h4>
        <p>Schengen, Job Seeker Visas</p>
      </div>
      
      <div class="destination-card">
        <div class="destination-flag">🇸🇦</div>
        <h4>Saudi Arabia</h4>
        <p>Hajj, Umrah, Work Visas</p>
      </div>
      
      <div class="destination-card">
        <div class="destination-flag">🇦🇪</div>
        <h4>UAE</h4>
        <p>Tourist, Business, Residence Visas</p>
      </div>
      
      <div class="destination-card">
        <div class="destination-flag">🇫🇷</div>
        <h4>France</h4>
        <p>Schengen, Long Stay Visas</p>
      </div>
    </div>
  </div>
</section>

<!-- FAQ Section -->
<section class="section section-light">
  <div class="container">
    <div class="section-header text-center">
      <p class="section-subtitle">Got Questions?</p>
      <h2 class="section-title">Frequently Asked Questions</h2>
      <p class="section-description">
        Find answers to common questions about our services and the application process.
      </p>
    </div>
    
    <div class="faq-container">
      <details class="faq-item">
        <summary>How long does visa processing take?</summary>
        <p>Processing times vary depending on the visa type and destination country. Tourist visas typically take 2-4 weeks, while work and immigration visas may take 2-6 months. Our team will provide you with accurate timelines based on your specific case.</p>
      </details>
      
      <details class="faq-item">
        <summary>What documents do I need for a visa application?</summary>
        <p>Required documents vary by visa type but generally include: valid passport, photographs, proof of funds, travel itinerary, accommodation details, and purpose of visit documentation. We provide a comprehensive checklist specific to your application.</p>
      </details>
      
      <details class="faq-item">
        <summary>Do you guarantee visa approval?</summary>
        <p>While we cannot guarantee approval as final decisions rest with embassies and consulates, we maintain an exceptionally high success rate through thorough preparation, accurate documentation, and expert guidance throughout the process.</p>
      </details>
      
      <details class="faq-item">
        <summary>What are your service fees?</summary>
        <p>Our fees vary based on the service type and complexity. We offer transparent pricing with no hidden charges. During your free consultation, we'll provide a detailed breakdown of all costs including our service fees and government charges.</p>
      </details>
      
      <details class="faq-item">
        <summary>Can you help if my visa was previously rejected?</summary>
        <p>Absolutely! We specialize in re-application cases. Our team will analyze your previous application, identify areas for improvement, and build a stronger case for your new application. Many of our clients have succeeded on their second attempt with our guidance.</p>
      </details>
      
      <details class="faq-item">
        <summary>Do you offer IELTS preparation for beginners?</summary>
        <p>Yes, our IELTS coaching program caters to all proficiency levels. Whether you're a beginner or looking to improve your score, our certified instructors will create a personalized study plan to help you achieve your target band score.</p>
      </details>
      
      <details class="faq-item">
        <summary>How can I track my application status?</summary>
        <p>Once you start an application with us, you'll receive access to our client portal where you can track your application status in real-time. You'll also receive email and SMS updates at every stage of the process.</p>
      </details>
    </div>
  </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
  <div class="container">
    <h2>Ready to Start Your Journey?</h2>
    <p>Get expert guidance and support for all your visa and immigration needs. Schedule a free consultation today.</p>
    <div class="cta-buttons">
      <a href="platform.php" class="btn btn-gold btn-lg">Apply Online</a>
      <a href="contact.php" class="btn btn-outline-white btn-lg">Contact Us</a>
    </div>
  </div>
</section>

<style>
/* Services Detail Grid */
.services-detail-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 32px;
}

.service-detail-card {
  background: #ffffff;
  border-radius: 20px;
  padding: 40px 32px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
  transition: all 0.4s ease;
  border: 1px solid rgba(0, 0, 0, 0.04);
}

.service-detail-card:hover {
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12);
  transform: translateY(-8px);
}

.service-detail-card .card-icon {
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
}

.service-detail-card .card-icon.gold {
  background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 100%);
  color: var(--primary-dark);
}

.service-detail-card h3 {
  font-size: 22px;
  font-weight: 700;
  color: var(--primary-dark);
  margin-bottom: 16px;
}

.service-detail-card p {
  color: var(--text-secondary);
  line-height: 1.8;
  margin-bottom: 20px;
}

.features-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.features-list li {
  display: flex;
  align-items: center;
  gap: 12px;
  color: var(--text-secondary);
  font-size: 15px;
}

.features-list li i {
  color: var(--gold);
  font-size: 12px;
  flex-shrink: 0;
}

/* Process Timeline */
.process-timeline {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 32px;
  position: relative;
}

.process-timeline::before {
  content: '';
  position: absolute;
  top: 30px;
  left: 15%;
  right: 15%;
  height: 3px;
  background: linear-gradient(90deg, var(--primary) 0%, var(--gold) 50%, var(--primary) 100%);
  z-index: 0;
}

.process-step {
  text-align: center;
  position: relative;
  z-index: 1;
}

.process-step .step-number {
  width: 60px;
  height: 60px;
  background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 22px;
  font-weight: 800;
  color: #ffffff;
  margin: 0 auto 20px;
  box-shadow: 0 8px 25px rgba(15, 76, 117, 0.35);
  border: 4px solid #ffffff;
}

.process-step:nth-child(even) .step-number {
  background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 100%);
  color: var(--primary-dark);
}

.process-step h4 {
  font-size: 18px;
  font-weight: 700;
  color: var(--primary-dark);
  margin-bottom: 12px;
}

.process-step p {
  color: var(--text-secondary);
  line-height: 1.7;
  font-size: 14px;
}

/* Destinations Grid */
.destinations-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 24px;
}

.destination-card {
  background: #ffffff;
  border-radius: 16px;
  padding: 28px 24px;
  text-align: center;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
  transition: all 0.3s ease;
  border: 1px solid rgba(0, 0, 0, 0.04);
}

.destination-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 35px rgba(0, 0, 0, 0.1);
}

.destination-flag {
  font-size: 48px;
  margin-bottom: 16px;
}

.destination-card h4 {
  font-size: 18px;
  font-weight: 700;
  color: var(--primary-dark);
  margin-bottom: 8px;
}

.destination-card p {
  color: var(--text-secondary);
  font-size: 14px;
  margin: 0;
}

/* FAQ Container */
.faq-container {
  max-width: 900px;
  margin: 0 auto;
}

.faq-item {
  background: #ffffff;
  border-radius: 12px;
  margin-bottom: 16px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
  overflow: hidden;
  border: 1px solid rgba(0, 0, 0, 0.04);
}

.faq-item summary {
  padding: 24px 60px 24px 28px;
  font-size: 17px;
  font-weight: 600;
  color: var(--primary-dark);
  cursor: pointer;
  list-style: none;
  position: relative;
  transition: all 0.3s ease;
}

.faq-item summary::-webkit-details-marker {
  display: none;
}

.faq-item summary::after {
  content: '\f078';
  font-family: 'Font Awesome 6 Free';
  font-weight: 900;
  position: absolute;
  right: 28px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--gold);
  transition: transform 0.3s ease;
}

.faq-item[open] summary::after {
  transform: translateY(-50%) rotate(180deg);
}

.faq-item summary:hover {
  color: var(--primary);
}

.faq-item p {
  padding: 0 28px 24px;
  margin: 0;
  color: var(--text-secondary);
  line-height: 1.8;
  border-top: 1px solid #E2E8F0;
  padding-top: 20px;
  margin-top: -4px;
}

/* CTA Section */
.cta-section {
  padding: 100px 24px;
  background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 50%, var(--primary-dark) 100%);
  text-align: center;
  position: relative;
  overflow: hidden;
}

.cta-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url('images/pattern/pattern.png') repeat;
  opacity: 0.05;
}

.cta-section .container {
  position: relative;
  z-index: 1;
}

.cta-section h2 {
  font-size: clamp(28px, 4vw, 42px);
  font-weight: 800;
  color: #ffffff;
  margin-bottom: 16px;
}

.cta-section p {
  font-size: 18px;
  color: rgba(255, 255, 255, 0.9);
  max-width: 600px;
  margin: 0 auto 40px;
  line-height: 1.7;
}

.cta-buttons {
  display: flex;
  gap: 16px;
  justify-content: center;
  flex-wrap: wrap;
}

/* Button Styles */
.btn-sm {
  padding: 10px 24px;
  font-size: 14px;
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
  .services-detail-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 991px) {
  .process-timeline {
    grid-template-columns: repeat(2, 1fr);
    gap: 40px;
  }
  
  .process-timeline::before {
    display: none;
  }
  
  .destinations-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 768px) {
  .services-detail-grid {
    grid-template-columns: 1fr;
  }
  
  .process-timeline {
    grid-template-columns: 1fr;
  }
  
  .destinations-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .faq-item summary {
    font-size: 15px;
    padding-right: 50px;
  }
}

@media (max-width: 576px) {
  .destinations-grid {
    grid-template-columns: 1fr;
  }
  
  .cta-buttons {
    flex-direction: column;
    align-items: center;
  }
  
  .cta-buttons .btn {
    width: 100%;
    max-width: 280px;
  }
}
</style>

<?php include_once "partials/footer.php"; ?>
