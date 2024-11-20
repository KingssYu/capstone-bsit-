<?php
// Load the database configuration file 
include '../connection/connections.php';


// Include XLSX generator library 
include '../PHPExcel/PHPExcel.php';

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

$employee = $_SESSION['employee'];
$employee_no = $_SESSION['employee']['employee_no'];

// Build the where condition
$where = "employee_no = '$employee_no'";
// Excel file name for download 
$fileName = "Timesheet " . $employee['last_name'] . " " . date('F d, Y') . ".xlsx";

// Define column names 
$excelData[] = array('Employee #', 'Date', 'Time In', 'Time Out', 'Break', 'Status');

// Fetch records from database and store in an array 
$query = $conn->query("SELECT *
FROM attendance_report WHERE employee_no = '$employee_no'");

if ($query->num_rows > 0) {
  while ($row = $query->fetch_assoc()) {
    $break_duration = '1 hour';
    $lineData = array($row['employee_no'], $row['date'], $row['time_in'], $row['time_out'], $break_duration, $row['status']);
    $excelData[] = $lineData;
  }
}

// Export data to excel and download as xlsx file 
$xlsx = CodexWorld\PhpXlsxGenerator::fromArray($excelData);
$xlsx->downloadAs($fileName);

exit;

?>