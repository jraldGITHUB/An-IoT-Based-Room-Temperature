<?php
include "../db.php";

$username = $_POST['username'];
$password = md5($_POST['password']); // match login system
$room_ids = $_POST['room_ids']; // multiple rooms

// CHECK EXISTING USER
$check = $conn->prepare("SELECT id FROM users WHERE username=?");
$check->bind_param("s", $username);
$check->execute();
$result = $check->get_result();

if($result->num_rows > 0){
    header("Location: register.php?error=1");
    exit();
}

// INSERT USER
$stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
$stmt->bind_param("ss", $username, $password);
$stmt->execute();

$user_id = $stmt->insert_id;

// INSERT USER ROOMS
$assign = $conn->prepare("INSERT INTO user_rooms (user_id, room_id) VALUES (?, ?)");

foreach($room_ids as $room_id){
    $assign->bind_param("ii", $user_id, $room_id);
    $assign->execute();
}

header("Location: register.php?success=1");
?>