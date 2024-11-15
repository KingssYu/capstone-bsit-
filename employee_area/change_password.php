<?php
session_start();
$host = "localhost";
$username = "root";
$password = "";
$database = "admin_login";
$conn = new mysqli($host, $username, $password, $database);

// Check if the employee is logged in
if (!isset($_SESSION['employee'])) {
    header("Location: portal.php");
    exit();
}

$update_error = "";
$success = false;

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'];
    $employee_no = $_SESSION['employee']['employee_no'];

    // Hash the new password before storing it
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update the password and set password_changed to true
    $stmt = $conn->prepare("UPDATE adding_employee SET password = ?, password_changed = 1 WHERE employee_no = ?");
    $stmt->bind_param("ss", $hashed_password, $employee_no);

    if ($stmt->execute()) {
        $success = true; // Set success flag to true
    } else {
        $update_error = "Error updating password: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f4f4f9;
        }

        .form-container {
            background-color: #fff;
            padding: 20px 40px;
            border-radius: 10px;
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .form-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            color: #555;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: border 0.3s ease;
        }

        .input-group input:focus {
            border-color: #007bff;
            outline: none;
        }

        .login-button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-button:hover {
            background-color: #0056b3;
        }

        p {
            margin-top: 10px;
            font-size: 14px;
        }

        .success-message {
            color: green;
            font-size: 16px;
            margin-top: 10px;
        }
    </style>

    <!-- Add a script to redirect after showing the success message -->
    <?php if ($success) : ?>
        <meta http-equiv="refresh" content="3;url=portal.php">
    <?php endif; ?>
</head>
<body>
    <div class="form-container">
        <h2>Change Your Password</h2>
        <?php if ($success): ?>
            <p class="success-message">Password successfully changed! Redirecting to login...</p>
        <?php else: ?>
            <form action="change_password.php" method="post">
                <div class="input-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                <button type="submit" class="login-button">Change Password</button>
                <?php if (!empty($update_error)) { echo "<p style='color:red;'>$update_error</p>"; } ?>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
