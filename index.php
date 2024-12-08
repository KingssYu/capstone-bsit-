<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MAPOLCom Incorporated</title>
  <link rel="stylesheet" href="./employee_area/employee_styles/homepage.css">
  <!-- Add Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    /* Carousel Styles */
    .carousel {
      position: relative;
      max-width: 100%;
      margin: auto;
      overflow: hidden;
      border-radius: 10px;
      /* Rounded corners for the carousel */
      padding: 13px;
      padding-bottom: 5rem;
      /* Adds space between the carousel and the card edges */
    }

    .carousel-inner {
      position: relative;
      display: flex;
      transition: transform 0.5s ease-in-out;
    }

    .carousel-item {
      min-width: 100%;
      display: none;
      /* Hide all slides by default */
    }

    .carousel-item.active {
      display: block;
      /* Show the active slide */
    }

    .carousel img {
      width: 100%;
      height: 550px;
      /* Adjusted height for better proportion */
      object-fit: cover;
      /* Ensures the image fits nicely without distortion */
      border-radius: 8px;
      /* Optional rounded corners for images */
    }

    .carousel-caption {
      position: absolute;
      /* Keep it at the bottom */
      left: 50%;
      transform: translateX(-50%);
      color: #fff;
      background-color: rgba(0, 0, 0, 0.6);
      padding: 10px 15px;
      border-radius: 5px;
      text-align: center;
    }

    /* Carousel Controls */
    .carousel-control {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background: rgba(0, 0, 0, 0.5);
      color: #fff;
      border: none;
      padding: 10px;
      cursor: pointer;
      border-radius: 50%;
    }

    .carousel-control.prev {
      left: 20px;
    }

    .carousel-control.next {
      right: 20px;
    }

    .carousel-indicators {
      text-align: center;
      position: absolute;
      bottom: 12px;
      width: 100%;
    }

    .carousel-indicators .indicator {
      display: inline-block;
      width: 10px;
      height: 10px;
      margin: 0 5px;
      background: rgba(255, 255, 255, 0.5);
      border-radius: 50%;
      cursor: pointer;
    }

    .carousel-indicators .indicator.active {
      background: #fff;
    }

    /* Card Styles */
    .card {
      border: 1px solid #ddd;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      margin-top: 3rem;
      margin-left: 4rem;
      margin-right: 4rem;
      margin-bottom: 3rem;


      /* Adds spacing above the card */
    }
  </style>
</head>

<body>
  <?php include './employee_area/header.php'; ?>

  <div id="home" class="home-section">
    <div class="card">
      <div class="card-body">
        <div class="carousel">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="image/slide1.jpg" alt="Construction Project 1">
              <div class="carousel-caption">
                <p><strong>Excellence in Construction</strong> (Building tomorrow's infrastructure today)</p>
              </div>
            </div>
            <div class="carousel-item">
              <img src="image/slide2.jpg" alt="Construction Project 2">
              <div class="carousel-caption">
                <h3></h3>
                <p><strong>Quality Engineering </strong>(Committed to superior standards)</p>
              </div>
            </div>
            <div class="carousel-item">
              <img src="image/slide3.jpg" alt="Construction Project 3">
              <div class="carousel-caption">
                <p><strong>Professional Services </strong>(Your trusted construction partner)</p>
              </div>
            </div>
          </div>
          <div class="carousel-indicators">
            <span class="indicator active" onclick="currentSlide(1)"></span>
            <span class="indicator" onclick="currentSlide(2)"></span>
            <span class="indicator" onclick="currentSlide(3)"></span>
          </div>
          <button class="carousel-control prev" onclick="plusSlides(-1)">
            <i class="fas fa-chevron-left"></i>
          </button>
          <button class="carousel-control next" onclick="plusSlides(1)">
            <i class="fas fa-chevron-right"></i>
          </button>
        </div>
      </div>
    </div>

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

    <br>

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

    <br>

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

    <br>

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