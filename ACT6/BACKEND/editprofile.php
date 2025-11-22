<?php
session_start();
if (empty($_SESSION['user_id'])) {
  header('Location: ../logsign.php');
  exit;
}

// Prefill form values from DB
$first_name = $last_name = $contact_number = $region = $country = $postal_id = $address = '';
$conn = new mysqli('localhost','root','','cogact');
if (!$conn->connect_error) {
  $stmt = $conn->prepare('SELECT first_name, last_name, contact_number, region, country, postal_id, address FROM users WHERE id = ?');
  if ($stmt) {
    $stmt->bind_param('i', $_SESSION['user_id']);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
      $first_name = $row['first_name'] ?? '';
      $last_name = $row['last_name'] ?? '';
      $contact_number = $row['contact_number'] ?? '';
      $region = $row['region'] ?? '';
      $country = $row['country'] ?? '';
      $postal_id = $row['postal_id'] ?? '';
      $address = $row['address'] ?? '';
    }
    $stmt->close();
  }
  $conn->close();
}

?><!doctype html>
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
    <link rel="stylesheet" href="../CSS/editprofile.css">
    <style>
    /* Flash overlay modal (reused style similar to dashboard welcome) */
    .flash-overlay {
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      width: 100vw; height: 100vh;
      display: none; /* toggled via JS */
      align-items: center; justify-content: center;
      background: rgba(0,0,0,0.32);
      z-index: 99999;
    }
    .flash-overlay.show {
      display: flex;
    }
    .flash-modal {
      background: #fff;
      border-radius: 14px;
      box-shadow: 0 12px 48px rgba(0,0,0,0.24);
      padding: 1.5rem 1.25rem;
      max-width: 520px;
      width: 90%;
      text-align: center;
    }
    .flash-modal h4 { margin-bottom: 0.5rem; }
    .flash-modal p { margin-bottom: 1rem; }
    /* Required star */
    .required-star { color: #dc3545; margin-left: 0.25rem; }

    /* Error outline for inputs when validation fails */
    .form-control.error {
      border: 2px solid #dc3545 !important;
      background: #fff6f6 !important;
      box-shadow: none !important;
    }
    .form-control.form-control-sm.error {
      padding-top: 0.375rem !important;
      padding-bottom: 0.375rem !important;
    }
    </style>
</head>


<body class="justify-content-center align-items-center d-flex min-vh-100">
  <!-- Page load white overlay (fades out) -->
  <div id="pageOverlay" class="page-overlay" aria-hidden="true"></div>
<!--START HERE-->
<section class="edit-container">
  <div class="edit-panel p-4">
    <a href="../index.php" class="com-logo-link d-inline-block" aria-label="Go to homepage">
      <img src="../PICS/DAHUAlogo.png" alt="comLOGO" class="com-logo rounded mx-auto d-block mb-3">
    </a>

    <form action="../BACKEND/process_editprofile.php" method="post">
      <div class="mb-3 position-relative">
        <label for="first_name" class="form-label">First name <span class="required-star">*</span></label>
        <input type="text" id="first_name" name="first_name" class="form-control" placeholder="First name" value="<?php echo htmlspecialchars($first_name); ?>">
      </div>
      <div class="mb-3 position-relative">
        <label for="last_name" class="form-label">Last name <span class="required-star">*</span></label>
        <input type="text" id="last_name" name="last_name" class="form-control" placeholder="Last name" value="<?php echo htmlspecialchars($last_name); ?>">
      </div>
      <div class="mb-3 position-relative">
        <label for="contact_number" class="form-label">Contact number <span class="required-star">*</span></label>
        <input type="tel" id="contact_number" name="contact_number" class="form-control" placeholder="Contact number" value="<?php echo htmlspecialchars($contact_number); ?>">
      </div>
      <div class="mb-3">
        <label for="region" class="form-label">Region / Country / Postal</label>
        <div class="row g-2">
          <div class="col-sm-6 col-md-4 position-relative">
            <input type="text" id="region" name="region" class="form-control form-control-sm" placeholder="Region" value="<?php echo htmlspecialchars($region); ?>">
          </div>
          <div class="col-sm-6 col-md-4 position-relative">
            <input type="text" id="country" name="country" class="form-control form-control-sm" placeholder="Country" value="<?php echo htmlspecialchars($country); ?>">
          </div>
          <div class="col-sm-12 col-md-4 position-relative">
            <input type="text" id="postal_id" name="postal_id" class="form-control form-control-sm" placeholder="Postal / ZIP" value="<?php echo htmlspecialchars($postal_id); ?>">
          </div>
        </div>
      </div>

      <div class="mb-3">
        <label for="address" class="form-label">Address</label>
        <textarea id="address" name="address" class="form-control" placeholder="Address" rows="3"><?php echo htmlspecialchars($address); ?></textarea>
      </div>

      <div class="btn-prof d-flex justify-content-center">
        <button type="submit" class="btn btn-primary fw-bold">Save</button>
        <a href="../userdash.php" class="btn btn-outline-secondary fw-bold text-muted">Back</a>
      </div>
    </form>

  </div>
</section>

<?php
// Flash messages: render as a centered overlay modal so it doesn't change layout
if (!empty($_SESSION['flash_success']) || !empty($_SESSION['flash_error'])):
  $fs = $_SESSION['flash_success'] ?? null;
  $fe = $_SESSION['flash_error'] ?? null;
  // consume
  unset($_SESSION['flash_success'], $_SESSION['flash_error']);
?>
  <div id="flashOverlay" class="flash-overlay" role="dialog" aria-modal="true">
    <div class="flash-modal">
      <h4 class="flash-title"><?php echo $fs ? 'Success' : 'Error'; ?></h4>
      <p class="flash-message"><?php echo htmlspecialchars($fs ?? $fe); ?></p>
      <div class="d-flex justify-content-center">
        <button id="flashCloseBtn" class="btn btn-primary px-4">OK</button>
      </div>
    </div>
  </div>
<?php endif; ?>


<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
crossorigin="anonymous"></script>

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
  document.addEventListener('DOMContentLoaded', function () {
    var flash = document.getElementById('flashOverlay');
    if (!flash) return;
    // show overlay (already present) and focus OK button
    flash.classList.add('show');
    var btn = document.getElementById('flashCloseBtn');
    if (btn) btn.focus();
    // Close on click
    btn.addEventListener('click', function () {
      flash.classList.remove('show');
      setTimeout(function () { if (flash && flash.parentNode) flash.parentNode.removeChild(flash); }, 300);
    });
    // Auto-dismiss after 3.5s for success messages
    var msgEl = flash.querySelector('.flash-title');
    if (msgEl && msgEl.textContent.trim().toLowerCase() === 'success') {
      setTimeout(function () { if (flash) { flash.classList.remove('show'); if (flash.parentNode) flash.parentNode.removeChild(flash); } }, 3500);
    }
  });
  </script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.querySelector('form');
  if (!form) return;

  const first = document.getElementById('first_name');
  const last = document.getElementById('last_name');
  const country = document.getElementById('country');
  const contact = document.getElementById('contact_number');
  const postal = document.getElementById('postal_id');

  // Remove error state on input
  [first, last, country, contact, postal].forEach(el => {
    if (!el) return;
    el.addEventListener('input', function() {
      if (el.classList.contains('error')) {
        if (el.value.trim()) {
          el.classList.remove('error');
          const tooltip = el.parentElement.querySelector('.error-tooltip');
          if (tooltip) tooltip.remove();
        }
      }
    });
  });

  form.addEventListener('submit', function(e) {
    // Clear old tooltips
    document.querySelectorAll('.error-tooltip').forEach(el => el.remove());
    [first, last, country, contact, postal].forEach(el => { if (el) el.classList.remove('error'); });

    let hasError = false;
    // Required fields check
    const required = [first, last, contact];
    required.forEach(el => {
      if (!el) return;
      if (!el.value.trim()) {
        showTooltip(el, 'This field is required.');
        hasError = true;
      }
    });
    // If required errors found, prevent further checks
    if (hasError) {
      e.preventDefault();
      return false;
    }
    const namePattern = /^[\p{L}\s\-']+$/u;
    // Names and country must not contain digits
    if (first && first.value.trim() && !namePattern.test(first.value.trim())) {
      showTooltip(first, 'First name may only contain letters, spaces, hyphens, or apostrophes.');
      hasError = true;
    }
    if (last && last.value.trim() && !namePattern.test(last.value.trim())) {
      showTooltip(last, 'Last name may only contain letters, spaces, hyphens, or apostrophes.');
      hasError = true;
    }
    if (country && country.value.trim() && !namePattern.test(country.value.trim())) {
      showTooltip(country, 'Country may only contain letters, spaces, hyphens, or apostrophes.');
      hasError = true;
    }

    // Contact and postal must not contain letters
    const letterRegex = /\p{L}/u;
    if (contact && contact.value.trim() && letterRegex.test(contact.value.trim())) {
      showTooltip(contact, 'Contact number must not contain letters. Use digits, +, or - only.');
      hasError = true;
    }
    if (postal && postal.value.trim() && letterRegex.test(postal.value.trim())) {
      showTooltip(postal, 'Postal code must not contain letters. Use digits only.');
      hasError = true;
    }

    if (hasError) {
      e.preventDefault();
      return false;
    }
  });

  function showTooltip(input, message) {
    input.classList.add('error');
    let tooltip = document.createElement('div');
    tooltip.className = 'error-tooltip';
    tooltip.innerHTML = '<span class="error-icon"><i class="bi bi-exclamation-square-fill"></i></span>' + message;
    // insert after the input (inside position-relative parent)
    input.parentElement.insertBefore(tooltip, input.nextSibling);
  }
});
</script>

</body> 
</html>