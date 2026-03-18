<?php
$conn = new mysqli("localhost","root","","iot_monitor");

$result = $conn->query("SELECT * FROM devices");
?>

<!DOCTYPE html>
<html>
<head>

<title>Device Manager</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

<h2 class="mb-4">Device Management</h2>

<table class="table table-bordered">

<thead class="table-dark">

<tr>
<th>ID</th>
<th>Device</th>
<th>Room</th>
<th>Status</th>
<th>Last Seen</th>
</tr>

</thead>

<tbody>

<?php while($row = $result->fetch_assoc()) { ?>

<tr>

<td><?php echo $row['id']; ?></td>
<td><?php echo $row['device_name']; ?></td>
<td><?php echo $row['room_name']; ?></td>
<td><?php echo $row['device_status']; ?></td>
<td><?php echo $row['last_seen']; ?></td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</body>
</html>