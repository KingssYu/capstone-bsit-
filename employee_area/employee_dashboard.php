<?php
// Start the session
session_start();

// THIS IS FOR LIVE
$servername = "localhost";
$username = "u759574209_bsupayroll";
$password = "Mybossrocks081677!";
$dbname = "u759574209_bsupayroll";

// // THIS IS FOR LOCAL TESTING
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "bsu_payroll";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    // echo "SUCCESS";
}

// Check if employee is logged in
if (!isset($_SESSION['employee'])) {
    header("Location: portal.php");
    exit();
}

// Get employee data from session
$employee = $_SESSION['employee'];
$employee_no = $_SESSION['employee']['employee_no'];

// Prepare statement to get present and late employees for today
$sql_present_late = "
    SELECT attendance.employee_no,
           CASE 
               WHEN attendance.clock_in <= '08:30:00' THEN 'Present'
               WHEN attendance.clock_in > '08:30:00' THEN 'Late'
               ELSE 'Absent'
           END AS status
    FROM attendance
    LEFT JOIN adding_employee ON adding_employee.employee_no = attendance.employee_no
    WHERE attendance.date = CURDATE() AND adding_employee.employee_no = ?";

// Prepare the statement to prevent SQL injection
$stmt_present_late = $conn->prepare($sql_present_late);
$stmt_present_late->bind_param("s", $employee_no); // Bind the employee number
$stmt_present_late->execute();
$result_present_late = $stmt_present_late->get_result();

$present = 0;
$late = 0;
$absent = 0;

// Loop through the results and categorize employees
while ($row = $result_present_late->fetch_assoc()) {
    if ($row['status'] == 'Present') {
        $present++;
    } elseif ($row['status'] == 'Late') {
        $late++;
    }
}

// Now, find the employees who are absent (no attendance record for today)
$sql_absent = "
    SELECT COUNT(DISTINCT adding_employee.employee_no) AS total_employees_absent
    FROM adding_employee
    LEFT JOIN attendance ON adding_employee.employee_no = attendance.employee_no
    WHERE (attendance.date != CURDATE() OR attendance.date IS NULL)
    AND adding_employee.employee_no = ?";

$stmt_absent = $conn->prepare($sql_absent);
$stmt_absent->bind_param("s", $employee_no); // Bind the employee number
$stmt_absent->execute();
$result_absent = $stmt_absent->get_result();

// Fetch the total number of absent employees
$total_employees_absent = 0;
if ($result_absent->num_rows > 0) {
    $row_absent = $result_absent->fetch_assoc();
    $total_employees_absent = $row_absent['total_employees_absent'];
}

// Display the total number of absent employees
// echo "Total Absent Employees: " . $total_employees_absent;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo $employee['first_name'] . ' ' . $employee['last_name']; ?>'s Dashboard</title>
    <link rel="stylesheet" href="employee_styles/employee_dashboard.css">
</head>

<body>

    <div class="sidebar">
        <div class="logo">
            <img src="../image/logobg.png" alt="Company Logo">
        </div>
        <ul class="nav-links">
            <li><a href="employee_dashboard.php">Home</a></li>
            <li><a href="my_profile.php">My Profile</a></li>
            <li><a href="cash_advance_request.php">Cash Advance Request</a></li>
            <li><a href="timesheet.php">Timesheet</a></li>
            <li><a href="payslip.php">Payslip Record</a></li>
            <li><a href="changepass_module.php">Change Password</a></li>

            <!-- <li><a href="#">Leave Request</a></li> -->
        </ul>
        <div class="logout">
            <a href="logout.php" class="logout-button">Logout</a>
        </div>
    </div>

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
                    <p><strong>Department:</strong> <?php echo $employee['department_name']; ?></p>
                    <p><strong>Contact:</strong> <?php echo $employee['contact']; ?></p>
                </div>
                <!-- <div class="stat-row">
                    <p><strong>Status:</strong> </?php echo $employee['employee_stats']; ?></p>
                </div> -->
            </div>
        </div>
    </div>

    <div class="attendance-calendar-container">
        <div class="attendance-container">
            <h3>Attendance for today <a href="timesheet.php" class="view-stats">View Stats</a></h3>
            <div class="attendance-status">
                <p><span class="dot green"></span> <?php echo $present . ' Present'; ?></p>
                <p><span class="dot yellow"></span> <?php echo $late . ' Late'; ?></p>
                <p><span class="dot red"></span> <?php echo $total_employees_absent . ' Absent'; ?></p>
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




</body>

</html>

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