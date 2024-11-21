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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

</head>


<body>

    <?php include 'employee_navigation.php' ?>
    <?php include '../modals/update_profile_modal.php'; ?>
    <div>
        <div class="employee-greeting" style="text-align: center; margin-bottom: 20px;">
            <h1 style="color: #333;">Hello, <?php echo $employee['first_name']; ?></h1>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateProfile">
                Update Profile
            </button>
        </div>

        <div class="employee-details-container">
            <div class="main-content">
                <div class="header" style="display: flex; align-items: center; gap: 20px;">
                    <div class="profile-circle"
                        style="width: 80px; height: 80px; border-radius: 50%; background-color: #007bff; color: white; display: flex; justify-content: center; align-items: center; font-size: 24px; font-weight: bold;">
                        <span><?php echo strtoupper(substr($employee['first_name'], 0, 1)) . strtoupper(substr($employee['last_name'], 0, 1)); ?></span>
                    </div>
                    <h1 style="color: #007bff; margin: 0;">My Profile</h1>
                </div>

                <div class="content-section" style="margin-top: 20px;">
                    <div class="section-header" style="margin-bottom: 10px;">
                        <span class="badge"
                            style="background-color: #007bff; color: white; padding: 5px 10px; border-radius: 5px;">Personal
                            and Contact Data</span>
                    </div>

                    <div class="info-grid" style="display: flex; gap: 20px;">
                        <div class="personal-info" style="flex: 1;">
                            <h2 style="color: #333;">Personal Info</h2>
                            <div class="info-group" style="margin-bottom: 10px;">
                                <label style="font-weight: bold; color: #666;">Fullname:</label>
                                <span
                                    style="display: block; color: #333;"><?php echo $employee['first_name'] . ' ' . $employee['last_name']; ?></span>
                            </div>
                            <div class="info-group" style="margin-bottom: 10px;">
                                <label style="font-weight: bold; color: #666;">Employee No.:</label>
                                <span
                                    style="display: block; color: #333;"><?php echo $employee['employee_no']; ?></span>
                            </div>
                            <div class="info-group" style="margin-bottom: 10px;">
                                <label style="font-weight: bold; color: #666;">Birth Date:</label>
                                <span
                                    style="display: block; color: #333;"><?php echo date('F d, Y', strtotime($employee['birthdate'])); ?></span>
                            </div>
                        </div>

                        <div class="contact-info" style="flex: 1;">
                            <h2 style="color: #333;">Contact Info</h2>
                            <div class="info-group" style="margin-bottom: 10px;">
                                <label style="font-weight: bold; color: #666;">Phone:</label>
                                <span style="display: block; color: #333;"><?php echo $employee['contact']; ?></span>
                            </div>
                            <div class="info-group" style="margin-bottom: 10px;">
                                <label style="font-weight: bold; color: #666;">Email:</label>
                                <span style="display: block; color: #333;"><?php echo $employee['email']; ?></span>
                            </div>
                            <div class="info-group" style="margin-bottom: 10px;">
                                <label style="font-weight: bold; color: #666;">Address:</label>
                                <span style="display: block; color: #333;"><?php echo $employee['address']; ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="content-section" style="margin-top: 20px;">
                    <div class="section-header" style="margin-bottom: 10px;">
                        <span class="badge"
                            style="background-color: #28a745; color: white; padding: 5px 10px; border-radius: 5px;">Emergency
                            Contact</span>
                    </div>
                    <div class="emergency-info">
                        <div class="info-group" style="margin-bottom: 10px;">
                            <label style="font-weight: bold; color: #666;">Name:</label>
                            <span
                                style="display: block; color: #333;"><?php echo $employee['emergency_contact_name']; ?></span>
                        </div>
                        <div class="info-group" style="margin-bottom: 10px;">
                            <label style="font-weight: bold; color: #666;">Phone:</label>
                            <span
                                style="display: block; color: #333;"><?php echo $employee['emergency_contact_number']; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>


</html>

<!-- Add Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Add Bootstrap JS and Popper.js for Modal functionality -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>