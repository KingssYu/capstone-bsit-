<?php
session_start();
include '../connection/connections.php';

// Check if the employee is logged in
if (!isset($_SESSION['employee'])) {
  echo "<script>
            alert('You must be logged in to change your password.');
            window.location.href = 'login.php';
          </script>";
  exit();
}

// Get the employee number from the session
$employee_no = $_SESSION['employee']['employee_no'];

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $current_password = $_POST['current_password'];
  $new_password = $_POST['new_password'];
  $confirm_password = $_POST['confirm_password'];

  // Validate new password and confirm password match
  if ($new_password !== $confirm_password) {
    echo "<script>
                alert('New password and confirm password do not match.');
                window.location.href = document.referrer; // Redirects to the previous page
              </script>";
    exit();
  }

  // Retrieve the current password hash from the database
  $query = "SELECT password FROM adding_employee WHERE employee_no = '$employee_no'";
  $result = mysqli_query($conn, $query);

  if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $hashed_password = $row['password'];

    // Verify the current password
    if (!password_verify($current_password, $hashed_password)) {
      echo "<script>
                    alert('Current password is incorrect.');
                    window.location.href = document.referrer;
                  </script>";
    }

    // Hash the new password
    $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update the password in the database
    $update_query = "UPDATE adding_employee SET password = '$new_hashed_password' WHERE employee_no = '$employee_no'";
    if (mysqli_query($conn, $update_query)) {
      echo "<script>
                    alert('Password updated successfully.');
                    window.location.href = document.referrer; // Redirects to the previous page
                  </script>";
    } else {
      echo "<script>
                    alert('Error updating password. Please try again.');
                    window.location.href = document.referrer;
                  </script>";
    }
  } else {
    echo "<script>
                alert('Employee not found.');
                window.location.href = document.referrer;
              </script>";
  }
} else {
  echo "<script>
            alert('Invalid request method.');
            window.location.href = document.referrer;
          </script>";
}
