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
$fileName = "Payroll " . $employee['last_name'] . " " . date('F d, Y') . ".xlsx";

// Define column names 
$excelData[] = array('Employee #', 'Payment Date', 'Payroll', 'Status');

// Fetch records from database and store in an array 
$query = $conn->query("SELECT *
FROM payroll WHERE employee_no = '$employee_no'");

if ($query->num_rows > 0) {
  while ($row = $query->fetch_assoc()) {
    $lineData = array($row['employee_no'], $row['payment_date'], $row['net_pay'], 'Received');
    $excelData[] = $lineData;
  }
}

// Export data to excel and download as xlsx file 
$xlsx = CodexWorld\PhpXlsxGenerator::fromArray($excelData);
$xlsx->downloadAs($fileName);

exit;
