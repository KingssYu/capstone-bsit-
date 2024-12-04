<?php
// Include database connection
include '../connection/connections.php';
session_start();

// Check if form is submitted
if (isset($_POST['add_ranking']) && $_POST['add_ranking'] == '1') {
  // Retrieve and sanitize input
  $position_id = mysqli_real_escape_string($conn, trim($_POST['position_id']));
  $rate_position = mysqli_real_escape_string($conn, trim($_POST['rate_position']));
  $rate_per_hour = mysqli_real_escape_string($conn, trim($_POST['rate_per_hour']));
  $rate_per_day = mysqli_real_escape_string($conn, trim($_POST['rate_per_day']));

  // Input validation
  if (!$rate_position || !$rate_per_hour || !$rate_per_day) {
    echo "<script>
                alert('All fields are required.');
                window.history.back();
              </script>";
    exit();
  }

  // Insert the new position into the database
  $insert_sql = "INSERT INTO under_position (position_id, rate_position, rate_per_hour, rate_per_day) 
                       VALUES ('$position_id', '$rate_position', '$rate_per_hour', '$rate_per_day')";

  if (mysqli_query($conn, $insert_sql)) {
    echo "<script>
                    alert('Position added successfully.');
                    window.location.href = document.referrer;
                  </script>";
  } else {
    echo "Error: " . $insert_sql . "<br>" . mysqli_error($conn);
  }
} else {
  echo "<script>
            alert('Invalid request.');
            window.history.back();
          </script>";
}
