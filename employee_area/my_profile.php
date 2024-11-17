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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="stylesheet" href="employee_styles/my_profile.css">
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <div class="logo">
                <img src="../image/logobg.png" alt="Company Logo">
            </div>
            <ul class="nav-links">
                <li><a href="employee_dashboard.php">Home</a></li>
                <li><a href="#" class="active">My Profile</a></li>
                <li><a href="#">Directory</a></li>
                <li><a href="#">Timesheets</a></li>
                <li><a href="#">Payroll</a></li>
                <li><a href="#">Leave Request</a></li>
            </ul>
            <div class="logout">
                <button class="logout-button">Logout</button>
            </div>
        </div>

        <div class="main-content">
            <div class="header">
                <div class="profile-circle">
                    <span>MA</span>
                </div>
                <h1>My Profile</h1>
            </div>

            <div class="content-section">
                <div class="section-header">
                    <span class="badge">Personal and Contact Data</span>
                </div>

                <div class="info-grid">
                    <div class="personal-info">
                        <h2>Personal Info</h2>
                        <div class="info-group">
                            <label>Fullname</label>
                            <span>Kristine Grace Castillo</span>
                        </div>
                        <div class="info-group">
                            <label>Employee No.</label>
                            <span>EMP-0316</span>
                        </div>
                        <div class="info-group">
                            <label>Birth Date</label>
                            <span>03/16/2002</span>
                        </div>
                        <div class="info-group">
                            <label>Marital Status</label>
                            <span>Single</span>
                        </div>
                    </div>

                    <div class="contact-info">
                        <h2>Contact Info</h2>
                        <div class="info-group">
                            <label>Phone</label>
                            <span>09604699786</span>
                        </div>
                        <div class="info-group">
                            <label>Email</label>
                            <span>kgbcastillo@gmail.com</span>
                        </div>
                        <div class="info-group">
                            <label>Address</label>
                            <span>B19 L13 Miramonte Park Subdivision Brgy 180 Malaria, Caloocan City</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <div class="section-header">
                    <span class="badge">Emergency Contact</span>
                </div>
                <div class="emergency-info">
                    <div class="info-group">
                        <label>Name</label>
                        <span>Teresita Castillo</span>
                    </div>
                    <div class="info-group">
                        <label>Relationship</label>
                        <span>Mother</span>
                    </div>
                    <div class="info-group">
                        <label>Phone</label>
                        <span>09604699786</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>