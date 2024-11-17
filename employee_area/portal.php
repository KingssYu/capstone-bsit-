<?php
session_start();

$host = 'localhost'; // Database host
$db = 'admin_login'; // Database name
$user = 'root'; // Database username
$pass = ''; // Database password;

try {
    // Database connection using PDO
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $login_error = ""; // Initialize login error

    // Check if both username and password are set
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = md5($_POST['password']); // Hash the password before comparison

            // Check if the user is an admin
            $admin_stmt = $conn->prepare("SELECT * FROM admin WHERE username = :username AND password = :password");
            $admin_stmt->bindParam(':username', $username);
            $admin_stmt->bindParam(':password', $password);
            $admin_stmt->execute();

            if ($admin = $admin_stmt->fetch(PDO::FETCH_ASSOC)) {
                // Admin login successful
                $_SESSION['admin'] = $admin['username'];
                header("Location: ./../admin_area/dashboard.php"); // Redirect to admin dashboard
                exit();
            } else {
                $username = $_POST['username']; // Employee No
                $password = $_POST['password']; // Entered Password (Initially this is the Last Name)

                // Prepare SQL to check employee credentials
                $stmt = $conn->prepare("SELECT * FROM adding_employee LEFT JOIN rate_position ON adding_employee.rate_id = rate_position.rate_id WHERE employee_no = :employee_no");
                $stmt->bindParam(':employee_no', $username);
                $stmt->execute();

                // If employee is found
                if ($employee = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
                            $login_error = "Invalid Last Name or Password!";
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
                    $login_error = "Invalid employee number or password!";
                }
            }
        } else {
            $login_error = "Please provide both username and password!";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
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
            <h2>Mapolcom Portal</h2>
            <form action="" method="post">
                <div class="input-group">
                    <label for="username">Username / Employee Number</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="input-group">
                    <label for="password">Password / Lastname</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" id="remember-me" name="remember-me">
                    <label for="remember-me">Remember Me</label>
                </div>
                <button type="submit" class="login-button">Sign In</button>
                <?php if (!empty($login_error)) {
                    echo "<p style='color:red;'>$login_error</p>";
                } ?>
                <div class="forgot-password">
                    <a href="#">Forgot Password?</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>