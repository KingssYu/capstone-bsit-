<?php

include '../connection/connections.php';

session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ./../employee_area/portal.php");
    exit;
}

$conn = new mysqli($servername, $username, $password, $dbname);

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

// Fetch employee data
// Fetch employee data with department after contact
$sql = "SELECT * FROM adding_employee LEFT JOIN rate_position ON adding_employee.rate_id = rate_position.rate_id";
$result = $conn->query($sql);

if (isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $sql = "SELECT * 
            FROM adding_employee LEFT JOIN rate_position ON adding_employee.rate_id = rate_position.rate_id
            WHERE first_name LIKE '%$search%' 
               OR last_name LIKE '%$search%' 
               OR email LIKE '%$search%' 
               OR rate_position LIKE '%$search%'";
    $result = $conn->query($sql);

    $employees = [];
    while ($row = $result->fetch_assoc()) {
        $employee_no = $row['employee_no'];
        $employee_image_path = "employee_faces/$employee_no/face_25.jpg";
        if (!file_exists($employee_image_path) || !is_readable($employee_image_path)) {
            $employee_image_path = "employee_profile/default.png";
        }
        $row['image_path'] = $employee_image_path;
        $employees[] = $row;
    }

    echo json_encode($employees);
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Employee</title>
    <link rel="stylesheet" href="admin_styles/all_employee.css">
</head>

<body>
    <?php include './header.php'; ?>

    <div class="main-content">
        <header class="main-header">
            <h1>Employee</h1>
            <div id="datetime" class="datetime"></div>
        </header>

        <!-- Employee Summary and Action Buttons -->
        <div class="employee-summary">
            <div class="total-employees">
                <h2><span><?php echo $total_employees; ?></span> Employees</h2>
            </div>
            <div class="search-employee">
                <input type="text" placeholder="Search Employee..." class="search-input">
            </div>
            <div class="action-buttons">
                <a href="#" class="filter-button">Filter</a>
                <a href="employee_register.php" class="add-button">Add Employee</a>
            </div>
        </div>

        <!-- Main content goes here -->

        <div class="employee-list-container">
            <div class="employee-list">
                <?php
                if ($result->num_rows > 0) {
                    // Loop through each employee and populate the HTML
                    while ($row = $result->fetch_assoc()) {
                        // Fetch data
                        $employee_no = $row['employee_no'];
                        $full_name = $row['first_name'] . ' ' . $row['last_name'];
                        $rate_position = $row['rate_position'];
                        $department = $row['department'];
                        $date_hired = date("M d, Y", strtotime($row['date_hired']));
                        $email = $row['email'];
                        $contact = $row['contact'];

                        // Check if the employee's first face sample exists
                        $employee_image_path = "employee_faces/$employee_no/face_25.jpg"; // Changed from face_30.png to face_1.png
                
                        // If the image doesn't exist, use a default placeholder image
                        if (!file_exists($employee_image_path)) {
                            $employee_image_path = "employee_profile/default.png";
                        }

                        // Make sure the image file is readable
                        if (!is_readable($employee_image_path)) {
                            error_log("Cannot read image file: $employee_image_path");
                            $employee_image_path = "employee_profile/default.png";
                        }

                        // Display employee box
                        echo '
            <a href="employee_all_details.php?employee_no=' . $row['employee_no'] . '" class="employee-box">
                <div class="employee-header">
                    <img src="' . htmlspecialchars($employee_image_path) . '" alt="Employee Image" class="employee-image">
                    <div class="employee-info">
                        <p class="employee-name">' . htmlspecialchars($full_name) . '</p>
                        <p class="employee-position">' . htmlspecialchars($rate_position) . '</p>
                    </div>
                </div>
                <div class="employee-details">
                    <div class="employee-group">
                        <div class="employee-detail-item">
                            <p class="employee-title">Department:</p>
                            <p class="employee-department">' . htmlspecialchars($department) . '</p>
                        </div>
                        <div class="employee-detail-item">
                            <p class="employee-title">Date Hired:</p>
                            <p class="employee-date-hired">' . $date_hired . '</p>
                        </div>
                    </div>
                    <div class="employee-contact-group">
                        <p class="employee-contact">
                            <i class="email-icon">📧</i> ' . htmlspecialchars($email) . '
                        </p>
                        <p class="employee-contact">
                            <i class="contact-icon">📞</i> ' . htmlspecialchars($contact) . '
                        </p>
                    </div>
                </div>
            </a>';
                    }
                } else {
                    echo '<p>No employees found.</p>';
                }

                // Close the database connection
                $conn->close();
                ?>
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
        </script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function () {
                $('.search-input').on('input', function () {
                    var searchTerm = $(this).val();
                    if (searchTerm.length > 2) {
                        $.ajax({
                            url: 'all_employee.php',
                            method: 'GET',
                            data: {
                                search: searchTerm
                            },
                            dataType: 'json',
                            success: function (data) {
                                var employeeList = $('.employee-list');
                                employeeList.empty();

                                if (data.length > 0) {
                                    $.each(data, function (index, employee) {
                                        var employeeHtml = `
                                <a href="employee_all_details.php?employee_no=${employee.employee_no}" class="employee-box">
                                    <div class="employee-header">
                                        <img src="${employee.image_path}" alt="Employee Image" class="employee-image">
                                        <div class="employee-info">
                                            <p class="employee-name">${employee.first_name} ${employee.last_name}</p>
                                            <p class="employee-position">${employee.rate_position}</p>
                                        </div>
                                    </div>
                                    <div class="employee-details">
                                        <div class="employee-group">
                                            <div class="employee-detail-item">
                                                <p class="employee-title">Department:</p>
                                                <p class="employee-department">${employee.department}</p>
                                            </div>
                                            <div class="employee-detail-item">
                                                <p class="employee-title">Date Hired:</p>
                                                <p class="employee-date-hired">${new Date(employee.date_hired).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}</p>
                                            </div>
                                        </div>
                                        <div class="employee-contact-group">
                                            <p class="employee-contact">
                                                <i class="email-icon">📧</i> ${employee.email}
                                            </p>
                                            <p class="employee-contact">
                                                <i class="contact-icon">📞</i> ${employee.contact}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            `;
                                        employeeList.append(employeeHtml);
                                    });
                                } else {
                                    employeeList.html('<p>No employees found.</p>');
                                }
                            },
                            error: function (xhr, status, error) {
                                console.log("XHR Object:", xhr);
                                console.log("Status:", status);
                                console.log("Error:", error);
                                console.error("An error occurred: " + error);
                            }

                        });
                    } else if (searchTerm.length === 0) {
                        // If search is cleared, reload the page to show all employees
                        location.reload();
                    }
                });
            });
        </script>
</body>

</html>