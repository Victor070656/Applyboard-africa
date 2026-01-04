<?php
include_once "config/config.php";
$loggedIn = false;
if (!empty($_SESSION["sdtravels_user"])) {
  $loggedIn = true;
  $uid = $_SESSION["sdtravels_user"];
}
$pageTitle = "Your Gateway to Global Opportunities";
$pageDescription = "ApplyBoard Africa Ltd - Expert visa processing, study abroad consultation, pilgrimage travel, and immigration services.";
include_once "partials/header.php";
?>

<!-- Hero Section -->
<section class="hero">
  <div class="hero-background">
    <img src="images/main-slider/2.jpg" alt="Global Opportunities" />
  </div>
  <div class="hero-overlay"></div>

  <div class="container">
    <div class="hero-content">
      <div class="hero-badge">
        <span class="pulse-dot"></span>
        <span>Trusted by 5,000+ Clients Worldwide</span>
      </div>

      <h1 class="hero-title " style="color: white;">
        Your Journey to <span class="highlight">Global Success</span> Starts Here
      </h1>

      <p class="hero-description">
        Expert guidance for study abroad programs, visa assistance, travel planning, and student recruitment. We connect
        you to global education and travel opportunities with clarity and support.
      </p>

      <div class="hero-actions">
        <a href="platform.php" class="btn btn-accent btn-xl">
          <span>Start Your Journey</span>
          <i class="fas fa-arrow-right"></i>
        </a>
        <a href="services.php" class="btn btn-outline-white btn-xl">
          <span>Explore Services</span>
        </a>
      </div>

      <div class="hero-stats">
        <div class="stat-item">
          <div class="stat-value">10+</div>
          <div class="stat-label">Years Experience</div>
        </div>
        <div class="stat-item">
          <div class="stat-value">5,000+</div>
          <div class="stat-label">Happy Clients</div>
        </div>
        <div class="stat-item">
          <div class="stat-value">99%</div>
          <div class="stat-label">Success Rate</div>
        </div>
        <div class="stat-item">
          <div class="stat-value">50+</div>
          <div class="stat-label">Countries Served</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Services Section -->
<section class="section section-lg bg-light">
  <div class="container">
    <div class="section-header">
      <span class="section-tag">
        <i class="fas fa-briefcase"></i>
        What We Offer
      </span>
      <h2 class="section-title">Comprehensive Education & Travel Services</h2>
      <p class="section-description">
        From study abroad programs to travel planning, we provide end-to-end solutions tailored to your unique needs.
      </p>
    </div>

    <div class="grid grid-3 gap-8">
      <!-- Study Abroad -->
      <div class="service-card">
        <div class="service-card-image">
          <img src="images/travel/new/01.jpeg" alt="Study Abroad" />
        </div>
        <div class="service-card-body">
          <div class="service-card-icon">
            <i class="fas fa-graduation-cap"></i>
          </div>
          <h3 class="service-card-title">Study Abroad</h3>
          <p class="service-card-description">
            Access world-class education in top universities across Canada, UK, USA, Australia, and Europe with our
            comprehensive admissions support and guidance.
          </p>
          <a href="services.php#study-abroad" class="service-card-link">
            Learn More <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>

      <!-- Visa Processing -->
      <div class="service-card">
        <div class="service-card-image">
          <img src="images/travel/new/02.jpeg" alt="Visa Processing" />
        </div>
        <div class="service-card-body">
          <div class="service-card-icon">
            <i class="fas fa-passport"></i>
          </div>
          <h3 class="service-card-title">Visa Assistance</h3>
          <p class="service-card-description">
            Expert assistance for tourist and student visa applications. We handle documentation, applications, and
            provide interview preparation support.
          </p>
          <a href="services.php#visa-processing" class="service-card-link">
            Learn More <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>

      <!-- Pilgrimage Tours -->
      <div class="service-card">
        <div class="service-card-image">
          <img src="images/travel/new/03.jpeg" alt="Pilgrimage Tours" />
        </div>
        <div class="service-card-body">
          <div class="service-card-icon">
            <i class="fas fa-kaaba"></i>
          </div>
          <h3 class="service-card-title">Pilgrimage Tours</h3>
          <p class="service-card-description">
            Embark on a spiritual journey with our carefully curated Hajj and Umrah packages. Complete arrangements from
            visa to accommodation.
          </p>
          <a href="services.php#pilgrimage" class="service-card-link">
            Learn More <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>

      <!-- Travel Booking -->
      <div class="service-card">
        <div class="service-card-image">
          <img src="images/travel/new/others/flight1.jpeg" alt="Travel Booking" />
        </div>
        <div class="service-card-body">
          <div class="service-card-icon">
            <i class="fas fa-plane-departure"></i>
          </div>
          <h3 class="service-card-title">Travel Planning</h3>
          <p class="service-card-description">
            Complete travel planning including flight reservations, hotel bookings, and relocation guidance
            to destinations worldwide.
          </p>
          <a href="services.php#travel-booking" class="service-card-link">
            Learn More <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>

      <!-- IELTS Preparation -->
      <div class="service-card">
        <div class="service-card-image">
          <img src="images/travel/new/04.jpeg" alt="IELTS Preparation" />
        </div>
        <div class="service-card-body">
          <div class="service-card-icon">
            <i class="fas fa-book-reader"></i>
          </div>
          <h3 class="service-card-title">IELTS Preparation</h3>
          <p class="service-card-description">
            Comprehensive English proficiency coaching with experienced tutors. Achieve your target score for study and
            immigration purposes.
          </p>
          <a href="services.php#ielts" class="service-card-link">
            Learn More <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>

      <!-- Immigration Consulting -->
      <div class="service-card">
        <div class="service-card-image">
          <img src="images/travel/new/05.jpeg" alt="Immigration Consulting" />
        </div>
        <div class="service-card-body">
          <div class="service-card-icon">
            <i class="fas fa-globe-americas"></i>
          </div>
          <h3 class="service-card-title">Immigration Consulting</h3>
          <p class="service-card-description">
            Expert guidance on permanent residency, work permits, and citizenship applications. Navigate complex
            immigration processes with ease.
          </p>
          <a href="services.php#immigration" class="service-card-link">
            Learn More <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Why Choose Us Section -->
<section class="section section-lg">
  <div class="container">
    <div class="grid grid-2 gap-8" style="align-items: center;">
      <div>
        <span class="section-tag" style="margin-bottom: var(--space-4);">
          <i class="fas fa-star"></i>
          Why Choose Us
        </span>
        <h2 class="section-title" style="text-align: left; margin-bottom: var(--space-6);">
          Your Success is Our Priority
        </h2>
        <p
          style="color: var(--neutral-600); font-size: var(--text-lg); line-height: 1.8; margin-bottom: var(--space-8);">
          With over a decade of experience, we've helped thousands of individuals achieve their international
          aspirations. Our personalized approach, industry expertise, and commitment to excellence set us apart.
        </p>

        <div class="grid grid-2 gap-6">
          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-shield-alt"></i>
            </div>
            <h4 class="feature-title">Licensed & Certified</h4>
            <p class="feature-description">Fully accredited consultancy with verified credentials and industry
              partnerships.</p>
          </div>

          <div class="feature-card">
            <div class="feature-icon" style="background: var(--accent-100); color: var(--accent-700);">
              <i class="fas fa-headset"></i>
            </div>
            <h4 class="feature-title">24/7 Support</h4>
            <p class="feature-description">Round-the-clock assistance from our dedicated team throughout your journey.
            </p>
          </div>

          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-award"></i>
            </div>
            <h4 class="feature-title">99% Success Rate</h4>
            <p class="feature-description">Industry-leading approval rates backed by meticulous attention to detail.</p>
          </div>

          <div class="feature-card">
            <div class="feature-icon" style="background: var(--accent-100); color: var(--accent-700);">
              <i class="fas fa-hand-holding-usd"></i>
            </div>
            <h4 class="feature-title">Transparent Pricing</h4>
            <p class="feature-description">No hidden fees. Clear, upfront pricing with flexible payment options.</p>
          </div>
        </div>
      </div>

      <div style="position: relative;">
        <img src="images/resource/about-1.jpg" alt="Why Choose Us"
          style="border-radius: var(--radius-2xl); box-shadow: var(--shadow-2xl);" />
        <div
          style="position: absolute; bottom: -30px; left: -30px; background: var(--white); padding: var(--space-6) var(--space-8); border-radius: var(--radius-2xl); box-shadow: var(--shadow-xl);">
          <div style="display: flex; align-items: center; gap: var(--space-4);">
            <div
              style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--accent-600), var(--accent-400)); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; color: var(--neutral-900); font-size: 1.5rem;">
              <i class="fas fa-trophy"></i>
            </div>
            <div>
              <p style="color: var(--neutral-500); font-size: var(--text-sm); margin: 0;">Trusted Agency</p>
              <p style="color: var(--neutral-900); font-size: var(--text-lg); font-weight: 700; margin: 0;">Award
                Winning</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Process Section -->
<section class="section section-lg bg-light">
  <div class="container">
    <div class="section-header">
      <span class="section-tag">
        <i class="fas fa-route"></i>
        How It Works
      </span>
      <h2 class="section-title">Simple Steps to Your Destination</h2>
      <p class="section-description">
        Our streamlined process makes your journey smooth and stress-free from start to finish.
      </p>
    </div>

    <div class="grid grid-4 gap-8">
      <div style="text-align: center; padding: var(--space-6);">
        <div
          style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--primary-700), var(--primary-500)); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; font-size: var(--text-2xl); font-weight: 800; color: var(--white); margin: 0 auto var(--space-5); box-shadow: var(--shadow-lg);">
          1</div>
        <h4
          style="font-size: var(--text-lg); font-weight: 700; color: var(--neutral-900); margin-bottom: var(--space-3);">
          Free Consultation</h4>
        <p style="font-size: var(--text-sm); color: var(--neutral-500); line-height: 1.7;">Schedule a free consultation
          to discuss your goals and explore available options.</p>
      </div>

      <div style="text-align: center; padding: var(--space-6);">
        <div
          style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--accent-600), var(--accent-400)); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; font-size: var(--text-2xl); font-weight: 800; color: var(--neutral-900); margin: 0 auto var(--space-5); box-shadow: var(--shadow-gold);">
          2</div>
        <h4
          style="font-size: var(--text-lg); font-weight: 700; color: var(--neutral-900); margin-bottom: var(--space-3);">
          Document Preparation</h4>
        <p style="font-size: var(--text-sm); color: var(--neutral-500); line-height: 1.7;">We guide you through
          gathering and organizing all required documents.</p>
      </div>

      <div style="text-align: center; padding: var(--space-6);">
        <div
          style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--primary-700), var(--primary-500)); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; font-size: var(--text-2xl); font-weight: 800; color: var(--white); margin: 0 auto var(--space-5); box-shadow: var(--shadow-lg);">
          3</div>
        <h4
          style="font-size: var(--text-lg); font-weight: 700; color: var(--neutral-900); margin-bottom: var(--space-3);">
          Application Submission</h4>
        <p style="font-size: var(--text-sm); color: var(--neutral-500); line-height: 1.7;">We handle your application
          submission and follow up with relevant authorities.</p>
      </div>

      <div style="text-align: center; padding: var(--space-6);">
        <div
          style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--accent-600), var(--accent-400)); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; font-size: var(--text-2xl); font-weight: 800; color: var(--neutral-900); margin: 0 auto var(--space-5); box-shadow: var(--shadow-gold);">
          4</div>
        <h4
          style="font-size: var(--text-lg); font-weight: 700; color: var(--neutral-900); margin-bottom: var(--space-3);">
          Success & Travel</h4>
        <p style="font-size: var(--text-sm); color: var(--neutral-500); line-height: 1.7;">Receive your approval and
          prepare for your journey with our continued support.</p>
      </div>
    </div>
  </div>
</section>

<!-- Testimonials Section -->
<section class="section section-lg">
  <div class="container">
    <div class="section-header">
      <span class="section-tag">
        <i class="fas fa-quote-left"></i>
        Client Stories
      </span>
      <h2 class="section-title">What Our Clients Say</h2>
      <p class="section-description">
        Real experiences from real people who trusted us with their international dreams.
      </p>
    </div>

    <div class="grid grid-3 gap-8">
      <div class="testimonial-card">
        <p class="testimonial-content">
          "ApplyBoard Africa made my Canadian study dream a reality. From university selection to visa approval, they
          were with me every step. Highly recommended!"
        </p>
        <div class="testimonial-author">
          <img src="images/travel/new/staff/01.jpeg" alt="Client" class="testimonial-avatar" />
          <div class="testimonial-info">
            <h4>Adebayo Johnson</h4>
            <p>Student - Canada</p>
            <div class="testimonial-rating">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="testimonial-card">
        <p class="testimonial-content">
          "Our Hajj experience was seamless thanks to their meticulous planning. Every detail was taken care of,
          allowing us to focus on our spiritual journey."
        </p>
        <div class="testimonial-author">
          <img src="images/travel/new/staff/02.jpeg" alt="Client" class="testimonial-avatar" />
          <div class="testimonial-info">
            <h4>Fatimah Ibrahim</h4>
            <p>Hajj Pilgrim - Saudi Arabia</p>
            <div class="testimonial-rating">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="testimonial-card">
        <p class="testimonial-content">
          "Professional, efficient, and genuinely caring. My UK tourist visa was processed within days. They exceeded
          all my expectations!"
        </p>
        <div class="testimonial-author">
          <img src="images/travel/new/staff/03.jpeg" alt="Client" class="testimonial-avatar" />
          <div class="testimonial-info">
            <h4>Chinedu Okonkwo</h4>
            <p>Tourist - United Kingdom</p>
            <div class="testimonial-rating">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
  <div class="container">
    <div class="cta-content">
      <h2 class="cta-title">Ready to Begin Your Journey?</h2>
      <p class="cta-description">
        Take the first step towards your international dreams. Our expert consultants are ready to guide you every step
        of the way.
      </p>
      <div class="cta-actions">
        <a href="platform.php" class="btn btn-accent btn-xl">
          <span>Get Started Today</span>
          <i class="fas fa-arrow-right"></i>
        </a>
        <a href="contact.php" class="btn btn-outline-white btn-xl">
          <i class="fas fa-phone-alt"></i>
          <span>Contact Us</span>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Partners Section -->
<section class="section">
  <div class="container">
    <div class="section-header">
      <span class="section-tag">
        <i class="fas fa-handshake"></i>
        Our Partners
      </span>
      <h2 class="section-title">Trusted Partnerships</h2>
      <p class="section-description">
        We collaborate with leading institutions and organizations worldwide to deliver exceptional service.
      </p>
    </div>

    <div class="partners-grid"
      style="display: flex; flex-wrap: wrap; justify-content: center; align-items: center; gap: var(--space-12);">
      <div class="partner-item"
        style="padding: 1.5rem 2.5rem; background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); border: 1px solid var(--neutral-200);">
        <img src="images/partners/saudi.png" alt="" style="display: block; margin: auto; height: 70px;"><br>
        <span style="font-weight: 700; font-size: 1.25rem; color: var(--primary-700);">Saudi Airlines</span>
      </div>
      <div class="partner-item"
        style="padding: 1.5rem 2.5rem; background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); border: 1px solid var(--neutral-200);">
        <img src="images/partners/emirates.png" alt="" style="display: block; margin: auto; height: 70px;"><br>
        <span style="font-weight: 700; font-size: 1.25rem; color: var(--primary-700);">Emirates</span>
      </div>
      <div class="partner-item"
        style="padding: 1.5rem 2.5rem; background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); border: 1px solid var(--neutral-200);">
        <img src="images/partners/british.png" alt="" style="display: block; margin: auto; height: 70px;"><br>
        <span style="font-weight: 700; font-size: 1.25rem; color: var(--primary-700);">British Council</span>
      </div>
      <div class="partner-item"
        style="padding: 1.5rem 2.5rem; background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); border: 1px solid var(--neutral-200);">
        <img src="images/partners/idp.png" alt="" class="text-center" style="display: block; margin: auto; height: 70px;"><br>
        <span style="font-weight: 700; font-size: 1.25rem; color: var(--primary-700);">IDP IELTS</span>
      </div>
      <div class="partner-item"
        style="padding: 1.5rem 2.5rem; background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); border: 1px solid var(--neutral-200);">
        <img src="images/partners/bookings.jpeg" alt="" style="display: block; margin: auto; height: 70px;"><br>
        <span style="font-weight: 700; font-size: 1.25rem; color: var(--primary-700);">Booking.com</span>
      </div>
    </div>
  </div>
</section>

<?php include_once "partials/footer.php"; ?>