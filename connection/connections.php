<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bsu_payroll";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
} else {
  // echo "SUCCESS";
}

// This is live connection

// $servername = "localhost";
// $username = "vssphcom_vetsolK";
// $password = "GreatCoal081677!!";
// $dbname = "vssphcom_vetsol_kal";

// // Create connection
// $conn = new mysqli($servername, $username, $password, $dbname);

// // Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// } else {
//     // echo "SUCCESS";
// }

?>