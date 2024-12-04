<?php

// Define table and primary key
$table = 'attendance';
$primaryKey = 'id';
// Define columns for DataTables
$columns = array(
  array(
    'db' => 'attendance.employee_no',
    'dt' => 0,
    'field' => 'employee_no',
    'formatter' => function ($lab1, $row) {
      return $row['employee_no'];
    }
  ),

  array(
    'db' => 'adding_employee.first_name',
    'dt' => 1,
    'field' => 'first_name',
    'formatter' => function ($lab1, $row) {
      return $row['first_name'] . ' ' . $row['last_name'];
    }
  ),

  array(
    'db' => 'status',
    'dt' => 2,
    'field' => 'status',
    'formatter' => function ($lab1, $row) {
      $status = $row['status'];

      // Define styles for different statuses
      $style = '';
      if ($status === 'Late') {
        $style = 'background-color: lightyellow; border-radius: 5px; padding: 5px;';
      } elseif ($status === 'Present') {
        $style = 'background-color: lightgreen; border-radius: 5px; padding: 5px;';
      } elseif ($status === 'Partially Paid') {
        $style = 'background-color: lightgreen; border-radius: 5px; padding: 5px;';
      } elseif ($status === 'Absent') {
        $style = 'background-color: #FF474C; border-radius: 5px; padding: 5px;';
      }

      return "<span style=\"$style\">{$status}</span>";
    }
  ),

  array(
    'db' => 'clock_in',
    'dt' => 3,
    'field' => 'clock_in',
    'formatter' => function ($lab1, $row) {
      return $row['clock_in'];
    }
  ),


  array(
    'db' => 'clock_out',
    'dt' => 4,
    'field' => 'clock_out',
    'formatter' => function ($lab4, $row) {
      // // Set timezone to Manila, Philippines
      // date_default_timezone_set('Asia/Manila');

      // $clockOutTime = DateTime::createFromFormat('H:i:s', $row['clock_out']); // Expecting full time format with seconds.
      // $thresholdTime = DateTime::createFromFormat('H:i', '16:59'); // Threshold for 4:59 PM.

      // // Check if time is valid (for potential null or incorrect data)
      // if ($clockOutTime && $clockOutTime >= $thresholdTime) {
      //   return $row['clock_out'];
      // } else {
      //   return '-';
      // }

      return $row['clock_out'];
    }
  ),

  array(
    'db' => 'adding_employee.last_name',
    'dt' => 5,
    'field' => 'last_name',
    'formatter' => function ($lab1, $row) {
      return $row['last_name'];
    }
  ),


);



// Database connection details
include '../connection/ssp_connection.php';

// Include the SSP class
require('../datatables/ssp.class.php');

// Set the timezone to Manila
date_default_timezone_set('Asia/Manila');

// Get today's date
$today = date('Y-m-d');

// Build the WHERE condition
$where = "`date` = '$today'"; // Replace 'your_date_column' with your actual date column name

// Fetch and encode data
// echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $where));


$joinQuery = "FROM $table LEFT JOIN adding_employee ON $table.employee_no = adding_employee.employee_no";

// Fetch and encode JOIN AND WHERE
echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where));
