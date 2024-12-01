<?php
// Load the database configuration file 
include '../connection/connections.php';


// Include XLSX generator library 
include '../PHPExcel/PHPExcel.php';

// if (session_status() == PHP_SESSION_NONE) {
//   session_start();
// }

// $employee = $_SESSION['employee'];
// $employee_no = $_SESSION['employee']['employee_no'];

// // Build the where condition
// $where = "employee_no = '$employee_no'";
// Excel file name for download 
$fileName = "All Employee Records as of " . date('F d, Y') . ".xlsx";

// Define column names 
$excelData[] = array('Employee #', 'Full Name', 'Department', 'Position', 'Employee Stats', 'Salary Per Day');

// Fetch records from database and store in an array 
$query = $conn->query("SELECT * FROM adding_employee 
                       LEFT JOIN department ON adding_employee.department_id = department.department_id
                       LEFT JOIN under_position ON adding_employee.rate_id = under_position.rate_id");

if ($query->num_rows > 0) {
  while ($row = $query->fetch_assoc()) {
    $employee_fullname = $row['first_name'] . ' ' . $row['last_name'];
    $lineData = array($row['employee_no'], $employee_fullname, $row['department_name'], $row['rate_position'], $row['employee_stats'], $row['rate_per_day']);
    $excelData[] = $lineData;
  }
}

// Export data to excel and download as xlsx file 
$xlsx = CodexWorld\PhpXlsxGenerator::fromArray($excelData);
$xlsx->downloadAs($fileName);

exit;
