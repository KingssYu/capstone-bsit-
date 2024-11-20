<?php

// Define table and primary key
$table = 'payroll';
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
    'db' => 'number_of_days',
    'dt' => 1,
    'field' => 'number_of_days',
    'formatter' => function ($lab1, $row) {
      return $row['number_of_days'];
    }
  ),


  array(
    'db' => 'total_hours',
    'dt' => 2,
    'field' => 'total_hours',
    'formatter' => function ($lab4, $row) {
      // Check if total_hours is an integer
      $total_hours = $row['total_hours'];

      // If it's a float but ends with .00, remove the decimal part
      return (intval($total_hours) == $total_hours) ? (int) $total_hours : $total_hours;
    }
  ),


  array(
    'db' => 'total_deductions',
    'dt' => 3,
    'field' => 'total_deductions',
    'formatter' => function ($lab4, $row) {
      return $row['total_deductions'];
    }
  ),

  array(
    'db' => 'net_pay',
    'dt' => 4,
    'field' => 'net_pay',
    'formatter' => function ($lab4, $row) {
      return $row['net_pay'];
    }
  ),
);

// Database connection details
include '../connection/ssp_connection.php';

// Include the SSP class
require('../datatables/ssp.class_with_where.php');


// Build the where condition
$where = "id";

// Fetch and encode ONLY WHERE
echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $where));

// $joinQuery = "FROM $table LEFT JOIN users ON $table.user_id = users.user_id";

// Fetch and encode JOIN AND WHERE
// echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where));
