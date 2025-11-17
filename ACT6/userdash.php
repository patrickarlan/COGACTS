<?php
session_start();
// Prevent browser caching so back button doesn't show dashboard after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
if (!isset($_SESSION['user_id'])) {
  header('Location: logsign.php');
  exit();
}
$username = $_SESSION['username'] ?? 'User';
$email = '';
// Fetch user email from database
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DAHUA: DASHBOARD</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">    
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="icon" type="image/png" href="PICS/DAHUAfavi.png">
    <style>
      .welcome-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(8px);
        z-index: 99999;
        display: none;
        align-items: center;
        justify-content: center;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.4s cubic-bezier(.4,0,.2,1);
      }
      .welcome-overlay.show {
        display: flex;
        opacity: 1;
        pointer-events: auto;
      }
      .welcome-overlay.hide {
        opacity: 0;
        pointer-events: none;
      }
      .welcome-modal {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.18);
        padding: 2.5rem 2rem 2rem 2rem;
        min-width: 320px;
        max-width: 90vw;
        text-align: center;
        animation: popIn 0.5s cubic-bezier(.4,0,.2,1);
      }
      @keyframes popIn {
        0% { transform: scale(0.7); opacity: 0; }
        60% { transform: scale(1.05); opacity: 1; }
        100% { transform: scale(1); opacity: 1; }
      }
    </style>

</head>
<body style="display: flex; flex-direction: column; min-height: 100vh;">
<!-- Welcome Popout Modal -->
<div id="welcomeOverlay" class="welcome-overlay">
  <div class="welcome-modal">
    <h4 class="mb-3">Welcome to your Dashboard!</h4>
    <p class="mb-4">We're glad to have you here. Explore your favourites and orders below.</p>
    <button id="thankYouBtn" class="btn btn-success px-4">Thank You</button>
  </div>
</div>
<!-- Header/Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark py-4 px-0">
  <div class="container-fluid">
    <!-- Website Title -->
     <a class="navbar-brand nav-hover fw-bold text-white d-flex align-items-center" href="index.html">
        <img src="PICS/DAHUAfavi.png" alt="Logo" class="navbar-logo ms-3 mx-3">
        <span>DAHUA: Timetrack</span>
    </a>


    <!-- Navbar Toggler (for mobile) -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button> 

    <!-- Collapsible Navbar -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <!-- Navigation Links -->
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0 custom-nav">
        
        <!-- Products with hover panel -->
        <li class="nav-item position-relative hover-panel-parent">
          <a class="nav-link nav-hover" href="products.html">Products</a>

          <!-- Hover Panel -->
          <div class="hover-panel bg-dark text-white py-4 px-0 shadow rounded-3">
            <ul class="list-unstyled m-0">
              <li><a href="attendancedevice.html" class="dropdown-item text-white py-2 px-3">Attendance Monitoring</a></li>
              <li><a href="accessdevice.html" class="dropdown-item text-white py-2 px-3">CCTV</a></li>
              <li><a href="accessdevice.html" class="dropdown-item text-white py-2 px-3">Access Control</a></li>
            </ul>
          </div>
        </li>

        <!-- About Us with hover panel -->
        <li class="nav-item position-relative hover-panel-parent">
          <a class="nav-link nav-hover" href="about.html">About Us</a>

          <!-- Hover Panel -->
          <div class="hover-panel bg-dark text-white py-4 px-0 shadow rounded-3">
            <ul class="list-unstyled m-0">
              <li><a href="about.html" class="dropdown-item text-white py-2 px-3">Introduction</a></li>
              <li><a href="contact.html" class="dropdown-item text-white py-2 px-3">Contact Us</a></li>
            </ul>
          </div>
        </li>
      </ul>

      <!-- Icons (Search & Profile) -->
      <div class="d-flex align-items-center position-relative">

                <!-- 🔍 Search Icon (Hover-based) -->
                <div class="search-container position-relative me-3">
                  <a href="#" class="nav-link nav-hover text-white">
                    <i class="bi bi-search"></i>
                  </a>

                  <!-- Search Panel -->
                  <div class="search-panel bg-dark text-white p-3 rounded-3 shadow">
                    <input 
                    type="text" 
                    class="form-control bg-secondary text-white border-0" 
                    placeholder="Search product..."
                    >
                  </div>
                </div>


        <!-- 👤 Profile Icon -->
        <div class="profile-container position-relative">
          <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="userdash.php" class="nav-link nav-hover text-white">
                      <i class="bi bi-person-circle"></i>
                    </a>
                  <?php else: ?>
                    <span class="nav-link nav-hover text-white" style="cursor: default;">
                      <i class="bi bi-person-circle"></i>
                    </span>
                  <?php endif; ?>

          <!-- Profile Dropdown -->
          <div class="profile-panel bg-dark text-white rounded-3 shadow py-2">
            <?php if (isset($_SESSION['user_id'])): ?>
              <a href="logout.php" class="dropdown-item text-white py-2 px-3">Logout</a>
            <?php else: ?>
              <a href="logsign.php" class="dropdown-item text-white py-2 px-3">Sign In</a>
            <?php endif; ?>
        </div>
      </div>

    </div>
  </div>
</nav>


<!-- Second Header / Banner -->
<main style="flex: 1 0 auto;">
<div class="container-fluid p-0 bg-dark">
  <div class="banner">
    <img src="PICS/DAHUAcontact.png" alt="Banner Image" class="img-fluid">
  </div>
</div>

<!--START HERE-->
<section class="dashboard-section py-1 m0 bg-light">
  <div class="dashboard-section container my-3 bg-light">
    <div class="row justify-content-center align-items-start">
      <!-- Profile Panel -->
      <div class="col-md-3 mb-4">
        <div class="card h-100 shadow-sm">
          <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Profile</h5>
          </div>
          <div class="card-body text-center">
            <img src="PICS/DAHUAfavi.png" alt="Profile" class="rounded-circle mb-3" style="width:80px;height:80px;object-fit:cover;">
            <h6 class="mb-1"><?php echo htmlspecialchars($username); ?></h6>
            <p class="text-muted mb-2"><?php echo htmlspecialchars($email); ?></p>
            <a href="#" class="btn btn-outline-primary btn-sm">Edit Profile</a>
          </div>
        </div>
      </div>
      <!-- Favourite Panel -->
      <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Favourite Panel</h5>
          </div>
          <div class="card-body">
            <p class="text-muted">Your favourite products will appear here.</p>
            <ul class="list-group list-group-flush" id="favourite-list">
              <li class="list-group-item">No favourites yet.</li>
            </ul>
          </div>
        </div>
      </div>
      <!-- Order Panel -->
      <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
          <div class="card-header bg-success text-white">
            <h5 class="mb-0">Order Panel</h5>
          </div>
          <div class="card-body">
            <p class="text-muted">Your recent orders will appear here.</p>
            <ul class="list-group list-group-flush" id="order-list">
              <li class="list-group-item">No orders yet.</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</main>




<!-- Footer -->
 <!-- Footer Section -->
<footer class="footer bg-dark text-white py-5" style="flex-shrink: 0;">

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
  const overlay = document.getElementById('welcomeOverlay');
  const btn = document.getElementById('thankYouBtn');
  setTimeout(() => {
    overlay.classList.add('show');
  }, 300);
  btn.addEventListener('click', function () {
    overlay.classList.remove('show');
    overlay.classList.add('hide');
    setTimeout(() => {
      overlay.classList.remove('hide');
      overlay.style.display = 'none';
    }, 400);
  });
});
</script>

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