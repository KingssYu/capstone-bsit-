<?php
// Include database connection
include '../connection/connections.php';
session_start(); // Ensure session is started

if (isset($_POST['update_profile'])) {
  // Get the POST data and sanitize inputs to avoid SQL injection
  $employee_no = isset($_POST['employee_no']) ? mysqli_real_escape_string($conn, $_POST['employee_no']) : null;
  $first_name = isset($_POST['first_name']) ? mysqli_real_escape_string($conn, $_POST['first_name']) : null;
  $last_name = isset($_POST['last_name']) ? mysqli_real_escape_string($conn, $_POST['last_name']) : null;
  $contact = isset($_POST['contact']) ? mysqli_real_escape_string($conn, $_POST['contact']) : null;
  $address = isset($_POST['address']) ? mysqli_real_escape_string($conn, $_POST['address']) : null;
  $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : null;
  $emergency_contact_name = isset($_POST['emergency_contact_name']) ? mysqli_real_escape_string($conn, $_POST['emergency_contact_name']) : null;
  $emergency_contact_number = isset($_POST['emergency_contact_number']) ? mysqli_real_escape_string($conn, $_POST['emergency_contact_number']) : null;

  // Check if all required fields are provided
  if ($employee_no && $first_name && $last_name && $contact && $address && $email) {
    // Update query
    $sql = "UPDATE adding_employee 
                SET first_name = '$first_name', 
                    last_name = '$last_name',
                    contact = '$contact',
                    `address` = '$address',
                    email = '$email',
                    emergency_contact_name = '$emergency_contact_name',
                    emergency_contact_number = '$emergency_contact_number'
                WHERE employee_no = '$employee_no'";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
      // Fetch updated data
      $result = mysqli_query($conn, "SELECT * FROM adding_employee 
                                     LEFT JOIN rate_position ON adding_employee.rate_id = adding_employee.rate_id
                                      WHERE employee_no = '$employee_no'");
      if ($result && mysqli_num_rows($result) > 0) {
        $updated_employee = mysqli_fetch_assoc($result);

        // Update session data
        $_SESSION['employee'] = $updated_employee;

        echo "<script>
                        alert('Profile updated successfully.');
                        window.location.href = document.referrer;
                      </script>";
      } else {
        echo "<script>
                        alert('Failed to fetch updated employee data.');
                        window.location.href = document.referrer;
                      </script>";
      }
    } else {
      echo "<script>
                    alert('Failed to update employee. Error: " . mysqli_error($conn) . "');
                    window.location.href = document.referrer;
                  </script>";
    }
  } else {
    echo "<script>
                alert('All fields are required.');
                window.location.href = document.referrer;
              </script>";
  }
} else {
  echo "<script>
            alert('Invalid request.');
            window.location.href = document.referrer;
          </script>";
}
