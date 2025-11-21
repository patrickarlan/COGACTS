<?php
// Prevent Chrome BFCache — only send headers if they haven't already been sent
if (!headers_sent()) {
  header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0, private");
  header("Pragma: no-cache");
  header("Expires: 0");
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- Header/Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark py-4 px-0">
  <div class="container-fluid">
    <!-- Website Title -->
     <a class="navbar-brand nav-hover fw-bold text-white d-flex align-items-center" href="index.php">
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
          <a class="nav-link nav-hover" href="products.php">Products <span class="dropdown-toggle-icon d-lg-none">&#9660;</span></a>

          <!-- Hover Panel -->
          <div class="hover-panel bg-dark text-white py-4 px-0 shadow rounded-3">
            <ul class="list-unstyled m-0">
              <li><a href="attendancedevice.php" class="dropdown-item text-white py-2 px-3">ASA1222G</a></li>
              <li><a href="attendance2.php" class="dropdown-item text-white py-2 px-3">ASA1222E-S</a></li>
              <li><a href="attendance3.php" class="dropdown-item text-white py-2 px-3">ASA1222E</a></li>
            </ul>
          </div>
        </li>

        <!-- About Us with hover panel -->
        <li class="nav-item position-relative hover-panel-parent">
          <a class="nav-link nav-hover" href="about.php">About Us <span class="dropdown-toggle-icon d-lg-none">&#9660;</span></a>

          <!-- Hover Panel -->
          <div class="hover-panel bg-dark text-white py-4 px-0 shadow rounded-3">
            <ul class="list-unstyled m-0">
              <li><a href="about.php" class="dropdown-item text-white py-2 px-3">Introduction</a></li>
              <li><a href="contact.php" class="dropdown-item text-white py-2 px-3">Contact Us</a></li>
            </ul>
          </div>
        </li>
      </ul>

    <div class="icon-container">
      <!-- Icons (Search & Profile) -->
      <div class="icons d-flex align-items-center position-relative">

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
              <?php /* Use server-side router to decide destination on click */ ?>
              <a href="/COG3BACK/ACT6/profile.php" class="nav-link nav-hover text-white profile-icon-link">
            <i class="bi bi-person-circle"></i>
          </a>
          <!-- Profile Dropdown -->
          <div class="profile-panel bg-dark text-white rounded-3 shadow py-2">
            <?php if (isset($_SESSION['user_id'])): ?>
              <a href="COMPONENTS/logout.php" class="dropdown-item text-white py-2 px-3">Logout</a>
            <?php else: ?>
              <a href="logsign.php" class="dropdown-item text-white text-center py-2">Sign In</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>  
    </div>
  </div>
</nav>

<script>
  document.addEventListener('click', function(event) {
  // Replace '.hover-panel.show' with your actual open menu selector
  const openPanels = document.querySelectorAll('.hover-panel.show');
  openPanels.forEach(panel => {
    if (!panel.contains(event.target) && !event.target.closest('.hover-panel-parent')) {
      panel.classList.remove('show');
      // reset aria-expanded on the toggle icon if present
      var icon = panel.parentElement.querySelector('.dropdown-toggle-icon');
      if (icon) icon.setAttribute('aria-expanded', 'false');
    }
  });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  function isMobile() {
    return window.innerWidth <= 992;
  }
  document.querySelectorAll('.hover-panel-parent').forEach(function(parent) {
    var link = parent.querySelector('.nav-link');
    var panel = parent.querySelector('.hover-panel');
    var icon = parent.querySelector('.dropdown-toggle-icon');
    if (icon) {
      icon.addEventListener('click', function(e) {
        // prevent the anchor navigation when tapping the chevron
        e.stopPropagation();
        e.preventDefault();
        if (isMobile()) {
          // Close other panels and reset their toggle states
          document.querySelectorAll('.hover-panel.show').forEach(function(otherPanel) {
            if (otherPanel !== panel) {
              otherPanel.classList.remove('show');
              var otherIcon = otherPanel.parentElement.querySelector('.dropdown-toggle-icon');
              if (otherIcon) otherIcon.setAttribute('aria-expanded', 'false');
            }
          });
          panel.classList.toggle('show');
          // update aria-expanded on this icon
          icon.setAttribute('aria-expanded', panel.classList.contains('show') ? 'true' : 'false');
        }
      });
    }
    // NOTE: do not intercept link clicks — anchor text should navigate normally.
  });
  window.addEventListener('resize', function() {
    if (!isMobile()) {
      document.querySelectorAll('.hover-panel').forEach(function(panel) {
        panel.classList.remove('show');
      });
    }
  });
});
</script>
