<?php
session_start();

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0, private");
header("Pragma: no-cache");
header("Expires: 0");

// If already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: userdash.php");
    exit();
}


$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $conn = new mysqli("localhost", "root", "", "cogact");
  if ($conn->connect_error) {
    $message = "Connection failed: " . $conn->connect_error;
  } else {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['rememberMe']);
    // Load protected admin config if present (server-side only)
    $adminConfig = null;
    $adminConfigPath = __DIR__ . '/config/admin.php';
    if (file_exists($adminConfigPath)) {
        $adminConfig = include $adminConfigPath; // returns ['username'=>..., 'password_hash'=>...]
    }
    if ($username && $password) {
      $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username=?");
      $stmt->bind_param("s", $username);
      $stmt->execute();
      $result = $stmt->get_result();
      $row = $result->fetch_assoc();

      $loggedIn = false;
      $isAdminLogin = false;

        // Primary: check DB user
        if ($row && password_verify($password, $row['password'])) {
          $_SESSION['user_id'] = $row['id'];
          $_SESSION['username'] = $row['username'];
          // If the users table has a 'role' column, fetch and set it in session
          $role = null;
          $colRes = $conn->query("SHOW COLUMNS FROM users LIKE 'role'");
          if ($colRes && $colRes->num_rows > 0) {
            // fetch role for this user
            $rstmt = $conn->prepare('SELECT role FROM users WHERE id=?');
            $rstmt->bind_param('i', $row['id']);
            $rstmt->execute();
            $rres = $rstmt->get_result();
            if ($rrow = $rres->fetch_assoc()) {
              $role = $rrow['role'];
              // normalize and store role as lowercase string
              $_SESSION['role'] = is_string($role) ? strtolower(trim($role)) : $role;
              if (is_string($role) && strtolower(trim($role)) === 'admin') {
                $_SESSION['is_admin'] = 1;
                $isAdminLogin = true;
              }
            }
          }
          $loggedIn = true;
          // If this DB username matches the protected admin username and password also matches, mark admin
          if ($adminConfig && ($row['username'] === ($adminConfig['username'] ?? '')) && password_verify($password, $adminConfig['password_hash'] ?? '')) {
            $_SESSION['is_admin'] = 1;
            $_SESSION['role'] = 'admin';
            $isAdminLogin = true;
          }
      }

      // Fallback: if no DB user matched, check protected admin config
        if (!$loggedIn && $adminConfig && ($username === ($adminConfig['username'] ?? '')) && password_verify($password, $adminConfig['password_hash'] ?? '')) {
          // Protected admin login (does not require a DB user). Assign a sentinel user_id=0.
          $_SESSION['user_id'] = 0;
          $_SESSION['username'] = $adminConfig['username'];
          $_SESSION['is_admin'] = 1;
          $_SESSION['role'] = 'admin';
          $loggedIn = true;
          $isAdminLogin = true;
      }

      if ($loggedIn) {
                    // Set cookies if remember me is checked. IMPORTANT: do NOT store passwords in cookies.
                    if ($remember && !$isAdminLogin) {
                      // only remember the username; storing plaintext passwords in cookies is insecure
                      setcookie('remember_username', $username, time()+60*60*24*30, '/');
                    } else {
                      setcookie('remember_username', '', time()-3600, '/');
                    }
                    // Redirect: admins -> admin panel, others -> user dashboard
                    if (!empty($_SESSION['is_admin'])) {
                        header('Location: BACKEND/admin.php');
                    } else {
                        header('Location: userdash.php');
                    }
                    exit();
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

<?php include 'COMPONENTS/head.php'; ?>

<body>
<?php include 'COMPONENTS/header.php'; ?>


<section class="login">
    <div class="container-login shadow">
      <div class="card login-panel shadow" style="width: 100%;">
            <h3 class="text-center mb-4">Login</h3>
            <form action="logsign.php" method="POST" autocomplete="off">
              <div class="mb-3 position-relative">
                  <label for="username" class="form-label">Username</label>
                  <input type="text" class="form-control login-control" id="username" name="username" placeholder="Enter username" autocomplete="username" value="<?php echo isset($_COOKIE['remember_username']) ? htmlspecialchars($_COOKIE['remember_username']) : ''; ?>">
              </div>
              <div class="mb-3 position-relative">
                  <label for="password" class="form-label">Password</label>
                  <div style="position:relative;">
                    <input type="password" class="form-control login-control" id="password" name="password" placeholder="Enter password" style="padding-right:2.5rem;" autocomplete="current-password" autocapitalize="off" autocorrect="off" spellcheck="false">
                    <span class="show-password-icon" onclick="togglePassword()" style="position:absolute; top:50%; right:1rem; transform:translateY(-50%); cursor:pointer;">
                      <i class="bi bi-eye" id="togglePasswordIcon" style="font-size:1.5rem;"></i>
                    </span>
                  </div>
              </div>
              <div class="mb-3 form-check">
                  <input type="checkbox" class="form-check-input" id="rememberMe" name="rememberMe" <?php echo isset($_COOKIE['remember_username']) ? 'checked' : ''; ?>>
                  <label class="form-check-label" for="rememberMe">Remember me</label>
              </div>
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary w-100 mb-2">Login</button>
                <button type="button" class="btn-register btn btn-outline-primary w-100" onclick="window.location.href='register.php'">Register</button>
              </div>
          </form>
      </div>

      <!-- ALERT: move outside of .login-panel -->
      <?php if ($message): ?>
        <div class="alert-container  mt-3">
          <?php if ($message === 'Login successful!'): ?>
            <div class="alert alert-success text-center">Login successful!</div>
          <?php else: ?>
            <div class="alert alert-danger text-center"><?php echo $message; ?></div>
          <?php endif; ?>
        </div>
      <?php endif; ?>

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