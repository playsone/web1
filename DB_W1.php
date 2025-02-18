<?php
$server = "localhost";
$username = "student";
$password = "abc123";
$db_name = "db_student";

$conn = new mysqli($server, $username, $password, $db_name);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    
    die("Connection  Failed :" .$conn->connect_error);
}
?>