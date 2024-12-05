<?php
// Include database connection
include '../connection/connections.php';
session_start(); // Ensure session is started

if (isset($_POST['update_rates'])) {
  // Sanitize inputs to avoid SQL injection
  $rate_id = isset($_POST['rate_id']) ? mysqli_real_escape_string($conn, $_POST['rate_id']) : null;
  $rate_position = isset($_POST['rate_position']) ? mysqli_real_escape_string($conn, $_POST['rate_position']) : null;
  $rate_per_hour = isset($_POST['rate_per_hour']) ? mysqli_real_escape_string($conn, $_POST['rate_per_hour']) : null;
  $rate_per_day = isset($_POST['rate_per_day']) ? mysqli_real_escape_string($conn, $_POST['rate_per_day']) : null;

  // Validate required fields
  if ($rate_id && $rate_position && $rate_per_hour && $rate_per_day) {
    // Update query
    $sql = "UPDATE under_position 
                SET rate_position = '$rate_position', 
                    rate_per_hour = '$rate_per_hour',
                    rate_per_day = '$rate_per_day'
                WHERE rate_id = '$rate_id'";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
      echo "<script>
                    alert('Rate updated successfully.');
                    window.location.href = document.referrer;
                  </script>";
    } else {
      echo "<script>
                    alert('Failed to update rate. Error: " . mysqli_error($conn) . "');
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
?>