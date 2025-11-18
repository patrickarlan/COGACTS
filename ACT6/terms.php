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
<section class="terms-section py-5">
  <div class="terms-banner p-4 shadow rounded-3 bg-white">
    <h2 class="fw-bold mb-4 text-center">Terms of Use</h2>
    <p class="terms fw-bold mb-3">
      Acceptance of Terms</p>
    <p class="termtext mb-4">
      By using this website, you agree to follow these Terms of Use. 
      If you do not agree, please do not use the site. 
      Our Privacy Policy explains how we collect and use your 
      information and is part of these terms.</p>
    <p class="terms fw-bold mb-3">
      Scope of Service</p>
    <p class="termtext mb-4">
      This website provides information about Dahua's attendance 
      monitoring products and services. We may change or stop 
      offering any part of the site at any time without notice.</p>
    <p class="terms fw-bold mb-3">
      Electronic Communications</p>
    <p class="termtext mb-4">
      By using this site, you consent to receive electronic 
      communications from us. We may send you important 
      information about your account or the site.</p>
    <p class="terms fw-bold mb-3">
      Intellectual Property</p>
    <p class="termtext mb-4">
      All content on this website, including text, images, 
      logos, and software, is owned by Dahua or its licensors 
      and is protected by copyright and other laws. You may 
      not use any content without our permission.</p>
    <p class="terms fw-bold mb-3">
      Trademarks</p>
    <p class="termtext mb-4">
      “Dahua”, “Dahua Technology”, “Dahua Security”, 
      and related logos are trademarks of the Company.
      Do not use these trademarks without our written permission.</p>
    <p class="terms fw-bold mb-3">
      Prohibited Use</p>
    <p class="termtext mb-4">
      You agree not to use this website for any illegal or 
      unauthorized purpose. You must not interfere with the 
      operation of the site or attempt to access it using 
      methods other than the provided interface.</p>
    <p class="terms fw-bold mb-3">
      Prohibited Use</p>
    <p class="termtext mb-4">
      You agree not to use this website for any illegal or 
      unauthorized purpose. You must not interfere with the 
      operation of the site or attempt to access it using 
      methods other than the provided interface.</p>
    <p class="terms fw-bold mb-3">
      Third-Party Information</p>
    <p class="termtext mb-4">
      The site may include links or content from third parties. 
      We do not guarantee their accuracy and are not responsible for them.</p>
    <p class="terms fw-bold mb-3">
      Viruses</p>
    <p class="termtext mb-4">
      We cannot guarantee the site is free from harmful code. 
      You are responsible for protecting your devices.
      Do not attempt to damage or disrupt the site.</p>
    <p class="terms fw-bold mb-3">
      Liability</p>
    <p class="termtext mb-4">
      To the fullest extent permitted by law, Dahua is not 
      liable for any damages arising from your use of this website. 
      Use the site at your own risk.</p>
    <p class="terms fw-bold mb-3">
      Governing Law</p>
    <p class="termtext mb-4">
      These terms are governed by the laws of the jurisdiction 
      where Dahua is headquartered, without regard to conflict 
      of law principles.</p>
    <p class="terms fw-bold mb-3">
      Severability</p>
    <p class="termtext mb-4">
      If any part of these terms is found to be invalid or 
      unenforceable, the remaining provisions will remain in effect.</p>
    <p class="terms fw-bold mb-3">
      No Agency</p>
    <p class="termtext mb-4">
      Nothing in these terms creates an agency, partnership, 
      or joint venture between you and Dahua.</p>
    <p class="terms fw-bold mb-3">
      Changes to Terms</p>
    <p class="termtext mb-4">
      We may update these terms at any time. Continued use 
      of the site after changes means you accept the new terms.</p>
    <p class="terms fw-bold mb-3">
      Contact</p>
    <p class="termtext mb-4">
      If you have questions about these Terms of Use, 
      please contact us through the information on our Contact Us page.</p>
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