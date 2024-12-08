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

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const notificationButton = document.getElementById("notificationButton");
    const notificationList = document.getElementById("notificationList");
    const notificationBadge = document.getElementById("notificationBadge");

    // Automatically fetch notifications when the page loads
    fetchNotifications();

    // Fetch notifications when the button is clicked
    notificationButton.addEventListener("click", function() {
      toggleTooltip();
    });

    // Function to toggle tooltip visibility
    function toggleTooltip() {
      const tooltipContent = document.getElementById("tooltipContent");
      tooltipContent.style.display =
        tooltipContent.style.display === "block" ? "none" : "block";
    }

    // Function to fetch notifications via AJAX
    function fetchNotifications() {
      const xhr = new XMLHttpRequest();
      xhr.open("GET", "./fetch_notifications.php", true); // Replace with your PHP script
      xhr.onload = function() {
        if (xhr.status === 200) {
          const notifications = JSON.parse(xhr.responseText);
          renderNotifications(notifications);
        }
      };
      xhr.send();
    }

    // Function to render notifications
    // Function to render notifications
    function renderNotifications(notifications) {
      notificationList.innerHTML = ""; // Clear the list
      if (notifications.length === 0) {
        notificationList.innerHTML = "<li>No new notifications</li>";
        notificationBadge.textContent = "0";
      } else {
        notificationBadge.textContent = notifications.length;
        notifications.forEach((notification) => {
          const li = document.createElement("li");

          // Make the notification clickable
          li.innerHTML = `
        <a href="admin_cash_advance.php" class="notification-link" style="text-decoration: none; color: black;">
          ${notification.message}
        </a>
        <a class="dismiss-btn" data-id="${notification.id}" style="cursor: pointer;">X</a>
      `;

          notificationList.appendChild(li);
        });
        attachDismissHandlers();
      }
    }



    // Function to attach dismiss handlers to buttons
    function attachDismissHandlers() {
      const dismissButtons = document.querySelectorAll(".dismiss-btn");
      dismissButtons.forEach((btn) => {
        btn.addEventListener("click", function() {
          const notificationId = this.getAttribute("data-id");
          dismissNotification(notificationId, this);
        });
      });
    }

    // Function to dismiss a notification
    function dismissNotification(notificationId, button) {
      const xhr = new XMLHttpRequest();
      xhr.open("POST", "./dismiss_notification.php", true); // Replace with your PHP script
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onload = function() {
        if (xhr.status === 200) {
          // If dismissed successfully, remove the notification from DOM
          button.parentElement.remove(); // Removes the list item (notification)

          // Update the badge count
          const remainingNotifications = document.querySelectorAll("#notificationList li");
          notificationBadge.textContent = remainingNotifications.length;
        } else {
          console.error("Error dismissing notification: " + xhr.responseText);
        }
      };
      xhr.send(`id=${notificationId}`);
    }
  });
</script>