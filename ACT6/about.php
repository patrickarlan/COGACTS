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

<!-- About Section -->
<section class="overview py-5 bg-light">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10 bg-white shadow rounded-4 p-5">

        <!-- Main Header -->
        <h2 class="fw-bold text-center text-uppercase mb-4">About Dahua Technology</h2>
        <hr class="mx-auto mb-4" style="width: 80%; height: 3px; background-color: #d32f2f; border: none;">

        <!-- Overview Paragraph -->
        <p class="about-text about-text-black mb-4 text-justify">
          Zhejiang Dahua Technology Co., Ltd., widely known as Dahua Technology, is a global leader in video-centric AIoT solutions and services. 
          Since launching its <em>‘Dahua Think’</em> corporate strategy in 2021, the company has focused on two key areas: City and Enterprise. 
          Through AIoT and IoT digital intelligence platforms, Dahua drives the digital transformation of cities and enterprises, creating value across multiple industries.
        </p>

        <!-- Image Panel -->
        <div class="text-center my-4">
          <img src="PICS/DAHUAabover.jpg" alt="About Dahua Technology" class="img-fluid rounded-3 shadow">
          <p class="text-secondary mt-2 small">Dahua Technology Headquarters, Hangzhou, China</p>
        </div>

        <!-- Company Profiling -->
        <div class="company-profile text-center mb-4 py-2 pt-4">
          <h3 class="fw-bold text-uppercase text-dark mb-3">Company Profiling</h3>
          <h4 class="fw-bold text-danger mb-4">Dahua Technology</h4>
        </div>

        <div class="text-start mt-2 mx-auto" style="max-width: 750px; font-size: 1.1rem; line-height: 1.7;">
          <p><strong>Company Name:</strong> Zhejiang Dahua Technology Co., Ltd.</p>
          <p><strong>Founded:</strong> 2001</p>
          <p><strong>Founder:</strong> Fu Liquan</p>
          <p><strong>Headquarters:</strong> Hangzhou, Zhejiang, China</p>
          <p><strong>Type:</strong> Public Company</p>

          <h5 class="fw-bold mt-4 mb-3 text-dark border-start border-3 border-danger ps-3">Company Overview</h5>
          <p>
            Dahua Technology is one of the world’s leading providers of video surveillance products and solutions. 
            The company focuses on video-centric AIoT (Artificial Intelligence of Things), offering innovative technologies 
            for security monitoring, smart cities, and business intelligence.
          </p>
          <p>
            Through continuous innovation, advanced R&D, and reliable products, Dahua contributes to creating safer, smarter, 
            and more connected communities worldwide.
          </p>

          <h5 class="fw-bold mt-4 mb-3 text-dark border-start border-3 border-danger ps-3">Products and Services</h5>
          <ul class="about-text text-muted fw-bold">
            <li>CCTV Cameras and Recorders (DVR/NVR)</li>
            <li>Access Control and Attendance Devices</li>
            <li>Smart City and Traffic Solutions</li>
            <li>AI and Cloud-Based Security Systems</li>
            <li>Video Management Software</li>
          </ul>

          <h5 class="fw-bold mt-5 mb-3 text-dark border-start border-3 border-danger ps-3">Company Highlights (2021)</h5>
          <ul class="about-text text-success fw-bold">
            <li>Total operating revenue: RMB 32.835 billion (USD 4.98 billion), a year-on-year increase of 24.07%</li>
            <li>Net profit after non-recurring gains/losses: RMB 3.103 billion (USD 470 million), up 13.47% YOY</li>
            <li>Over 22,000 employees, with more than 50% engaged in R&D</li>
            <li>Annual R&D investment: approximately 10% of sales revenue</li>
            <li>Established specialized research institutes including:
              <ul>
                <li>Advanced Technology Institute</li>
                <li>Big Data Institute</li>
                <li>Central Research Institute</li>
                <li>Cybersecurity Institute</li>
                <li>Smart City Institute</li>
              </ul>
            </li>
            <li>Expanding into new sectors such as machine vision, robotics, video collaboration, automotive electronics, and smart thermal imaging</li>
          </ul>

          <p class="about-text about-text-black mt-4">
            Committed to the mission of <strong>“Enabling a safer society and smarter living”</strong>,
            Dahua Technology stays true to its core value of being <strong>customer-centered</strong>. 
            The company continuously strives to deliver reliable, intelligent, and innovative solutions for a more secure and connected world.
          </p>
        </div>
      </div>
    </div>
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