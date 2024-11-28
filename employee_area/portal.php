<?php
include '../connection/connections.php';

session_start();

if (isset($_SESSION['employee'])) {
    header("Location: employee_dashboard.php");
    exit();
}

try {
    // Database connection using PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $login_error = ""; // Initialize login error

    // Check if both username and password are set
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $user_password = $_POST['password']; // Plain text password

            // Check if the user is an admin
            $admin_stmt = $conn->prepare("SELECT * FROM admin WHERE username = :username");
            $admin_stmt->bindParam(':username', $username);
            $admin_stmt->execute();

            if ($admin = $admin_stmt->fetch(PDO::FETCH_ASSOC)) {
                // Admin login successful - Verify password
                if (password_verify($user_password, $admin['password'])) {
                    $_SESSION['admin'] = $admin['username'];
                    header("Location: ./../admin_area/dashboard.php"); // Redirect to admin dashboard
                    exit();
                } else {
                    $login_error = "Invalid Admin Password!";
                }
            } else {
                // Now checking for employee login
                $employee_stmt = $conn->prepare("SELECT * FROM adding_employee LEFT JOIN under_position ON adding_employee.rate_id = under_position.rate_id WHERE employee_no = :employee_no");
                $employee_stmt->bindParam(':employee_no', $username);
                $employee_stmt->execute();

                // If employee is found
                if ($employee = $employee_stmt->fetch(PDO::FETCH_ASSOC)) {
                    // First-time login (using Last Name as the default password)
                    if ($employee['password_changed'] == 0) {
                        // Check if the entered password matches their last name (default password)
                        if ($user_password == $employee['last_name']) {
                            // Store employee data in session
                            $_SESSION['employee'] = $employee;
                            $_SESSION['employee_no'] = $employee['employee_no']; // Assign employee_no to the session

                            // Redirect to password change page
                            header("Location: change_password.php");
                            exit();
                        } else {
                            $login_error = "Invalid Last Name or Password!";
                        }
                    } else {
                        // Normal login after password has been changed - Verify password
                        if (password_verify($user_password, $employee['password'])) {
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
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" required>
                        <span id="togglePassword">Show</span>
                    </div>
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
                    <a href="./../forgot_password.php">Forgot Password?</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>

<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordField = document.getElementById('password');
        const toggleButton = this;

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleButton.textContent = 'Hide';
        } else {
            passwordField.type = 'password';
            toggleButton.textContent = 'Show';
        }
    });
</script>

<style>
    .input-group {
        display: flex;
        flex-direction: column;
        margin-bottom: 20px;
    }

    .input-wrapper {
        position: relative;
        display: inline-block;
        width: 100%;
    }

    .input-wrapper input {
        width: 100%;
        padding-right: 50px;
        /* Space for the toggle button */
        box-sizing: border-box;
    }

    #togglePassword {
        position: absolute;
        top: 50%;
        right: 10px;
        /* Aligns the toggle button to the right */
        transform: translateY(-50%);
        cursor: pointer;
        font-size: 0.9rem;
        color: #007BFF;
        user-select: none;
    }

    #togglePassword:hover {
        color: #0056b3;
    }
</style>