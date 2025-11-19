<?php include 'BACKEND/registererror.php'; ?>


<!doctype html>
<html lang="en">

<?php include 'COMPONENTS/head.php'; ?>

<body>
<?php include 'COMPONENTS/header.php'; ?>


<!--START HERE-->
<section class="login">
    <div class="container-reg py-5 shadow">
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


<?php include 'COMPONENTS/footer.php'; ?>


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