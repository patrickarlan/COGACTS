<!doctype html>
<html lang="en">
    <?php include 'COMPONENTS/head.php'; ?>
<body>
    <?php include 'COMPONENTS/header.php'; ?>


<!-- Second Header / Banner -->
<div class="container-fluid p-0 bg-dark">
  <div class="banner">
    <img src="PICS/DAHUAaboutus.png" alt="Banner Image" class="img-fluid">
  </div>
</div>

<!--START HERE-->
<section class="privacy-section py-5">
  <div class="privacy-banner p-4 shadow rounded-3 bg-white">
    <h1 class="fw-bold mb-4 text-center" > Privacy Policy </h1>
    <p class="privacy fw-bold mb-3">
      Information Collection</p>
    <p class="privacytext mb-4">
      We collect personal information such as your name, email address, 
      and usage data when you interact with our website. This information 
      is used to improve our services and provide a better user experience.</p>
    <p class="privacy fw-bold mb-3">
      Use of Information</p>
    <p class="privacytext mb-4">
      The information we collect is used to operate and maintain our website, 
      communicate with you, and enhance your experience. We do not sell or 
      share your personal information with third parties without your consent, 
      except as required by law.</p>
    <p class="privacy fw-bold mb-3">
      Cookies and Tracking Technologies</p>
    <p class="privacytext mb-4">
      We use cookies and similar tracking technologies to collect information 
      about your browsing activities. This helps us analyze trends, administer 
      the site, and improve our services. You can manage your cookie preferences 
      through your browser settings.</p>
    <p class="privacy fw-bold mb-3">
      Data Security</p>
    <p class="privacytext mb-4">
      We implement appropriate security measures to protect your personal 
      information from unauthorized access, alteration, disclosure, or destruction. 
      However, no method of transmission over the internet is completely secure.</p>
    <p class="privacy fw-bold mb-3">
      Changes to This Policy</p>
    <p class="privacytext mb-4">
      We may update this Privacy Policy from time to time. Any changes will be 
      posted on this page with an updated effective date. We encourage you to 
      review this policy periodically to stay informed about how we are protecting 
      your information.</p>
    <p class="privacy fw-bold mb-3">
      Contact Us</p>
    <p class="privacytext mb-4">
      If you have any questions or concerns about this Privacy Policy, 
      please contact us at supportoverseas@dahuatech.com.</p>
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


</body> 
</html>