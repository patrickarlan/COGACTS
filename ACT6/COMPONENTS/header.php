<?php
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
          <a class="nav-link nav-hover" href="products.php">Products</a>

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
          <a class="nav-link nav-hover" href="about.php">About Us</a>

          <!-- Hover Panel -->
          <div class="hover-panel bg-dark text-white py-4 px-0 shadow rounded-3">
            <ul class="list-unstyled m-0">
              <li><a href="about.php" class="dropdown-item text-white py-2 px-3">Introduction</a></li>
              <li><a href="contact.php" class="dropdown-item text-white py-2 px-3">Contact Us</a></li>
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
  </div>
</nav>
