<?php
session_start();
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $conn = new mysqli("localhost", "root", "", "cogact");
  if ($conn->connect_error) {
    $message = "Connection failed: " . $conn->connect_error;
  } else {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    if ($username && $password) {
      $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username=?");
      $stmt->bind_param("s", $username);
      $stmt->execute();
      $result = $stmt->get_result();
      if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
                    // Set session variables for the logged-in user
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['username'] = $row['username'];
                    // Redirect to user dashboard
                    header('Location: userdash.php');
                    exit();
        } else {
           $message = "ERROR: Username or Password is incorrect";
        }
      } else {
          $message = "ERROR: Username or Password is incorrect";
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
    <title>DAHUA: SIGN IN</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">    
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="icon" type="image/png" href="PICS/DAHUAfavi.png">

</head>
<body>
<?php include 'COMPONENTS/header.php'; ?>


<!--START HERE-->
<section class="login bg-light">
  <div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="container-login shadow">
      <div class="card login-panel shadow" style="width: 100%;">
            <h3 class="text-center mb-4">Login</h3>
            <form action="logsign.php" method="POST">
              <div class="mb-3 position-relative">
                  <label for="username" class="form-label">Username</label>
                  <input type="text" class="form-control login-control" id="username" name="username" placeholder="Enter username">
              </div>
              <div class="mb-3 position-relative">
                  <label for="password" class="form-label">Password</label>
                  <div style="position:relative;">
                    <input type="password" class="form-control login-control" id="password" name="password" placeholder="Enter password" style="padding-right:2.5rem;">
                    <span class="show-password-icon" onclick="togglePassword()" style="position:absolute; top:50%; right:1rem; transform:translateY(-50%); cursor:pointer;">
                      <i class="bi bi-eye" id="togglePasswordIcon" style="font-size:1.5rem;"></i>
                    </span>
                  </div>
              </div>
              <div class="mb-3 form-check">
                  <input type="checkbox" class="form-check-input" id="rememberMe">
                  <label class="form-check-label" for="rememberMe">Remember me</label>
              </div>
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary w-100 mb-2">Login</button>
                <button type="button" class="btn btn-outline-primary w-100" style="padding: 1rem 0; font-size: 1.25rem;" onclick="window.location.href='register.php'">Register</button>
              </div>
          </form>
      </div>
      <?php if ($message): ?>
        <?php if ($message === 'Login successful!'): ?>
          <div class="alert alert-success mt-3 text-center">Login successful!</div>
        <?php else: ?>
          <div class="alert alert-danger mt-3 text-center"><?php echo $message; ?></div>
        <?php endif; ?>
      <?php endif; ?>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
  // Scroll-to-top button logic
  const btn = document.getElementById('scrollTopBtn');
  if (btn) {
    function checkButtonVisibility() {
      const doc = document.documentElement;
      const scrollTop = window.scrollY || window.pageYOffset;
      const maxScroll = doc.scrollHeight - window.innerHeight;
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
    checkButtonVisibility();
    window.addEventListener('scroll', checkButtonVisibility, { passive: true });
    window.addEventListener('resize', checkButtonVisibility);
    btn.addEventListener('click', function () {
      document.documentElement.scrollTo({ top: 0, behavior: 'smooth' });
      document.body.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  // Error tooltip and validation logic for login
  const form = document.querySelector('form');
  const username = document.getElementById('username');
  const password = document.getElementById('password');
  // Remove error state on input
  [username, password].forEach(el => {
    if (el) {
      el.addEventListener('input', function() {
        if (el.classList.contains('error')) {
          if (el.value.trim()) {
            el.classList.remove('error');
            const tooltip = el.parentElement.querySelector('.error-tooltip');
            if (tooltip) tooltip.remove();
          }
        }
      });
    }
  });
  form.addEventListener('submit', function(e) {
    // Only prevent submission if there are errors
    document.querySelectorAll('.error-tooltip').forEach(el => el.remove());
    document.querySelectorAll('.login-control').forEach(el => el.classList.remove('error'));
    let hasError = false;
    if (!username.value.trim()) {
      showTooltip(username, "Username is required.");
      hasError = true;
    }
    if (!password.value.trim()) {
      showTooltip(password, "Password is strictly required.");
      hasError = true;
    }
    if (hasError) {
      e.preventDefault();
    }
    // If no error, allow normal form submission to backend
  });
  function showTooltip(input, message) {
    input.classList.add('error');
    let tooltip = document.createElement('div');
    tooltip.className = 'error-tooltip';
    tooltip.innerHTML = '<span class="error-icon"><i class="bi bi-exclamation-square-fill"></i></span>' + message;
    input.parentElement.insertBefore(tooltip, input.nextSibling);
  }
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
</script>

</body> 
</html>