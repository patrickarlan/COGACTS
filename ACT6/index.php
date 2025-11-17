<?php
session_start();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DAHUA: TIMETRACK</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">    
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="icon" type="image/png" href="PICS/DAHUAfavi.png">

</head>
<body>
<?php include 'COMPONENTS/header.php'; ?>

<!-- Second Header / Banner -->
<div class="container-fluid p-0 bg-DARK">
  <div class="banner">
    <img src="PICS/DAHUA.png" alt="Banner Image" class="img-fluid">
  </div>
</div>

<!-- Overview / About + Product Section Combined -->
<section class="overview py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-20 text-center bg-white p-5 shadow rounded-3">
        <h2 class="fw-bold mb-3">Welcome to DAHUA: Timetrack</h2>
        <p class="custom-text mb-4">
          The Dahua Attendance Monitoring System is a web-based solution developed to provide a reliable and efficient way to manage attendance records.
          It enables accurate tracking of employee check-ins and check-outs, offering organizations a structured approach to monitoring workforce activity.
        </p>
        <p class="custom-text mb-5">
          This website serves as a dedicated platform for showcasing Dahua’s attendance monitoring products and their key features.
          Visitors can explore available devices, understand their capabilities, and learn how these solutions enhance productivity and accountability.
          For registered users and existing customers, the site also provides secure access to attendance data and device management tools.
        </p>

        <!-- ✅ Product Carousel -->
        <div class="product-carousel">
          <h3 class="fw-bold mt-3 text-dark">Our Products</h3>

          <div id="dahuaProductCarousel" 
              class="carousel slide mx-auto position-relative" 
              data-bs-ride="carousel" 
              data-bs-interval="3000" 
              style="max-width:700px;">

              <div class="carousel-inner overflow-hidden rounded-4">

                <!-- Product 1 -->
                <div class="carousel-item active">
                  <div class="pcard mx-5 mb-5 mt-5">
                    <div class="image-wrap position-relative">
                      <img src="PICS/DAHUAprod1.png" class="pcard-img" alt="Dahua Product 1">
                      <div class="pcard-overlay">
                        <a href="attendancedevice.php" class="view-details">View Details</a>
                      </div>
                    </div>
                    <div class="pcard-body">
                      <h5 class="pcard-title">Dahua: ASA1222G</h5>
                      <p class="pcard-desc">A standalone fingerprint time and attendance
                                            terminal that uses fingerprint, password, or 
                                            ID/IC card verification.</p>
                      <p class="pcard-price">₱10,000</p>
                    </div>
                  </div>
                </div>

                <!-- Product 2 -->
                <div class="carousel-item">
                  <div class="pcard mx-5 mb-5 mt-5">
                    <div class="image-wrap position-relative">
                      <img src="PICS/DAHUAattendance2.png" class="pcard-img" alt="Dahua Attendance 2">
                      <div class="pcard-overlay">
                        <a href="attendance2.php" class="view-details">View Details</a>
                      </div>
                    </div>
                    <div class="pcard-body">
                      <h5 class="pcard-title">Dahua: ASA1222E-S</h5>
                      <p class="pcard-desc">A standalone time and attendance terminal 
                                            that uses fingerprint, password, or optional card 
                                            for employee clock-ins.</p>
                      <p class="pcard-price">₱5,500</p>
                    </div>
                  </div>
                </div>

                <!-- Product 3 -->
                <div class="carousel-item">
                  <div class="pcard mx-5 mb-5 mt-5">
                    <div class="image-wrap position-relative">
                      <img src="PICS/DAHUAattendance3.png" class="pcard-img" alt="Dahua Attendance 3">
                      <div class="pcard-overlay">
                        <a href="attendance3.php" class="view-details">View Details</a>
                      </div>
                    </div>
                    <div class="pcard-body">
                      <h5 class="pcard-title">Dahua: ASA1222E</h5>
                      <p class="pcard-desc">A standalone time and attendance recorder 
                                            used for managing employee work schedules 
                                            and attendance for monitoring.</p>
                      <p class="pcard-price">₱5,000</p>
                    </div>
                  </div>
              </div>
            </div>

            <!-- ✅ Carousel Controls (inside image area) -->
            <button class="carousel-control-prev" type="button" data-bs-target="#dahuaProductCarousel" data-bs-slide="prev">
              <span class="custom-icon" aria-hidden="true">
                <i class="bi bi-chevron-left text-white fs-4"></i>
              </span>
              <span class="visually-hidden">Previous</span>
            </button>

            <button class="carousel-control-next" type="button" data-bs-target="#dahuaProductCarousel" data-bs-slide="next">
              <span class="custom-icon" aria-hidden="true">
                <i class="bi bi-chevron-right text-white fs-4"></i>
              </span>
              <span class="visually-hidden">Next</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>



<!-- Footer -->
<footer class="footer bg-dark text-white py-5">
  <!-- Top Columns: Centered -->
  <div class="container mb-4">
    <div class="row justify-content-center text-center text-md-start">
      
      <!-- Column 1: Products -->
      <div class="col-md-3 mb-4">
        <h5 class="fw-bold text-uppercase mb-3 footer-title">Products</h5>
        <ul class="list-unstyled">
          <li><a href="products.php" class="footer-link">Attendance Device</a></li>
          <li><a href="cctv.php" class="footer-link">CCTV Device</a></li>
          <li><a href="accessdevice.php" class="footer-link">Access Panel</a></li>
        </ul>
      </div>

      <!-- Column 2: About Us -->
      <div class="col-md-3 mb-4">
        <h5 class="fw-bold text-uppercase mb-3 footer-title">About Us</h5>
        <ul class="list-unstyled">
          <li><a href="about.php" class="footer-link">Introduction</a></li>
          <li><a href="contact.php" class="footer-link">Contact Us</a></li>
          <li><a href="terms.php" class="footer-link">Terms of Use</a></li>
          <li><a href="privacy.php" class="footer-link">Privacy Policy</a></li>
        </ul>
      </div>

    </div>
  </div>

  <hr class="border-secondary">

  <!-- Bottom Row: Full Width -->
  <div class="container-fluid px-5">
    <div class="row align-items-center justify-content-between text-center text-md-start">
      
      <!-- Brand on left -->
      <div class="col-md-6 d-flex justify-content-md-start justify-content-center mb-3 mb-md-0">
        <h5 class="fw-bold footer-brand">DAHUA: <span class="text-danger">Timetrack</span></h5>
      </div>

      <!-- Social Media on right -->
      <div class="col-md-6 d-flex justify-content-md-end justify-content-center">
        <a href="https://www.facebook.com/DahuaHQ/" class="text-white me-3 social-link"><i class="bi bi-facebook"></i></a>
        <a href="https://www.instagram.com/dahua_malaysia/" class="text-white me-3 social-link"><i class="bi bi-instagram"></i></a>
        <a href="https://www.linkedin.com/company/dahua-technology" class="text-white me-3 social-link"><i class="bi bi-linkedin"></i></a>
        <a href="https://www.youtube.com/user/DahuaTechnology" class="text-white social-link"><i class="bi bi-youtube"></i></a>
      </div>

    </div>
  </div>

</footer>


<!-- Scroll-to-top button (appears when page is scrolled past 50%) -->
<button id="scrollTopBtn" class="scroll-btn" aria-label="Scroll to top">
  <i class="bi bi-arrow-up text-white fs-5"></i>
</button>








<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
crossorigin="anonymous"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const btn = document.getElementById('scrollTopBtn');
  if (!btn) return;

  // Check scroll position and toggle visibility when user has scrolled past 50% 
  function checkButtonVisibility() {
    const doc = document.documentElement;
    const scrollTop = window.scrollY || window.pageYOffset;
    const maxScroll = doc.scrollHeight - window.innerHeight; // total scrollable distance

    // if there's nothing to scroll, hide button
    if (maxScroll <= 0) {
      btn.classList.remove('visible');
      return;
    }

    const threshold = maxScroll * 0.5;
    if (scrollTop > threshold) {
      btn.classList.add('visible');
    } else {
      btn.classList.remove('visible');
    }
  }

  // initial check
  checkButtonVisibility();

  // update on scroll and resize (recalculate threshold on resize)
  window.addEventListener('scroll', checkButtonVisibility, { passive: true });
  window.addEventListener('resize', checkButtonVisibility);

  // Scroll to top when clicked
  btn.addEventListener('click', function () {
  // Scroll both html and body
  document.documentElement.scrollTo({ top: 0, behavior: 'smooth' });
  document.body.scrollTo({ top: 0, behavior: 'smooth' });
  });
});
</script>


</body> 
</html>
