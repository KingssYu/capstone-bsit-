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
    'db' => 'rate_position',
    'dt' => 2,
    'field' => 'rate_position',
    'formatter' => function ($lab4, $row) {
      return $row['rate_position'];
    }
  ),

  array(
    'db' => 'rate_position',
    'dt' => 3,
    'field' => 'rate_position',
    'formatter' => function ($lab4, $row) {
      return $row['rate_position'];
    }
  ),
);

// Database connection details
$sql_details = array(
  'user' => 'root',
  'pass' => '',
  'db' => 'admin_login',
  'host' => 'localhost',
);

// Include the SSP class
require('../datatables/ssp.class_with_where.php');


// Build the where condition
$where = "rate_id";

// Fetch and encode ONLY WHERE
echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $where));

// $joinQuery = "FROM $table LEFT JOIN users ON $table.user_id = users.user_id";

// Fetch and encode JOIN AND WHERE
// echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where));
