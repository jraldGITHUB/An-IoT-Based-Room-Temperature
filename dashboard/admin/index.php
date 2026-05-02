<?php
session_start();
include "../../db.php";

if(!isset($_SESSION['username'])){
    header("Location: ../../login/index.php");
    exit();
}

if(isset($_POST['log_refresh'])){
    $user_id = $_SESSION['user_id'];
    $action = "Refresh";
    $details = "Refreshed sensor data on dashboard";
    $stmt = $conn->prepare("INSERT INTO activity_logs (user_id, action, details, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iss", $user_id, $action, $details);
    $stmt->execute();
    echo "success";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>IoT Room Monitor | Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg shadow-sm">
<div class="container">
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
    <span class="navbar-toggler-icon"></span>
</button>
<a class="navbar-brand" href="index.php">IoT Room Monitor</a>
<div class="collapse navbar-collapse" id="navMenu">
<ul class="navbar-nav ms-auto">
<li class="nav-item"><a class="nav-link active" href="../admin/index.php">Dashboard</a></li>
<li class="nav-item"><a class="nav-link" href="../admin/logs_page/index.php">Logs</a></li>
<li class="nav-item"><a class="nav-link" href="../admin/add_monitor_rooms/index.php">Manage Rooms</a></li>
<li class="nav-item"><a class="nav-link" href="../admin/activity_logs/index.php">Activity Logs</a></li>
<li class="nav-item"><a class="nav-link" href="../admin/account_settings/index.php">Account Settings</a></li>
<li class="nav-item"><a class="nav-link" href="../../login/logout.php">Logout</a></li>
</ul>
</div>
</div>
</nav>

<div class="page-wrapper">

<div class="update-bar">Last update: <span id="lastUpdate">--</span></div>

<div class="row g-4">

<!-- MAP -->
<div class="col-lg-8">
<div class="panel">
<div class="panel-header"><h5>Live Room Map</h5></div>
<div id="map"></div>
<div class="map-legend">
    <span><span class="legend-dot" style="background:blue"></span>Cold</span>
    <span><span class="legend-dot" style="background:gold"></span>Normal</span>
    <span><span class="legend-dot" style="background:#f87171"></span>Hot</span>
</div>
<div style="padding:14px 18px;text-align:center;border-top:1px solid #243044">
    <div style="font-size:12px;color:#64748b;margin-bottom:4px;font-weight:600;text-transform:uppercase;letter-spacing:.06em">Aircon Runtime</div>
    <div id="acRuntime" style="font-size:20px;font-weight:600;color:#fbbf24">0 min</div>
</div>
</div>
</div>

<!-- SIDE PANEL -->
<div class="col-lg-4">

<div class="panel mb-3">
<div class="panel-header"><h5>Room Information</h5></div>
<div class="info-panel">
    <h4 id="roomName" style="font-size:18px;font-weight:600;color:#f1f5f9;margin-bottom:16px">Lab 1</h4>
    <div class="info-label">Current Temperature</div>
    <div class="info-value" id="temp">-- °C</div>
    <div class="stat-row">
        <div class="stat-box">
            <div class="stat-label">Average</div>
            <div class="stat-val" id="avgTemp">-- °C</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Minimum</div>
            <div class="stat-val" id="minTemp">-- °C</div>
        </div>
        <div class="stat-box" style="grid-column:1/-1">
            <div class="stat-label">Maximum</div>
            <div class="stat-val" id="maxTemp">-- °C</div>
        </div>
    </div>
</div>
</div>

<div class="status-card">
    <span class="sc-label">Air Conditioner</span>
    <span class="sc-value sc-on" id="acStatus">--</span>
</div>

<div class="status-card">
    <span class="sc-label">Exhaust Fan</span>
    <span class="sc-value sc-off" id="fanStatus">--</span>
</div>

<button class="btn-refresh" id="refreshBtn">Refresh Sensor Data</button>

</div>
</div>

<!-- CHART -->
<div class="row chart-panel">
<div class="col-12">
<div class="panel">
<div class="panel-header"><h5>Temperature History</h5></div>
<div class="chart-body"><canvas id="tempChart"></canvas></div>
</div>
</div>
</div>

</div>

<script src="script.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById("refreshBtn").addEventListener("click", function(){
    if(typeof updateStatus === "function") updateStatus();
    fetch("", {
        method:"POST",
        headers:{"Content-Type":"application/x-www-form-urlencoded"},
        body:"log_refresh=1"
    }).then(res => res.text()).then(data => console.log("Activity logged:", data));
});
</script>
</body>
</html>