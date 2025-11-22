<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DAHUA: TERMS OF USE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="icon" type="image/png" href="../PICS/DAHUAfavi.png">
    <link rel="stylesheet" href="../CSS/editaccount.css">
</head>


<?php
session_start();
// Require login
if (empty($_SESSION['user_id'])) {
  header('Location: ../logsign.php');
  exit;
}

// Fetch current user email to prefill the form
$current_email = '';
try {
  $db = new mysqli('localhost','root','','cogact');
  if (!$db->connect_error) {
    $stmt = $db->prepare('SELECT email FROM users WHERE id = ? LIMIT 1');
    $stmt->bind_param('i', $_SESSION['user_id']);
    if ($stmt->execute()) {
      $res = $stmt->get_result();
      $row = $res->fetch_assoc();
      $current_email = $row['email'] ?? '';
    }
    $stmt->close();
  }
  $db->close();
} catch (Exception $e) {
  // silently ignore DB errors; keep email empty
}

?>

<body class="justify-content-center align-items-center d-flex min-vh-100">
  <!-- Page load white overlay (fades out) -->
  <div id="pageOverlay" class="page-overlay" aria-hidden="true"></div>
<!--START HERE-->
<section class="editacc-container">
  <div class="editacc-panel p-4">
    <a href="../index.php" class="com-logo-link d-inline-block" aria-label="Go to homepage">
      <img src="../PICS/DAHUAlogo.png" alt="comLOGO" class="com-logo rounded mx-auto d-block mb-3">
    </a>

    <form id="accountForm" action="../BACKEND/process_accountsettings.php" method="post">
    <?php
    // show flash messages (if any)
    $fs = $_SESSION['flash_success'] ?? null;
    $fe = $_SESSION['flash_error'] ?? null;
    unset($_SESSION['flash_success'], $_SESSION['flash_error']);
    if ($fs) echo '<div class="alert alert-success">' . htmlspecialchars($fs, ENT_QUOTES, 'UTF-8') . '</div>'; 
    if ($fe) echo '<div class="alert alert-danger">' . htmlspecialchars($fe, ENT_QUOTES, 'UTF-8') . '</div>'; 
    ?>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="you@example.com" autocomplete="email" value="<?php echo htmlspecialchars($current_email, ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="mb-3">
        <label for="new_password" class="form-label">New password</label>
        <div class="input-group">
          <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Enter new password" autocomplete="new-password">
          <button class="btn btn-outline-secondary btn-sm pwd-toggle" type="button" data-target="new_password" aria-label="Toggle new password visibility"><i class="bi bi-eye"></i></button>
        </div>
      </div>
      <div class="mb-3">
        <label for="confirm_password" class="form-label">Confirm password</label>
        <div class="input-group">
          <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm new password" autocomplete="new-password">
          <button class="btn btn-outline-secondary btn-sm pwd-toggle" type="button" data-target="confirm_password" aria-label="Toggle confirm password visibility"><i class="bi bi-eye"></i></button>
        </div>
        <div id="pwdFormError" class="text-danger small mt-1" style="display:none"></div>
      </div>

      <div class="btn-prof d-flex justify-content-center">
        <button type="submit" class="btn btn-primary fw-bold">Save</button>
        <a href="../userdash.php" class="btn btn-outline-secondary fw-bold text-muted">Back</a>
      </div>
    </form>

    
  </div>
</section>


<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
crossorigin="anonymous"></script>

<!-- Floating modal: confirm current password when changing password (moved outside panel to avoid stacking issues) -->
<div id="confirmOldPwdModal" class="modal fade" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm your current password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Please enter your current password to confirm changing to a new password.</p>
        <div class="mb-2">
          <label for="current_password_confirm" class="form-label">Current password</label>
          <div class="input-group">
            <input type="password" id="current_password_confirm" class="form-control" placeholder="Current password">
            <button class="pwd-toggle" type="button" data-target="current_password_confirm" aria-label="Toggle current password visibility"><i class="bi bi-eye"></i></button>
          </div>
        </div>
        <div id="confirmError" class="text-danger small" style="display:none"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button id="confirmOldPwdBtn" type="button" class="btn btn-primary">Confirm</button>
      </div>
    </div>
  </div>
</div>

<script>
  // Fade out and remove overlay shortly after DOM is ready
  document.addEventListener('DOMContentLoaded', function () {
    var overlay = document.getElementById('pageOverlay');
    if (!overlay) return;
    // Small timeout so the overlay is visible for a moment
    setTimeout(function () {
      overlay.classList.add('fade-out');
      // remove from DOM after transition (500ms)
      setTimeout(function () { if (overlay && overlay.parentNode) overlay.parentNode.removeChild(overlay); }, 600);
    }, 80);
  });
</script>

<script>
document.addEventListener('DOMContentLoaded', function(){
  var accountForm = document.getElementById('accountForm');
  var newPwd = document.getElementById('new_password');
  var confirmModalEl = document.getElementById('confirmOldPwdModal');
  var confirmInput = document.getElementById('current_password_confirm');
  var confirmBtn = document.getElementById('confirmOldPwdBtn');
  var confirmError = document.getElementById('confirmError');

  // Bootstrap modal instance
  var bsModal = null;
  if (confirmModalEl) bsModal = new bootstrap.Modal(confirmModalEl, {keyboard:true});

  // Hide the page overlay (if present) while modal is open to avoid layering issues
  if (confirmModalEl && bsModal) {
    confirmModalEl.addEventListener('show.bs.modal', function(){
      var ov = document.getElementById('pageOverlay');
      if (ov) ov.style.display = 'none';
    });
    confirmModalEl.addEventListener('hidden.bs.modal', function(){
      var ov = document.getElementById('pageOverlay');
      if (ov) ov.style.display = '';
    });
  }

  if (!accountForm) return;

  // show/hide password toggle buttons
  document.querySelectorAll('.pwd-toggle').forEach(function(btn){
    btn.addEventListener('click', function(){
      var targetId = btn.getAttribute('data-target');
      var input = document.getElementById(targetId);
      if (!input) return;
      if (input.type === 'password') {
        input.type = 'text';
        btn.querySelector('i').classList.remove('bi-eye');
        btn.querySelector('i').classList.add('bi-eye-slash');
      } else {
        input.type = 'password';
        btn.querySelector('i').classList.remove('bi-eye-slash');
        btn.querySelector('i').classList.add('bi-eye');
      }
    });
  });

  accountForm.addEventListener('submit', function(e){
    // If user did not enter a new password, allow normal submit
    if (!newPwd || !newPwd.value) return;
    // pre-modal validations: confirm presence and match
    var confirmPwd = document.getElementById('confirm_password');
    var formErr = document.getElementById('pwdFormError');
    if (!confirmPwd || !confirmPwd.value) {
      e.preventDefault();
      if (formErr) { formErr.textContent = 'Please confirm your new password.'; formErr.style.display = 'block'; }
      confirmPwd && confirmPwd.focus();
      return;
    }
    if (newPwd.value !== confirmPwd.value) {
      e.preventDefault();
      if (formErr) { formErr.textContent = 'New password and confirmation do not match.'; formErr.style.display = 'block'; }
      confirmPwd && confirmPwd.focus();
      return;
    }
    // Prevent normal submit; show confirmation modal
    e.preventDefault();
    if (formErr) { formErr.style.display = 'none'; }
    confirmError.style.display = 'none';
    confirmInput.value = '';
    if (bsModal) bsModal.show();
    setTimeout(function(){ if (confirmInput) confirmInput.focus(); }, 200);
  });

  // When user confirms, verify current password via AJAX then submit
  if (confirmBtn) confirmBtn.addEventListener('click', function(){
    var v = confirmInput.value || '';
    confirmError.style.display = 'none';
    if (!v) {
      confirmError.textContent = 'Please enter your current password to confirm.';
      confirmError.style.display = 'block';
      confirmInput.focus();
      return;
    }
    // disable button while verifying
    confirmBtn.disabled = true;
    var oldText = confirmBtn.textContent;
    confirmBtn.textContent = 'Checking...';

    // verify current password via AJAX
    fetch('../BACKEND/verify_current_password.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'current_password=' + encodeURIComponent(v)
    }).then(function(resp){ return resp.json(); }).then(function(json){
      if (!json || !json.ok) {
        confirmError.textContent = json && json.message ? json.message : 'Current password is incorrect.';
        confirmError.style.display = 'block';
        confirmBtn.disabled = false;
        confirmBtn.textContent = oldText;
        confirmInput.focus();
        return;
      }
      // current password is correct — ensure new password differs from current
      if (newPwd && newPwd.value && newPwd.value === v) {
        confirmError.textContent = 'New password must be different from your current password.';
        confirmError.style.display = 'block';
        confirmBtn.disabled = false;
        confirmBtn.textContent = oldText;
        return;
      }
      // attach hidden input to the form and submit
      var existing = document.querySelector('input[name="current_password"]');
      if (existing) existing.value = v;
      else {
        var hi = document.createElement('input');
        hi.type = 'hidden'; hi.name = 'current_password'; hi.value = v;
        accountForm.appendChild(hi);
      }
      if (bsModal) bsModal.hide();
      accountForm.submit();
    }).catch(function(err){
      confirmError.textContent = 'Verification failed — try again.';
      confirmError.style.display = 'block';
      confirmBtn.disabled = false;
      confirmBtn.textContent = oldText;
    });
  });
});
</script>
</body> 
</html>