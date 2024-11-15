<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

$host = "localhost";
$username = "root";
$password = "";
$database = "admin_login";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get employee details
function getEmployeeDetails($conn, $employeeNumber) {
    $stmt = $conn->prepare("SELECT first_name, last_name, middle_name, position_title, department FROM adding_employee WHERE employee_no = ?");
    $stmt->bind_param("s", $employeeNumber);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Kiosk</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: url('path/to/clock-background.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            color: white;
        }

        .container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            grid-column: 1 / -1;
            text-align: center;
            padding: 20px;
        }

        .logo {
            width: 150px;
            margin-bottom: 20px;
        }

        .time-display {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .date-display {
            font-size: 1.2em;
            margin-bottom: 20px;
        }

        .camera-container {
            background: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        #cameraFeed {
            width: 100%;
            height: 300px;
            background: #000;
            margin-bottom: 20px;
        }

        .employee-info {
            background: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 10px;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
            color: white;
        }

        .input-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: rgba(255, 255, 255, 0.9);
        }

        .button-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 20px;
        }

        .button-group button {
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .log-in-am {
            background-color: #4a90e2;
            color: white;
        }

        .log-out-pm {
            background-color: #f5a623;
            color: white;
        }

        #employeeDetails {
            margin-top: 20px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
            display: none;
        }

        #message {
            grid-column: 1 / -1;
            text-align: center;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
        }

        .success { background-color: rgba(40, 167, 69, 0.7); }
        .error { background-color: rgba(220, 53, 69, 0.7); }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="../image/logobg.png" alt="Logo" class="logo">
            <div class="time-display" id="currentTime"></div>
            <div class="date-display" id="currentDate"></div>
            <h2 id="greeting"></h2>
        </div>

        <div class="camera-container">
            <div id="cameraFeed">
                <!-- Camera feed will be displayed here -->
                <video id="video" autoplay style="width: 100%; height: 100%; display: none;"></video>
                <canvas id="canvas" style="width: 100%; height: 100%; display: none;"></canvas>
            </div>
        </div>

        <div class="employee-info">
            <div class="input-group">
                <label for="employeeNumber">EMPLOYEE NUMBER:</label>
                <input type="text" id="employeeNumber" name="employeeNumber">
            </div>
            <div id="employeeDetails">
                <p><strong>Name:</strong> <span id="empName"></span></p>
                <p><strong>Department:</strong> <span id="empDepartment"></span></p>
                <p><strong>Position:</strong> <span id="empPosition"></span></p>
                <p><strong>Time:</strong> <span id="empTime"></span></p>
            </div>
            <div class="button-group">
                <button class="log-in-am" onclick="handleAttendance('in')">Log In AM</button>
                <button class="log-out-pm" onclick="handleAttendance('out')">Log Out PM</button>
            </div>
        </div>

        <div id="message"></div>
    </div>

    <script>
        let videoStream = null;
        
        function updateTime() {
            const now = new Date();
            const timeOptions = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            };
            const dateOptions = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            
            document.getElementById('currentTime').textContent = now.toLocaleTimeString('en-US', timeOptions);
            document.getElementById('currentDate').textContent = now.toLocaleDateString('en-US', dateOptions);
            
            const hours = now.getHours();
            let greeting = hours < 12 ? 'Good Morning!!' : hours < 17 ? 'Good Afternoon!!' : 'Good Evening!!';
            document.getElementById('greeting').textContent = greeting;
        }

        setInterval(updateTime, 1000);
        updateTime();

        async function initializeCamera() {
            try {
                const video = document.getElementById('video');
                const constraints = {
                    video: {
                        width: 640,
                        height: 480
                    }
                };
                
                videoStream = await navigator.mediaDevices.getUserMedia(constraints);
                video.srcObject = videoStream;
                video.style.display = 'block';
                return true;
            } catch (err) {
                console.error('Error initializing camera:', err);
                document.getElementById('message').innerHTML = 'Error accessing camera. Please check permissions.';
                document.getElementById('message').className = 'error';
                return false;
            }
        }

        function stopCamera() {
            if (videoStream) {
                videoStream.getTracks().forEach(track => track.stop());
                document.getElementById('video').style.display = 'none';
                videoStream = null;
            }
        }

        async function handleAttendance(type) {
            const employeeNumber = document.getElementById('employeeNumber').value;
            if (!employeeNumber) {
                document.getElementById('message').innerHTML = 'Please enter your employee number.';
                document.getElementById('message').className = 'error';
                return;
            }

            const success = await initializeCamera();
            if (success) {
                document.getElementById('message').innerHTML = 'Please look at the camera for face recognition.';
                document.getElementById('message').className = 'info';
                
                // Start face recognition process
                startFaceRecognition(employeeNumber, type);
            }
        }

        function startFaceRecognition(employeeNumber, type) {
            // Simulate face recognition process
            setTimeout(() => {
                // Make AJAX call to get employee details
                fetch(`get_employee_details.php?employee_no=${employeeNumber}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            displayEmployeeDetails(data.employee, type);
                            logAttendance(employeeNumber, type);
                        } else {
                            document.getElementById('message').innerHTML = 'Employee not found.';
                            document.getElementById('message').className = 'error';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('message').innerHTML = 'Error processing request.';
                        document.getElementById('message').className = 'error';
                    })
                    .finally(() => {
                        stopCamera();
                    });
            }, 3000); // Simulate 3-second recognition process
        }

        function displayEmployeeDetails(employee, type) {
            const detailsDiv = document.getElementById('employeeDetails');
            document.getElementById('empName').textContent = `${employee.first_name} ${employee.last_name}`;
            document.getElementById('empDepartment').textContent = employee.department;
            document.getElementById('empPosition').textContent = employee.position_title;
            document.getElementById('empTime').textContent = new Date().toLocaleTimeString();
            detailsDiv.style.display = 'block';
        }

        function logAttendance(employeeNumber, type) {
            fetch('log_attendance.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    employee_no: employeeNumber,
                    type: type
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('message').innerHTML = `Attendance logged successfully: ${type.toUpperCase()}`;
                    document.getElementById('message').className = 'success';
                } else {
                    throw new Error(data.message || 'Failed to log attendance');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('message').innerHTML = error.message;
                document.getElementById('message').className = 'error';
            });
        }
    </script>
</body>
</html>