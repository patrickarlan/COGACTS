<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DAHUA: CONTACTS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">    
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="icon" type="image/png" href="PICS/DAHUAfavi.png">

</head>
<body>
<?php include 'COMPONENTS/header.php'; ?>


<!-- Product 1: ASA1222G Overview -->
<section class="product-overview py-5 bg-light">
  <div class="container">
    <div class="product-panel mx-auto p-5 text-center shadow-lg rounded-4 bg-white" style="max-width: 850px;">
      <h2 class="fw-bold mb-4">ASA1222E Time Attendance Terminal</h2>
      <p class="text-secondary mb-5">
        Explore the different angles of the Dahua ASA1222E device below.
      </p>

      <!-- Carousel -->
      <div class="container py-3">
        <div class="mx-auto text-center" style="max-width:760px;">
          
          <!-- Main Carousel -->
          <div id="asaCarousel" class="carousel slide mb-3" data-bs-ride="carousel" data-bs-interval="false">
            <div class="carousel-inner rounded overflow-hidden shadow-sm">
              <div class="carousel-item active">
                <img src="PICS/DAHUAattendance3.png" class="d-block w-100" alt="ASA1222G - back">
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
            <button type="button" class="thumb-btn" data-bs-target="#asaCarousel" data-bs-slide-to="2" aria-label="Back view">
              <img src="PICS/DAHUAattendance3.png" alt="Back">
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
        <table class="table table-striped table-bordered align-middle">
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

            <!-- CARD -->
            <tr class="table-danger">
              <th colspan="2" class="text-center fw-bold">FUNCTION</th>
            </tr>
            <tr>
              <td class="fw-semibold">verification Mode</td>
              <td>Fingerprint/Password,Only Fingerprint ,Only Password</td>
            </tr>
          </tbody>
        </table>
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


</body> 
</html>