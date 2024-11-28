<?php

// Define table and primary key
$table = 'rate_position';
$primaryKey = 'rate_id';
// Define columns for DataTables
$columns = array(
  array(
    'db' => 'rate_id',
    'dt' => 0,
    'field' => 'rate_id',
    'formatter' => function ($lab1, $row) {
      return $row['rate_id'];
      // return '<a href="../admin_area/under_position.php?rate_id=' . $row['rate_id'] . '">' . $row['rate_id'] . ' </a>';
    }
  ),

  array(
    'db' => 'rate_position',
    'dt' => 1,
    'field' => 'rate_position',
    'formatter' => function ($lab1, $row) {
      return $row['rate_position'];
    }
  ),


  array(
    'db' => 'rate_id',
    'dt' => 2,
    'field' => 'rate_id',
    'formatter' => function ($lab4, $row) {

      return '
            <div class="dropdown">
                <button class="btn btn-info" type="button" id="dropdownMenuButton' . $row['rate_id'] . '" data-bs-toggle="dropdown" aria-expanded="false" data-bs-toggle="tooltip">
                    &#x22EE;
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $row['rate_id'] . '">
                    <li>
                        <a class="dropdown-item fetchDataCashAdvance" href="../admin_area/under_position.php?rate_id=' . $row['rate_id'] . '">View Positions</a>
                    </li>
                    <li>
                        <a class="dropdown-item fetchDataCashAdvanceDecline" href="#"" >Cancel</a>
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
require('../datatables/ssp.class_with_where.php');


// Build the where condition
$where = "rate_id";

// Fetch and encode ONLY WHERE
echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $where));

// $joinQuery = "FROM $table LEFT JOIN users ON $table.user_id = users.user_id";

// Fetch and encode JOIN AND WHERE
// echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where));
