<?php
session_start();

include '../connection/connections.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = md5($_POST['password']); // Hash the password before comparison

        $stmt = $conn->prepare("SELECT * FROM admin WHERE username = :username AND password = :password");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        $admin = $stmt->fetch();

        if ($admin) {
            $_SESSION['admin'] = $admin['username'];
            header("Location: dashboard.php"); // Redirect to dashboard after login
        } else {
            $error = "Invalid credentials!";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="admin_styles/admin_login.css">
</head>

<body>
    <div class="login-container">
        <!-- Left Container: Background Image and Logo -->
        <div class="left-container">
            <div class="logo-container">
                <img src="../image/logobg.png" alt="Company Logo" class="logo">
            </div>
        </div>

        <div class="right-container">
            <form class="login-form" method="POST" action="admin_login.php">
                <h2>Admin Login</h2>

                <?php if (!empty($error)): ?>
                    <p style="color:red;"><?php echo $error; ?></p>
                <?php endif; ?>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" required>
                        <span class="toggle-password" onclick="togglePassword()"></span>
                    </div>
                </div>

                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember Me</label>
                </div>

                <button type="submit" class="login-button">Login</button>
            </form>
        </div>

        <script>
            function togglePassword() {
                var passwordField = document.getElementById("password");
                if (passwordField.type === "password") {
                    passwordField.type = "text";
                } else {
                    passwordField.type = "password";
                }
            }
        </script>


</body>

</html>