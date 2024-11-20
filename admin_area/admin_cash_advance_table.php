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
    'db' => 'months',
    'dt' => 2,
    'field' => 'months',
    'formatter' => function ($lab4, $row) {
      return $row['months'];

    }
  ),


  array(
    'db' => 'remaining_balance',
    'dt' => 3,
    'field' => 'remaining_balance',
    'formatter' => function ($lab4, $row) {
      return $row['remaining_balance'];
    }
  ),

  array(
    'db' => 'status',
    'dt' => 4,
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
    'dt' => 5,
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





  array(
    'db' => 'cash_advance_id',
    'dt' => 6,
    'field' => 'cash_advance_id',
    'formatter' => function ($lab4, $row) {
      return $row['cash_advance_id'];
    }
  ),

);



// Database connection details
include '../connection/ssp_connection.php';

// Include the SSP class
require('../datatables/ssp.class_with_where.php');


// Build the where condition
$where = "cash_advance_id";


// Fetch and encode ONLY WHERE
echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $where));

// $joinQuery = "FROM $table LEFT JOIN users ON $table.user_cash_advance_id = users.user_cash_advance_id";

// Fetch and encode JOIN AND WHERE
// echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where));
