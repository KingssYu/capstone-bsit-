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
    'db' => 'department_name',
    'dt' => 1,
    'field' => 'department_name',
    'formatter' => function ($lab1, $row) {
      return $row['department_name'];
    }
  ),

  array(
    'db' => 'rate_position',
    'dt' => 2,
    'field' => 'rate_position',
    'formatter' => function ($lab1, $row) {
      return $row['rate_position'];
    }
  ),


  array(
    'db' => 'rate_id',
    'dt' => 3,
    'field' => 'rate_id',
    'formatter' => function ($lab4, $row) {
      return '
            <div class="dropdown">
                <button class="btn btn-info" type="button" id="dropdownMenuButton' . $row['rate_id'] . '" data-bs-toggle="dropdown" aria-expanded="false">
                    &#x22EE;
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $row['rate_id'] . '">
                    <li>
                        <a class="dropdown-item" href="../admin_area/under_position.php?rate_id=' . $row['rate_id'] . '">View Positions</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" onclick="if(confirm(\'Are you sure you want to delete this position?\')) { window.location.href=\'delete_position_process.php?rate_id=' . $row['rate_id'] . '\'; }">Delete</a>
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
$where = "rate_id";

// Fetch and encode ONLY WHERE
// echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $where));

$joinQuery = "FROM $table LEFT JOIN department ON $table.department_id = department.department_id";

// Fetch and encode JOIN AND WHERE
echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where));
