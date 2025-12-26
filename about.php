<?php
$pageTitle = "About Us";
$pageDescription = "Learn about ApplyBoard Africa Ltd - Your trusted partner for visa processing, study abroad consultation, and immigration services.";
include_once "config/config.php";
include_once "partials/header.php";
?>

<!-- Page Hero -->
<section class="page-hero">
  <div class="hero-bg"><img src="images/main-slider/2.jpg" alt="" /></div>
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <p class="hero-subtitle">Get To Know Us</p>
    <h1>About ApplyBoard Africa</h1>
    <p class="hero-desc">Your trusted partner for hassle-free visa processing, study abroad consultation, and immigration services.</p>
  </div>
</section>

<!-- Breadcrumb -->
<section class="breadcrumb">
  <div class="container">
    <p><a href="./">Home</a> <span style="margin: 0 8px;">/</span> <span style="color: #D4A853;">About Us</span></p>
  </div>
</section>

<style>
  /* STORY SECTION */
  .story-section { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
  .story-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: center; }
  .story-content .badge { display: inline-block; padding: 8px 20px; background: linear-gradient(135deg, rgba(15, 76, 117, 0.1), rgba(50, 130, 184, 0.1)); color: #0F4C75; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; border-radius: 50px; margin-bottom: 20px; }
  .story-content h2 { font-size: clamp(32px, 4vw, 42px); font-weight: 800; color: #0F172A; margin-bottom: 24px; line-height: 1.2; }
  .story-content > p { color: #64748B; line-height: 1.8; margin-bottom: 20px; }
  .story-stats { display: flex; gap: 40px; flex-wrap: wrap; }
  .story-stat h3 { font-size: 42px; font-weight: 800; background: linear-gradient(135deg, #0F4C75, #3282B8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; line-height: 1; }
  .story-stat p { color: #64748B; font-size: 14px; text-transform: uppercase; letter-spacing: 1px; margin-top: 8px; }
  .story-image { position: relative; }
  .story-image img { width: 100%; border-radius: 32px; box-shadow: 0 16px 48px rgba(0, 0, 0, 0.15); }
  .story-image .floating-badge { position: absolute; bottom: -30px; left: -30px; background: #FFFFFF; padding: 24px 32px; border-radius: 24px; box-shadow: 0 16px 48px rgba(0, 0, 0, 0.15); }
  .floating-badge .badge-icon { width: 60px; height: 60px; background: linear-gradient(135deg, #D4A853, #E8C97A); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 24px; color: #0F172A; margin-bottom: 12px; }

  /* MISSION VISION */
  .mission-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 32px; max-width: 1200px; margin: 0 auto; padding: 0 24px; }
  .mission-card { background: #FFFFFF; padding: 48px; border-radius: 24px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); transition: all 0.3s ease; }
  .mission-card:hover { box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12); }
  .mission-card .card-icon { width: 70px; height: 70px; background: linear-gradient(135deg, #0F4C75, #3282B8); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 28px; color: #FFFFFF; margin-bottom: 24px; }
  .mission-card:nth-child(2) .card-icon { background: linear-gradient(135deg, #D4A853, #E8C97A); color: #0F172A; }
  .mission-card h3 { font-size: 26px; font-weight: 700; color: #0F172A; margin-bottom: 16px; }
  .mission-card p { color: #64748B; line-height: 1.8; }

  /* VALUES */
  .values-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 32px; max-width: 1200px; margin: 0 auto; padding: 0 24px; }
  .value-card { text-align: center; padding: 32px 20px; background: #FFFFFF; border-radius: 24px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); transition: all 0.3s ease; }
  .value-card:hover { box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12); transform: translateY(-4px); }
  .value-card .value-icon { width: 60px; height: 60px; background: linear-gradient(135deg, #0F4C75, #3282B8); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; color: #FFFFFF; margin: 0 auto 20px; }
  .value-card:nth-child(even) .value-icon { background: linear-gradient(135deg, #D4A853, #E8C97A); color: #0F172A; }
  .value-card h4 { font-size: 18px; font-weight: 700; color: #0F172A; margin-bottom: 12px; }
  .value-card p { font-size: 14px; color: #64748B; line-height: 1.6; }

  /* TEAM */
  .team-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px; max-width: 1200px; margin: 0 auto; padding: 0 24px; }
  .team-card { background: #FFFFFF; border-radius: 24px; overflow: hidden; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12); transition: all 0.4s ease; }
  .team-card:hover { transform: translateY(-8px); box-shadow: 0 16px 48px rgba(0, 0, 0, 0.2); }
  .team-card .team-image { height: 320px; overflow: hidden; }
  .team-card .team-image img { width: 100%; height: 100%; object-fit: cover; }
  .team-card .team-content { padding: 24px; }
  .team-card .team-name { font-size: 20px; font-weight: 700; color: #0F172A; margin-bottom: 4px; }
  .team-card .team-role { font-size: 14px; color: #D4A853; font-weight: 600; margin-bottom: 12px; }
  .team-card .team-bio { font-size: 14px; color: #64748B; line-height: 1.6; }

  /* CTA */
  .cta-section { padding: 100px 24px; background: linear-gradient(135deg, #0F4C75 0%, #3282B8 100%); text-align: center; color: #FFFFFF; }
  .cta-section h2 { font-size: clamp(32px, 4vw, 48px); font-weight: 800; margin-bottom: 16px; }
  .cta-section p { font-size: 18px; opacity: 0.9; margin-bottom: 32px; max-width: 600px; margin-left: auto; margin-right: auto; }
  .cta-section .cta-btn { display: inline-flex; align-items: center; gap: 12px; padding: 18px 40px; background: #FFFFFF; color: #0F4C75; font-weight: 700; font-size: 16px; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 8px 25px rgba(0,0,0,0.2); }
  .cta-section .cta-btn:hover { transform: translateY(-4px); box-shadow: 0 12px 35px rgba(0,0,0,0.3); }
  .cta-section .cta-btn-sec { display: inline-flex; align-items: center; gap: 12px; padding: 18px 40px; background: transparent; color: #FFFFFF; font-weight: 700; font-size: 16px; border: 2px solid #FFFFFF; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; margin-left: 16px; }
  .cta-section .cta-btn-sec:hover { background: rgba(255,255,255,0.1); }

  /* RESPONSIVE */
  @media (max-width: 1024px) {
    .story-grid { grid-template-columns: 1fr; gap: 60px; }
    .story-image { order: -1; }
    .mission-grid { grid-template-columns: 1fr; }
    .values-grid { grid-template-columns: repeat(2, 1fr); }
  }
  @media (max-width: 768px) {
    .team-grid { grid-template-columns: 1fr; }
    .values-grid { grid-template-columns: 1fr; }
    .story-stats { gap: 24px; }
    .story-stat h3 { font-size: 32px; }
  }
</style>

<!-- Story Section -->
<section class="modern-section">
  <div class="story-section">
    <div class="story-grid">
      <div class="story-content">
        <span class="badge"><i class="fas fa-heart"></i> Our Story</span>
        <h2>Your Trusted Partner in Travel and Education</h2>
        <p>ApplyBoard Africa Ltd is a leading travel and immigration consultancy dedicated to providing seamless travel and educational consulting services. Whether you are looking to study abroad, explore new destinations, or require visa assistance, we are here to guide you every step of the way.</p>
        <p>Our mission is to empower individuals to achieve their global dreams through transparent, reliable, and personalized support. With over 10 years of experience and a 99% success rate, we have helped thousands of clients successfully relocate, study, and work abroad.</p>
        <div class="story-stats">
          <div class="story-stat">
            <h3>10+</h3>
            <p>Years Experience</p>
          </div>
          <div class="story-stat">
            <h3>5000+</h3>
            <p>Happy Clients</p>
          </div>
          <div class="story-stat">
            <h3>99%</h3>
            <p>Success Rate</p>
          </div>
        </div>
      </div>
      <div class="story-image">
        <img src="images/resource/about-1.jpg" alt="About Us" />
        <div class="floating-badge">
          <div class="badge-icon"><i class="fas fa-award"></i></div>
          <div>
            <p style="font-size: 14px; color: #64748B; margin: 0;">Trusted Agency</p>
            <p style="font-size: 18px; font-weight: 700; color: #0F172A; margin: 4px 0 0;">Certified Experts</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Mission & Vision -->
<section class="modern-section bg-light">
  <div class="section-header">
    <span class="section-tag"><i class="fas fa-star"></i> What Drives Us</span>
    <h2>Our Mission & Vision</h2>
    <p>The foundation of our commitment to excellence in immigration services.</p>
  </div>
  <div class="mission-grid">
    <div class="mission-card">
      <div class="card-icon"><i class="fas fa-bullseye"></i></div>
      <h3>Our Mission</h3>
      <p>To provide accessible, transparent, and reliable immigration services that empower individuals to achieve their dreams of living, studying, and working abroad. We are committed to delivering personalized support that ensures every client's journey is smooth and successful.</p>
    </div>
    <div class="mission-card">
      <div class="card-icon"><i class="fas fa-eye"></i></div>
      <h3>Our Vision</h3>
      <p>To be Africa's leading immigration consultancy, recognized for excellence, integrity, and innovation. We envision a world where borders are no longer barriers to education, career growth, and family reunification.</p>
    </div>
  </div>
</section>

<!-- Core Values -->
<section class="modern-section">
  <div class="section-header">
    <span class="section-tag"><i class="fas fa-gem"></i> Our Values</span>
    <h2>Core Values That Guide Us</h2>
  </div>
  <div class="values-grid">
    <div class="value-card">
      <div class="value-icon"><i class="fas fa-shield-alt"></i></div>
      <h4>Integrity</h4>
      <p>We maintain honesty and transparency in all our dealings.</p>
    </div>
    <div class="value-card">
      <div class="value-icon"><i class="fas fa-award"></i></div>
      <h4>Excellence</h4>
      <p>We deliver the highest quality service in everything we do.</p>
    </div>
    <div class="value-card">
      <div class="value-icon"><i class="fas fa-users"></i></div>
      <h4>Client Focus</h4>
      <p>Our clients' success is our top priority.</p>
    </div>
    <div class="value-card">
      <div class="value-icon"><i class="fas fa-lightbulb"></i></div>
      <h4>Innovation</h4>
      <p>We continuously improve and embrace new solutions.</p>
    </div>
  </div>
</section>

<!-- Team -->
<section class="modern-section bg-light">
  <div class="section-header">
    <span class="section-tag"><i class="fas fa-user-friends"></i> Our Team</span>
    <h2>Leadership Team</h2>
    <p>Meet the experienced professionals dedicated to making your dreams a reality.</p>
  </div>
  <div class="team-grid">
    <div class="team-card">
      <div class="team-image"><img src="images/travel/new/staff/07.jpeg" alt="CEO" /></div>
      <div class="team-content">
        <h3 class="team-name">Oluwasesan Marcus Debo</h3>
        <p class="team-role">Chief Executive Officer</p>
      </div>
    </div>
    <div class="team-card">
      <div class="team-image"><img src="images/travel/new/staff/05.jpeg" alt="MD" /></div>
      <div class="team-content">
        <h3 class="team-name">Oluwasesan Omolola</h3>
        <p class="team-role">Managing Director</p>
      </div>
    </div>
    <div class="team-card">
      <div class="team-image"><img src="images/travel/new/staff/03.jpeg" alt="Manager" /></div>
      <div class="team-content">
        <h3 class="team-name">Adabiri Deborah</h3>
        <p class="team-role">Operations Manager</p>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-section">
  <h2>Ready to Start Your Journey?</h2>
  <p>Contact us today for a free consultation and let our experts guide you through your visa application process.</p>
  <div>
    <a href="contact.php" class="cta-btn">Get Started <i class="fas fa-arrow-right" style="margin-left: 8px;"></i></a>
    <a href="tel:+2349069503394" class="cta-btn-sec"><i class="fas fa-phone-alt" style="margin-right: 8px;"></i> +234 906 9503 394</a>
  </div>
</section>

<?php include_once "partials/footer.php"; ?>
</body>
</html>
