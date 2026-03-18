<?php

$conn = new mysqli("localhost","root","","iot_monitor");

$device = $_POST['device_id'];
$temp = $_POST['temperature'];
$ac = $_POST['ac'];
$fan = $_POST['fan'];

$conn->query("
INSERT INTO sensor_data(device_id,temperature,ac_status,fan_status)
VALUES('$device','$temp','$ac','$fan')
");

$conn->query("
UPDATE devices
SET device_status='Online', last_seen=NOW()
WHERE id='$device'
");

echo "DATA RECEIVED";

?>