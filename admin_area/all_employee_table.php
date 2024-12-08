<?php

// Define table and primary key
$table = 'adding_employee';
$primaryKey = 'id';
// Define columns for DataTables
$columns = array(
  array(
    'db' => 'employee_no',
    'dt' => 0,
    'field' => 'employee_no',
    'formatter' => function ($lab1, $row) {
      return $row['employee_no'];
      // return '<a href="../admin_area/under_position.php?id=' . $row['id'] . '">' . $row['id'] . ' </a>';
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
    'db' => 'department_name',
    'dt' => 3,
    'field' => 'department_name',
    'formatter' => function ($lab1, $row) {
      return $row['department_name'];
    }
  ),

  array(
    'db' => 'under_position.rate_position',
    'dt' => 4,
    'field' => 'rate_position',
    'formatter' => function ($lab1, $row) {
      return $row['rate_position'];
    }
  ),

  array(
    'db' => 'date_hired',
    'dt' => 5,
    'field' => 'date_hired',
    'formatter' => function ($lab1, $row) {
      return $row['date_hired'];
    }
  ),

  array(
    'db' => 'employee_stats',
    'dt' => 6,
    'field' => 'employee_stats',
    'formatter' => function ($lab1, $row) {
      return $row['employee_stats'];
    }
  ),

  array(
    'db' => 'under_position.rate_per_day',
    'dt' => 7,
    'field' => 'rate_per_day',
    'formatter' => function ($lab1, $row) {
      return $row['rate_per_day'];
    }
  ),

  array(
    'db' => 'id',
    'dt' => 8,
    'field' => 'id',
    'formatter' => function ($lab4, $row) {
      $cashAdvanceLink = '';
      // Check if employee_stats is 'Regular'
      if ($row['employee_stats'] === 'Regular') {
        $cashAdvanceLink = '<a class="dropdown-item" href="cash_advance_configuration.php?employee_no=' . $row['employee_no'] . '">Cash Advance Configuration</a>';
      }
      return '
            <div class="dropdown">
                <button class="btn btn-info" type="button" id="dropdownMenuButton' . $row['id'] . '" data-bs-toggle="dropdown" aria-expanded="false">
                    &#x22EE;
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $row['id'] . '">
                    <li>
                        <a class="dropdown-item" href="employee_all_details.php?employee_no=' . $row['employee_no'] . '">View Employee</a>
                        ' . $cashAdvanceLink . '
                    </li>
                </ul>
            </div>
        ';
    }
  ),


  array(
    'db' => 'id',
    'dt' => 9,
    'field' => 'id',
    'formatter' => function ($lab1, $row) {
      return $row['id'];
    }
  ),




);

// Database connection details
include '../connection/ssp_connection.php';

// Include the SSP class
require('../datatables/ssp.class.php');


// Build the where condition
$where = "id";

// Fetch and encode ONLY WHERE
// echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $where));

$joinQuery = "FROM $table LEFT JOIN department ON $table.department_id = department.department_id
              LEFT JOIN under_position ON $table.rate_id = under_position.rate_id
              ";

// Fetch and encode JOIN AND WHERE
echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where));
