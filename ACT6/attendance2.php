<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  include 'COMPONENTS/needtosign.php';
  showNeedToSignPopup();
  exit;
}

// If not logged in, redirect immediately
if (!isset($_SESSION['user_id'])) {
    header("Location: logsign.php");
    exit();
}

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0, private");
header("Pragma: no-cache");
header("Expires: 0");

// Fetch user data
$username = $_SESSION['username'] ?? 'User';
$email = '';
$conn = new mysqli("localhost", "root", "", "cogact");
if (!$conn->connect_error) {
    $stmt = $conn->prepare("SELECT email FROM users WHERE id=?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $email = $row['email'];
    }
    $conn->close();
}
?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DAHUA: Timetrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="icon" type="image/png" href="PICS/DAHUAfavi.png">
    <link rel="stylesheet" href="style.css?v=20251118-1">
    <link rel="stylesheet" href="CSS/btnprod.css?v=20251122-1">
<!-- BFCache / back button protection -->
    <script>
    window.addEventListener("pageshow", function(event) {
        if (event.persisted || (performance.getEntriesByType("navigation")[0].type === "back_forward")) {
            window.location.replace("logsign.php");
        }
    });
    </script>
    <style>
    .product-panel .btn, .product-overview .btn { transition: transform .12s ease, box-shadow .12s ease; }
    .product-panel .btn:hover, .product-overview .btn:hover { transform: scale(1.02); box-shadow: 0 12px 30px rgba(2,6,23,0.12); }
    .product-panel .btn-primary:hover { box-shadow: 0 16px 40px rgba(37,99,235,0.16); }
    .profile-modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,0.38);display:none;align-items:center;justify-content:center;z-index:16000}
    .profile-modal-overlay.show{display:flex; -webkit-backdrop-filter: blur(6px); backdrop-filter: blur(6px);} 
    .profile-modal{background:linear-gradient(180deg,#ffffff,#fbfdff);padding:20px;border-radius:10px;max-width:480px;width:94%;box-shadow:0 30px 80px rgba(2,6,23,0.18)}
    /* Blur page when overlay is active (exclude overlay itself) */
    body.overlay-open *:not(.profile-modal-overlay):not(.profile-modal-overlay *) { filter: blur(6px) !important; transition: filter .15s ease !important; }
    </style>
</head>

<body>
<?php include 'COMPONENTS/header.php'; ?>

<!-- Product 1: ASA1222G Overview -->
<section class="product-overview py-5 bg-light">
  <div class="container">
    <div class="product-panel mx-auto p-5 text-center shadow-lg rounded-4 bg-white" style="max-width: 850px;">
      <h2 class="fw-bold mb-4">ASA1222E-S Time Attendance Terminal</h2>
      <p class="text-secondary mb-5">
        Explore the different angles of the Dahua ASA1222E-S device below.
      </p>

      <!-- Carousel -->
      <div class="container py-3">
        <div class="mx-auto text-center" style="max-width:760px;">
          
          <!-- Main Carousel -->
          <div id="asaCarousel" class="carousel slide mb-3" data-bs-ride="carousel" data-bs-interval="false">
            <div class="carousel-inner rounded overflow-hidden shadow-sm">
              <div class="carousel-item active">
                <img src="PICS/DAHUAattendance2.png" class="d-block w-100" alt="ASA1222G - front">
              </div>
              <div class="carousel-item">
                <img src="PICS/DAHUAattdevbck2.png" class="d-block w-100" alt="ASA1222G - side">
              </div>
              <div class="carousel-item">
                <img src="PICS/DAHUAattdevside2.png" class="d-block w-100" alt="ASA1222G - back">
              </div>
            </div>

            <!-- Controls (inside image edges) -->
            <!--<button class="carousel-control-prev" type="button" data-bs-target="#asaCarousel" data-bs-slide="prev">
              <span class="carousel-control-prev-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#asaCarousel" data-bs-slide="next">
              <span class="carousel-control-next-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>-->
          </div>

          <!-- Thumbnails (Image Buttons) -->
          <div class="d-flex justify-content-center gap-3 align-items-center thumbnail-panel">
            <button type="button" class="thumb-btn active" data-bs-target="#asaCarousel" data-bs-slide-to="0" aria-current="true" aria-label="Front view">
              <img src="PICS/DAHUAattendance2.png" alt="Front">
            </button>

            <button type="button" class="thumb-btn" data-bs-target="#asaCarousel" data-bs-slide-to="1" aria-label="Side view">
              <img src="PICS/DAHUAattdevbck2.png" alt="Side">
            </button>

            <button type="button" class="thumb-btn" data-bs-target="#asaCarousel" data-bs-slide-to="2" aria-label="Back view">
              <img src="PICS/DAHUAattdevside2.png" alt="Back">
            </button>
          </div>
          
          <!-- Buttons: Favourite / Order (inside product panel, under carousel) -->
          <div class="d-flex justify-content-center gap-3 mt-4">
            <button id="favBtn" class="btn btn-outline-primary btn-lg" data-product="attendance2" aria-pressed="false" title="Add to favourites">
              <i class="bi bi-star" id="favIcon"></i> <span class="btn-label">Favourite</span>
            </button>
            <button id="orderBtn" class="btn btn-primary btn-lg" data-product="attendance2">
              <i class="bi bi-cart-plus"></i> <span class="btn-label">Order now</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="specification-panel bg-danger">
        <!-- Product Specification Section -->
    <div class="specification bg-white mt-5">
      <h3 class="fw-bold text-center mb-4 mt-1 text-dark">Product Specifications</h3>
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <tbody>
            <!-- SYSTEM PARAMETER -->
            <tr class="table-danger">
              <th colspan="2" class="text-center fw-bold">SYSTEM PARAMETER</th>
            </tr>
            <tr>
              <td class="fw-semibold">Main Processor</td>
              <td>32-bit Processor</td>
            </tr>
            <tr>
              <td class="fw-semibold">Memory</td>
              <td>8MB Flash</td>
            </tr>

            <!-- FINGERPRINT -->
            <tr class="table-danger">
              <th colspan="2" class="text-center fw-bold">FINGERPRINT</th>
            </tr>
            <tr>
              <td class="fw-semibold">Applicable</td>
              <td>Yes</td>
            </tr>
            <tr>
              <td class="fw-semibold">Response Time</td>
              <td>&le;1.5s</td>
            </tr>
            <tr>
              <td class="fw-semibold">FAR</td>
              <td>&le;0.00004%</td>
            </tr>
            <tr>
              <td class="fw-semibold">FRR</td>
              <td>&le;0.15%</td>
            </tr>

            <!-- CAPACITY -->
            <tr class="table-secondary">
              <th colspan="2" class="text-center fw-bold">CAPACITY</th>
            </tr>
            <tr>
              <td class="fw-semibold">Fingerprint</td>
              <td>2,000</td>
            </tr>
            <tr>
              <td class="fw-semibold">User</td>
              <td>1,000</td>
            </tr>
            <tr>
              <td class="fw-semibold">Attendance Records</td>
              <td>100,000</td>
            </tr>
            <tr>
              <td class="fw-semibold">Management Records</td>
              <td>10,000</td>
            </tr>

            <!-- FUNCTION -->
            <tr class="table-danger">
              <th colspan="2" class="text-center fw-bold">FUNCTION</th>
            </tr>
            <tr>
              <td class="fw-semibold">User Level</td>
              <td>Admin, User</td>
            </tr>
            <tr>
              <td class="fw-semibold">Schedule Mode</td>
              <td>by User / by Department</td>
            </tr>
            <tr>
              <td class="fw-semibold">Verification Mode</td>
              <td>Fingerprint/Password, Only Fingerprint, Only Password</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</section>

<!-- Profile incomplete modal (hidden by default) -->
<div id="profileModal" class="profile-modal-overlay" aria-hidden="true">
  <div class="profile-modal" role="dialog" aria-modal="true" aria-labelledby="profileModalTitle">
    <h5 id="profileModalTitle">Update your profile info</h5>
    <p class="mb-3">You need to add your contact number and address before placing an order.</p>
    <div class="text-end">
      <a href="BACKEND/editprofile.php" class="btn btn-primary me-2">Edit Profile</a>
      <button id="profileModalBack" class="btn btn-secondary">Back</button>
    </div>
  </div>
</div>

<!-- Floating Order Panel (hidden by default) -->
<div id="orderPanel" class="profile-modal-overlay" aria-hidden="true">
  <div class="profile-modal" role="dialog" aria-modal="true" aria-labelledby="orderPanelTitle">
    <h5 id="orderPanelTitle">Order device</h5>
    <p class="mb-2">Enter quantity to order</p>
    <div class="mb-3">
      <input id="orderQty" type="number" inputmode="numeric" pattern="[0-9]*" min="1" value="1" class="form-control" style="max-width:140px;" aria-label="Quantity">
    </div>
    <div class="text-end">
      <button id="orderConfirmBtn" class="btn btn-primary me-2">Confirm</button>
      <button id="orderCancelBtn" class="btn btn-secondary">Cancel</button>
    </div>
  </div>
</div>

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
document.addEventListener('DOMContentLoaded', function () {
  const carouselEl = document.getElementById('asaCarousel');
  const thumbButtons = document.querySelectorAll('.thumb-btn');

  thumbButtons.forEach((btn) => {
    btn.addEventListener('click', () => {
      thumbButtons.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
    });
  });

  carouselEl.addEventListener('slid.bs.carousel', function (e) {
    const activeIndex = e.to;
    thumbButtons.forEach((b, i) => {
      b.classList.toggle('active', i === activeIndex);
    });
  });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function(){
  const favBtn = document.getElementById('favBtn');
  const favIcon = document.getElementById('favIcon');
  const orderBtn = document.getElementById('orderBtn');
  const profileModal = document.getElementById('profileModal');
  const profileModalBack = document.getElementById('profileModalBack');

  function showProfileModal(){ profileModal.classList.add('show'); profileModal.setAttribute('aria-hidden','false'); }
  function hideProfileModal(){ profileModal.classList.remove('show'); profileModal.setAttribute('aria-hidden','true'); }

  if (favBtn) {
    favBtn.addEventListener('click', function(){
      const product = this.getAttribute('data-product');
      const pressed = this.getAttribute('aria-pressed') === 'true';
      this.setAttribute('aria-pressed', (!pressed).toString());
      if (!pressed) { favIcon.classList.remove('bi-star'); favIcon.classList.add('bi-star-fill'); }
      else { favIcon.classList.remove('bi-star-fill'); favIcon.classList.add('bi-star'); }

      const productTitleEl = document.querySelector('.product-panel h2');
      const productTitle = productTitleEl ? productTitleEl.textContent.trim() : product;
      const imgEl = document.querySelector('.carousel-inner img[alt*="front"]') || document.querySelector('.carousel-inner img') || document.querySelector('.thumb-btn img[alt*="Front"]');
      const productImage = imgEl ? imgEl.src : '';

      fetch('BACKEND/toggle_favourite.php', {
        method: 'POST', headers: {'Content-Type':'application/json'}, credentials: 'same-origin',
        body: JSON.stringify({ product_key: product, product_title: productTitle, product_image: productImage })
      }).then(r=>r.json()).then(json=>{
        if (json && json.status === 'ok') return;
        const revertPressed = !(!pressed);
        favBtn.setAttribute('aria-pressed', revertPressed.toString());
        if (revertPressed) { favIcon.classList.remove('bi-star'); favIcon.classList.add('bi-star-fill'); }
        else { favIcon.classList.remove('bi-star-fill'); favIcon.classList.add('bi-star'); }
        alert(json.message || 'Failed to update favourite');
      }).catch(err=>{
        const revertPressed = !(!pressed);
        favBtn.setAttribute('aria-pressed', revertPressed.toString());
        if (revertPressed) { favIcon.classList.remove('bi-star'); favIcon.classList.add('bi-star-fill'); }
        else { favIcon.classList.remove('bi-star-fill'); favIcon.classList.add('bi-star'); }
        console.error(err); alert('Network error while updating favourite');
      });
    });
  }

  if (orderBtn) {
    orderBtn.addEventListener('click', function(){
      const panel = document.getElementById('orderPanel');
      const qtyInput = document.getElementById('orderQty');
      if (!panel || !qtyInput) return;
      qtyInput.value = '1';
      panel.classList.add('show'); panel.setAttribute('aria-hidden','false');
      document.body.classList.add('overlay-open');
      setTimeout(()=>{ qtyInput.focus(); qtyInput.select(); }, 120);
    });
  }

  if (profileModalBack) profileModalBack.addEventListener('click', hideProfileModal);
  // Order panel handlers
  const orderPanel = document.getElementById('orderPanel');
  const orderQty = document.getElementById('orderQty');
  const orderConfirmBtn = document.getElementById('orderConfirmBtn');
  const orderCancelBtn = document.getElementById('orderCancelBtn');

  function hideOrderPanel(){ if (orderPanel){ orderPanel.classList.remove('show'); orderPanel.setAttribute('aria-hidden','true'); } document.body.classList.remove('overlay-open'); }

  if (orderQty){
    orderQty.addEventListener('input', function(e){ this.value = this.value.replace(/\D/g,''); if (this.value === '' || parseInt(this.value,10) < 1) this.value = '1'; });
    orderQty.addEventListener('keydown', function(e){ if (e.key === 'e' || e.key === 'E' || e.key === '+' || e.key === '-') e.preventDefault(); });
  }

  if (orderCancelBtn) orderCancelBtn.addEventListener('click', function(){ hideOrderPanel(); });

  if (orderConfirmBtn){
    orderConfirmBtn.addEventListener('click', function(){
      const qty = Math.max(1, parseInt(orderQty.value || '1', 10));
      orderConfirmBtn.disabled = true;
      const product = orderBtn.getAttribute('data-product');
      const productTitleEl = document.querySelector('.product-panel h2');
      const productTitle = productTitleEl ? productTitleEl.textContent.trim() : product;
      const imgEl = document.querySelector('.carousel-inner img[alt*="front"]') || document.querySelector('.carousel-inner img') || document.querySelector('.thumb-btn img[alt*="Front"]');
      const productImage = imgEl ? imgEl.src : '';

      fetch('BACKEND/create_order.php', {
        method: 'POST', headers: {'Content-Type':'application/json'}, credentials: 'same-origin',
        body: JSON.stringify({ product_key: product, qty: qty, product_title: productTitle, product_image: productImage })
      }).then(r=>r.json()).then(json=>{
        if (!json) { alert('Invalid server response'); return; }
        if (json.status === 'ok') { hideOrderPanel(); }
        else if (json.status === 'error' && json.code === 'incomplete_profile') { hideOrderPanel(); showProfileModal(); }
        else { alert(json.message || 'Failed to place order'); }
      }).catch(err=>{ console.error(err); alert('Network error while placing order'); })
      .finally(()=>{ orderConfirmBtn.disabled = false; });
    });
  }
});
</script>


</body> 
</html>