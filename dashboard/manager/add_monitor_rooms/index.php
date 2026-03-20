<?php
include "../../../db.php";
session_start();

// PROTECT PAGE
if(!isset($_SESSION['username'])){
    header("Location: ../../../login/index.php");
    exit();
}

// ADD ROOM
if(isset($_POST['add_room'])){

    $name = $_POST['room_name'];
    $lat  = $_POST['latitude'];
    $lng  = $_POST['longitude'];

    $stmt = $conn->prepare("INSERT INTO rooms (room_name, latitude, longitude) VALUES (?,?,?)");
    $stmt->bind_param("sdd", $name, $lat, $lng);
    $stmt->execute();

    header("Location: index.php?success=1");
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Add Room</title>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>

<link rel="stylesheet" href="style.css">



</head>
<style>

.custom-navbar .navbar-brand{
    color:white !important;
}

#map{
    height:400px;
    border-radius:10px;
  
}
</style>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg custom-navbar shadow-sm py-3">
<div class="container">

<a class="navbar-brand fw-bold fs-4" href="../index.php">
IoT Room Monitor
</a>

<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
<span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="navbarNav">

<ul class="navbar-nav ms-auto align-items-lg-center">

<li class="nav-item">
<a class="nav-link" href="../index.php">Dashboard</a>
</li>

<li class="nav-item">
<a class="nav-link" href="../logs_page/index.html">Logs</a>
</li>   

<li class="nav-item">
<a class="nav-link active" href="#">Add Rooms</a>
</li>

<li class="nav-item">
<a class="btn btn-danger ms-lg-3 mt-2 mt-lg-0" href="../../../login/logout.php">Logout</a>
</li>

</ul>

</div>
</div>
</nav>

<!-- CONTENT -->
<div class="container mt-5" style="max-width:500px;">

<h3 class="mb-4">Add Room</h3>

<?php if(isset($_GET['success'])){ ?>
<div class="alert alert-success">
Room added successfully
</div>
<?php } ?>

<div class="form-card">

<form method="POST">

<div class="mb-3">
<label>Room Name</label>
<input type="text" name="room_name" class="form-control" required>
</div>

<div class="mb-3">
<label>Latitude</label>
<input type="text" name="latitude" id="latitude" class="form-control" required>
</div>

<div class="mb-3">
<label>Longitude</label>
<input type="text" name="longitude" id="longitude" class="form-control" required>
</div>

<div class="mb-3">
<label>Select Location on Map</label>
<div id="map"></div>
</div>

<button class="btn btn-success w-100" name="add_room">
Add Room
</button>

</form>

</div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>

// INIT MAP (Default: Bukidnon area)
let map = L.map('map').setView([8.359634, 124.869002], 15);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
}).addTo(map);

let marker;

// CLICK MAP TO GET COORDINATES
map.on('click', function(e){

    let lat = e.latlng.lat;
    let lng = e.latlng.lng;

    // AUTO FILL INPUTS
    document.getElementById("latitude").value = lat;
    document.getElementById("longitude").value = lng;

    // ADD / MOVE MARKER
    if(marker){
        marker.setLatLng(e.latlng);
    } else {
        marker = L.marker(e.latlng).addTo(map);
    }

});




</script>

</body>
</html>