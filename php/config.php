<?php
$servername = "localhost";
$username = "root"; // or your database username
$password = ""; // or your database password
$dbname = "idcard";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
