<?php
include "../../../db.php";
session_start();

if(!isset($_SESSION['username'])){
    header("Location: ../../../login/index.php");
    exit();
}

function logActivity($conn, $user_id, $action, $details = null){
    $stmt = $conn->prepare("INSERT INTO activity_logs (user_id, action, details) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $action, $details);
    $stmt->execute();
}

$user_id = $_SESSION['user_id'] ?? null;

if(isset($_POST['add_room'])){
    $name = $_POST['room_name'];
    $lat  = $_POST['latitude'];
    $lng  = $_POST['longitude'];

    $stmt = $conn->prepare("INSERT INTO rooms (room_name, latitude, longitude) VALUES (?,?,?)");
    $stmt->bind_param("sdd", $name, $lat, $lng);
    $stmt->execute();
    $room_id = $stmt->insert_id;

    $stmt2 = $conn->prepare("INSERT INTO room_sensors (room_id, sensor_status) VALUES (?, 'ON')");
    $stmt2->bind_param("i", $room_id);
    $stmt2->execute();

    if($user_id) logActivity($conn, $user_id, "Added Room", "Room: $name");
    header("Location: index.php?success=1");
    exit();
}

if(isset($_GET['toggle'])){
    $id = $_GET['toggle'];
    $conn->query("UPDATE room_sensors SET sensor_status = IF(sensor_status='ON','OFF','ON') WHERE room_id = $id");
    $res = $conn->query("SELECT sensor_status FROM room_sensors WHERE room_id = $id");
    $row = $res->fetch_assoc();
    $status = $row['sensor_status'];
    if($user_id) logActivity($conn, $user_id, "Toggle Sensor", "Room ID: $id → $status");
    header("Location: index.php");
    exit();
}

$result = $conn->query("SELECT rooms.*, room_sensors.sensor_status
    FROM rooms LEFT JOIN room_sensors ON rooms.id = room_sensors.room_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>IoT Room Monitor | Manage Rooms</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg shadow-sm">
<div class="container">
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
    <span class="navbar-toggler-icon"></span>
</button>
<a class="navbar-brand" href="../index.php">IoT Room Monitor</a>
<div class="collapse navbar-collapse" id="navMenu">
<ul class="navbar-nav ms-auto">
<li class="nav-item"><a class="nav-link" href="../index.php">Dashboard</a></li>
<li class="nav-item"><a class="nav-link" href="../logs_page/index.php">Logs</a></li>
<li class="nav-item"><a class="nav-link active" href="#">Manage Rooms</a></li>
<li class="nav-item"><a class="nav-link" href="../activity_logs/index.php">Activity logs</a></li>
<li class="nav-item"><a class="nav-link" href="../../../login/logout.php">Logout</a></li>
</ul>
</div>
</div>
</nav>

<div class="page-wrapper">
<h1 class="page-title">Manage Rooms</h1>

<div class="row g-4">
<div class="col-lg-4">
<div class="panel">
<div class="panel-header"><h5>Add New Room</h5></div>
<div class="panel-body">
<?php if(isset($_GET['success'])): ?>
<div class="alert-success-custom">Room added successfully.</div>
<?php endif; ?>
<form method="POST">
<div class="mb-3">
    <label class="form-label">Room Name</label>
    <input type="text" name="room_name" class="form-control" placeholder="e.g. Lab 1" required>
</div>
<div class="row g-2 mb-3">
<div class="col-6">
    <label class="form-label">Latitude</label>
    <input type="text" name="latitude" id="latitude" class="form-control" placeholder="Click map" required>
</div>
<div class="col-6">
    <label class="form-label">Longitude</label>
    <input type="text" name="longitude" id="longitude" class="form-control" placeholder="Click map" required>
</div>
</div>
<button class="btn-add" name="add_room">Add Room</button>
</form>
</div>
</div>
</div>

<div class="col-lg-8">
<div class="panel">
<div class="panel-header"><h5>Room Sensor Control</h5></div>
<div class="table-responsive">
<table class="table">
<thead>
<tr>
    <th>Room</th><th>Sensor Status</th><th style="text-align:right">Control</th>
</tr>
</thead>
<tbody>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
<td class="room-name">📍 <?php echo htmlspecialchars($row['room_name']); ?></td>
<td><span class="<?php echo $row['sensor_status']=='ON' ? 'badge-on' : 'badge-off'; ?>"><?php echo $row['sensor_status']; ?></span></td>
<td style="text-align:right"><a href="?toggle=<?php echo $row['id']; ?>" class="btn-toggle">Toggle</a></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
</div>
</div>
</div>

<div class="row mt-4">
<div class="col-12">
<div class="panel">
<div class="panel-header"><h5>Room Location Map</h5></div>
<div class="panel-body">
<div id="map"></div>
<p class="map-hint">Click anywhere on the map to set room coordinates.</p>
</div>
</div>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
let map = L.map('map').setView([8.359634,124.869002],30);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
let marker;
map.on('click', function(e){
    document.getElementById("latitude").value = e.latlng.lat;
    document.getElementById("longitude").value = e.latlng.lng;
    if(marker){ marker.setLatLng(e.latlng); }
    else{ marker = L.marker(e.latlng).addTo(map); }
});
</script>
</body>
</html>