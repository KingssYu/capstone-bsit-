<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "admin_login";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success = false;
$error_message = '';
$num_samples = 0;

$cameras_command = "python face_capture.py list_cameras";
$cameras_output = shell_exec($cameras_command);
$cameras_result = json_decode($cameras_output, true);
$available_cameras = $cameras_result['cameras'] ?? [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn->begin_transaction();

    try {
        $employee_no = $_POST['employee_id'];
        $last_name = $_POST['last_name'];
        $first_name = $_POST['first_name'];
        $middle_name = $_POST['middle_name'];
        $email = isset($_POST['email']) ? $_POST['email'] : null;
        $position = $_POST['position'];
        $contact = $_POST['contact_no'];
        $department = $_POST['department'];
        $date_hired = $_POST['date_hired'];
        $address = $_POST['address'];
        $camera_index = $_POST['camera_index'];

        $stmt = $conn->prepare("INSERT INTO adding_employee (employee_no, last_name, first_name, middle_name, email, position, contact, department, date_hired, address, password_changed) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)");
        $stmt->bind_param("ssssssssss", $employee_no, $last_name, $first_name, $middle_name, $email, $position, $contact, $department, $date_hired, $address);

        if (!$stmt->execute()) {
            throw new Exception("Error inserting employee data: " . $stmt->error);
        }

        $command = "python face_capture.py " . escapeshellarg($employee_no) . " " . escapeshellarg($camera_index);
        $output = shell_exec($command);
        $result = json_decode($output, true);

        if ($result === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error decoding JSON: " . json_last_error_msg());
        }

        if ($result && isset($result['num_samples']) && $result['num_samples'] > 0) {
            $num_samples = $result['num_samples'];
            
            $update_stmt = $conn->prepare("UPDATE adding_employee SET face_samples = ? WHERE employee_no = ?");
            $update_stmt->bind_param("is", $num_samples, $employee_no);
            if (!$update_stmt->execute()) {
                throw new Exception("Error updating face samples: " . $update_stmt->error);
            }
            $update_stmt->close();

            $success = true;
        } else {
            $error_message = isset($result['error']) ? $result['error'] : "Failed to capture face samples.";
            error_log("Face capture error: " . $error_message);
            throw new Exception($error_message);
        }

        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        $success = false;
        $error_message = "Error: " . $e->getMessage();
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <link rel="stylesheet" href="admin_styles/employee_register.css">
    <style>
        .error-message { color: #ff0000; margin-bottom: 10px; }
        #faceCaptureModal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.4); }
        .modal-content { background-color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 500px; text-align: center; }
        #faceCaptureProgress { width: 100%; background-color: #ddd; }
        #faceCaptureBar { width: 0%; height: 30px; background-color: #4CAF50; text-align: center; line-height: 30px; color: white; }
        .retry-button { background-color: #4CAF50; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; margin-top: 10px; }
        .retry-button:hover { background-color: #45a049; }
    </style>
</head>
<body>
    <header>
        <div class="left-section">
            <img src="../image/logobg.png" alt="Company Logo" class="logo">
            <span class="title">New Employee</span>
        </div>
        <div class="right-section" id="datetime"></div>
    </header>

    <div class="form-container">
        <h2>Employee Registration</h2>
        <?php if ($success): ?>
            <div id="successMessage" style="display: none;">
                <h2 style="color: #4CAF50;">Success!</h2>
                <p>Employee added successfully with <?php echo $num_samples; ?> face samples!</p>
            </div>
        <?php elseif ($error_message): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
            <button id="retryFaceCapture" class="retry-button">Retry Face Capture</button>
        <?php endif; ?>
        <form id="employee-form" method="POST" action="employee_register.php">
            <div class="form-grid">
                <div class="form-group">
                    <label for="emp-no">Employee No.</label>
                    <input type="text" id="employee_id" name="employee_id" required>
                </div>
                <div class="form-group">
                    <label for="last-name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" required>
                </div>
                <div class="form-group">
                    <label for="first-name">First Name</label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>
                <div class="form-group">
                    <label for="middle-name">Middle Name</label>
                    <input type="text" id="middle_name" name="middle_name">
                </div>
                <div class="form-group">                                                                  
                    <label for="email">Email (optional)</label>
                    <input type="email" id="email" name="email">
                </div>
                <div class="form-group">
                    <label for="position">Position</label>
                    <input type="text" id="position" name="position" required>
                </div>
                <div class="form-group">
                    <label for="contact-no">Contact No.</label>
                    <input type="tel" id="contact-no" name="contact_no" required>
                </div>
                <div class="form-group">
                    <label for="department">Department</label>
                    <select id="department" name="department" required>
                        <option value="">Select Department</option>
                        <option value="HR">Human Resources</option>
                        <option value="IT">Information Technology</option>
                        <option value="Finance">Finance</option>
                        <option value="Marketing">Marketing</option>
                        <option value="Sales">Sales</option>
                        <option value="Operations">Operations</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="date-hired">Date Hired</label>
                    <input type="date" id="date_hired" name="date_hired" required>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="camera-index">Select Camera</label>
                    <select id="camera-index" name="camera_index" required>
                        <?php foreach ($available_cameras as $index): ?>
                            <option value="<?php echo $index; ?>">Camera <?php echo $index; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <button type="submit" class="submit-button">Register Employee</button>
        </form>
    </div>

    <div id="faceCaptureModal">
        <div class="modal-content">
            <h2>Capturing Face Samples</h2>
            <p>Please look at the camera and follow the instructions.</p>
            <div id="faceCaptureProgress">
                <div id="faceCaptureBar">0%</div>
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
        updateTime();

        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('employee_id').value = 'EMP-' + Math.floor(1000 + Math.random() * 9000);

            const form = document.getElementById('employee-form');
            const modal = document.getElementById('faceCaptureModal');
            const progressBar = document.getElementById('faceCaptureBar');

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                modal.style.display = 'block';
                let progress = 0;
                const interval = setInterval(function() {
                    if (progress >= 100) {
                        clearInterval(interval);
                        form.submit();
                    } else {
                        progress += 1;
                        progressBar.style.width = progress + '%';
                        progressBar.textContent = progress + '%';
                    }
                }, 100);
            });

            const retryButton = document.getElementById('retryFaceCapture');
            if (retryButton) {
                retryButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    const employeeId = document.getElementById('employee_id').value;
                    const cameraIndex = document.getElementById('camera-index').value;
                    
                    fetch('capture_face.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `employee_id=${employeeId}&camera_index=${cameraIndex}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Face capture successful!');
                            location.reload();
                        } else {
                            alert('Face capture failed: ' + data.error);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred during face capture.');
                    });
                });
            }

            <?php if ($success): ?>
            const successMessage = document.getElementById('successMessage');
            successMessage.style.display = 'block';
            setTimeout(function() {
                window.location.href = 'all_employee.php';
            }, 3000);
            <?php endif; ?>
        });
    </script>
</body>
</html>