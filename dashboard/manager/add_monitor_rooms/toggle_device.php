<?php
include "../../../db.php";

if(isset($_GET['id'])){

    $id = intval($_GET['id']);

    $conn->query("
        UPDATE room_sensors
        SET sensor_status = IF(sensor_status='ON','OFF','ON')
        WHERE room_id = $id
    ");

    echo "success";
}
?>