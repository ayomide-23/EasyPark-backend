<?php
// $host = "localhost";  
// $user = "root";            
// $pass = "";  
// $dbname = "pms"; 

$host = "fdb1033.awardspace.net";
$user = "4653705_easypark";
$pass = "aappeter1";
$dbname = "4653705_easypark";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
