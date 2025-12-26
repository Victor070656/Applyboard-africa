<?php
$pageTitle = "Our Platform";
$pageDescription = "Discover our advanced digital platform that connects students, agents, and partners for seamless education and travel experiences.";
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
    <p class="hero-subtitle">Technology</p>
    <h1>Our Digital Platform</h1>
    <p>A unified experience for students, agents, and partners in the education and travel journey.</p>
    <div class="breadcrumb">
      <a href="./">Home</a>
      <span>/</span>
      <span>Platform</span>
    </div>
  </div>
</section>

<style>
  /* Platform Content */
  .platform-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px;
  }

  .platform-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
  }

  .platform-content .platform-text h2 {
    font-size: clamp(28px, 4vw, 42px);
    font-weight: 800;
    color: #0A3655;
    margin-bottom: 20px;
    line-height: 1.2;
  }

  .platform-content .platform-text .subtitle {
    display: inline-block;
    padding: 8px 20px;
    background: linear-gradient(135deg, rgba(15, 76, 117, 0.1), rgba(50, 130, 184, 0.1));
    color: #0F4C75;
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    border-radius: 50px;
    margin-bottom: 20px;
  }

  .platform-content .platform-text p {
    color: #64748B;
    line-height: 1.8;
    margin-bottom: 32px;
    font-size: 17px;
  }

  .platform-content .feature-list {
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 16px;
    margin-bottom: 32px;
  }

  .platform-content .feature-list li {
    display: flex;
    align-items: center;
    gap: 12px;
    color: #334155;
    font-size: 16px;
  }

  .platform-content .feature-list li i {
    color: #0F4C75;
    font-size: 18px;
  }

  .platform-content .platform-image img {
    width: 100%;
    border-radius: 20px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
  }

  .platform-content .btn-platform {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 18px 40px;
    background: linear-gradient(135deg, #0F4C75, #3282B8);
    color: #ffffff;
    font-weight: 700;
    font-size: 16px;
    border-radius: 50px;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(15, 76, 117, 0.3);
  }

  .platform-content .btn-platform:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(15, 76, 117, 0.4);
  }

  /* Responsive */
  @media (max-width: 1024px) {
    .platform-grid {
      grid-template-columns: 1fr;
      gap: 40px;
    }
    .platform-content .platform-image {
      order: -1;
    }
  }
</style>

<section class="modern-section">
  <div class="platform-content">
    <div class="platform-grid">
      <div class="platform-text">
        <span class="subtitle">Technology</span>
        <h2>A Unified Experience for Students, Agents, and Partners</h2>
        <p>Our advanced digital platform connects all stakeholders in the education and travel journey. Experience seamless collaboration, real-time updates, and secure document management in one centralized hub.</p>
        <ul class="feature-list">
          <li><i class="fas fa-check-circle"></i> Real-time Application Tracking</li>
          <li><i class="fas fa-check-circle"></i> Secure Document Management</li>
          <li><i class="fas fa-check-circle"></i> Dedicated Agent Dashboards</li>
          <li><i class="fas fa-check-circle"></i> Direct Communication Channels</li>
        </ul>
        <div>
          <a href="user/register.php" class="btn-platform">
            Get Started <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
      <div class="platform-image">
        <img src="images/resource/news-2.jpg" alt="Platform" />
      </div>
    </div>
  </div>
</section>

<?php include_once "partials/footer.php"; ?>
</body>
</html>
