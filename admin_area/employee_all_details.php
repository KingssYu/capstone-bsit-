<?php
// Database connection
include '../connection/connections.php';


$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the employee_no from the URL
$employee_no = isset($_GET['employee_no']) ? $conn->real_escape_string($_GET['employee_no']) : '';

if ($employee_no) {
    // Query to fetch employee details based on employee_no
    $sql = "SELECT adding_employee.id AS employee_id, 
                adding_employee.*, 
                cash_advance.id AS cash_advance_id, 
                cash_advance.requested_amount,
                cash_advance.months,
                cash_advance.monthly_payment,
                cash_advance.remaining_balance,
                cash_advance.paid_amount,
                cash_advance.status,
                rate_position.*
        FROM adding_employee
        LEFT JOIN rate_position ON adding_employee.rate_id = rate_position.rate_id
        LEFT JOIN cash_advance ON adding_employee.id = cash_advance.id
        WHERE adding_employee.employee_no = '$employee_no'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch the employee details
        $employee = $result->fetch_assoc();

        // Check if the face image exists in the employee_faces directory
        $face_image_path = "employee_faces/" . $employee_no . "/face_30.jpg";
        if (!file_exists($face_image_path)) {
            $face_image_path = "default_face.png"; // Fallback image if no face image exists
        }
    } else {
        echo "<p>Employee not found.</p>";
        exit();
    }
} else {
    echo "<p>No employee number provided.</p>";
    exit();
}

// Handle the delete request
if (isset($_POST['delete_employee'])) {
    // Start a transaction
    $conn->begin_transaction();

    try {
        // First, delete related records from attendance_report
        $delete_attendance_sql = "DELETE FROM attendance_report WHERE employee_no = '$employee_no'";
        $conn->query($delete_attendance_sql);

        // Then, delete the employee
        $delete_employee_sql = "DELETE FROM adding_employee WHERE employee_no = '$employee_no'";
        $conn->query($delete_employee_sql);

        // Delete the employee's face folder
        $face_folder = "employee_faces/$employee_no";
        if (is_dir($face_folder)) {
            $files = glob($face_folder . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            rmdir($face_folder);
        }

        // If we've made it this far without errors, commit the transaction
        $conn->commit();

        echo "<script>alert('Employee removed successfully.'); window.location.href = 'all_employee.php';</script>";
    } catch (Exception $e) {
        // An error occurred; rollback the transaction
        $conn->rollback();
        echo "Error deleting employee: " . $e->getMessage();
    }
}

// Modify the get_monthly_attendance function to accept JSON requests
function get_monthly_attendance($conn, $employee_no, $year, $month)
{
    // Set the time zone to Philippines Time (PHT)
    date_default_timezone_set('Asia/Manila');

    // Ensure the MySQL session time zone matches the PHP timezone
    $conn->query("SET time_zone = '+08:00'");

    // Query to retrieve attendance data
    $query = "
    SELECT DATE(date) as date, status, TIME(time_in) as time_in, TIME(time_out) as time_out, actual_time
    FROM attendance_report
    WHERE employee_no = ? AND YEAR(date) = ? AND MONTH(date) = ? AND is_paid = 0 AND date < CURDATE()
    ORDER BY date
    ";

    // Prepare and execute the query
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sii", $employee_no, $year, $month);
    $stmt->execute();
    $result = $stmt->get_result();

    $records = [];
    while ($row = $result->fetch_assoc()) {
        $records[] = $row;
    }

    // Initialize summary variables
    $total_present = 0;
    $total_absent = 0;
    $total_late = 0;
    $total_hours = 0;
    $total_overtime = 0; // Initialize total overtime

    foreach ($records as $record) {
        // Count attendance status
        if ($record['status'] == 'Present')
            $total_present++;
        elseif ($record['status'] == 'Absent')
            $total_absent++;
        elseif ($record['status'] == 'Late')
            $total_late++;

        // Calculate regular hours
        if ($record['actual_time']) {
            list($hours, $minutes, $seconds) = explode(':', $record['actual_time']);
            $hours = (int) $hours;
            $minutes = (int) $minutes;
            $seconds = (int) $seconds;
            $total_hours += $hours + ($minutes / 60) + ($seconds / 3600);
        }

        // Calculate overtime (OT) if time_out is after 18:00
        if (isset($record['time_out']) && $record['time_out'] > '18:00:00') {
            // Convert time_out and 18:00:00 into DateTime objects
            $timeOut = new DateTime($record['time_out']);
            $endOfRegularShift = new DateTime('18:00:00');

            // Calculate the difference in hours
            $overtimeInterval = $timeOut->diff($endOfRegularShift);
            $overtimeHours = $overtimeInterval->h + ($overtimeInterval->i / 60) + ($overtimeInterval->s / 3600);

            // Add to total overtime
            $total_overtime += $overtimeHours;
        }
    }

    // Round the total hours and overtime
    $total_hours = round($total_hours, 2);
    $total_overtime = round($total_overtime, 2);

    // Return the records and summary along with current date in PHT
    return [
        'records' => $records,
        'summary' => [
            'total_present' => $total_present,
            'total_absent' => $total_absent,
            'total_late' => $total_late,
            'total_hours' => $total_hours,
            'total_overtime' => $total_overtime // Add OT to the summary
        ],
        'current_date' => date('Y-m-d') // Current date in PHT
    ];
}

// Handle AJAX requests
if (isset($_GET['ajax']) && $_GET['ajax'] == 'getAttendance') {
    $employee_no = $_GET['employee_no'];
    $year = $_GET['year'];
    $month = $_GET['month'];
    $attendance_data = get_monthly_attendance($conn, $employee_no, $year, $month);
    header('Content-Type: application/json');
    echo json_encode($attendance_data);
    exit;
}

// Get current month and year
$current_month = date('n');
$current_year = date('Y');

// Get initial attendance data
$attendance_data = get_monthly_attendance($conn, $employee_no, $current_year, $current_month);

// Add this function to calculate payroll
function calculatePayroll($conn, $employee_no, $year, $month)
{
    $attendance_data = get_monthly_attendance($conn, $employee_no, $year, $month);

    $rate_per_hour = 68.75;
    $hours_per_day = 8;
    $basic_per_day = $rate_per_hour * $hours_per_day;

    $total_days = $attendance_data['summary']['total_present'] + $attendance_data['summary']['total_late'];
    $total_hours = $attendance_data['summary']['total_hours'];

    $gross_pay = $total_hours * $rate_per_hour;

    return [
        'rate_per_hour' => $rate_per_hour,
        'basic_per_day' => $basic_per_day,
        'total_days' => $total_days,
        'total_hours' => $total_hours,
        'gross_pay' => $gross_pay
    ];
}

// Calculate payroll data
$payroll_data = calculatePayroll($conn, $employee_no, $current_year, $current_month);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Employee Details</title>
    <link rel="stylesheet" href="admin_styles/employee_all_details.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>
    <style>
        .calendar-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .nav-button {
            text-decoration: none;
            color: #185519;
            font-weight: bold;
        }

        .current-date {
            font-size: 18px;
            font-weight: bold;
        }

        .calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
        }

        .day {
            aspect-ratio: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            font-weight: bold;
        }

        .day-header {
            font-weight: bold;
            text-align: center;
            padding: 5px;
        }

        .day.empty {
            background-color: transparent;
        }

        .day.future {
            background-color: #f0f0f0;
            color: #999;
        }

        .day.absent {
            background-color: #FF0000;
        }

        .day.present {
            background-color: #00712D;
        }

        .day.late {
            background-color: #ffff99;
        }

        .attendance-summary {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        .attendance-summary div {
            text-align: center;
        }

        .attendance-summary span {
            font-weight: bold;
        }

        .attendance-summary h2 {
            margin: 5px 0;
            font-size: 24px;
        }

        .modal {
            display: none;
            rate_position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
        }

        .payslip {
            font-family: Arial, sans-serif;
        }

        .payslip-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .payslip-header .logo {
            width: 50px;
            height: auto;
        }

        .payslip-info {
            text-align: right;
        }

        .employee-info {
            margin-bottom: 20px;
        }

        .payslip-details {
            display: flex;
            justify-content: space-between;
        }

        .earnings,
        .deductions {
            width: 48%;
        }

        .payslip-total {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #000;
        }

        .payslip-netpay {
            text-align: center;
            margin-top: 20px;
            font-size: 1.2em;
        }

        .payslip-footer {
            margin-top: 40px;
        }

        .modal-buttons {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .modal-buttons button {
            margin-left: 10px;
            padding: 5px 10px;
            cursor: pointer;
        }

        .time-record-table {
            border: 1px solid #000;
            margin-top: 20px;
        }

        .time-record-table th,
        .time-record-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        .info-field {
            border-bottom: 1px solid #000;
            padding: 4px 0;
            min-height: 20px;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            #timeRecordContent,
            #timeRecordContent * {
                visibility: visible;
            }

            #timeRecordContent {
                rate_position: absolute;
                left: 0;
                top: 0;
            }

            .modal-buttons {
                display: none;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="left-section">
            <a href="./dashboard.php"
                style="text-decoration: none; display: flex; align-items: center; color:#f0f0f0 !important;">
                <img src="../image/logobg.png" alt="Company Logo" class="logo">
                <span class="title">Employee Details</span>
            </a>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>
        </div>
        <div class="right-section" id="datetime"></div>
    </header>


    <div class="main-container">
        <!-- Employee Details Container (Left Side) -->
        <!-- HTML to display employee details -->
        <div class="employee-details-container">
            <div class="profile-image-box">
                <img src="<?php echo $face_image_path; ?>" alt="Profile Image" class="profile-image">
            </div>
            <div class="employee-name">
                <?php echo htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']); ?>
            </div>
            <div class="employee-indicator"><?php echo htmlspecialchars($employee['rate_position']); ?></div>
            <div class="employee-info">
                <div class="info-item">
                    <div class="info-title">Employee No.</div>
                    <div class="info-content"><?php echo htmlspecialchars($employee['employee_no']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-title">Hire Date</div>
                    <div class="info-content">
                        <?php echo htmlspecialchars(date("M d, Y", strtotime($employee['date_hired']))); ?>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-title">Position</div>
                    <div class="info-content"><?php echo htmlspecialchars($employee['rate_position']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-title">Department</div>
                    <div class="info-content"><?php echo htmlspecialchars($employee['department']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-title">Email</div>
                    <div class="info-content"><?php echo htmlspecialchars($employee['email']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-title">Contact</div>
                    <div class="info-content"><?php echo htmlspecialchars($employee['contact']); ?></div>
                </div>
            </div>
            <button class="delete-button" onclick="confirmDelete()">Remove Employee</button>

            <!-- Add this form somewhere in your HTML, preferably near the delete button -->
            <form id="delete-form" method="POST" style="display: none;">
                <input type="hidden" name="delete_employee" value="1">
            </form>
            </form>
        </div>

        <!-- Employee Information Container (Right Side) -->
        <div class="employee-information-container">
            <div class="information-header">
                <button class="info-section active" onclick="showSection('personalContact')">Personal and contact
                    data</button>
                <button class="info-section" onclick="showSection('attendanceRecord')">Attendance record</button>
                <button class="info-section" onclick="showSection('payrollSystem')">Payroll</button>
            </div>

            <div class="information-content">
                <!-- Personal & Contact Data Section -->
                <div id="personalContact" class="info-section-content active">
                    <h3>Personal Data
                        <i class="edit-icon" onclick="openEditModal('personalData')">&#9998;</i>
                    </h3>
                    <div class="personal-data" id="personalData">
                        <div class="info-pair">
                            <div class="info-container">
                                <div class="info-title">Last Name</div>
                                <div class="info-content"><?php echo htmlspecialchars($employee['last_name']); ?></div>
                            </div>
                            <div class="info-container">
                                <div class="info-title">Given Name</div>
                                <div class="info-content"><?php echo htmlspecialchars($employee['first_name']); ?></div>
                            </div>
                        </div>
                        <div class="info-pair">
                            <div class="info-container">
                                <div class="info-title">Middle Name</div>
                                <div class="info-content"><?php echo htmlspecialchars($employee['middle_name']); ?>
                                </div>
                            </div>
                            <div class="info-container">
                                <div class="info-title">Birthdate</div>
                                <div class="info-content">
                                    <?php echo isset($employee['birthdate']) ? htmlspecialchars($employee['birthdate']) : 'Not provided'; ?>
                                </div>
                            </div>
                        </div>
                        <div class="info-pair">
                            <div class="info-container">
                                <div class="info-title">Gender</div>
                                <div class="info-content">
                                    <?php echo isset($employee['gender']) ? htmlspecialchars($employee['gender']) : 'Not provided'; ?>
                                </div>
                            </div>
                            <div class="info-container">
                                <div class="info-title">Nationality</div>
                                <div class="info-content">
                                    <?php echo isset($employee['nationality']) ? htmlspecialchars($employee['nationality']) : 'Not provided'; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h3>Contact
                        <i class="edit-icon" onclick="openEditModal('contactData')">&#9998;</i>
                    </h3>
                    <div class="contact-data" id="contactData">
                        <div class="info-pair">
                            <div class="info-container">
                                <div class="info-title">Permanent Address</div>
                                <div class="info-content"><?php echo htmlspecialchars($employee['address']); ?></div>
                            </div>
                        </div>
                        <div class="info-pair">
                            <div class="info-container">
                                <div class="info-title">Contact No.</div>
                                <div class="info-content"><?php echo htmlspecialchars($employee['contact']); ?></div>
                            </div>
                            <div class="info-container">
                                <div class="info-title">Email</div>
                                <div class="info-content"><?php echo htmlspecialchars($employee['email']); ?></div>
                            </div>
                        </div>
                    </div>

                    <h3>Emergency Contact
                        <i class="edit-icon" onclick="openEditModal('emergencyContact')">&#9998;</i>
                    </h3>
                    <div class="emergency-contact" id="emergencyContact">
                        <div class="info-pair">
                            <div class="info-container">
                                <div class="info-title">Name</div>
                                <div class="info-content">
                                    <?php echo isset($employee['emergency_contact_name']) ? htmlspecialchars($employee['emergency_contact_name']) : 'Not provided'; ?>
                                </div>
                            </div>
                            <div class="info-container">
                                <div class="info-title">Contact No.</div>
                                <div class="info-content">
                                    <?php echo isset($employee['emergency_contact_number']) ? htmlspecialchars($employee['emergency_contact_number']) : 'Not provided'; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Modal -->
                <div id="editModal" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeEditModal()">&times;</span>
                        <h2>Edit Information</h2>
                        <div id="editFormContainer">
                            <!-- Form will be loaded here dynamically -->
                        </div>
                        <!-- Save Button -->
                        <button class="save-btn" onclick="saveChanges()">Save</button>
                    </div>
                </div>




                <?php include 'calendar_tab.php'; ?>


                <?php include 'payroll_tab.php'; ?>



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

        //section container
        function showSection(sectionId) {
            document.querySelectorAll('.info-section-content').forEach((section) => {
                section.classList.remove('active');
            });
            document.querySelectorAll('.info-section').forEach((button) => {
                button.classList.remove('active');
            });
            document.getElementById(sectionId).classList.add('active');
            document
                .querySelector(`.info-section[onclick="showSection('${sectionId}')"]`)
                .classList.add('active');
        }

        //edit
        // Add this function to close the modal
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Modify the openEditModal function to show the modal
        function openEditModal(sectionId) {
            const modal = document.getElementById('editModal');
            const formContainer = document.getElementById('editFormContainer');

            // Load the form dynamically based on the section to be edited
            if (sectionId === 'personalData') {
                formContainer.innerHTML = `
            <form id="personalDataForm">
                <label for="lastName">Last Name</label>
                <input type="text" id="lastName" name="last_name" value="<?php echo htmlspecialchars($employee['last_name']); ?>">
                <label for="firstName">First Name</label>
                <input type="text" id="firstName" name="first_name" value="<?php echo htmlspecialchars($employee['first_name']); ?>">
                <label for="middleName">Middle Name</label>
                <input type="text" id="middleName" name="middle_name" value="<?php echo htmlspecialchars($employee['middle_name']); ?>">
                <label for="birthdate">Birthdate</label>
                <input type="date" id="birthdate" name="birthdate" value="<?php echo isset($employee['birthdate']) ? htmlspecialchars($employee['birthdate']) : ''; ?>">
                <label for="gender">Gender</label>
                <input type="text" id="gender" name="gender" value="<?php echo isset($employee['gender']) ? htmlspecialchars($employee['gender']) : ''; ?>">
                <label for="nationality">Nationality</label>
                <input type="text" id="nationality" name="nationality" value="<?php echo isset($employee['nationality']) ? htmlspecialchars($employee['nationality']) : ''; ?>">
            </form>
        `;
            } else if (sectionId === 'contactData') {
                formContainer.innerHTML = `
            <form id="contactDataForm">
                <label for="address">Permanent Address</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($employee['address']); ?>">
                <label for="contactNo">Contact No.</label>
                <input type="text" id="contactNo" name="contact" value="<?php echo htmlspecialchars($employee['contact']); ?>">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($employee['email']); ?>">
            </form>
        `;
            } else if (sectionId === 'emergencyContact') {
                formContainer.innerHTML = `
            <form id="emergencyContactForm">
                <label for="emergencyName">Name</label>
                <input type="text" id="emergencyName" name="emergency_contact_name" value="<?php echo isset($employee['emergency_contact_name']) ? htmlspecialchars($employee['emergency_contact_name']) : ''; ?>">
                <label for="emergencyContactNo">Contact No.</label>
                <input type="text" id="emergencyContactNo" name="emergency_contact_number" value="<?php echo isset($employee['emergency_contact_number']) ? htmlspecialchars($employee['emergency_contact_number']) : ''; ?>">
            </form>
        `;
            }

            modal.style.display = 'block';
        }

        // Add event listener to close the modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                closeEditModal();
            }
        }


        function saveChanges() {
            const activeForm = document.querySelector('.modal-content form');
            const formData = new FormData(activeForm);
            formData.append('employee_no', '<?php echo $employee_no; ?>');
            formData.append('action', 'update_employee');

            fetch('update_employee.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Employee information updated successfully');
                        location.reload(); // Reload the page to show updated information
                    } else {
                        alert('Error updating employee information: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating employee information');
                });

            closeEditModal();
        }

        function confirmDelete() {
            if (confirm("Are you sure you want to delete this employee?")) {
                // If the user confirms, submit the form to delete the employee
                document.getElementById("delete-form").submit();
            }
        }

        function confirmDelete() {
            if (confirm("Are you sure you want to delete this employee? This will also delete their face samples.")) {
                document.getElementById("delete-form").submit();
            }
        }

        //DTR print

        // Function to show the time record modal
        function printTimeRecord() {
            const modal = document.getElementById('timeRecordModal');
            const tbody = document.getElementById('timeRecordBody');

            // Clear existing rows
            tbody.innerHTML = '';

            // Get the current day of the month
            const today = new Date();
            const lastDayOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0).getDate();
            const startDay = today.getDate() <= 15 ? 1 : 16;
            const endDay = today.getDate() <= 15 ? 15 : lastDayOfMonth;

            // Fetch attendance data for the current month
            fetch(`get_attendance_details.php?employee_no=${employeeNo}&year=${currentYear}&month=${currentMonth}`)
                .then(response => response.json())
                .then(data => {
                    // Sort the records by date
                    const sortedRecords = data.records.sort((a, b) => new Date(a.date) - new Date(b.date));

                    // Create rows for the selected range of days
                    for (let i = startDay; i <= endDay; i++) {
                        const date = `${currentYear}-${String(currentMonth).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
                        const record = sortedRecords.find(r => r.date === date);

                        const row = document.createElement('tr');
                        row.innerHTML = `
                    <td>${i}</td>
                    <td>${record ? formatTime(record.morning_in) : ''}</td>
                    <td>${record ? formatTime(record.afternoon_out) : ''}</td>
                    <td>${record ? formatTime(record.overtime_out) : ''}</td>
                `;
                        tbody.appendChild(row);
                    }
                });

            modal.style.display = 'block';
        }


        function formatTime(time) {
            if (!time) return '';
            return new Date('2000-01-01 ' + time).toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });
        }

        function closeTimeRecordModal() {
            document.getElementById('timeRecordModal').style.display = 'none';
        }

        function downloadTimeRecordPDF() {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();
            const content = document.getElementById('timeRecordContent');

            doc.html(content, {
                callback: function(doc) {
                    doc.save('daily-time-record.pdf');
                },
                x: 10,
                y: 10,
                width: 190,
                windowWidth: 800
            });
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('timeRecordModal');
            if (event.target == modal) {
                closeTimeRecordModal();
            }
        }
    </script>

    <!-- Add this modal for the Daily Time Record just before the closing </body> tag -->
    <div id="timeRecordModal" class="modal">
        <div class="modal-content" style="max-width: 800px;">
            <span class="close" onclick="closeTimeRecordModal()">&times;</span>
            <div id="timeRecordContent">
                <div class="daily-time-record" style="padding: 20px;">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <img src="../image/logobg.png" alt="Company Logo" style="width: 60px; height: auto;">
                        <h2 style="margin: 10px 0;">Daily Time Record</h2>
                    </div>

                    <div class="employee-details" style="margin-bottom: 20px;">
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
                            <div>
                                <label>Last Name:</label>
                                <div class="info-field"><?php echo htmlspecialchars($employee['last_name']); ?></div>
                            </div>
                            <div>
                                <label>First Name:</label>
                                <div class="info-field"><?php echo htmlspecialchars($employee['first_name']); ?></div>
                            </div>
                            <div>
                                <label>MI:</label>
                                <div class="info-field">
                                    <?php echo htmlspecialchars($employee['middle_name'][0] ?? ''); ?>
                                </div>
                            </div>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-top: 10px;">
                            <div>
                                <label>Department:</label>
                                <div class="info-field"><?php echo htmlspecialchars($employee['department']); ?></div>
                            </div>
                            <div>
                                <label>Position:</label>
                                <div class="info-field"><?php echo htmlspecialchars($employee['rate_position']); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <table class="time-record-table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>MORNING<br>TIME IN</th>
                                <th>AFTERNOON<br>TIME OUT</th>
                                <th>OVERTIME<br>TIME OUT</th>
                            </tr>
                        </thead>
                        <tbody id="timeRecordBody">
                            <!-- Will be populated dynamically -->
                        </tbody>
                    </table>

                    <div style="margin-top: 30px; text-align: right;">
                        <div>EMPLOYEE SIGNATURE: _________________________</div>
                    </div>
                </div>
            </div>
            <div class="modal-buttons" style="margin-top: 20px;">
                <button onclick="printTimeRecord()">Print</button>
                <button onclick="downloadTimeRecordPDF()">Download PDF</button>
                <button onclick="closeTimeRecordModal()">Close</button>
            </div>
        </div>
    </div>

    <?php include '../modals/daily_time_record_modal.php' ?>

</body>

</html>

<script>
    // THIS SCRIPT IS FOR THE 2nd tab.
    let currentMonth = <?php echo $current_month; ?>;
    let currentYear = <?php echo $current_year; ?>;
    const employeeNo = '<?php echo $employee_no; ?>';


    function updateCalendar() {
        fetch(`employee_all_details.php?ajax=getAttendance&employee_no=${employeeNo}&year=${currentYear}&month=${currentMonth}`)
            .then(response => response.json())
            .then(data => {
                const calendarDays = document.getElementById('calendarDays');
                calendarDays.innerHTML = '';

                const daysInMonth = new Date(currentYear, currentMonth, 0).getDate();
                const firstDay = new Date(currentYear, currentMonth - 1, 1).getDay();

                // Days of the week header
                const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                daysOfWeek.forEach(day => {
                    const dayHeader = document.createElement('div');
                    dayHeader.className = 'day-header';
                    dayHeader.textContent = day;
                    calendarDays.appendChild(dayHeader);
                });

                // Empty cells for days before the 1st
                for (let i = 0; i < firstDay; i++) {
                    const emptyDay = document.createElement('div');
                    emptyDay.className = 'day empty';
                    calendarDays.appendChild(emptyDay);
                }

                // Calendar days
                for (let day = 1; day <= daysInMonth; day++) {
                    const date = `${currentYear}-${String(currentMonth).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                    const dayElement = document.createElement('div');
                    dayElement.className = 'day';
                    dayElement.textContent = day;

                    if (date > data.current_date) {
                        dayElement.classList.add('future');
                    } else {
                        const record = data.records.find(r => r.date === date);
                        if (record) {
                            dayElement.classList.add(record.status.toLowerCase());
                            dayElement.title = `In: ${record.time_in}\nOut: ${record.time_out}`;
                        } else {
                            dayElement.classList.add('absent');
                        }
                    }

                    calendarDays.appendChild(dayElement);
                }

                // Update summary
                document.getElementById('totalAttendance').textContent = data.summary.total_present;
                document.getElementById('totalAbsent').textContent = data.summary.total_absent;
                document.getElementById('totalHours').textContent = `${data.summary.total_hours} hours`;

                // Update current date display
                document.getElementById('currentDate').textContent = `Current Date: ${new Date(currentYear, currentMonth - 1, 1).toLocaleString('default', { month: 'long', year: 'numeric' })}`;
            });
    }

    document.getElementById('prevMonth').addEventListener('click', (e) => {
        e.preventDefault();
        currentMonth--;
        if (currentMonth < 1) {
            currentMonth = 12;
            currentYear--;
        }
        updateCalendar();
    });

    document.getElementById('nextMonth').addEventListener('click', (e) => {
        e.preventDefault();
        currentMonth++;
        if (currentMonth > 12) {
            currentMonth = 1;
            currentYear++;
        }
        updateCalendar();
    });

    // Initial calendar update
    updateCalendar();
</script>