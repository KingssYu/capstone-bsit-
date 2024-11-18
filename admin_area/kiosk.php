<?php
include '../connection/connections.php';

session_start();
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';
$employee_data = null;
$attendance_time = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_no = $_POST['employee_no'];
    $attendance_type = $_POST['attendance_type'];
    $camera_type = $_POST['camera_type'];

    // Verify if the employee exists
    $stmt = $conn->prepare("SELECT * FROM adding_employee WHERE employee_no = ?");
    $stmt->bind_param("s", $employee_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $employee_data = $result->fetch_assoc();

        // Call the face recognition script
        $command = "python face_recognition_attendance.py " . escapeshellarg($employee_no) . " " . escapeshellarg($attendance_type) . " " . escapeshellarg($camera_type);
        $output = shell_exec($command);
        $result = json_decode($output, true);

        if ($result && isset($result['success']) && $result['success']) {
            $message = $result['message'];
            $attendance_time = date('h:i:s A'); // Set the exact attendance time
        } else {
            $message = "Face recognition failed. Please try again.";
        }
    } else {
        $message = "Employee not found.";
    }
    $stmt->close();
}

// Handle AJAX request for employee information
if (isset($_GET['get_employee_info'])) {
    $employee_no = $_GET['get_employee_info'];
    $stmt = $conn->prepare("SELECT * FROM adding_employee WHERE employee_no = ?");
    $stmt->bind_param("s", $employee_no);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $employee_data = $result->fetch_assoc();
        echo json_encode($employee_data);
    } else {
        echo json_encode(["error" => "Employee not found"]);
    }
    $stmt->close();
    $conn->close();
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Face Recognition Attendance</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f4f8;
            color: #333;
        }

        .container {
            text-align: center;
            width: 80%;
            max-width: 900px;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #004085;
            font-weight: bold;
        }

        .content {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .left-section {
            width: 60%;
            text-align: center;
        }

        .logo-circle {
            width: 150px;
            height: 150px;
            border: 3px solid #004085;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            font-size: 18px;
            color: #004085;
            font-weight: bold;
        }

        .date-time {
            margin-top: 15px;
            font-size: 16px;
            color: #555;
        }

        .employee-number,
        .camera-type {
            margin-top: 20px;
            color: #333;
        }

        .employee-number label,
        .camera-type label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        .employee-number input,
        .camera-type select {
            width: 80%;
            padding: 12px;
            border: 2px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .employee-number input:focus,
        .camera-type select:focus {
            border-color: #004085;
            outline: none;
        }

        .buttons {
            margin-top: 20px;
        }

        .buttons button {
            width: 130px;
            padding: 10px;
            margin: 5px;
            font-size: 16px;
            font-weight: bold;
            border: 2px solid #004085;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #ffffff;
            background-color: #004085;
        }

        .buttons button:hover {
            background-color: #003366;
        }

        .right-section {
            width: 35%;
            text-align: left;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .right-section p {
            font-size: 16px;
            margin: 10px 0;
            color: #333;
        }

        .message {
            margin-top: 20px;
            padding: 10px;
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

        #loading-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .loading-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
        }

        .loading-spinner {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Employee Face Recognition Attendance</h1>

        <div class="content">
            <div class="left-section">
                <div class="logo-circle">
                    <span>Logo</span>
                </div>
                <div class="date-time">
                    <span id="current-datetime">Real date and time</span>
                </div>
                <form id="attendance-form" method="POST">
                    <div class="employee-number">
                        <label for="emp-number">Employee number:</label>
                        <input type="text" id="emp-number" name="employee_no" placeholder="Enter employee number"
                            required>
                    </div>
                    <div class="camera-type">
                        <label for="camera-type">Camera Type:</label>
                        <select id="camera-type" name="camera_type" required>
                            <option value="web">Web Camera</option>
                            <option value="usb">USB Camera</option>
                        </select>
                    </div>
                    <div class="buttons">
                        <button type="submit" name="attendance_type" value="in">Time In AM</button>
                        <button type="submit" name="attendance_type" value="out">Time Out PM</button>
                    </div>
                </form>
            </div>

            <div class="right-section">
                <p><strong>Last name:</strong> <span id="last-name"></span></p>
                <p><strong>Middle name:</strong> <span id="middle-name"></span></p>
                <p><strong>First name:</strong> <span id="first-name"></span></p>
                <p><strong>Exact attendance time:</strong> <span id="exact-time"><?php echo $attendance_time; ?></span>
                </p>
            </div>
        </div>

        <?php if ($message): ?>
            <div class="message <?php echo strpos($message, 'successful') !== false ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
    </div>

    <div id="loading-modal">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <p>Processing attendance...</p>
        </div>
    </div>

    <script>
        function updateDateTime() {
            const now = new Date();
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            const formattedDate = now.toLocaleDateString('en-US', options);
            document.getElementById('current-datetime').textContent = formattedDate;
        }

        setInterval(updateDateTime, 1000);
        updateDateTime();

        document.getElementById('emp-number').addEventListener('blur', function () {
            const employeeNo = this.value;
            if (employeeNo) {
                fetch(`kiosk.php?get_employee_info=${employeeNo}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            alert(data.error);
                        } else {
                            document.getElementById('last-name').textContent = data.last_name;
                            document.getElementById('middle-name').textContent = data.middle_name;
                            document.getElementById('first-name').textContent = data.first_name;
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        });

        document.getElementById('attendance-form').addEventListener('submit', function (e) {
            e.preventDefault();
            document.getElementById('loading-modal').style.display = 'block';
            const formData = new FormData(this);
            fetch('kiosk.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(html => {
                    document.body.innerHTML = html;
                    updateDateTime();
                })
                .catch(error => {
                    console.error('Error:', error);
                })
                .finally(() => {
                    document.getElementById('loading-modal').style.display = 'none';
                });
        });
    </script>
</body>

</html>