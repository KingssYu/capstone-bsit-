<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

// Database connection
$host = "localhost"; // Change if needed
$username = "root";  // Change if needed
$password = "";      // Change if needed
$database = "admin_login";  // Replace with your database name

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch total number of employees
$sql_total = "SELECT COUNT(*) AS total_employees FROM adding_employee";
$result_total = $conn->query($sql_total);
$total_employees = 0;

if ($result_total->num_rows > 0) {
    $row_total = $result_total->fetch_assoc();
    $total_employees = $row_total['total_employees'];
}

function getRecentEmployees($conn) {
    $threeOaysAgo = date('Y-m-d', strtotime('-3 days'));
    $sql = "SELECT employee_no, first_name, last_name, position, date_hired 
            FROM adding_employee 
            WHERE date_hired >= '$threeOaysAgo' 
            ORDER BY date_hired DESC 
            LIMIT 5";
    $result = $conn->query($sql);
    $recentEmployees = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $recentEmployees[] = $row;
        }
    }
    return $recentEmployees;
}

// Fetch recent employees
$recentEmployees = getRecentEmployees($conn);

// ... (rest of the existing PHP code)

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="admin_styles/dashboard.css">
    <style>
        /* Add this to your existing CSS or in a <style> tag */
        .recent-employees {
            background-color: #f0f0f0;
            padding: 15px;
            border-radius: 8px;
        }
        .recent-employee-item {
            background-color: #ffffff;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .recent-employee-name {
            font-weight: bold;
        }
        .recent-employee-position {
            color: #666;
            font-size: 0.9em;
        }
        .recent-employee-date {
            font-size: 0.8em;
            color: #888;
        }

        .calendar-container {
            max-width: 500px;
            margin: 20px auto;
            font-family: Arial, sans-serif;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .nav-button {
            background-color: #185519;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 4px;
        }

        .calendar {
            width: 100%;
            border-collapse: collapse;
        }

        .calendar th, .calendar td {
            text-align: center;
            padding: 8px;
            border: 1px solid #ddd;
        }

        .calendar th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .calendar td.inactive {
            color: #ccc;
        }

        .calendar td.today {
            background-color: #185519;
            color: white;
            font-weight: bold;
        }

        
    </style>
</head>
<body>
        <div class="sidenav">
            <div class="logo">
                <img src="../image/logobg.png" alt="Logo">
            </div>
            <div class="menu">
                <a href="dashboard.php">
                    <button>Dashboard</button>
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
                <a href="leave_management.php">
                    <button>Leave Management</button>
                </a>
            </div>
            <div class="footer">
                <a href="admin_logout.php">
                    <button>Logout</button>
                </a>
            </div>
        </div>
        <div class="main-content">
            <header class="main-header">
                <h1>Welcome to the Dashboard</h1>
                <div id="datetime" class="datetime"></div>
            </header>
            <!-- Main content goes here -->

                <!-- Horizontal Container for Employee Statistics -->
            <div class="stats-container">
                <div class="stats-item total">
                    <h3>Total Employees</h3>
                    <p class="count"><?php echo $total_employees; ?></p>
                </div>
                <div class="stats-item present">
                    <h3>Present</h3>
                    <p class="count">250</p>
                </div>
                <div class="stats-item absent">
                    <h3>Absent</h3>
                    <p class="count">50</p>
                </div>
        </div>

    <div class="info-containers">
        <div class="recent-employees">
            <h2>Recent Employees</h2>
            <?php if (count($recentEmployees) > 0): ?>
                <?php foreach ($recentEmployees as $employee): ?>
                    <div class="recent-employee-item">
                        <div class="recent-employee-name"><?php echo htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']); ?></div>
                        <div class="recent-employee-position"><?php echo htmlspecialchars($employee['position']); ?></div>
                        <div class="recent-employee-date">Hired: <?php echo date('M d, Y', strtotime($employee['date_hired'])); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No recent employees to display.</p>
            <?php endif; ?>
        </div>
        <div class="calendar-container">
            <h2>Real-Time Calendar</h2>
            <div id="calendar"></div>
        </div>
        
    </div>

</div>
    

    <script>
        function updateTime() {
            const now = new Date();
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
            };
            const formattedDate = now.toLocaleDateString('en-US', options);
            document.getElementById('datetime').textContent = formattedDate;
        }

        setInterval(updateTime, 1000);
        updateTime(); // Initial call to set date/time immediately

        document.addEventListener('DOMContentLoaded', function() {
    const calendarContainer = document.getElementById('calendar');
    const date = new Date();
    let currentMonth = date.getMonth();
    let currentYear = date.getFullYear();

    function createCalendar(month, year) {
        const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        const firstDay = new Date(year, month, 1).getDay();
        const lastDate = new Date(year, month + 1, 0).getDate();
        const prevLastDate = new Date(year, month, 0).getDate();

        let calendarHTML = `
            <div class="calendar-header">
                <button id="prevMonth" class="nav-button">Prev</button>
                <div>${new Date(year, month).toLocaleString('default', { month: 'long' })} ${year}</div>
                <button id="nextMonth" class="nav-button">Next</button>
            </div>
            <table class="calendar">
                <thead>
                    <tr>${daysOfWeek.map(day => `<th>${day}</th>`).join('')}</tr>
                </thead>
                <tbody>`;

        let day = 1;
        for (let i = 0; i < 6; i++) {
            calendarHTML += '<tr>';
            for (let j = 0; j < 7; j++) {
                if (i === 0 && j < firstDay) {
                    // Previous month days
                    calendarHTML += `<td class="inactive">${prevLastDate - firstDay + j + 1}</td>`;
                } else if (day > lastDate) {
                    // Next month days
                    calendarHTML += `<td class="inactive">${day - lastDate}</td>`;
                    day++;
                } else {
                    // Current month days
                    const todayClass = day === date.getDate() && month === date.getMonth() && year === date.getFullYear() ? 'today' : '';
                    calendarHTML += `<td class="${todayClass}">${day}</td>`;
                    day++;
                }
            }
            calendarHTML += '</tr>';
            if (day > lastDate) break;
        }

        calendarHTML += `</tbody></table>`;
        calendarContainer.innerHTML = calendarHTML;

        // Add event listeners to the new buttons
        document.getElementById('prevMonth').addEventListener('click', () => changeMonth(-1));
        document.getElementById('nextMonth').addEventListener('click', () => changeMonth(1));
    }

    function changeMonth(delta) {
        currentMonth += delta;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        } else if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        createCalendar(currentMonth, currentYear);
    }

    createCalendar(currentMonth, currentYear);
});
    </script>
</body>
</body>
</html>