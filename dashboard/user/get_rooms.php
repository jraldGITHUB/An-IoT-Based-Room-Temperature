<?php
session_start();
include "../../db.php";

if(!isset($_SESSION['user_id'])){
    echo json_encode([]);
    exit();
}

$user_id = $_SESSION['user_id'];

// GET ONLY USER ASSIGNED ROOMS
$stmt = $conn->prepare("
    SELECT r.id, r.room_name, r.latitude, r.longitude, rs.sensor_status
    FROM rooms r
    JOIN user_rooms ur ON r.id = ur.room_id
    LEFT JOIN room_sensors rs ON r.id = rs.room_id
    WHERE ur.user_id = ?
");

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$rooms = [];

while($row = $result->fetch_assoc()){
    $rooms[] = $row;
}

echo json_encode($rooms);