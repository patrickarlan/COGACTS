<?php
// requireLogin.php logic embedded at the very top
session_start();

// If not logged in, redirect immediately
if (!isset($_SESSION['user_id'])) {
    header("Location: logsign.php");
    exit();
}

// Determine whether to show the welcome popup on this login.
// Use a server-side session flag so logging out/clearing the session will show it again on next login.
$showWelcome = empty($_SESSION['welcome_shown']);
if ($showWelcome) {
  // mark it shown for subsequent requests in this session
  $_SESSION['welcome_shown'] = 1;
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
    <title>DAHUA: DASHBOARD</title>
    <!-- BFCache / back button protection -->
    <script>
    window.addEventListener("pageshow", function(event) {
        if (event.persisted || (performance.getEntriesByType("navigation")[0].type === "back_forward")) {
            window.location.replace("logsign.php");
        }
    });
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="icon" type="image/png" href="PICS/DAHUAfavi.png">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="CSS/userdash.css">
    <style>  /* Page overlay - ensures the white overlay can fade out on this page */
    #pageOverlay.page-overlay {
      position: fixed;
      inset: 0;
      background: #ffffff;
      z-index: 99999;
      opacity: 1;
      transition: opacity 0.5s ease;
      pointer-events: none;
    }
    /* Stronger override class — uses !important so it can't be defeated by other CSS rules */
    #pageOverlay.inline-fade {
      opacity: 0 !important;
      transition: opacity 0.5s ease !important;
      pointer-events: none !important;
    }
      .welcome-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(8px);
        z-index: 99999;
        display: none;
        align-items: center;
        justify-content: center;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.4s cubic-bezier(.4,0,.2,1);
      }
      .welcome-overlay.show {
        display: flex;
        opacity: 1;
        pointer-events: auto;
      }
      .welcome-overlay.hide {
        opacity: 0;
        pointer-events: none;
      }
      .welcome-modal {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.18);
        padding: 2.5rem 2rem 2rem 2rem;
        min-width: 320px;
        max-width: 90vw;
        text-align: center;
        animation: popIn 0.5s cubic-bezier(.4,0,.2,1);
      }
      @keyframes popIn {
        0% { transform: scale(0.7); opacity: 0; }
        60% { transform: scale(1.05); opacity: 1; }
        100% { transform: scale(1); opacity: 1; }
      }
      /* Confirmation modal + blur styles */
      .confirm-modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,0.38);display:none;align-items:center;justify-content:center;z-index:16000}
      .confirm-modal-overlay.show{display:flex; -webkit-backdrop-filter: blur(6px); backdrop-filter: blur(6px);} 
      .confirm-modal{background:linear-gradient(180deg,#ffffff,#fbfdff);padding:20px;border-radius:10px;max-width:480px;width:94%;box-shadow:0 30px 80px rgba(2,6,23,0.18)}
      /* Blur page when overlay is active (exclude overlay itself) */
      body.overlay-open *:not(.confirm-modal-overlay):not(.confirm-modal-overlay *) { filter: blur(6px) !important; transition: filter .15s ease !important; }
      /* Favourite image hover effect */
      .fav-img { transition: transform .14s ease, box-shadow .14s ease; cursor: pointer; display: inline-block; }
      .fav-img:hover { transform: translateY(-6px) scale(1.03); box-shadow: none; }
    </style>
    </style>
  </head>

  <body style="flex-direction: column; min-height:100vh;">  

<!-- Page Overlay -->
<div id="pageOverlay" class="page-overlay" aria-hidden="true"></div>

<!-- Welcome Popout Modal (rendered only when server-side session requests it) -->
<?php if (!empty(
$showWelcome
)) : ?>
  <div id="welcomeOverlay" class="welcome-overlay">
    <div class="welcome-modal">
      <h4 class="mb-3">Welcome to your Dashboard!</h4>
      <p class="mb-4">We're glad to have you here. Explore your favourites and orders below.</p>
      <button id="thankYouBtn" class="btn btn-success px-4">Thank You</button>
    </div>
  </div>
<?php endif; ?>

<!-- Cancel confirmation modal (floating) -->
<div id="cancelConfirmModal" class="confirm-modal-overlay" aria-hidden="true">
  <div class="confirm-modal" role="dialog" aria-modal="true" aria-labelledby="cancelModalTitle">
    <h5 id="cancelModalTitle">Cancel order</h5>
    <p id="cancelModalBody" class="mb-3">Are you sure you want to cancel <strong id="cancelModalTitle"></strong>?</p>
    <div class="text-end">
      <button id="cancelConfirmBtn" class="btn btn-danger me-2">Yes, cancel</button>
      <button id="cancelCancelBtn" class="btn btn-secondary">Back</button>
    </div>
  </div>
</div>

<?php include 'COMPONENTS/header.php'; ?>


<!-- Second Header / Banner -->
<main style="flex: 1 0 auto;">
<div class="banner-panel">
  <div class="banner-dash">
    <img src="PICS/DAHUAdash.png" alt="Bannerdash" class="banner-dash" style="width:100%; max-height:300px; object-fit:cover;" />
  </div>
</div>

<!--START HERE-->
<section class="dashboard-section py-1">
  <div class="dashboard-section container my-3">
    <div class="row justify-content-center align-items-start">
      <!-- Profile Panel -->
      <div class="col-md-3 mb-4">
        <div class="card-profile h-100" style="outline: #5353531a 0.5px solid; border-radius:3px; box-shadow: 0 4px 12px #5353531a; padding-bottom:30px;">
          <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Profile</h5>
          </div>
          <div class="card-body text-center">
            <img src="PICS/DAHUAfavi.png" alt="Profile" class="rounded-circle mb-3" style="width:80px;height:80px;object-fit:cover;">
            <h6 class="mb-1"><?php echo htmlspecialchars($username); ?></h6>
            <p class="text-muted mb-2"><?php echo htmlspecialchars($email); ?></p>
            <a href="BACKEND/editprofile.php" class="btn-text btn btn-outline-primary btn-sm">Edit Profile</a>
            <div class="mt-2">
              <a href="BACKEND/accountsettings.php" class="btn-text btn btn-outline-success btn-sm">Account Settings</a>
            </div>
          </div>
        </div>
      </div>
      <!-- Favourite Panel -->
      <div class="col-md-4 mb-4">
        <div class="card-fav h-100 shadow-sm" style="outline: #5353531a 0.5px solid; border-radius:3px; box-shadow: 0 4px 12px #5353531a; padding-bottom:5px;">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Favourite Panel</h5>
          </div>
          <div class="card-body">
            <p class="text-muted text-center">Your favourite products will appear here.</p>
            <ul class="list-group list-group-flush text-center" id="favourite-list">
              <li class="list-group-item">No favourites yet.</li>
            </ul>
          </div>
        </div>
      </div>
      <!-- Order Panel -->
      <div class="col-md-5 mb-4">
        <div class="card-order h-100 shadow-sm" style="outline: #5353531a 0.5px solid; border-radius:3px; box-shadow: 0 4px 12px #5353531a; padding-bottom:20px;">
          <div class="card-header bg-success text-white">
            <h5 class="mb-0">Order Panel</h5>
          </div>
          <div class="card-body">
            <p class="text-muted text-center">Your recent orders will appear here.</p>
            <ul class="list-group list-group-flush" id="order-list">
              <li class="list-group-item">No orders yet.</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</main>

<?php include 'COMPONENTS/footer.php'; ?>

<!-- Scroll-to-top button (appears when page is scrolled past 50%) -->
<button id="scrollTopBtn" class="scroll-btn" aria-label="Scroll to top">
  <i class="bi bi-arrow-up text-white fs-5"></i>
</button>


<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
crossorigin="anonymous"></script>


<script>
  // Robust fade-out for the page overlay using an !important override class
  document.addEventListener('DOMContentLoaded', function () {
    var overlay = document.getElementById('pageOverlay');
    if (!overlay) return;

    // Small timeout so the overlay is visible for a moment
    setTimeout(function () {
      // Force a reflow so the browser notices the transition
      // eslint-disable-next-line no-unused-expressions
      overlay.offsetWidth;

      // Add a class that uses !important to force the opacity transition
      overlay.classList.add('inline-fade');

      // Remove from DOM after transition completes (give 600ms to be safe)
      setTimeout(function () {
        if (overlay && overlay.parentNode) overlay.parentNode.removeChild(overlay);
      }, 600);
    }, 80);
  });
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const overlay = document.getElementById('welcomeOverlay');
  const btn = document.getElementById('thankYouBtn');
  if (!overlay) return;

  // Server controls whether the overlay exists on first login; simply show it when present
  setTimeout(() => overlay.classList.add('show'), 300);

  if (btn) {
    btn.addEventListener('click', function () {
      overlay.classList.remove('show');
      overlay.classList.add('hide');
      setTimeout(() => {
        overlay.classList.remove('hide');
        overlay.style.display = 'none';
      }, 400);
    });
  }
});
</script>

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
// Fetch and render user's favourites and orders into the dashboard panels
document.addEventListener('DOMContentLoaded', function () {
  const favList = document.getElementById('favourite-list');
  const orderList = document.getElementById('order-list');

  // Map known product_key values to front images (fallback when DB doesn't have an image)
  const productFrontMap = {
    'attendancedevice': 'PICS/DAHUAattdevfr.png',
    'attendance2': 'PICS/DAHUAattendance2.png',
    'attendance3': 'PICS/DAHUAattendance3.png'
  };
  // Map product_key to product page path
  const productPageMap = {
    'attendancedevice': 'attendancedevice.php',
    'attendance2': 'attendance2.php',
    'attendance3': 'attendance3.php'
  };
  // Short display names for products (used in orders list)
  const productNameMap = {
    'attendancedevice': 'ASA1222G',
    'attendance2': 'ASA1222-ES',
    'attendance3': 'ASA1222-E'
  };
  // Product prices (per-unit). Update values to match your catalogue or fetch server-side later.
  const productPriceMap = {
    'attendancedevice': 12500,
    'attendance2': 14500,
    'attendance3': 16800
  };

  function formatCurrency(n){
    try{ return '₱' + Number(n).toLocaleString(); }catch(e){ return '₱' + n; }
  }

  // Cancel confirmation modal elements and helpers
  const cancelModal = document.getElementById('cancelConfirmModal');
  const cancelConfirmBtn = document.getElementById('cancelConfirmBtn');
  const cancelCancelBtn = document.getElementById('cancelCancelBtn');
  const cancelModalBody = document.getElementById('cancelModalBody');
  let pendingCancel = { id: null, button: null };

  function showCancelConfirm(orderId, title, sourceBtn){
    pendingCancel.id = orderId;
    pendingCancel.button = sourceBtn;
    if (cancelModalBody) cancelModalBody.innerHTML = 'Are you sure you want to cancel <strong>' + (title ? safeText(title) : 'this order') + '</strong>?';
    if (cancelModal){ cancelModal.classList.add('show'); cancelModal.setAttribute('aria-hidden','false'); }
    document.body.classList.add('overlay-open');
    if (pendingCancel.button) pendingCancel.button.disabled = true;
    setTimeout(()=>{ if (cancelConfirmBtn) cancelConfirmBtn.focus(); }, 120);
  }

  function hideCancelModal(){
    if (cancelModal){ cancelModal.classList.remove('show'); cancelModal.setAttribute('aria-hidden','true'); }
    document.body.classList.remove('overlay-open');
    if (pendingCancel && pendingCancel.button) pendingCancel.button.disabled = false;
    pendingCancel = { id: null, button: null };
  }

  if (cancelCancelBtn) cancelCancelBtn.addEventListener('click', function(){ hideCancelModal(); });
  if (cancelConfirmBtn) cancelConfirmBtn.addEventListener('click', function(){
    if (!pendingCancel.id) return;
    cancelConfirmBtn.disabled = true;
    fetch('BACKEND/cancel_order.php', {
      method: 'POST', headers: {'Content-Type':'application/json'},
      body: JSON.stringify({ order_id: pendingCancel.id })
    }).then(r=>r.json()).then(res=>{
      if (res && res.status === 'ok') {
        hideCancelModal();
        render();
      } else {
        alert(res.message || 'Failed to cancel order');
        hideCancelModal();
      }
    }).catch(err=>{ console.error(err); alert('Network error'); hideCancelModal(); })
    .finally(()=>{ cancelConfirmBtn.disabled = false; });
  });

  function makeImg(src, alt) {
    const img = document.createElement('img');
    img.src = src;
    img.alt = alt || 'front';
    img.style.width = '140px';
    img.style.height = '90px';
    img.style.objectFit = 'cover';
    img.className = 'rounded mb-2 fav-img';
    return img;
  }

  function safeText(s){ return String(s||'').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

  function render() {
    fetch('BACKEND/user_items.php', { credentials: 'same-origin' })
      .then(r => r.json())
      .then(json => {
        if (!json || json.status !== 'ok') return;

        // Favourites
        favList.innerHTML = '';
        if (!json.favourites || json.favourites.length === 0) {
          const li = document.createElement('li');
          li.className = 'list-group-item';
          li.textContent = 'No favourites yet.';
          favList.appendChild(li);
        } else {
          json.favourites.forEach(item => {
            const li = document.createElement('li');
            li.className = 'list-group-item text-center';
            const src = item.product_image && item.product_image.trim() ? item.product_image : (productFrontMap[item.product_key] || 'PICS/DAHUAfavi.png');

            // make image clickable to the product page when possible
            const pageHref = productPageMap[item.product_key] || '#';
            const imgEl = makeImg(src, 'front');
            const a = document.createElement('a');
            a.href = pageHref;
            a.appendChild(imgEl);
            li.appendChild(a);

            const title = document.createElement('div');
            title.innerHTML = '<strong>' + safeText(item.product_title || item.product_key) + '</strong>';
            li.appendChild(title);

            // Remove favourite button
            const removeWrap = document.createElement('div');
            removeWrap.className = 'mt-2';
            const removeBtn = document.createElement('button');
            removeBtn.className = 'btn btn-sm btn-outline-danger';
            removeBtn.textContent = 'Remove';
            removeBtn.addEventListener('click', function(){
              removeBtn.disabled = true;
              fetch('BACKEND/toggle_favourite.php', {
                method: 'POST', headers: {'Content-Type':'application/json'}, credentials: 'same-origin',
                body: JSON.stringify({ product_key: item.product_key })
              }).then(r=>r.json()).then(res=>{
                if (res && res.status === 'ok') {
                  render();
                } else {
                  alert(res && res.message ? res.message : 'Failed to remove favourite');
                  removeBtn.disabled = false;
                }
              }).catch(err=>{ console.error(err); alert('Network error'); removeBtn.disabled = false; });
            });
            removeWrap.appendChild(removeBtn);
            li.appendChild(removeWrap);

            favList.appendChild(li);
          });
        }

        // Orders
        orderList.innerHTML = '';
        if (!json.orders || json.orders.length === 0) {
          const li = document.createElement('li');
          li.className = 'list-group-item';
          li.textContent = 'No orders yet.';
          orderList.appendChild(li);
        } else {
          json.orders.forEach(item => {
            const li = document.createElement('li');
            li.className = 'list-group-item d-flex gap-3 align-items-center';

            const src = item.product_image && item.product_image.trim() ? item.product_image : (productFrontMap[item.product_key] || 'PICS/DAHUAfavi.png');
            const img = makeImg(src, 'front');
            img.style.width = '120px'; img.style.height = '80px';

            // product page link (if known)
            const pageHref = productPageMap[item.product_key] || '#';

            // clickable area: image only (no text under image)
            const clickWrap = document.createElement('a');
            clickWrap.href = pageHref;
            clickWrap.style.textDecoration = 'none';
            clickWrap.style.color = 'inherit';
            clickWrap.style.display = 'inline-block';
            clickWrap.style.flex = '0 0 140px';
            clickWrap.appendChild(img);
            li.appendChild(clickWrap);

            const info = document.createElement('div');
            info.style.flex = '1 1 auto';
            const qtyVal = (parseInt(item.qty,10)||1);
            const shortName = productNameMap[item.product_key] || safeText(item.product_title || item.product_key);
            const unitPrice = (productPriceMap[item.product_key] !== undefined) ? productPriceMap[item.product_key] : 0;
            const totalPrice = unitPrice * qtyVal;
            info.innerHTML = '<div class="text-dark fw-bold">' + safeText(shortName) + '</div>' +
                             '<div class="text-muted">Qty: ' + qtyVal + '</div>' +
                             '<div class="text-muted">Price: ' + formatCurrency(unitPrice) + '</div>' +
                             '<div class="text-muted">Total: ' + formatCurrency(totalPrice) + '</div>' +
                             '<div class="text-muted">Payment: ' + safeText(item.payment_method || 'cash') + '</div>';
            li.appendChild(info);

            const actions = document.createElement('div');
            actions.style.minWidth = '120px';
            actions.style.textAlign = 'right';

            // Status label
            const status = document.createElement('div');
            status.className = 'mb-2';
            status.innerHTML = '<small class="text-secondary">' + safeText(item.status || '') + '</small>';
            actions.appendChild(status);

            // Cancel button for pending orders
            actions.style.textAlign = 'center';
            if ((item.status || '').toLowerCase() === 'pending') {
              const btn = document.createElement('button');
              btn.className = 'btn-cancel btn btn-sm btn-outline-danger';
              btn.textContent = 'Cancel';
              btn.addEventListener('click', function () {
                // show floating confirm modal
                showCancelConfirm(item.id, item.product_title || item.product_key, btn);
              });
              actions.appendChild(btn);
            }

            li.appendChild(actions);
            orderList.appendChild(li);
          });
        }
      })
      .catch(err => { console.error('Failed to fetch user items', err); });
  }

  render();
});
</script>

</body> 
</html>