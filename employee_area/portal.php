<?php
// Start the session
session_start();

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "admin_login";

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize login error
$login_error = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username']; // Employee No
    $password = $_POST['password']; // Entered Password (Initially this is the Last Name)

    // Prepare SQL to check employee credentials
    $stmt = $conn->prepare("SELECT * FROM adding_employee WHERE employee_no = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // If employee is found
    if ($result->num_rows > 0) {
        $employee = $result->fetch_assoc();
        
        // First-time login (using Last Name as the default password)
        if ($employee['password_changed'] == 0) {
            // Check if the entered password matches their last name (default password)
            if ($password == $employee['last_name']) {
                // Store employee data in session
                $_SESSION['employee'] = $employee;
                
                // Redirect to password change page
                header("Location: change_password.php");
                exit();
            } else {
                $login_error = "Invalid employee number or last name!";
            }
        } else {
            // Normal login after password has been changed
            if (password_verify($password, $employee['password'])) {
                // Store employee data in session
                $_SESSION['employee'] = $employee;
                
                // Redirect to employee dashboard
                header("Location: employee_dashboard.php");
                exit();
            } else {
                $login_error = "Invalid employee number or password!";
            }
        }
    } else {
        $login_error = "Invalid employee number or last name!";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <link rel="stylesheet" href="employee_styles/portal.css">
</head>
<body>
<div class="form-container">
    <div class="login-box" id="login-box">
        <div class="icon-container">
            <img src="image/logobg.png" alt="Employee Icon">
        </div>
        <h2>Employee Portal</h2>
        <form action="" method="post">
            <div class="input-group">
                <label for="username">Employee Number</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Last Name</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="checkbox-group">
                <input type="checkbox" id="remember-me" name="remember-me">
                <label for="remember-me">Remember Me</label>
            </div>
            <button type="submit" class="login-button">Sign In</button>
            <?php if (!empty($login_error)) { echo "<p style='color:red;'>$login_error</p>"; } ?>
            <div class="forgot-password">
                <a href="#">Forgot Password?</a>
            </div>
        </form>
    </div>
</div>
</div>

</body>
</html>