<?php
$pageTitle = "Our Agents";
$pageDescription = "Meet our certified travel agents and get personalized guidance for your education and travel journey.";
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
    <p class="hero-subtitle">Connect with Experts</p>
    <h1>Our Verified Agents</h1>
    <p>Meet our certified travel agents ready to assist you with your education and travel needs.</p>
    <div class="breadcrumb">
      <a href="./">Home</a>
      <span>/</span>
      <span>Agents</span>
    </div>
  </div>
</section>

<style>
  /* Agents Grid */
  .agents-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 32px;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px;
  }

  .agent-card {
    background: #ffffff;
    border-radius: 20px;
    padding: 32px;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    transition: all 0.3s ease;
  }

  .agent-card:hover {
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
    transform: translateY(-5px);
  }

  .agent-card .agent-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    margin: 0 auto 20px;
    object-fit: cover;
    border: 4px solid #F8FAFC;
  }

  .agent-card h3 {
    font-size: 18px;
    font-weight: 700;
    color: #0A3655;
    margin-bottom: 8px;
  }

  .agent-card .agent-badge {
    display: inline-block;
    padding: 6px 16px;
    background: linear-gradient(135deg, #0F4C75, #3282B8);
    color: #ffffff;
    border-radius: 50px;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 12px;
  }

  .agent-card p {
    color: #64748B;
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 20px;
  }

  .agent-card .btn-contact {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 28px;
    background: transparent;
    color: #0F4C75;
    border: 2px solid #0F4C75;
    border-radius: 50px;
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.3s ease;
  }

  .agent-card .btn-contact:hover {
    background: #0F4C75;
    color: #ffffff;
  }

  .no-agents {
    text-align: center;
    color: #64748B;
    font-size: 18px;
    padding: 60px 24px;
  }

  /* CTA */
  .cta-box {
    background: linear-gradient(135deg, rgba(15, 76, 117, 0.05), rgba(50, 130, 184, 0.05));
    border-radius: 20px;
    padding: 48px;
    text-align: center;
    max-width: 800px;
    margin: 60px auto 0;
  }

  .cta-box h3 {
    font-size: 28px;
    font-weight: 700;
    color: #0A3655;
    margin-bottom: 16px;
  }

  .cta-box p {
    color: #64748B;
    margin-bottom: 24px;
  }

  .cta-box .btn-cta {
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

  .cta-box .btn-cta:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(15, 76, 117, 0.4);
  }

  /* Responsive */
  @media (max-width: 1024px) {
    .agents-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }

  @media (max-width: 768px) {
    .agents-grid {
      grid-template-columns: 1fr;
    }
    .cta-box {
      padding: 32px 24px;
      margin: 40px 24px 0;
    }
  }
</style>

<!-- Agents Section -->
<section class="modern-section">
  <div class="section-header">
    <span class="section-tag">
      <i class="fas fa-user-friends"></i> Our Network
    </span>
    <h2>Meet Our Certified Travel Agents</h2>
    <p>Our verified agents are ready to provide personalized guidance for your journey.</p>
  </div>

  <div class="agents-grid">
    <?php
    $sql = "SELECT * FROM `agents` WHERE `status` = 'verified'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
    ?>
    <div class="agent-card">
      <img src="images/resource/avatar.png" class="agent-avatar" alt="Agent">
      <h3><?= htmlspecialchars($row['fullname']) ?></h3>
      <span class="agent-badge"><?= $row['agent_code'] ?></span>
      <p>Certified ApplyBoard Africa Agent ready to assist you with your education and travel needs.</p>
      <a href="contact.php?ref=<?= $row['agent_code'] ?>" class="btn-contact">
        <i class="fas fa-envelope"></i> Contact Agent
      </a>
    </div>
    <?php
        }
    } else {
        echo '<div class="no-agits"><p>No agents currently listed.</p></div>';
    }
    ?>
  </div>

  <!-- Become Agent CTA -->
  <div class="cta-box">
    <h3>Become a Certified Agent</h3>
    <p>Join our network of verified agents and earn commissions while helping students achieve their dreams.</p>
    <a href="agent/register.php" class="btn-cta">
      <i class="fas fa-user-plus"></i> Register as Agent
    </a>
  </div>
</section>

<?php include_once "partials/footer.php"; ?>
</body>
</html>
