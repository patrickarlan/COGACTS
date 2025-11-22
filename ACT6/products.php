<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  include 'COMPONENTS/needtosign.php';
  showNeedToSignPopup();
  exit;
}

// If not logged in, redirect immediately
if (!isset($_SESSION['user_id'])) {
    header("Location: logsign.php");
    exit();
}

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0, private");
header("Pragma: no-cache");
header("Expires: 0");

// Fetch user data
$username = $_SESSION['username'] ?? 'User';
$email = '';
$conn = new mysqli("localhost", "root", "", "cogact");
if (!$conn->connect_error) {
    $stmt = $conn->prepare("SELECT email FROM users WHERE id=?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $email = $row['email'];
    }
    $conn->close();
}
?>

<!doctype html>
<html lang="en">
  <head>
        <!-- Inline JS to prevent flash if logged out -->
    <script>
    if (!<?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>) {
        window.location.replace("logsign.php");
    }
    </script>

    <!-- BFCache / back button protection -->
    <script>
    window.addEventListener("pageshow", function(event) {
        if (event.persisted || (performance.getEntriesByType("navigation")[0].type === "back_forward")) {
            window.location.replace("logsign.php");
        }
    });
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DAHUA: Timetrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="icon" type="image/png" href="PICS/DAHUAfavi.png">
    <link rel="stylesheet" href="style.css?v=20251118-1">
  </head>

<body>
<?php include 'COMPONENTS/header.php'; ?>

<!-- Second Header / Banner -->
<div class="container-fluid p-0 bg-dark">
  <div class="banner">
    <img src="PICS/DAHUAprodbanner.png" alt="Banner Image" class="img-fluid">
  </div>
</div>

<!-- PRODUCT Section -->
<section class="product-section py-5 bg-light">
  <div class="container">
    <!-- White panel wrapper -->
    <div class="product-panel bg-white shadow-lg rounded-4 p-5">
      <h2 class="text-center mb-2 fw-bold text-dark">Our Attendance Device Products</h2>
      <p class="text-center mb-3 text-secondary">
        Explore our range of advanced attendance devices designed to streamline your workforce management.
      <div class="row g-4">
        <!-- Product Card 1 -->
        <div class="col-md-4">
          <div class="card product-card h-100 text-center shadow-sm border-0">
            <div class="card-img-wrapper">
              <img src="PICS/DAHUAprod1.png" class="card-img-top" alt="Attendance1">
            </div>
            <div class="card-body d-flex flex-column justify-content-between">
              <div>
                <h5 class="card-title fw-semibold">ASA1222G</h5>
                <p class="card-text text-secondary">
                  Time & Attendance Terminal
                </p>
              </div>
              <p class="price text-success fw-bold fs-5 mt-3">₱10,000</p>
            </div>
            <div class="card-footer bg-transparent pb-3 border-0">
              <a href="attendancedevice.php" class="btn btn-primary w-100 fw-semibold">Learn More</a>
            </div>
          </div>
        </div>

        <!-- Product Card 2 -->
        <div class="col-md-4">
          <div class="card product-card h-100 text-center shadow-sm border-0">
            <div class="card-img-wrapper">
              <img src="PICS/DAHUAattendance2.png" class="card-img-top" alt="Attendance2">
            </div>
            <div class="card-body d-flex flex-column justify-content-between">
              <div>
                <h5 class="card-title fw-semibold">ASA1222E-S</h5>
                <p class="card-text text-secondary">
                  Time & Attendance Terminal
                </p>
              </div>
              <p class="price text-success fw-bold fs-5 mt-3">₱5,500</p>
            </div>
            <div class="card-footer bg-transparent pb-3 border-0">
              <a href="attendance2.php" class="btn btn-primary w-100 fw-semibold">Learn More</a>
            </div>
          </div>
        </div>

        <!-- Product Card 3 -->
        <div class="col-md-4">
          <div class="card product-card h-100 text-center shadow-sm border-0">
            <div class="card-img-wrapper">
              <img src="PICS/DAHUAattendance3.png" class="card-img-top" alt="Attendance3">
            </div>
            <div class="card-body d-flex flex-column justify-content-between">
              <div>
                <h5 class="card-title fw-semibold">ASA1222E</h5>
                <p class="card-text text-secondary">
                  Time & Attendance Terminal
                </p>
              </div>
              <p class="price text-success fw-bold fs-5 mt-3">₱5,000</p>
            </div>
            <div class="card-footer bg-transparent pb-3 border-0">
              <a href="attendance3.php" class="btn btn-primary w-100 fw-semibold">Learn More</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>




<!-- Footer -->
 <!-- Footer Section -->
<footer class="footer bg-dark text-white py-5">

  <!-- Top Columns: Centered -->
  <div class="container mb-4">
    <div class="row justify-content-center text-center text-md-start">
      
      <!-- Column 1: Products -->
      <div class="col-md-3 mb-4">
        <h5 class="fw-bold text-uppercase mb-3 footer-title">Products</h5>
        <ul class="list-unstyled">
          <li><a href="products.html" class="footer-link">Attendance Device</a></li>
          <li><a href="cctv.html" class="footer-link">CCTV Device</a></li>
          <li><a href="accessdevice.html" class="footer-link">Access Panel</a></li>
        </ul>
      </div>

      <!-- Column 2: About Us -->
      <div class="col-md-3 mb-4">
        <h5 class="fw-bold text-uppercase mb-3 footer-title">About Us</h5>
        <ul class="list-unstyled">
          <li><a href="about.html" class="footer-link">Introduction</a></li>
          <li><a href="contact.html" class="footer-link">Contact Us</a></li>
          <li><a href="terms.html" class="footer-link">Terms of Use</a></li>
          <li><a href="privacy.html" class="footer-link">Privacy Policy</a></li>
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