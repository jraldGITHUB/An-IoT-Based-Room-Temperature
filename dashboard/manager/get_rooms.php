<?php

include "../../db.php";

$sql = "SELECT * FROM rooms";
$result = mysqli_query($conn,$sql);

$rooms = [];

while($row = mysqli_fetch_assoc($result)){
$rooms[] = $row;
}

echo json_encode($rooms);

?>