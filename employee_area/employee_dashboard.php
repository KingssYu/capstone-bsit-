<?php
// Start the session
session_start();

// Check if employee is logged in
if (!isset($_SESSION['employee'])) {
    header("Location: login.php");
    exit();
}

// Get employee data from session
$employee = $_SESSION['employee'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo $employee['first_name'] . ' ' . $employee['last_name']; ?>'s Dashboard</title>
    <link rel="stylesheet" href="employee_styles/employee_dashboard.css">
</head>

<body>

    <?php include 'employee_navigation.php' ?>

    <div class="employee-greeting">
        <h1>Hello, <?php echo $employee['first_name']; ?></h1>
    </div>

    <div class="employee-details-container">
        <div class="employee-profile">
            <img src="../image/logobg.png" alt="Employee Profile" class="profile-image">
            <div class="employee-info">
                <h2><?php echo $employee['first_name'] . ' ' . $employee['last_name']; ?></h2>
                <p><?php echo $employee['rate_position']; ?></p>
            </div>
        </div>

        <div class="employee-stats">
            <div class="left-stats">
                <div class="stat-row">
                    <p><strong>Employee No:</strong> <?php echo $employee['employee_no']; ?></p>
                    <p><strong>Date Hired:</strong> <?php echo $employee['date_hired']; ?></p>
                </div>
                <div class="stat-row">
                    <p><strong>Department:</strong> <?php echo $employee['department']; ?></p>
                    <p><strong>Contact:</strong> <?php echo $employee['contact']; ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="attendance-calendar-container">
        <div class="attendance-container">
            <h3>Attendance <a href="#" class="view-stats">View Stats</a></h3>
            <div class="attendance-status">
                <p><span class="dot green"></span> 1000 Present</p>
                <p><span class="dot yellow"></span> 100 Late</p>
                <p><span class="dot red"></span> 50 Absent</p>
            </div>
        </div>

        <div class="calendar-container">
            <h3>Current Date</h3>
            <div id="calendar"></div>
        </div>
    </div>


    <div class="members-events-container">
        <div class="department-members-container">
            <h3>Department Members
                <a href="#" class="view-directory">View Directory</a>
            </h3>
            <div class="member-list">
                <!-- Dynamically list members -->
                <div class="member-item">
                    <img src="../image/logobg.png" alt="Member 1" class="member-image">
                    <div class="member-info">
                        <p class="member-name">Member 1</p>
                        <p class="member-contact">123-456-7890</p>
                    </div>
                </div>
                <!-- Add more member-items as needed -->
            </div>
        </div>

        <div class="upcoming-events-container">
            <h3>Upcoming Events</h3>
            <div class="events-list">
                <!-- Add dynamic event content here -->
                <h2>Comming up...</h2>
            </div>
        </div>
    </div>


    <script>
        // Create the real calendar
        function generateCalendar() {
            const calendarElement = document.getElementById('calendar');
            const date = new Date();
            const month = date.getMonth();
            const year = date.getFullYear();

            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const firstDayIndex = new Date(year, month, 1).getDay();

            // Month and Year Header
            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            const calendarHeader = `<h2>${monthNames[month]} ${year}</h2>`;

            // Days of the Week Header
            const weekDays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            let weekDaysRow = '<div class="weekdays">';
            for (let i = 0; i < 7; i++) {
                weekDaysRow += `<div>${weekDays[i]}</div>`;
            }
            weekDaysRow += '</div>';

            // Days of the Month
            let daysHTML = '<div class="days">';
            for (let i = 0; i < firstDayIndex; i++) {
                daysHTML += '<div class="empty"></div>'; // Empty days before the start of the month
            }
            for (let i = 1; i <= daysInMonth; i++) {
                if (i === date.getDate()) {
                    daysHTML += `<div class="today">${i}</div>`; // Highlight current day
                } else {
                    daysHTML += `<div>${i}</div>`;
                }
            }
            daysHTML += '</div>';

            // Final Calendar HTML
            calendarElement.innerHTML = calendarHeader + weekDaysRow + daysHTML;
        }

        // Call the function to generate the calendar
        generateCalendar();
    </script>

</body>

</html>

<!-- Add Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Add Bootstrap JS and Popper.js for Modal functionality -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>