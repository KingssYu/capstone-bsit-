<?php
include '../connection/connections.php';

if (isset($_POST['id'])) {
  $notification_id = mysqli_real_escape_string($conn, $_POST['id']); // Sanitize the input

  $query_cash_advance = "UPDATE cash_advance SET notification_status = 'Read' WHERE cash_advance_id = '$notification_id'";

  $result = mysqli_query($conn, $query_cash_advance);

  if (mysqli_affected_rows($conn) > 0) {
    echo "Notification dismissed successfully from cash_advance.";
  } else {
    $query_payroll = "UPDATE payroll SET payment_notification = 'Read' WHERE id = '$notification_id'";

    $result_payroll = mysqli_query($conn, $query_payroll);

    if (mysqli_affected_rows($conn) > 0) {
      echo "Notification dismissed successfully from payroll.";
    } else {
      echo "No matching notification found to update.";
    }
  }

} else {
  echo "Error: Notification ID not provided.";
}
?>