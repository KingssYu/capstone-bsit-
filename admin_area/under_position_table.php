<?php

// Define table and primary key
$table = 'under_position';
$primaryKey = 'rate_id';
// Define columns for DataTables
$columns = array(
  array(
    'db' => 'rate_id',
    'dt' => 0,
    'field' => 'rate_id',
    'formatter' => function ($lab1, $row) {
      return $row['rate_id'];
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
    'db' => 'rate_per_hour',
    'dt' => 2,
    'field' => 'rate_per_hour',
    'formatter' => function ($lab4, $row) {
      return $row['rate_per_hour'];
    }
  ),


  array(
    'db' => 'rate_per_day',
    'dt' => 3,
    'field' => 'rate_per_day',
    'formatter' => function ($lab4, $row) {
      return $row['rate_per_day'];
    }
  ),

  array(
    'db' => 'rate_id',
    'dt' => 4,
    'field' => 'rate_id',
    'formatter' => function ($lab4, $row) {
      return '
            <div class="dropdown">
                <button class="btn btn-info" type="button" id="dropdownMenuButton' . $row['rate_id'] . '" data-bs-toggle="dropdown" aria-expanded="false">
                    &#x22EE;
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $row['rate_id'] . '">
                    <li>
                        <a class="dropdown-item fetchData" href="#">Edit</a>
                        <a class="dropdown-item" href="#" onclick="if(confirm(\'Are you sure you want to delete this Rank?\')) { window.location.href=\'delete_rank_process.php?rate_id=' . $row['rate_id'] . '\'; }">Delete</a>
                    </li>
                </ul>
            </div>

            
        ';
    }
  ),

  array(
    'db' => 'position_id',
    'dt' => 5,
    'field' => 'position_id',
    'formatter' => function ($lab4, $row) {
      return $row['position_id'];
    }
  ),



  array(
    'db' => 'ot_per_hour',
    'dt' => 6,
    'field' => 'ot_per_hour',
    'formatter' => function ($lab4, $row) {
      return $row['ot_per_hour'];
    }
  ),
);

// Database connection details
include '../connection/ssp_connection.php';

// Include the SSP class
require('../datatables/ssp.class_with_where.php');

$rate_id = isset($_GET['rate_id']) ? $_GET['rate_id'] : null;


// Build the where condition
$where = "position_id = $rate_id";

// Fetch and encode ONLY WHERE
echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $where));

// $joinQuery = "FROM $table LEFT JOIN users ON $table.user_id = users.user_id";

// Fetch and encode JOIN AND WHERE
// echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where));
