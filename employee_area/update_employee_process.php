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

  $sql = "SELECT profile_picture FROM adding_employee WHERE employee_no='$employee_no'";
  $result = mysqli_query($conn, $sql);
  if ($result) {
    $row = mysqli_fetch_assoc($result);
    $old_file = $row['profile_picture'];
  } else {
    $response['message'] = "Error retrieving old file information.";
    exit();
  }

  $target_dir = "../uploads/";
  $uploadOk = 1;

  // Handle file upload
  if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] != UPLOAD_ERR_NO_FILE) {
    $target_filename = basename($_FILES["fileToUpload"]["name"]);
    $target_file = $target_dir . $target_filename;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    var_dump($_FILES["fileToUpload"]["name"]);

    // Check for upload errors
    if ($_FILES["fileToUpload"]["error"] !== UPLOAD_ERR_OK) {
      $response['message'] = "File upload error: " . $_FILES["fileToUpload"]["error"];
      exit();
    }

    // Check if file already exists
    if (file_exists($target_file)) {
      $response['message'] = "Sorry, file already exists.";
      $uploadOk = 0;
    }

    // Allow certain file formats
    if (
      $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
      && $imageFileType != "gif" && $imageFileType != "pdf"
    ) {
      $response['message'] = "Sorry, only JPG, JPEG, PNG, GIF, and PDF files are allowed.";
      $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
      $response['message'] = "Sorry, your file was not uploaded.";
      exit();
    } else {
      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $response['message'] = "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";

        // Delete the old file if it exists and is different from the new file
        if (!empty($old_file) && $old_file !== $target_file) {
          if (file_exists($old_file)) {
            unlink($old_file);
          }
        }
      } else {
        $response['message'] = "Sorry, there was an error uploading your file.";
        exit();
      }
    }

    // Set new filename if a new file is uploaded
    $new_filename = $target_file;
  } else {
    // No file uploaded, retain old filename
    $new_filename = $old_file;
  }

  // Check if all required fields are provided
  if ($employee_no && $first_name && $last_name && $contact && $address && $email) {
    // Update query
    $sql = "UPDATE adding_employee 
                SET first_name = '$first_name', 
                    last_name = '$last_name',
                    contact = '$contact',
                    `address` = '$address',
                    email = '$email',
                    profile_picture = '$new_filename'
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
