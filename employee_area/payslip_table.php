<?php

// Define table and primary key
$table = 'payroll';
$primaryKey = 'id';
// Define columns for DataTables
$columns = array(

  array(
    'db' => 'employee_name',
    'dt' => 0,
    'field' => 'employee_name',
    'formatter' => function ($lab1, $row) {
      return $row['employee_name'];
    }
  ),

  array(
    'db' => 'payment_date',
    'dt' => 1,
    'field' => 'payment_date',
    'formatter' => function ($lab1, $row) {
      return $row['payment_date'];
    }
  ),

  array(
    'db' => 'net_pay',
    'dt' => 2,
    'field' => 'net_pay',
    'formatter' => function ($lab1, $row) {
      return $row['net_pay'];
    }
  ),

  array(
    'db' => 'attendance_report.id',
    'dt' => 3,
    'field' => 'id',
    'formatter' => function ($lab4, $row) {

      $status = 'Received';

      // Define styles for different statuses
      $style = '';
      if ($status === 'Received') {
        $style = 'background-color: lightgreen; border-radius: 5px; padding: 5px;';
      }

      return "<span style=\"$style\">{$status}</span>";
    }
  ),

  array(
    'db' => 'attendance_report.id',
    'dt' => 4,
    'field' => 'id',
    'formatter' => function ($lab5, $row) {

      return $row['id'];
    }
  ),

);

// Database connection details
include '../connection/ssp_connection.php';

// Include the SSP class
require('../datatables/ssp.class.php');
session_start();
$employee_no = $_SESSION['employee']['employee_no'];

// Build the where condition
$where = "payroll.employee_no = '$employee_no'";


// Fetch and encode ONLY WHERE
// echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $where));

$joinQuery = "FROM $table LEFT JOIN attendance_report ON $table.employee_no = attendance_report.employee_no";

// Fetch and encode JOIN AND WHERE
echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where));
