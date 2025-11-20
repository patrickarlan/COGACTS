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
    <link rel="stylesheet" href="../CSS/editprofile.css">
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
      <div class="mb-3">
        <label for="first_name" class="form-label">First name</label>
        <input type="text" id="first_name" name="first_name" class="form-control" placeholder="First name">
      </div>
      <div class="mb-3">
        <label for="last_name" class="form-label">Last name</label>
        <input type="text" id="last_name" name="last_name" class="form-control" placeholder="Last name">
      </div>
      <div class="mb-3">
        <label for="contact_number" class="form-label">Contact number</label>
        <input type="tel" id="contact_number" name="contact_number" class="form-control" placeholder="Contact number">
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

</body> 
</html>