<?php
session_start();
include "../../../db.php";

$user_id = $_SESSION['user_id'];

$totalRooms    = $conn->query("SELECT COUNT(*) as total FROM rooms")->fetch_assoc()['total'];
$activeSensors = $conn->query("SELECT COUNT(*) as total FROM room_sensors WHERE sensor_status='ON'")->fetch_assoc()['total'];
$avgTemp       = $conn->query("SELECT AVG(room_temp) as avg FROM sensor_logs")->fetch_assoc()['avg'];

$logs = $conn->query("SELECT activity_logs.*, users.username 
FROM activity_logs
JOIN users ON users.id = activity_logs.user_id
ORDER BY created_at DESC LIMIT 10");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>IoT Room Monitor | Activity Logs</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

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
<li class="nav-item"><a class="nav-link" href="../index.php">Dashboard</a></li>
<li class="nav-item"><a class="nav-link" href="../logs_page/index.php">Logs</a></li>
<li class="nav-item"><a class="nav-link" href="../add_monitor_rooms/index.php">Manage Rooms</a></li>
<li class="nav-item"><a class="nav-link active" href="#">Activity Logs</a></li>
<li class="nav-item"><a class="nav-link" href="../account_settings/index.php">Account Settings</a></li>
<li class="nav-item"><a class="nav-link" href="../../../login/logout.php">Logout</a></li>
</ul>
</div>
</div>
</nav>

<div class="page-wrapper">
<h1 class="page-title">Activity Logs</h1>

<!-- KPI CARDS -->
<div class="kpi-grid">
<div class="kpi-card">
    <div class="kpi-label">Total Rooms</div>
    <div class="kpi-value blue"><?php echo $totalRooms; ?></div>
</div>
<div class="kpi-card">
    <div class="kpi-label">Active Sensors</div>
    <div class="kpi-value green"><?php echo $activeSensors; ?></div>
</div>
<div class="kpi-card">
    <div class="kpi-label">Avg Temperature</div>
    <div class="kpi-value orange"><?php echo number_format($avgTemp,2); ?>°C</div>
</div>
</div>

<!-- LOG TABLE -->
<div class="panel">
<div class="panel-header">
    <h5>Recent Activity</h5>
    <span class="panel-badge">Last 10 entries</span>
</div>
<div class="table-responsive">
<table class="table">
<thead>
<tr>
    <th>User</th>
    <th>Action</th>
    <th>Details</th>
    <th>Date</th>
</tr>
</thead>
<tbody>
<?php while($row = $logs->fetch_assoc()): ?>
<tr>
<td class="td-user"><?php echo htmlspecialchars($row['username']); ?></td>
<td class="td-action"><?php echo htmlspecialchars($row['action']); ?></td>
<td><?php echo htmlspecialchars($row['details']); ?></td>
<td class="td-date"><?php echo $row['created_at']; ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>