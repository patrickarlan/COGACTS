<?php
// Prevent Chrome BFCache — only send headers if they haven't already been sent
if (!headers_sent()) {
  header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0, private");
  header("Pragma: no-cache");
  header("Expires: 0");
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

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
          <a class="nav-link nav-hover" href="products.php">Products <span class="dropdown-toggle-icon d-lg-none">&#9660;</span></a>

          <!-- Hover Panel -->
          <div class="hover-panel bg-dark text-white py-4 px-0 shadow rounded-3">
            <ul class="list-unstyled m-0">
              <li><a href="attendancedevice.php" class="dropdown-item text-white py-2 px-3">ASA1222G</a></li>
              <li><a href="attendance2.php" class="dropdown-item text-white py-2 px-3">ASA1222E-S</a></li>
              <li><a href="attendance3.php" class="dropdown-item text-white py-2 px-3">ASA1222E</a></li>
            </ul>
          </div>
        </li>

        <!-- About Us with hover panel -->
        <li class="nav-item position-relative hover-panel-parent">
          <a class="nav-link nav-hover" href="about.php">About Us <span class="dropdown-toggle-icon d-lg-none">&#9660;</span></a>

          <!-- Hover Panel -->
          <div class="hover-panel bg-dark text-white py-4 px-0 shadow rounded-3">
            <ul class="list-unstyled m-0">
              <li><a href="about.php" class="dropdown-item text-white py-2 px-3">Introduction</a></li>
              <li><a href="contact.php" class="dropdown-item text-white py-2 px-3">Contact Us</a></li>
            </ul>
          </div>
        </li>
      </ul>

    <div class="icon-container">
      <!-- Icons (Search & Profile) -->
      <div class="icons d-flex align-items-center position-relative">

                <!-- 🔍 Search Icon (Hover-based) -->
                <div class="search-container position-relative me-3">
                  <a href="#" class="nav-link nav-hover text-white">
                    <i class="bi bi-search"></i>
                  </a>

                  <!-- Search Panel -->
                  <div class="search-panel bg-dark text-white p-3 rounded-3 shadow">
                    <input 
                      id="headerSearchInput"
                      type="text" 
                      class="form-control bg-secondary text-white border-0" 
                      placeholder="Search product..."
                      aria-label="Search products"
                      autocomplete="off"
                    >
                    <ul id="headerSearchResults" class="list-unstyled m-0 mt-2" style="max-height:320px;overflow:auto;"></ul>
                  </div>
                </div>


        <!-- 👤 Profile Icon -->
        <div class="profile-container position-relative">
              <?php /* Use server-side router to decide destination on click */ ?>
              <a href="/COG3BACK/ACT6/profile.php" class="nav-link nav-hover text-white profile-icon-link">
            <i class="bi bi-person-circle"></i>
          </a>
          <!-- Profile Dropdown -->
          <div class="profile-panel bg-dark text-white rounded-3 shadow py-2">
            <?php if (isset($_SESSION['user_id'])): ?>
              <a href="COMPONENTS/logout.php" id="headerLogout" class="dropdown-item text-white py-2 px-3">Logout</a>
            <?php else: ?>
              <a href="logsign.php" class="dropdown-item text-white text-center py-2">Sign In</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>  
    </div>
  </div>
</nav>

<script>
  document.addEventListener('click', function(event) {
  // Replace '.hover-panel.show' with your actual open menu selector
  const openPanels = document.querySelectorAll('.hover-panel.show');
  openPanels.forEach(panel => {
    if (!panel.contains(event.target) && !event.target.closest('.hover-panel-parent')) {
      panel.classList.remove('show');
      // reset aria-expanded on the toggle icon if present
      var icon = panel.parentElement.querySelector('.dropdown-toggle-icon');
      if (icon) icon.setAttribute('aria-expanded', 'false');
    }
  });
});
</script>

<!-- Inline logout confirmation overlay (appears on current page) -->
<div id="headerLogoutOverlay" style="display:none;position:fixed;inset:0;z-index:16000;align-items:center;justify-content:center;">
  <div style="position:absolute;inset:0;background:rgba(255,255,255,0.06);backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px);"></div>
  <div role="dialog" aria-modal="true" aria-labelledby="hlTitle" style="position:relative;max-width:420px;width:92%;background:rgba(255,255,255,0.92);padding:18px;border-radius:10px;box-shadow:0 20px 60px rgba(2,6,23,0.18);">
    <h3 id="hlTitle" style="margin:0 0 8px 0;font-size:1.05rem;color:#111">Sign out from the site?</h3>
    <p style="margin:0 0 14px 0;color:#444">You are about to sign out. This will end your current session and return you to the login page.</p>
    <div style="display:flex;gap:8px;justify-content:flex-end">
      <button id="headerLogoutCancel" class="btn" style="padding:8px 12px;border-radius:6px;border:1px solid #d0d5dd;background:transparent;cursor:pointer">Cancel</button>
      <form id="headerLogoutForm" method="post" action="COMPONENTS/logout.php" style="display:inline;margin:0;padding:0;">
        <button type="submit" class="btn primary" style="padding:8px 12px;border-radius:6px;border:1px solid #dc3545;background:#dc3545;color:#fff">Sign out</button>
      </form>
    </div>
  </div>
</div>

<!--search bar feature-->
<script>
document.addEventListener('DOMContentLoaded', function(){
  const input = document.getElementById('headerSearchInput');
  const resultsEl = document.getElementById('headerSearchResults');
  if (!input || !resultsEl) return;

  // Small in-page product catalog (kept in sync with userdash mappings)
  const products = [
    { key: 'attendancedevice', title: 'ASA1222G', image: 'PICS/DAHUAprod1.png', page: 'attendancedevice.php' },
    { key: 'attendance2', title: 'ASA1222E-S', image: 'PICS/DAHUAattendance2.png', page: 'attendance2.php' },
    { key: 'attendance3', title: 'ASA1222E', image: 'PICS/DAHUAattendance3.png', page: 'attendance3.php' }
  ];

  let debounceTimer = null;
  let activeIndex = -1;

  function clearResults(){ resultsEl.innerHTML = ''; activeIndex = -1; }

  function renderResults(list){
    resultsEl.innerHTML = '';
    if (!list || list.length === 0) return;
    list.forEach((p, idx) => {
      const li = document.createElement('li');
      li.className = 'py-2 px-2 d-flex gap-2 align-items-center search-result-item';
      li.style.cursor = 'pointer';
      li.tabIndex = 0;
      li.dataset.index = idx;

      const img = document.createElement('img'); img.src = p.image; img.alt = p.title; img.style.width='56px'; img.style.height='44px'; img.style.objectFit='cover'; img.className='rounded';
      const wrap = document.createElement('div');
      wrap.innerHTML = '<div class="text-white fw-semibold">' + (p.title||p.key) + '</div><div class="text-secondary small">' + (p.key) + '</div>';
      li.appendChild(img); li.appendChild(wrap);

      li.addEventListener('click', function(){ window.location.href = p.page; });
      li.addEventListener('keydown', function(e){ if (e.key === 'Enter') window.location.href = p.page; });

      resultsEl.appendChild(li);
    });
  }

  function doSearch(q){
    q = String(q||'').trim().toLowerCase();
    if (!q) { clearResults(); return; }
    const matches = products.filter(p => (p.title||p.key||'').toString().toLowerCase().indexOf(q) !== -1 || (p.key||'').toString().toLowerCase().indexOf(q) !== -1);
    renderResults(matches);
  }

  input.addEventListener('input', function(e){
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(()=> doSearch(input.value), 260);
  });

  // keyboard nav inside results
  input.addEventListener('keydown', function(e){
    const items = resultsEl.querySelectorAll('.search-result-item');
    if (!items.length) return;
    if (e.key === 'ArrowDown'){
      e.preventDefault(); activeIndex = Math.min(activeIndex+1, items.length-1); items[activeIndex].focus();
    } else if (e.key === 'ArrowUp'){
      e.preventDefault(); activeIndex = Math.max(activeIndex-1, 0); items[activeIndex].focus();
    }
  });

  // close results when clicking outside
  document.addEventListener('click', function(ev){
    if (!ev.target.closest('.search-container')) clearResults();
  });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  function isMobile() {
    return window.innerWidth <= 992;
  }
  document.querySelectorAll('.hover-panel-parent').forEach(function(parent) {
    var link = parent.querySelector('.nav-link');
    var panel = parent.querySelector('.hover-panel');
    var icon = parent.querySelector('.dropdown-toggle-icon');
    if (icon) {
      icon.addEventListener('click', function(e) {
        // prevent the anchor navigation when tapping the chevron
        e.stopPropagation();
        e.preventDefault();
        if (isMobile()) {
          // Close other panels and reset their toggle states
          document.querySelectorAll('.hover-panel.show').forEach(function(otherPanel) {
            if (otherPanel !== panel) {
              otherPanel.classList.remove('show');
              var otherIcon = otherPanel.parentElement.querySelector('.dropdown-toggle-icon');
              if (otherIcon) otherIcon.setAttribute('aria-expanded', 'false');
            }
          });
          panel.classList.toggle('show');
          // update aria-expanded on this icon
          icon.setAttribute('aria-expanded', panel.classList.contains('show') ? 'true' : 'false');
        }
      });
    }
    // NOTE: do not intercept link clicks — anchor text should navigate normally.
  });
  window.addEventListener('resize', function() {
    if (!isMobile()) {
      document.querySelectorAll('.hover-panel').forEach(function(panel) {
        panel.classList.remove('show');
      });
    }
  });
  // Header logout navigates to the logout confirmation page (no inline confirm)
});
</script>

<script>
// Intercept header logout link to show inline floating confirmation modal
document.addEventListener('DOMContentLoaded', function(){
  try {
    var headerLogout = document.getElementById('headerLogout');
    var overlay = document.getElementById('headerLogoutOverlay');
    var overlayCancel = document.getElementById('headerLogoutCancel');
    var logoutForm = document.getElementById('headerLogoutForm');
    if (headerLogout && overlay) {
      headerLogout.addEventListener('click', function(e){
        // if JS enabled, prevent navigation and show inline overlay
        e.preventDefault(); e.stopPropagation();
        overlay.style.display = 'flex';
        // focus cancel button for keyboard users
        setTimeout(function(){ overlayCancel && overlayCancel.focus(); }, 80);
      });
      overlayCancel && overlayCancel.addEventListener('click', function(){ overlay.style.display='none'; });
      // allow Escape to close
      document.addEventListener('keydown', function(ev){ if (ev.key === 'Escape' && overlay.style.display === 'flex') { overlay.style.display='none'; } });
      // clicking outside the modal panel should close overlay
      overlay.addEventListener('click', function(ev){ if (ev.target === overlay) overlay.style.display='none'; });
    }
  } catch(e) {}
});
</script>
