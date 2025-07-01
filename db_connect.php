<?php
$host = "sql3.freesqldatabase.com";
$user = "sql3787784";
$pass = "jzigiNuX44";
$dbname = "sql3787784";
$port = 3306;

$conn = new mysqli($host, $user, $pass, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
