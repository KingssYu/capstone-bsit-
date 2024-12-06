<?php

// Define table and primary key
$table = 'cash_advance';
$primaryKey = 'cash_advance_id';
// Define columns for DataTables
$columns = array(
  array(
    'db' => 'cash_advance_id',
    'dt' => 0,
    'field' => 'cash_advance_id',
    'formatter' => function ($lab1, $row) {
      return $row['cash_advance_id'];
    }
  ),

  array(
    'db' => 'first_name',
    'dt' => 1,
    'field' => 'first_name',
    'formatter' => function ($lab1, $row) {
      return $row['first_name'];
    }
  ),

  array(
    'db' => 'last_name',
    'dt' => 2,
    'field' => 'last_name',
    'formatter' => function ($lab1, $row) {
      return $row['last_name'];
    }
  ),

  array(
    'db' => 'requested_amount',
    'dt' => 3,
    'field' => 'requested_amount',
    'formatter' => function ($lab1, $row) {
      return $row['requested_amount'];
    }
  ),


  array(
    'db' => 'months',
    'dt' => 4,
    'field' => 'months',
    'formatter' => function ($lab4, $row) {
      return $row['months'];
    }
  ),


  array(
    'db' => 'request_date',
    'dt' => 5,
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
    'db' => 'remaining_balance',
    'dt' => 6,
    'field' => 'remaining_balance',
    'formatter' => function ($lab4, $row) {
      return intval($row['remaining_balance']);
    }
  ),

  array(
    'db' => 'monthly_payment',
    'dt' => 7,
    'field' => 'monthly_payment',
    'formatter' => function ($lab4, $row) {
      return $row['monthly_payment'];

    }
  ),

  array(
    'db' => 'status',
    'dt' => 8,
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
      } elseif ($status === 'Declined') {
        $style = 'background-color: #FF474C; border-radius: 5px; padding: 5px;';
      }

      return "<span style=\"$style\">{$status}</span>";
    }
  ),

  array(
    'db' => 'cash_advance_id',
    'dt' => 9,
    'field' => 'cash_advance_id',
    'formatter' => function ($lab4, $row) {
      // Check if status is 'Approved', 'Declined', 'Paid' or 'Partially Paid'
      $disabled = in_array($row['status'], ['Approved', 'Declined', 'Paid', 'Partially Paid']) ? 'disabled' : ''; // Disable options if status is 'Approved', 'Declined', 'Paid', or 'Partially Paid'
      $tooltip = in_array($row['status'], ['Approved', 'Declined', 'Paid', 'Partially Paid']) ? 'This request has already been processed' : 'More options';

      return '
            <div class="dropdown">
                <button class="btn btn-info" type="button" id="dropdownMenuButton' . $row['cash_advance_id'] . '" data-bs-toggle="dropdown" aria-expanded="false" data-bs-toggle="tooltip" title="' . $tooltip . '">
                    &#x22EE;
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $row['cash_advance_id'] . '">
                    <li>
                        <a class="dropdown-item fetchDataCashAdvance" href="#" data-bs-toggle="tooltip" title="Approve this request" ' . (in_array($row['status'], ['Approved', 'Declined', 'Paid', 'Partially Paid']) ? 'style="pointer-events: none; color: #6c757d;"' : '') . '>Approve</a>
                    </li>
                    <li>
                        <a class="dropdown-item fetchDataCashAdvanceDecline" href="#" data-user-id="' . $row['cash_advance_id'] . '" data-bs-toggle="tooltip" title="Decline this request" ' . (in_array($row['status'], ['Approved', 'Declined', 'Paid', 'Partially Paid']) ? 'style="pointer-events: none; color: #6c757d;"' : '') . '>Decline</a>
                    </li>
                </ul>
            </div>
        ';
    }
  ),

);



// Database connection details
include '../connection/ssp_connection.php';

// Include the SSP class
require('../datatables/ssp.class.php');


// Build the where condition
$where = "cash_advance_id";


// Fetch and encode ONLY WHERE
// echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $where));

$joinQuery = "FROM $table LEFT JOIN adding_employee ON $table.employee_no = adding_employee.employee_no";

// Fetch and encode JOIN AND WHERE
echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where));
