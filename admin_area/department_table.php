<?php

// Define table and primary key
$table = 'department';
$primaryKey = 'department_id';
// Define columns for DataTables
$columns = array(
  array(
    'db' => 'department_id',
    'dt' => 0,
    'field' => 'department_id',
    'formatter' => function ($lab1, $row) {
      return $row['department_id'];
      // return '<a href="../admin_area/under_position.php?department_id=' . $row['department_id'] . '">' . $row['department_id'] . ' </a>';
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
    'db' => 'department_id',
    'dt' => 2,
    'field' => 'department_id',
    'formatter' => function ($lab4, $row) {
      return '
            <div class="dropdown">
                <button class="btn btn-info" type="button" id="dropdownMenuButton' . $row['department_id'] . '" data-bs-toggle="dropdown" aria-expanded="false">
                    &#x22EE;
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $row['department_id'] . '">
                    <li>
                        <a class="dropdown-item" href="#" onclick="if(confirm(\'Are you sure you want to delete this department?\')) { window.location.href=\'delete_department_process.php?department_id=' . $row['department_id'] . '\'; }">Delete</a>
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
$where = "department_id";

// Fetch and encode ONLY WHERE
echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $where));

// $joinQuery = "FROM $table LEFT JOIN users ON $table.user_id = users.user_id";

// Fetch and encode JOIN AND WHERE
// echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where));
