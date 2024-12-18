<?php

// Define table and primary key
$table = 'cash_advance';
$primaryKey = 'cash_advance_id';
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
    'db' => 'requested_amount',
    'dt' => 1,
    'field' => 'requested_amount',
    'formatter' => function ($lab1, $row) {
      return $row['requested_amount'];
    }
  ),

  array(
    'db' => 'request_date',
    'dt' => 2,
    'field' => 'request_date',
    'formatter' => function ($lab4, $row) {
      // Get the request_date from the row
      $datetime = $row['request_date'];

      // Format it as Month day, Year (e.g., November 28, 2024)
      $formatted_date = date("F j, Y", strtotime($datetime));

      // Return the formatted date
      return $formatted_date;
    }
  ),


  array(
    'db' => 'months',
    'dt' => 3,
    'field' => 'months',
    'formatter' => function ($lab4, $row) {
      return $row['months'];
    }
  ),

  array(
    'db' => 'monthly_payment',
    'dt' => 4,
    'field' => 'monthly_payment',
    'formatter' => function ($lab4, $row) {
      return $row['monthly_payment'];
    }
  ),


  array(
    'db' => 'remaining_balance',
    'dt' => 5,
    'field' => 'remaining_balance',
    'formatter' => function ($lab4, $row) {
      return intval($row['remaining_balance']);
    }
  ),

  array(
    'db' => 'status',
    'dt' => 6,
    'field' => 'status',
    'formatter' => function ($lab4, $row) {
      $status = $row['status'];

      // Define styles for different statuses
      $style = '';
      if ($status === 'Pending') {
        $style = 'background-color: lightyellow; border-radius: 5px; padding: 5px;';
      } elseif ($status === 'Approved') {
        $style = 'background-color: lightgreen; border-radius: 5px; padding: 5px;';
      } elseif ($status === 'Partially Paid') {
        $style = 'background-color: lightgreen; border-radius: 5px; padding: 5px;';
      } elseif ($status === 'Paid') {
        $style = 'background-color: lightgreen; border-radius: 5px; padding: 5px;';
      } elseif ($status === 'Cancelled') {
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
