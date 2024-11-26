<?php
include './connection/connections.php';

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['forgot_password'])) {
  $email = $_POST['email']; // Directly use the email without trimming

  // Check if the email exists in the database
  $sql = "SELECT first_name, last_name, email FROM adding_employee WHERE email = '$email'"; // Directly compare email
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $user_fullname = $user['first_name'] . ' ' . $user['last_name'];

    // Generate a temporary password
    $temporary_password = bin2hex(random_bytes(4)); // Generates an 8-character random password
    $hashed_password = password_hash($temporary_password, PASSWORD_DEFAULT); // Hash the password

    // Update the password in the database
    $update_sql = "UPDATE adding_employee SET `password` = '$hashed_password' WHERE email = '$email'"; // Use the hashed password

    if ($conn->query($update_sql)) {
      // Send the temporary password via email
      $mail = new PHPMailer(true);
      try {
        // Server settings
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                       // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'cassielee680@gmail.com';          // SMTP username
        $mail->Password   = 'wtanlkmdxodolnvk';                     // SMTP password
        $mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption; `ssl` encouraged
        $mail->Port       = 465;                                    // TCP port to connect to

        // Recipients
        $mail->setFrom('cassielee680@gmail.com', 'Mapolcom');
        $mail->addAddress($email, $user_fullname);                  // Add a recipient

        // Content
        $mail->isHTML(true);                                        // Set email format to HTML
        $mail->Subject = 'Password Reset Request';
        $mail->Body    = "
                    <html>
                    <head>
                        <style>
                            .email-container {
                                font-family: Arial, sans-serif;
                                line-height: 1.6;
                                color: #333;
                            }
                            .email-header {
                                background-color: #007bff;
                                padding: 20px;
                                color: #fff;
                                text-align: center;
                            }
                            .email-body {
                                padding: 20px;
                                background-color: #f9f9f9;
                            }
                            .email-footer {
                                text-align: center;
                                padding: 10px;
                                background-color: #007bff;
                                color: #fff;
                            }
                            .temporary-password {
                                font-weight: bold;
                                color: #007bff;
                            }
                        </style>
                    </head>
                    <body>
                        <div class='email-container'>
                            <div class='email-header'>
                                <h2>Password Reset</h2>
                            </div>
                            <div class='email-body'>
                                <p>Dear $user_fullname,</p>
                                <p>You have requested a password reset. Here is your temporary password:</p>
                                <p class='temporary-password'>$temporary_password</p>
                                <p>Please use this password to log in and update your password as soon as possible.</p>
                                <p>If you did not request a password reset, please contact our support team immediately.</p>
                                <p>Best Regards,<br>Mapolcom</p>
                            </div>
                            <div class='email-footer'>
                                &copy; 2024 Your Company. All rights reserved.
                            </div>
                        </div>
                    </body>
                    </html>";

        $mail->send();

        // Respond with success message
        echo "<script>
            alert('Password reset successfully!');
            window.location.href = 'index.php'; // Redirects to index.php
          </script>";
      } catch (Exception $e) {
        $response = array('success' => false, 'message' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        echo json_encode($response);
        exit();
      }
    } else {
      $response = array('success' => false, 'message' => 'Failed to update the password. Please try again.');
      echo json_encode($response);
      exit();
    }
  } else {
    $response = array('success' => false, 'message' => 'Email not found in our records.');
    echo json_encode($response);
    exit();
  }
}
