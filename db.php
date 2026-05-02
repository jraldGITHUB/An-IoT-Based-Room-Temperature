<?php

$host = "localhost";
$user = "";
$pass = "";
$db   = "iot_room_monitor";

$conn = mysqli_connect($host, $user, $pass, $db);

if(!$conn){
    die("Database connection failed: " . mysqli_connect_error());
}

?>