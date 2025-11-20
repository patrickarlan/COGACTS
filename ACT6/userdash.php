<?php
// requireLogin.php logic embedded at the very top
session_start();

// If not logged in, redirect immediately
if (!isset($_SESSION['user_id'])) {
    header("Location: logsign.php");
    exit();
}

// Determine whether to show the welcome popup on this login.
// Use a server-side session flag so logging out/clearing the session will show it again on next login.
$showWelcome = empty($_SESSION['welcome_shown']);
if ($showWelcome) {
  // mark it shown for subsequent requests in this session
  $_SESSION['welcome_shown'] = 1;
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DAHUA: DASHBOARD</title>
    <!-- BFCache / back button protection -->
    <script>
    window.addEventListener("pageshow", function(event) {
        if (event.persisted || (performance.getEntriesByType("navigation")[0].type === "back_forward")) {
            window.location.replace("logsign.php");
        }
    });
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="icon" type="image/png" href="PICS/DAHUAfavi.png">
    <link rel="stylesheet" href="style.css">
    <style>  /* Page overlay - ensures the white overlay can fade out on this page */
    #pageOverlay.page-overlay {
      position: fixed;
      inset: 0;
      background: #ffffff;
      z-index: 99999;
      opacity: 1;
      transition: opacity 0.5s ease;
      pointer-events: none;
    }
    /* Stronger override class — uses !important so it can't be defeated by other CSS rules */
    #pageOverlay.inline-fade {
      opacity: 0 !important;
      transition: opacity 0.5s ease !important;
      pointer-events: none !important;
    }
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
    </style>
  </head>

  <body style="flex-direction: column; min-height:100vh;">  

<!-- Page Overlay -->
<div id="pageOverlay" class="page-overlay" aria-hidden="true"></div>

<!-- Welcome Popout Modal (rendered only when server-side session requests it) -->
<?php if (!empty(
$showWelcome
)) : ?>
  <div id="welcomeOverlay" class="welcome-overlay">
    <div class="welcome-modal">
      <h4 class="mb-3">Welcome to your Dashboard!</h4>
      <p class="mb-4">We're glad to have you here. Explore your favourites and orders below.</p>
      <button id="thankYouBtn" class="btn btn-success px-4">Thank You</button>
    </div>
  </div>
<?php endif; ?>

<?php include 'COMPONENTS/header.php'; ?>


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
            <a href="BACKEND/editprofile.php" class="btn btn-outline-primary btn-sm">Edit Profile</a>
            <div class="mt-2">
              <a href="BACKEND/accountsettings.php" class="btn btn-outline-success btn-sm">Account Settings</a>
            </div>
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

<?php include 'COMPONENTS/footer.php'; ?>

<!-- Scroll-to-top button (appears when page is scrolled past 50%) -->
<button id="scrollTopBtn" class="scroll-btn" aria-label="Scroll to top">
  <i class="bi bi-arrow-up text-white fs-5"></i>
</button>


<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
crossorigin="anonymous"></script>


<script>
  // Robust fade-out for the page overlay using an !important override class
  document.addEventListener('DOMContentLoaded', function () {
    var overlay = document.getElementById('pageOverlay');
    if (!overlay) return;

    // Small timeout so the overlay is visible for a moment
    setTimeout(function () {
      // Force a reflow so the browser notices the transition
      // eslint-disable-next-line no-unused-expressions
      overlay.offsetWidth;

      // Add a class that uses !important to force the opacity transition
      overlay.classList.add('inline-fade');

      // Remove from DOM after transition completes (give 600ms to be safe)
      setTimeout(function () {
        if (overlay && overlay.parentNode) overlay.parentNode.removeChild(overlay);
      }, 600);
    }, 80);
  });
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const overlay = document.getElementById('welcomeOverlay');
  const btn = document.getElementById('thankYouBtn');
  if (!overlay) return;

  // Server controls whether the overlay exists on first login; simply show it when present
  setTimeout(() => overlay.classList.add('show'), 300);

  if (btn) {
    btn.addEventListener('click', function () {
      overlay.classList.remove('show');
      overlay.classList.add('hide');
      setTimeout(() => {
        overlay.classList.remove('hide');
        overlay.style.display = 'none';
      }, 400);
    });
  }
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