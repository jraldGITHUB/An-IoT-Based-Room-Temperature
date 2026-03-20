<?php
include "../../db.php";

header("Content-Type: application/json");

$result = $conn->query("
    SELECT rooms.id, rooms.room_name, rooms.latitude, rooms.longitude,
           room_sensors.sensor_status
    FROM rooms
    LEFT JOIN room_sensors 
    ON rooms.id = room_sensors.room_id
");

$rooms = [];

while($row = $result->fetch_assoc()){
    $rooms[] = $row;
}

echo json_encode($rooms);
?>