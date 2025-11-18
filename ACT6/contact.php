<!doctype html>
<html lang="en">

<?php include 'COMPONENTS/head.php'; ?>

<body>
<?php include 'COMPONENTS/header.php'; ?>

<!-- Second Header / Banner -->
<div class="container-fluid p-0 bg-dark">
  <div class="banner">
    <img src="PICS/DAHUAcontact.png" alt="Banner Image" class="img-fluid">
  </div>
</div>

<!-- CONTACT Section -->
<section class="contact-section py-1 m0 bg-light">
  <div class="contact-banner container text-center my-5 bg-light">
    <h1 class="fw-bold">Contact Us</h1>
      <p class="contact-text fw-semibold">
        If you are interested in our products and services, 
        or want to know more detailed information, 
        please do not hesitate to contact with us.</p>  

    <section class="overview py-2 mb-5">
      <div class="container">
        <div class="row justify-content-center">
      
          <div class="col-md-8">

            <!-- Contact Card -->
          

            <!-- Image -->
            <div class="image-hover-wrapper mt-3">
              <img src="PICS/DAHUAcont.jpg" alt="Contact Image" class="img-fluid">
            </div>

            <!-- Contact Panel -->
              <div class="contact-panel bg-light p-4">
                <h2 class="fw-bold text-dark mb-3">Zhejiang Dahua Technology Co., Ltd</h2>
                <p class="cont-info fs-6 text-dark mb-0"><b>No.1199, Bin'an Road, Binjiang District, Hangzhou, China</b></p>
                <p class="cont-info fs-6 mb-0"><b>P.C:</b> 310053</p>
                <p class="cont-info fs-6 mb-0"><b>Fax:</b> +86 571 8768 8815</p>
                <p class="cont-info fs-6 mb-0"><b>Business:</b> overseas@dahuatech.com</p>
                <p class="cont-info fs-6 mb-0"><b>Press Inquiries:</b> PR_Global@dahuatech.com</p>
              </div>
          </div>
          <!-- End Contact Card -->
         </div> 
      </div>
    </section>
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