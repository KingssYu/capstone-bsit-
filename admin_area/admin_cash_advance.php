<?php
include '../connection/connections.php';

session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: ./../employee_area/portal.php");

  exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Cash Advance</title>
  <style>
    /* General Styles */
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      min-height: 100vh;
    }

    /* Sidebar Navigation */
    .sidenav {
      height: 100vh;
      width: 250px;
      background-color: #D5ED9F;
      color: #fff;
      rate_position: fixed;
      top: 0;
      left: 0;
      display: flex;
      flex-direction: column;
      box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    }

    /* Logo Section */
    .sidenav .logo {
      padding: 20px;
      text-align: center;
      background-color: #D5ED9F;
      border-bottom: 1px solid #D5ED9F;
    }

    .sidenav .logo img {
      max-width: 80%;
      height: auto;
    }

    /* Menu Buttons */
    .sidenav .menu {
      flex: 1;
    }

    .sidenav .menu button {
      width: 100%;
      padding: 15px;
      border: none;
      background: #185519;
      color: #fff;
      text-align: left;
      font-size: 16px;
      cursor: pointer;
      border-bottom: 1px solid #ffffff;
      transition: background-color 0.3s ease;
    }

    .sidenav .menu button:hover {
      background: #00712D;
    }

    /* Footer Buttons */
    .sidenav .footer {
      padding: 20px;
      background-color: #D5ED9F;
      text-align: center;
    }

    .sidenav .footer button {
      width: 100%;
      padding: 10px;
      border: none;
      background: #185519;
      border-radius: 50px;
      color: #fff;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .sidenav .footer button:hover {
      background: #00712D;
    }

    /* Directory Container */
    .directory-container {
      padding: 20px;
      width: calc(100% - 250px);
      background-color: #f4f4f9;
      height: 100vh;
      overflow: auto;
    }

    .directory-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding-bottom: 20px;
      border-bottom: 2px solid #e0e0e0;
    }

    .directory-header h1 {
      font-size: 24px;
      color: #333;
    }

    /* Search Bar Section */
    .search-container {
      display: flex;
      align-items: center;
      width: 100%;
      justify-content: space-between;
    }

    /* Dropdowns */
    .search-container select {
      padding: 10px;
      border-radius: 5px;
      border: 1px solid #ddd;
      font-size: 14px;
      color: #333;
    }

    /* Search input */
    .search-container input[type="text"] {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 25px;
      width: 100%;
      max-width: 500px;
      font-size: 14px;
      color: #333;
      margin: 0 10px;
    }

    /* Search Button */
    .search-container button {
      padding: 10px 20px;
      background-color: #185519;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 14px;
      margin-left: 10px;
    }

    /* Directory Table */
    .directory-table {
      width: 100%;
      margin-top: 20px;
      border-collapse: collapse;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .directory-table th,
    .directory-table td {
      padding: 15px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    .directory-table th {
      background-color: #f9f9f9;
      color: #333;
    }

    .directory-table td {
      background-color: #fff;
    }

    /* Profile image */
    .directory-table img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      margin-right: 10px;
    }

    .directory-table .name-cell {
      display: flex;
      align-items: center;
    }
  </style>
</head>

<body>
  <!-- Sidebar -->
  <?php include './header.php'; ?>

  <div class="directory-container">
    <div class="directory-header">
      <div class="header-left">
        <h1>Cash Advance Requests</h1>
      </div>
      <!-- <div class="header-right">
        <div class="notification-container">
          <button class="notification-button" id="notificationButton">
            Notifications <span class="notification-badge" id="notificationBadge">0</span>
          </button>
          <div class="tooltip-content" id="tooltipContent">
            <ul id="notificationList">
            </ul>
          </div>
        </div>
      </div> -->
    </div>

    <div id="modalContainerCashAdvance"></div>
    <!-- Table with Employee Data -->
    <table class="directory-table" name="admin_cash_advance_table" id="admin_cash_advance_table">
      <thead>
        <tr>
          <th>Cash Advance ID</th>
          <th>First Name</th>
          <th>Last Name</th>
          <th>Requested Amount</th>
          <th># of Months</th>
          <th>Date Requested</th>
          <th>Remaining Balance</th>
          <th>Monthly Payment</th>
          <th>Status</th>
          <th>Manage</th>
        </tr>
      </thead>
    </table>
  </div>

  <!-- Add Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Add Bootstrap JS and Popper.js for Modal functionality -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../datatables/datatables.min.css" />
  <script type="text/javascript" src="../datatables/datatables.min.js"></script>
  <script>
    var admin_cash_advance_table = $('#admin_cash_advance_table').DataTable({
      "pagingType": "numbers",
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": "./admin_cash_advance_table.php",
      },
    });

    $(document).ready(function() {
      // Function to handle click event on datatable rows
      $('#admin_cash_advance_table').on('click', 'tr td:nth-child(10) .fetchDataCashAdvance', function() {
        var cash_advance_id = $(this).closest('tr').find('td').first().text(); // Get the user_id from the clicked row

        $.ajax({
          url: '../modals/approve_cash_advance_modal.php', // Path to PHP script to fetch modal content
          method: 'POST',
          data: {
            cash_advance_id: cash_advance_id
          },
          success: function(response) {
            $('#modalContainerCashAdvance').html(response);
            $('#updateCashAdvance').modal('show');
            console.log("#updateCashAdvance" + cash_advance_id);
          },
          error: function(xhr, status, error) {
            console.error(xhr.responseText);
          }
        });
      });
    });

    $(document).ready(function() {
      // Function to handle click event on datatable rows
      $('#admin_cash_advance_table').on('click', 'tr td:nth-child(10) .fetchDataCashAdvanceDecline', function() {
        var cash_advance_id = $(this).closest('tr').find('td').first().text(); // Get the user_id from the clicked row

        $.ajax({
          url: '../modals/decline_cash_advance_modal.php', // Path to PHP script to fetch modal content
          method: 'POST',
          data: {
            cash_advance_id: cash_advance_id
          },
          success: function(response) {
            $('#modalContainerCashAdvance').html(response);
            $('#updateCashAdvance').modal('show');
            console.log("#updateCashAdvance" + cash_advance_id);
          },
          error: function(xhr, status, error) {
            console.error(xhr.responseText);
          }
        });
      });
    });
  </script>

</body>

</html>

<!-- <style>
  .directory-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #ddd;
  }

  .header-left h1 {
    margin: 0;
    font-size: 24px;
    font-weight: bold;
  }

  .header-right {
    display: flex;
    align-items: center;
  }

  .notification-container {
    position: relative;
  }

  .notification-button {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 15px;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
    position: relative;
  }

  .notification-badge {
    background-color: red;
    color: white;
    font-size: 14px;
    font-weight: bold;
    padding: 5px 10px;
    border-radius: 50%;
    position: absolute;
    top: -10px;
    right: -10px;
  }

  .tooltip-content {
    display: none;
    position: absolute;
    top: 120%;
    right: 0;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 550px;
    z-index: 1000;
    padding: 10px;
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

  .notification-button:focus+.tooltip-content,
  .notification-button:hover+.tooltip-content {
    display: block;
  }
</style> -->

<!-- <script>
  document.addEventListener("DOMContentLoaded", function () {
    const notificationButton = document.getElementById("notificationButton");
    const notificationList = document.getElementById("notificationList");
    const notificationBadge = document.getElementById("notificationBadge");

    // Automatically fetch notifications when the page loads
    fetchNotifications();

    // Fetch notifications when the button is clicked
    notificationButton.addEventListener("click", function () {
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
      xhr.onload = function () {
        if (xhr.status === 200) {
          const notifications = JSON.parse(xhr.responseText);
          renderNotifications(notifications);
        }
      };
      xhr.send();
    }

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
          li.innerHTML = `
          ${notification.message}
          <button class="dismiss-btn" data-id="${notification.id}">X</button>
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
        btn.addEventListener("click", function () {
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
      xhr.onload = function () {
        if (xhr.status === 200) {
          // If dismissed successfully, remove the notification from DOM
          button.parentElement.remove();  // Removes the list item (notification)

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


</script> -->