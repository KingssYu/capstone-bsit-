<?php
include '../connection/connections.php';

// Get today's date
$current_date = date('Y-m-d');

// Initialize an array to hold all notifications
$notifications = [];

// Fetch unread notifications from the `cash_advance` table
$query_cash_advance = "SELECT cash_advance_id AS id, 
                              CONCAT('Cash advance request from: ', first_name, ' ', last_name) AS message 
                       FROM cash_advance
                       LEFT JOIN adding_employee ON cash_advance.employee_no = adding_employee.employee_no 
                       WHERE notification_status = 'Unread'";

$result_cash_advance = mysqli_query($conn, $query_cash_advance);

if ($result_cash_advance && mysqli_num_rows($result_cash_advance) > 0) {
  while ($row = mysqli_fetch_assoc($result_cash_advance)) {
    $notifications[] = $row;
  }
}

// Fetch unread notifications from the `payroll` table
$query_payroll = "SELECT payroll.id AS id, 
                          CONCAT('Payment notification for: ', first_name, ' ', last_name, 
                                 ' - Paid: ₱', FORMAT(cash_advance_pay, 2)) AS message 
                  FROM payroll
                  LEFT JOIN adding_employee ON payroll.employee_no = adding_employee.employee_no 
                  WHERE payment_notification = 'Unread' AND cash_advance_pay != 0";


$result_payroll = mysqli_query($conn, $query_payroll);

if ($result_payroll && mysqli_num_rows($result_payroll) > 0) {
  while ($row = mysqli_fetch_assoc($result_payroll)) {
    $notifications[] = $row;
  }
}

// Return the notifications as JSON
echo json_encode($notifications);
?>