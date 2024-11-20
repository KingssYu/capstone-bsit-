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
    'db' => 'date',
    'dt' => 1,
    'field' => 'date',
    'formatter' => function ($lab1, $row) {
      return $row['date'];
    }
  ),

  array(
    'db' => 'time_in',
    'dt' => 2,
    'field' => 'time_in',
    'formatter' => function ($lab1, $row) {
      return $row['time_in'];
    }
  ),

  array(
    'db' => 'time_out',
    'dt' => 3,
    'field' => 'time_out',
    'formatter' => function ($lab4, $row) {
      return $row['time_out'];
    }
  ),

  array(
    'db' => 'id',
    'dt' => 4,
    'field' => 'id',
    'formatter' => function ($id, $row) {

      $break_duration = 3600;

      return gmdate("H:i", $break_duration);
    }
  ),


  array(
    'db' => 'status',
    'dt' => 5,
    'field' => 'status',
    'formatter' => function ($lab4, $row) {
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
);

// Database connection details
include '../connection/ssp_connection.php';

// Include the SSP class
require('../datatables/ssp.class_with_where.php');
session_start();
$employee_no = $_SESSION['employee']['employee_no'];

// Build the where condition
$where = "employee_no = '$employee_no'";


// Fetch and encode ONLY WHERE
echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $where));

// $joinQuery = "FROM $table LEFT JOIN users ON $table.user_cash_advance_id = users.user_cash_advance_id";

// Fetch and encode JOIN AND WHERE
// echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where));
