<?php
// $host = "localhost";  
// $user = "root";            
// $pass = "";  
// $dbname = "pms"; 

$host = "sql207.infinityfree.com";
$user = "if0_39353212";
$pass = "LkggfblkqeGqN";
$dbname = "if0_39353212_easypark";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
