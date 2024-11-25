<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MAPOLCom Incorporated</title>
  <link rel="stylesheet" href="./employee_area/employee_styles/homepage.css">
  <!-- Add Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
  <?php include './employee_area/header.php'; ?>


  <!-- Rest of your existing content remains the same -->
  <!-- Just adding the enhanced carousel controls -->
  <div id="home" class="home-section">
    <div class="carousel">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="image/slide1.jpg" alt="Construction Project 1">
          <div class="carousel-caption">
            <h3>Excellence in Construction</h3>
            <p>Building tomorrow's infrastructure today</p>
          </div>
        </div>
        <div class="carousel-item">
          <img src="image/slide2.jpg" alt="Construction Project 2">
          <div class="carousel-caption">
            <h3>Quality Engineering</h3>
            <p>Committed to superior standards</p>
          </div>
        </div>
        <div class="carousel-item">
          <img src="image/slide3.jpg" alt="Construction Project 3">
          <div class="carousel-caption">
            <h3>Professional Services</h3>
            <p>Your trusted construction partner</p>
          </div>
        </div>
        <div class="carousel-item">
          <img src="image/slide4.jpg" alt="Construction Project 4">
          <div class="carousel-caption">
            <h3>Professional Services</h3>
            <p>Your trusted construction partner</p>
          </div>
        </div>
        <div class="carousel-item">
          <img src="image/slide5.jpg" alt="Construction Project 5">
          <div class="carousel-caption">
            <h3>Professional Services</h3>
            <p>Your trusted construction partner</p>
          </div>
        </div>
      </div>
      <div class="carousel-indicators">
        <span class="indicator active" onclick="currentSlide(1)"></span>
        <span class="indicator" onclick="currentSlide(2)"></span>
        <span class="indicator" onclick="currentSlide(3)"></span>
        <span class="indicator" onclick="currentSlide(4)"></span>
        <span class="indicator" onclick="currentSlide(5)"></span>
      </div>
      <button class="carousel-control prev" onclick="plusSlides(-1)">
        <i class="fas fa-chevron-left"></i>
      </button>
      <button class="carousel-control next" onclick="plusSlides(1)">
        <i class="fas fa-chevron-right"></i>
      </button>
    </div>

    <!-- Content Section -->
    <!-- <div class="content-section">
            <div class="image-box">
                <img src="image/cover.jpg" alt="Box Image">
            </div>
            <div class="portal-container">
                <div class="portal-title">Company Portal</div>
                <div class="portal-button-box">
                    <a href="portal.php" class="button">Enter Portal</a>
                </div>
            </div>
        </div> -->

    <!-- Content Sections -->
    <div id="about" class="section about-company-container">
      <h2 class="section-title">About Company</h2>
      <div class="about-content">
        <div class="about-text">
          <h2>About Our Company</h2>
          <p>
            For twenty (20) worthwhile years, MAPOL Construction & Services has successfully established itself as a prominent provider of engineering services. Since its inception in March 1999, the company has maneuvered through a rapidly evolving industry landscape, leading to the creation of MAPOLCOM INCORPORATED—a strategic move to meet the growing demands of our clients.
          </p>
          <p>
            We specialize in a broad range of services including Mechanical Maintenance, Civil, Structural, Electrical Works, and General Janitorial Services. Our exceptional technical expertise spans Erection, Alignment, Fabrication, and Installation of Machineries. Our traditional business in Trucking, Heavy Equipment Rental, and hauling/transporting services across the country has solidified our reputation as an Accredited Contractor and Dealer of major companies such as REPUBLIC CEMENT & BUILDING MATERIALS, HOLCIM PHILIPPINES INC., and EAGLE CEMENT CORPORATION.
          </p>
          <p>
            Our legacy continues with the introduction of MAPOLMIX BATCHING & PULVERIZING PLANT, expanding our horizons and cementing our position as industry leaders.
          </p>
        </div>
        <div class="about-image">
          <img src="image/aboutprof.jpg" alt="About Company">
        </div>
      </div>
    </div>

    <div class="mission-vision-container">
      <h2 class="section-title">Mission and Vision</h2>
      <div class="mission-vision-content">
        <div class="mission">
          <h2>Our Mission</h2>
          <p>
            To uphold the values of integrity and honest dealing with our esteemed customers, and to contribute to progress by providing the best, safe, quality job opportunities to professionals and skilled laborers.
          </p>
        </div>
        <div class="vision">
          <h2>Our Vision</h2>
          <p>
            To diversify our competence and dependability in the building construction industry, and to sustain a remarkable, enduring relationship and profitability.
          </p>
        </div>
      </div>
    </div>

    <div class="trade-reference-container">
      <h2 class="section-title">Trade Reference</h2>
      <div class="trade-images">
        <div class="trade-image">
          <img src="image/COMPANY-PROFILE-mapolcom_page-0017.jpg" alt="Trade Reference 1">
        </div>
        <div class="trade-image">
          <img src="image/COMPANY-PROFILE-mapolcom_page-0018.jpg" alt="Trade Reference 2">
        </div>
        <!-- Add more images as needed -->
      </div>
    </div>

    <!-- Enhanced Footer -->
    <footer class="company-footer">
      <div class="footer-content">
        <div class="footer-contact">
          <h3><i class="fas fa-envelope"></i> Contact Us</h3>
          <p><i class="fas fa-phone"></i> Phone: +1 (555) 123-4567</p>
          <p><i class="fas fa-map-marker-alt"></i> Address: 123 Main Street, City, Country</p>
        </div>

        <div class="footer-social">
          <h3>Connect With Us</h3>
          <div class="social-icons">
            <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
            <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
            <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; <span id="year"></span> MAPOLCom Incorporated. All rights reserved.</p>
      </div>
    </footer>

    <script>
      // Enhanced Mobile Menu Toggle
      function toggleMenu() {
        const navMenu = document.getElementById('nav-menu');
        const hamburger = document.querySelector('.hamburger');
        navMenu.classList.toggle('show');
        hamburger.classList.toggle('active');
      }

      // Close menu when clicking outside
      document.addEventListener('click', function(event) {
        const navMenu = document.getElementById('nav-menu');
        const hamburger = document.querySelector('.hamburger');
        if (!event.target.closest('.nav-section') && !event.target.closest('.hamburger')) {
          navMenu.classList.remove('show');
          hamburger.classList.remove('active');
        }
      });

      // Carousel logic
      let slideIndex = 1;
      showSlides(slideIndex);

      function plusSlides(n) {
        showSlides(slideIndex += n);
      }

      function currentSlide(n) {
        showSlides(slideIndex = n);
      }

      function showSlides(n) {
        const slides = document.getElementsByClassName("carousel-item");
        const indicators = document.getElementsByClassName("indicator");

        if (n > slides.length) {
          slideIndex = 1;
        }
        if (n < 1) {
          slideIndex = slides.length;
        }

        for (let i = 0; i < slides.length; i++) {
          slides[i].style.display = "none";
          indicators[i].classList.remove("active");
        }

        slides[slideIndex - 1].style.display = "block";
        indicators[slideIndex - 1].classList.add("active");
      }

      // Auto advance slides
      setInterval(() => {
        plusSlides(1);
      }, 5000);

      // Update footer year
      document.getElementById('year').textContent = new Date().getFullYear();
    </script>
</body>

</html>