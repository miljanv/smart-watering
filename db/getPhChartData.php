<?php
require_once('connect.php');
require_once('sensorService.php');

$selected_device_id = getSelectedDeviceId();

$sql_latest_data = "SELECT device_id, type, value, timestamp FROM sensor
               WHERE type = 'ph'
               ORDER BY timestamp ASC";

$result = mysqli_query($conn, $sql_latest_data);

if (!$result) {
    die('Error: ' . mysqli_error($conn));
}

$data = array();

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);

mysqli_close($conn);
?>
