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
            <?php if (!empty($registration_success) && $message === 'Registration successful!'): ?>
              <!-- Success modal trigger; modal will be rendered below -->
            <?php else: ?>
              <div class="alert alert-danger mt-3 text-center"><?php echo $message; ?></div>
            <?php endif; ?>
          <?php endif; ?>
      </div>
    </div> 
  </div>
</section>

<!-- Success Modal (hidden by default) -->
<?php if (!empty($registration_success) && $message === 'Registration successful!'): ?>
  <div id="regSuccessOverlay" aria-hidden="false" style="position:fixed;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(2,17,44,0.35);backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px);z-index:9999;">
    <div id="regSuccessPanel" role="dialog" aria-modal="true" style="background:#ffffff;color:#0b1726;border-radius:12px;padding:28px;max-width:480px;width:92%;box-shadow:0 10px 40px rgba(2,17,44,0.12);border:1px solid rgba(6,44,91,0.06);text-align:center;backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px);">
      <h4 class="mb-2" style="color:#062c5b;margin-bottom:8px;">Registration successful!</h4>
      <p class="mb-3" style="color:#062c5b;margin-bottom:18px;">Your account has been created.</p>
      <div style="display:flex;gap:12px;justify-content:center;">
        <button id="regGoLogin" class="btn btn-primary" style="min-width:120px;">Login</button>
        <button id="regAnother" class="btn btn-outline-primary" style="min-width:120px;">Register another</button>
      </div>
    </div>
  </div>
  <script>
    (function(){
      var overlay = document.getElementById('regSuccessOverlay');
      var panel = document.getElementById('regSuccessPanel');
      function closeOverlay(){
        if (overlay) overlay.remove();
      }
      // Button handlers
      document.getElementById('regGoLogin').addEventListener('click', function(){
        window.location.href = 'logsign.php';
      });
      document.getElementById('regAnother').addEventListener('click', function(){
        // reset the form inputs to empty/placeholder-ready state
        var form = document.querySelector('form');
        if (!form) return;
        form.reset();
        // remove validation error states/tooltips if present
        document.querySelectorAll('.error-tooltip').forEach(function(it){ it.remove(); });
        document.querySelectorAll('.register-control').forEach(function(it){ it.classList.remove('error'); });
        closeOverlay();
      });
      // Do NOT dismiss when clicking outside or pressing Escape — modal is mandatory
      // (overlay covers the page and prevents interaction with underlying content)
      overlay.addEventListener('click', function(e){
        // prevent clicks from reaching underlying page
        e.stopPropagation();
        e.preventDefault();
      });
      // Accessibility: focus the primary button
      var primary = document.getElementById('regGoLogin');
      if (primary) primary.focus();
    })();
  </script>
<?php endif; ?>


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