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
  <title>ApplyBoard Africa Ltd | Our Agents</title>
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
    $page = "agents";
    include "partials/header.php"; ?>

    <section
      class="page-title"
      style="background-image: url(images/background/page-title.jpg)">
      <div class="auto-container">
        <div class="title-outer">
          <h1 class="title">Our Verified Agents</h1>
          <ul class="page-breadcrumb">
            <li><a href="./">Home</a></li>
            <li>Agents</li>
          </ul>
        </div>
      </div>
    </section>

    <section class="agents-page-section">
      <div class="container">
        <div class="sec-title text-center">
            <span class="sub-title">Connect with Experts</span>
            <h2>Meet Our Certified Travel Agents</h2>
        </div>
        
        <div class="row">
            <?php
            $sql = "SELECT * FROM `agents` WHERE `status` = 'verified'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="col-lg-4 col-md-6">
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="mb-3">
                             <img src="images/resource/avatar.png" class="rounded-circle" style="width: 100px; height: 100px;" alt="Agent">
                        </div>
                        <h5 class="card-title"><?= htmlspecialchars($row['fullname']) ?></h5>
                        <p class="text-muted mb-2">Code: <span class="badge bg-primary"><?= $row['agent_code'] ?></span></p>
                        <p class="card-text small text-muted">Certified ApplyBoard Africa Agent ready to assist you.</p>
                        <a href="contact.php?ref=<?= $row['agent_code'] ?>" class="btn btn-outline-primary btn-sm">Contact Agent</a>
                    </div>
                </div>
            </div>
            <?php
                }
            } else {
                echo '<div class="col-12 text-center"><p>No agents currently listed.</p></div>';
            }
            ?>
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
