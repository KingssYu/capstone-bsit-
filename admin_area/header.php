<div class="sidenav">
  <div class="logo">
    <img src="../image/logobg.png" alt="Logo">
  </div>
  <div class="menu">
    <div class="notification-container">
      <button class="notification-button" id="notificationButton" style="background-color: #D5ED9F !important; color:black !important;">
        Notifications <span class="notification-badge" id="notificationBadge">0</span>
      </button>
      <!-- Tooltip Content -->
      <div class="tooltip-content" id="tooltipContent">
        <ul id="notificationList">
          <!-- Notifications will be dynamically loaded here -->
        </ul>
      </div>
    </div>
    <a href="dashboard.php">
      <button class="dashboard-button">Dashboard</button>
    </a>
    <a href="admin_cash_advance.php">
      <button>Cash Advance Requests</button>
    </a>
    <a href="all_employee.php">
      <button>Employee</button>
    </a>
    <a href="emp_attendance.php">
      <button>Attendance</button>
    </a>
    <a href="directory.php">
      <button>Directory</button>
    </a>
    <a href="payslip.php">
      <button>Record of Payslip</button>
    </a>
    <a href="department.php">
      <button>Department</button>
    </a>
    <a href="position.php">
      <button>Positions</button>
    </a>
  </div>
  <div class="footer">
    <a href="admin_logout.php">
      <button>Logout</button>
    </a>
  </div>
</div>

<style>
  li {
    color: black;
  }

  /* .sidenav {
    overflow-y: auto;
    scrollbar-width: thin;
  } */

  .logo img {
    display: block;
    margin: 0 auto 20px;
    max-width: 100%;
  }

  .menu a {
    margin-bottom: 10px;
    text-decoration: none;
  }

  .menu button {
    width: 100%;
    padding: 10px 15px;
    font-size: 16px;
    border: none;
    background-color: #007bff;
    color: white;
    border-radius: 5px;
    cursor: pointer;
    text-align: left;
  }

  .menu button:hover {
    background-color: #0056b3;
  }

  .notification-container {
    position: relative;
    margin-bottom: 20px;
  }

  .notification-button {
    background-color: transparent;
    color: #007bff;
    border: 1px solid #007bff;
    padding: 10px 15px;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
    position: relative;
  }

  .notification-button:hover {
    background-color: #f4f4f4;
  }

  .notification-badge {
    background-color: red;
    color: white;
    font-size: 14px;
    font-weight: bold;
    padding: 5px 10px;
    border-radius: 50%;
    position: absolute;
    top: 8px;
    right: 6rem;
  }

  .sidenav {
    /* Remove scrolling here */
    scrollbar-width: thin;
  }

  .menu {
    overflow-y: auto;
    scrollbar-width: thin;
  }


  .tooltip-content {
    display: none;
    position: fixed;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 10px;
    max-height: 300px;
    overflow-y: auto;
    z-index: 1000;
  }


  .tooltip-content ul {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .tooltip-content li {
    padding: 5px 0;
    border-bottom: 1px solid #ddd;
  }

  .tooltip-content li:last-child {
    border-bottom: none;
  }

  /* .notification-button:focus+.tooltip-content,
  .notification-button:hover+.tooltip-content {
    display: block;
  } */

  .footer button {
    background-color: #ff4d4d;
    color: white;
  }

  .footer button:hover {
    background-color: #cc0000;
  }
</style>

<div class="sidenav">
  <div class="logo">
    <img src="../image/logobg.png" alt="Logo">
  </div>
  <div class="menu">
    <div class="notification-container">
      <button class="notification-button" id="notificationButton" style="background-color: #D5ED9F !important; color:black !important;">
        Notifications <span class="notification-badge" id="notificationBadge">0</span>
      </button>
      <!-- Tooltip Content -->
      <div class="tooltip-content" id="tooltipContent">
        <ul id="notificationList">
          <!-- Notifications will be dynamically loaded here -->
        </ul>
        <div class="pagination-container">
          <button id="prevPage" disabled>Previous</button>
          <button id="nextPage" disabled>Next</button>
        </div>
      </div>
    </div>
    <a href="dashboard.php">
      <button class="dashboard-button">Dashboard</button>
    </a>
    <a href="admin_cash_advance.php">
      <button>Cash Advance Requests</button>
    </a>
    <a href="all_employee.php">
      <button>Employee</button>
    </a>
    <a href="emp_attendance.php">
      <button>Attendance</button>
    </a>
    <a href="directory.php">
      <button>Directory</button>
    </a>
    <a href="payslip.php">
      <button>Record of Payslip</button>
    </a>
    <a href="department.php">
      <button>Department</button>
    </a>
    <a href="position.php">
      <button>Positions</button>
    </a>
  </div>
  <div class="footer">
    <a href="admin_logout.php">
      <button>Logout</button>
    </a>
  </div>
</div>

<style>
  li {
    color: black;
  }

  .tooltip-content {
    display: none;
    position: fixed;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 10px;
    max-height: 300px;
    overflow-y: auto;
    z-index: 1000;
    width: 300px;
  }

  .pagination-container {
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
  }

  .pagination-container button {
    padding: 5px 10px;
    cursor: pointer;
  }

  .pagination-container button:disabled {
    cursor: not-allowed;
  }

  .notification-badge {
    background-color: red;
    color: white;
    font-size: 14px;
    font-weight: bold;
    padding: 5px 10px;
    border-radius: 50%;
    position: absolute;
    top: 8px;
    right: 6rem;
  }
</style>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const notificationButton = document.getElementById("notificationButton");
    const notificationList = document.getElementById("notificationList");
    const notificationBadge = document.getElementById("notificationBadge");
    const prevPageButton = document.getElementById("prevPage");
    const nextPageButton = document.getElementById("nextPage");

    let notifications = [];
    let currentPage = 0;
    const itemsPerPage = 1;

    // Automatically fetch notifications when the page loads
    fetchNotifications();

    // Fetch notifications when the button is clicked
    notificationButton.addEventListener("click", toggleTooltip);

    function toggleTooltip() {
      const tooltipContent = document.getElementById("tooltipContent");
      tooltipContent.style.display =
        tooltipContent.style.display === "block" ? "none" : "block";

      if (tooltipContent.style.display === "block") {
        renderPagination();
      }
    }

    function fetchNotifications() {
      const xhr = new XMLHttpRequest();
      xhr.open("GET", "./fetch_notifications.php", true); // Replace with your PHP script
      xhr.onload = function() {
        if (xhr.status === 200) {
          notifications = JSON.parse(xhr.responseText);
          notificationBadge.textContent = notifications.length;
          currentPage = 0; // Reset to the first page after fetching
          renderPagination();
        }
      };
      xhr.send();
    }

    function renderPagination() {
      const totalPages = Math.ceil(notifications.length / itemsPerPage);

      prevPageButton.disabled = currentPage === 0;
      nextPageButton.disabled = currentPage >= totalPages - 1;

      const startIndex = currentPage * itemsPerPage;
      const endIndex = startIndex + itemsPerPage;
      const currentNotifications = notifications.slice(startIndex, endIndex);

      renderNotifications(currentNotifications);
    }

    function renderNotifications(notificationsToDisplay) {
      notificationList.innerHTML = "";
      if (notificationsToDisplay.length === 0) {
        notificationList.innerHTML = "<li>No new notifications</li>";
      } else {
        notificationsToDisplay.forEach(notification => {
          const li = document.createElement("li");
          li.innerHTML = `
          <a href="admin_cash_advance.php" class="notification-link" style="text-decoration: none; color: black;">
            ${notification.message}
          </a>
        `;
          notificationList.appendChild(li);
        });
      }
    }

    prevPageButton.addEventListener("click", function() {
      if (currentPage > 0) {
        currentPage--;
        renderPagination();
      }
    });

    nextPageButton.addEventListener("click", function() {
      if (currentPage < Math.ceil(notifications.length / itemsPerPage) - 1) {
        currentPage++;
        renderPagination();
      }
    });
  });
</script>