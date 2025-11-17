<?php
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli("localhost", "root", "", "cogact");
    if ($conn->connect_error) {
        $message = "Connection failed: " . $conn->connect_error;
    } else {
        $username = $_POST['reg-username'] ?? '';
        $email = $_POST['reg-email'] ?? '';
        $password = $_POST['reg-password'] ?? '';
        if ($username && $email && $password) {
            $check = $conn->prepare("SELECT * FROM users WHERE username=? OR email=?");
            $check->bind_param("ss", $username, $email);
            $check->execute();
            $result = $check->get_result();
            if ($result->num_rows > 0) {
                $message = "Username or email already exists.";
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $username, $email, $hashed);
                if ($stmt->execute()) {
                    $message = "Registration successful!";
                } else {
                    $message = "Error: " . $stmt->error;
                }
            }
        } else {
            $message = "All fields are required.";
        }
        $conn->close();
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DAHUA: SIGN UP</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">    
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="icon" type="image/png" href="PICS/DAHUAfavi.png">

</head>
<body>
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
          <a href="#" class="nav-link nav-hover text-white">
            <i class="bi bi-person-circle"></i>
          </a>

          <!-- Profile Dropdown -->
          <div class="profile-panel bg-dark text-white rounded-3 shadow py-2">
            <a href="logsign.php" class="dropdown-item text-white py-2 px-3">Sign In</a>
          </div>
        </div>
      </div>

    </div>
  </div>
</nav>


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
                    <a href="terms.html" target="_blank" style="color:#007bff;text-decoration:underline;">Terms</a>
                    and
                    <a href="privacy.html" target="_blank" style="color:#007bff;text-decoration:underline;">Privacy Policy</a>
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

<script>
function togglePassword() {
  const passwordInput = document.getElementById('password');
  const icon = document.getElementById('togglePasswordIcon');
  if (passwordInput.type === 'password') {
    passwordInput.type = 'text';
    icon.classList.remove('bi-eye');
    icon.classList.add('bi-eye-slash');
  } else {
    passwordInput.type = 'password';
    icon.classList.remove('bi-eye-slash');
    icon.classList.add('bi-eye');
  }
}

function toggleRegisterPassword() {
  const passwordInput = document.getElementById('reg-password');
  const icon = document.getElementById('toggleRegisterPasswordIcon');
  if (passwordInput.type === 'password') {
    passwordInput.type = 'text';
    icon.classList.remove('bi-eye');
    icon.classList.add('bi-eye-slash');
  } else {
    passwordInput.type = 'password';
    icon.classList.remove('bi-eye-slash');
    icon.classList.add('bi-eye');
  }
}

function toggleRegisterConfirmPassword() {
  const confirmPasswordInput = document.getElementById('reg-confirm-password');
  const icon = document.getElementById('toggleRegisterConfirmPasswordIcon');
  if (confirmPasswordInput.type === 'password') {
    confirmPasswordInput.type = 'text';
    icon.classList.remove('bi-eye');
    icon.classList.add('bi-eye-slash');
  } else {
    confirmPasswordInput.type = 'password';
    icon.classList.remove('bi-eye-slash');
    icon.classList.add('bi-eye');
  }
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.querySelector('form');
  // Remove error state on input for text fields
  const fields = ['reg-username', 'reg-email', 'reg-password', 'reg-confirm-password'];
  fields.forEach(id => {
    const el = document.getElementById(id);
    if (el) {
      el.addEventListener('input', function() {
        // Remove error class and tooltip if field is valid
        if (el.classList.contains('error')) {
            if (id === 'reg-email') {
              // Email: must be non-empty, contain '@', a period after '@', and a valid domain ending
              const value = el.value.trim();
              // Simple regex for email validation
              const emailPattern = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
              if (emailPattern.test(value)) {
                el.classList.remove('error');
                const tooltip = el.parentElement.querySelector('.error-tooltip');
                if (tooltip) tooltip.remove();
              }
          } else if (id === 'reg-confirm-password') {
            // Confirm password: must match password
            const passwordEl = document.getElementById('reg-password');
            if (el.value.trim() && passwordEl.value === el.value) {
              el.classList.remove('error');
              const tooltip = el.parentElement.querySelector('.error-tooltip');
              if (tooltip) tooltip.remove();
            }
          } else {
            // Other fields: just non-empty
            if (el.value.trim()) {
              el.classList.remove('error');
              const tooltip = el.parentElement.querySelector('.error-tooltip');
              if (tooltip) tooltip.remove();
            }
          }
        }
      });
    }
  });
  // Remove error state on check for terms checkbox
  const terms = document.getElementById('registerTerms');
  if (terms) {
    terms.addEventListener('change', function() {
      if (terms.checked) {
        terms.classList.remove('error');
        const tooltip = terms.parentElement.querySelector('.error-tooltip');
        if (tooltip) tooltip.remove();
      }
    });
  }

  form.addEventListener('submit', function (e) {
    // Remove previous errors and error classes
    document.querySelectorAll('.error-tooltip').forEach(el => el.remove());
    document.querySelectorAll('.register-control').forEach(el => el.classList.remove('error'));
    document.querySelectorAll('.form-check-input').forEach(el => el.classList.remove('error'));
    let hasError = false;
    const username = document.getElementById('reg-username');
    const email = document.getElementById('reg-email');
    const password = document.getElementById('reg-password');
    const confirmPassword = document.getElementById('reg-confirm-password');
    const terms = document.getElementById('registerTerms');

    // Error checks
    if (!username.value.trim()) {
      showTooltip(username, "Username is required.");
      hasError = true;
    }
    if (!email.value.trim()) {
      showTooltip(email, "Email is required.");
      hasError = true;
    } else if (!email.value.includes('@')) {
      showTooltip(email, "Please include an '@' in the email address. '" + email.value + "' is missing an '@'.");
      hasError = true;
    }
    if (!password.value.trim()) {
      showTooltip(password, "Password is strictly required.");
      hasError = true;
    }
      if (!confirmPassword.value.trim()) {
        showTooltip(confirmPassword, "Please confirm your password.");
        hasError = true;
      } else if (password.value && confirmPassword.value && password.value !== confirmPassword.value) {
        showTooltip(confirmPassword, "Passwords do not match.");
        hasError = true;
      } else {
        // If confirm password is correct, remove error style immediately
        confirmPassword.classList.remove('error');
        const tooltip = confirmPassword.parentElement.querySelector('.error-tooltip');
        if (tooltip) tooltip.remove();
      }
    if (!terms.checked) {
      terms.classList.add('error');
      showTooltip(terms, "You must agree to the Terms and Privacy Policy.", true);
      hasError = true;
    } else {
      terms.classList.remove('error');
        // Remove tooltip if present
        const tooltip = terms.parentElement.querySelector('.error-tooltip');
        if (tooltip) tooltip.remove();
    }
    if (hasError) {
      // Prevent submission if there are errors
      e.preventDefault();
    }
    // If no error, allow normal form submission to backend
  });

  function showTooltip(input, message, isCheckbox) {
    if (isCheckbox) {
      let tooltip = document.createElement('div');
      tooltip.className = 'error-tooltip';
      tooltip.innerHTML = '<span class="error-icon"><i class="bi bi-exclamation-square-fill"></i></span>' + message;
      input.parentElement.insertBefore(tooltip, input.nextSibling);
      return;
    }
    input.classList.add('error');
    let tooltip = document.createElement('div');
    tooltip.className = 'error-tooltip';
    tooltip.innerHTML = '<span class="error-icon"><i class="bi bi-exclamation-square-fill"></i></span>' + message;
    input.parentElement.insertBefore(tooltip, input.nextSibling);
  }
});
</script>

</body> 
</html>