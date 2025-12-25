<?php
include_once "config/config.php";
// session_start();
$loggedIn = false;
if (!empty($_SESSION["sdtravels_user"])) {
  $loggedIn = true;
  $uid = $_SESSION["sdtravels_user"];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>ApplyBoard Africa Ltd | Our Platform</title>
  <link href="css/bootstrap.min.css" rel="stylesheet" />
  <link href="css/style.css" rel="stylesheet" />
  <link href="css/responsive.css" rel="stylesheet" />
  <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon" />
  <link rel="icon" href="images/favicon.png" type="image/x-icon" />
  <link href="intro/intro.css" rel="stylesheet" />
</head>

<body>
  <div class="page-wrapper">
    <div class="preloader"></div>
    <?php
    $page = "platform";
    include "partials/header.php"; ?>

    <section
      class="page-title"
      style="background-image: url(images/background/page-title.jpg)">
      <div class="auto-container">
        <div class="title-outer">
          <h1 class="title">Our Digital Platform</h1>
          <ul class="page-breadcrumb">
            <li><a href="./">Home</a></li>
            <li>Platform</li>
          </ul>
        </div>
      </div>
    </section>

    <section class="platform-section">
      <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="sec-title">
                  <span class="sub-title">Technology</span>
                  <h2>A Unified Experience for Students, Agents, and Partners</h2>
                  <div class="text">
                    Our advanced digital platform connects all stakeholders in the education and travel journey. 
                  </div>
                </div>
                <ul class="list-style-one">
                    <li><i class="fa fa-check-circle text-primary"></i> Real-time Application Tracking</li>
                    <li><i class="fa fa-check-circle text-primary"></i> Secure Document Management</li>
                    <li><i class="fa fa-check-circle text-primary"></i> Dedicated Agent Dashboards</li>
                    <li><i class="fa fa-check-circle text-primary"></i> Direct Communication Channels</li>
                </ul>
                <div class="mt-4">
                    <a href="user/register.php" class="theme-btn btn-style-one"><span class="btn-title">Get Started</span></a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="images/resource/news-2.jpg" alt="Platform" class="img-fluid rounded shadow">
            </div>
        </div>
      </div>
    </section>

    <?php include "partials/footer.php"; ?>
  </div>

  <div class="scroll-to-top scroll-to-target" data-target="html">
    <span class="fa fa-angle-up"></span>
  </div>

  <script src="js/jquery.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.fancybox.js"></script>
  <script src="js/jquery-ui.js"></script>
  <script src="js/wow.js"></script>
  <script src="js/appear.js"></script>
  <script src="js/select2.min.js"></script>
  <script src="js/owl.js"></script>
  <script src="js/mixitup.js"></script>
  <script src="js/bxslider.js"></script>
  <script src="js/script.js"></script>
</body>
</html>
