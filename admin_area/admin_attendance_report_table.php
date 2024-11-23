<?php

// Define table and primary key
$table = 'attendance_report';
$primaryKey = 'id';
// Define columns for DataTables
$columns = array(
  array(
    'db' => 'employee_no',
    'dt' => 0,
    'field' => 'employee_no',
    'formatter' => function ($lab1, $row) {
      return $row['employee_no'];
    }
  ),

  array(
    'db' => 'employee_name',
    'dt' => 1,
    'field' => 'employee_name',
    'formatter' => function ($lab1, $row) {
      return $row['employee_name'];
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
    'db' => 'time_in',
    'dt' => 3,
    'field' => 'time_in',
    'formatter' => function ($lab1, $row) {
      return $row['time_in'];
    }
  ),


  array(
    'db' => 'time_out',
    'dt' => 4,
    'field' => 'time_out',
    'formatter' => function ($lab4, $row) {
      return $row['time_out'];
    }
  ),


  array(
    'db' => 'actual_time',
    'dt' => 5,
    'field' => 'actual_time',
    'formatter' => function ($lab4, $row) {
      return intval($row['actual_time']);
    }
  ),


);



// Database connection details
include '../connection/ssp_connection.php';

// Include the SSP class
require('../datatables/ssp.class_with_where.php');

// Set the timezone to Manila
date_default_timezone_set('Asia/Manila');

// Get today's date
$today = date('Y-m-d');

// Build the WHERE condition
$where = "`date` = '$today'"; // Replace 'your_date_column' with your actual date column name

// Fetch and encode data
echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $where));


// $joinQuery = "FROM $table LEFT JOIN adding_employee ON $table.employee_no = adding_employee.employee_no";

// Fetch and encode JOIN AND WHERE
// echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where));
