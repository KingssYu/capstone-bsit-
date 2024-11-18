<?php
include '../connection/connections.php';

session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ./../employee_area/portal.php");

    exit;
}

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function fetchAttendanceData($conn)
{
    $query = "SELECT e.employee_no, e.first_name, e.last_name, a.clock_in, a.clock_out, a.status,
              TIMEDIFF(IFNULL(a.clock_out, NOW()), a.clock_in) as actual_time
              FROM attendance a
              INNER JOIN adding_employee e ON a.employee_no = e.employee_no
              WHERE DATE(a.date) = CURDATE()
              ORDER BY e.last_name, e.first_name";
    $result = $conn->query($query);

    $attendance_data = [];
    while ($row = $result->fetch_assoc()) {
        $attendance_data[] = $row;
    }
    return $attendance_data;
}

$attendance_data = fetchAttendanceData($conn);

$conn->close();

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    echo json_encode($attendance_data);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance</title>
    <link rel="stylesheet" href="admin_styles/emp_attendance.css">
    <style>
        .message {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            font-weight: bold;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .info {
            background-color: #e2e3e5;
            color: #383d41;
        }

        .employee-attendance-row {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .employee-image {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
        }

        .employee-details {
            display: flex;
            flex-grow: 1;
            justify-content: space-between;
        }

        .employee-details span {
            flex-basis: 14%;
            text-align: center;
        }

        #cameraOptions,
        #attendanceTypeOptions {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <?php include './header.php'; ?>


    <div class="main-content">
        <header class="main-header">
            <h1>Attendance</h1>
            <div id="datetime" class="datetime"></div>
        </header>
        <div class="content-section">
            <div class="date-display">
                <span id="current-date-display"></span>
            </div>
            <div class="button-group">
                <a href="attendance_reports.php" class="report-button" target="_blank">Reports</a>
                <div id="cameraOptions">
                    <label for="cameraType">Camera Type:</label>
                    <select id="cameraType">
                        <option value="web">Web Camera</option>
                        <option value="usb">USB Camera</option>
                    </select>
                </div>
                <div id="attendanceTypeOptions">
                    <label for="attendanceType">Attendance Type:</label>
                    <select id="attendanceType">
                        <option value="in">Time In</option>
                        <option value="out">Time Out</option>
                    </select>
                </div>
                <button id="logAttendanceBtn" class="camera-button">
                    <i class="camera-icon">📸</i> Log Attendance
                </button>
            </div>
        </div>

        <div id="message" class="message" style="display: none;"></div>

        <div class="attendance-container">
            <div class="attendance-navigation">
                <ul class="nav-list">
                    <li class="nav-item">Name</li>
                    <li class="nav-item">Status</li>
                    <li class="nav-item">Clock In</li>
                    <li class="nav-item">Clock Out</li>
                    <li class="nav-item">Actual Time</li>
                </ul>
            </div>
            <div id="attendance-row-container" class="attendance-row-container">
                <!-- Attendance rows will be dynamically inserted here -->
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
            document.getElementById('current-date-display').textContent = `Today, ${formattedDate}`;
        }

        setInterval(updateTime, 1000);
        updateTime();

        function updateAttendanceData() {
            fetch('emp_attendance.php', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('attendance-row-container');
                    container.innerHTML = '';
                    if (data.length === 0) {
                        container.innerHTML = '<p>No attendance records for today.</p>';
                    } else {
                        data.forEach(employee => {
                            const row = document.createElement('div');
                            row.className = `employee-attendance-row status-${employee.status.toLowerCase()}`;
                            row.innerHTML = `
                            <img src="employee_profile/profiles.jpg" alt="Employee Image" class="employee-image">
                            <div class="employee-details">
                                <span class="employee-name">${employee.first_name} ${employee.last_name}</span>
                                <span class="employee-status">${employee.status || 'Present'}</span>
                                <span class="employee-clock-in">${employee.clock_in || '-'}</span>
                                <span class="employee-clock-out">${employee.clock_out || '-'}</span>
                                <span class="employee-actual-time">${employee.actual_time || '-'}</span>
                            </div>
                        `;
                            container.appendChild(row);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        document.getElementById('logAttendanceBtn').addEventListener('click', function () {
            const messageElement = document.getElementById('message');
            messageElement.style.display = 'block';
            messageElement.textContent = 'Initializing camera...';
            messageElement.className = 'message info';

            const cameraType = document.getElementById('cameraType').value;
            const attendanceType = document.getElementById('attendanceType').value;

            fetch('attendance_log.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'start_camera',
                    cameraType: cameraType,
                    attendanceType: attendanceType
                })
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Response from server:', data);
                    if (data.success) {
                        messageElement.textContent = 'Camera initialized. Please look at the camera for face recognition.';
                        messageElement.className = 'message success';
                        pollAttendanceResult();
                    } else {
                        messageElement.textContent = 'Error: ' + data.message;
                        messageElement.className = 'message error';
                        console.error('Server error:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    messageElement.textContent = 'An error occurred while initializing the camera. Please try again.';
                    messageElement.className = 'message error';
                });
        });

        function pollAttendanceResult() {
            const messageElement = document.getElementById('message');
            const pollInterval = setInterval(() => {
                fetch('attendance_log.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'check_result'
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Poll response:', data);
                        if (data.success) {
                            clearInterval(pollInterval);
                            messageElement.textContent = `${data.message} - Employee: ${data.employee_name} (${data.employee_no})`;
                            messageElement.className = 'message success';
                            updateAttendanceData();
                        } else if (data.error) {
                            if (data.error === 'Face recognition in progress') {
                                messageElement.textContent = 'Face recognition in progress. Please keep looking at the camera.';
                                messageElement.className = 'message info';
                            } else {
                                clearInterval(pollInterval);
                                messageElement.textContent = 'Error: ' + data.error;
                                messageElement.className = 'message error';
                                console.error('Face recognition error:', data.error);
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Poll error:', error);
                        clearInterval(pollInterval);
                        messageElement.textContent = 'An error occurred while checking attendance result. Please try again.';
                        messageElement.className = 'message error';
                    });
            }, 2000);

            setTimeout(() => {
                clearInterval(pollInterval);
                if (messageElement.className === 'message info') {
                    messageElement.textContent = 'Face recognition timed out. Please try again.';
                    messageElement.className = 'message error';
                }
            }, 35000);
        }

        updateAttendanceData();
        setInterval(updateAttendanceData, 30000);
    </script>
</body>

</html>