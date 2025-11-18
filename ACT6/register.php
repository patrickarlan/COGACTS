<?php include 'BACKEND/registererror.php'; ?>


<!doctype html>
<html lang="en">

<?php include 'COMPONENTS/head.php'; ?>

<body>
<?php include 'COMPONENTS/header.php'; ?>


<!--START HERE-->
<section class="login bg-light">
  <div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="container-login shadow">
      <div class="card login-panel shadow" style="width: 100%;">
            <h3 class="text-center mb-4">Register</h3>
            <form action="register.php" method="POST">
              <div class="mb-3 position-relative">
                  <label for="reg-username" class="form-label">Username</label>
                  <input type="text" class="form-control register-control" id="reg-username" name="reg-username" placeholder="Enter username">
              </div>
              <div class="mb-3 position-relative">
                  <label for="reg-email" class="form-label">Email</label>
                  <input type="email" class="form-control register-control" id="reg-email" name="reg-email" placeholder="Enter email">
              </div>
              <div class="mb-3 position-relative">
                  <label for="reg-password" class="form-label">Password</label>
                  <div style="position:relative;">
                    <input type="password" class="form-control register-control" id="reg-password" name="reg-password" placeholder="Enter password" style="padding-right:2.5rem;">
                    <span class="show-password-icon" onclick="toggleRegisterPassword()" style="position:absolute; top:50%; right:1rem; transform:translateY(-50%); cursor:pointer;">
                      <i class="bi bi-eye" id="toggleRegisterPasswordIcon" style="font-size:1.5rem;"></i>
                    </span>
                  </div>
              </div>
              <div class="mb-3 position-relative">
                  <label for="reg-confirm-password" class="form-label">Confirm Password</label>
                  <div style="position:relative;">
                    <input type="password" class="form-control register-control" id="reg-confirm-password" placeholder="Confirm password" style="padding-right:2.5rem;">
                    <span class="show-password-icon" onclick="toggleRegisterConfirmPassword()" style="position:absolute; top:50%; right:1rem; transform:translateY(-50%); cursor:pointer;">
                      <i class="bi bi-eye" id="toggleRegisterConfirmPasswordIcon" style="font-size:1.5rem;"></i>
                    </span>
                  </div>
              </div>
              <div class="mb-3 form-check position-relative">
                  <input type="checkbox" class="form-check-input" id="registerTerms">
                  <label class="form-check-label" for="registerTerms">
                    I agree to the
                    <a href="terms.php" target="_blank" style="color:#007bff;text-decoration:underline;">Terms</a>
                    and
                    <a href="privacy.php" target="_blank" style="color:#007bff;text-decoration:underline;">Privacy Policy</a>
                  </label>
              </div>
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary w-100 mb-2 register-btn">Register</button>
                <button type="button" class="btn btn-outline-primary w-100 register-btn" style="padding: 1rem 0; font-size: 1.25rem;" onclick="window.location.href='logsign.html'">Back to Login</button>
              </div>
          </form>
          <?php if ($message): ?>
            <?php if ($message === 'Registration successful!'): ?>
              <div class="alert alert-success mt-3 text-center">Registration successful! You can now <a href="logsign.php">login</a>.</div>
            <?php else: ?>
              <div class="alert alert-danger mt-3 text-center"><?php echo $message; ?></div>
            <?php endif; ?>
          <?php endif; ?>
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

<?php include 'BACKEND/registerjs.php'; ?>

</body> 
</html>