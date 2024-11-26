<header>
  <div class="header-container">
    <!-- Logo and Title -->
    <div>
      <a href="/payroll/index.php" class="logo-section">
        <img src="image/logobg.png" alt="Company Logo" class="logo">
        <h1 class="company-title">MAPOLCom <span>Incorporated</span></h1>
      </a>
    </div>


    <!-- Enhanced Hamburger Menu -->
    <div class="hamburger" onclick="toggleMenu()">
      <span></span>
      <span></span>
      <span></span>
    </div>

    <!-- Enhanced Navigation -->
    <nav class="nav-section">
      <ul id="nav-menu">
        <li><a href="/payroll/index.php" class="active"><i class="fas fa-home"></i> Home</a></li>
        <li><a href="/payroll/employee_area/homepage.php#about"><i class="fas fa-info-circle"></i> About</a></li>

        <li><a href="/bsu_payroll/employee_area/projects.php" class="projects-link"><i
              class="fas fa-project-diagram"></i> Projects</a></li>
        <li><a href="/bsu_payroll/employee_area/portal.php" class="admin-button">
            <i class="fas fa-user"></i>
            <span>Login</span>
          </a></li>
      </ul>
    </nav>
  </div>
</header>

<style>
  .logo-section {
    text-decoration: none;
    /* Removes underline from the link */
    color: inherit;
    /* Keeps the text color as defined by the parent or default styling */
  }

  .logo-section:hover {
    text-decoration: none;
    /* Ensures no underline on hover */
  }
</style>