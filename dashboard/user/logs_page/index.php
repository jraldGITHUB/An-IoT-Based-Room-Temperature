<?php
session_start();

if(!isset($_SESSION['username'])){
    header("Location: ../../../login/index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>IoT Room Monitor | Logs</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
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
<li class="nav-item"><a class="nav-link" href="../../user/index.php">Dashboard</a></li>
<li class="nav-item"><a class="nav-link active" href="#">Logs</a></li>
<li class="nav-item"><a class="nav-link" href="../../../login/logout.php">Logout</a></li>
</ul>
</div>
</div>
</nav>

<div class="page-wrapper">
<div class="page-header">
    <div>
        <h1 class="page-title">Temperature &amp; Aircon Logs</h1>
        <p class="page-subtitle">Sensor readings across all monitored rooms</p>
    </div>
    <div class="filter-bar">
        <label for="roomFilter">Filter room:</label>
        <select id="roomFilter">
            <option value="all">All Rooms</option>
        </select>
    </div>
</div>

<div class="table-card">
<div class="table-responsive">
<table class="table">
<thead>
<tr>
    <th>Date</th><th>Time</th><th>Room</th><th>Room Temp (°C)</th>
    <th>Exhaust Temp (°C)</th><th>Aircon Status</th><th>Exhaust Fan</th><th>Runtime</th>
</tr>
</thead>
<tbody id="logTable"></tbody>
</table>
</div>
<div class="action-bar">
    <span class="action-bar-note">Showing latest entries &bull; Live via script.js</span>
    <button class="btn-csv" onclick="downloadCSV()">↓ Download CSV</button>
</div>
</div>
</div>

<script src="script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>