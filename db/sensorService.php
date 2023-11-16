<?php
require_once('connect.php');

function getSelectedDeviceId()
{
    return isset($_GET['device']) ? $_GET['device'] : 1;
}

function getLatestData($conn, $selected_device_id, $prop)
{
    $sql_latest_data = "SELECT value FROM sensor
                   WHERE type = '$prop' AND device_id = $selected_device_id
                   ORDER BY timestamp DESC
                   LIMIT 1";

    return mysqli_query($conn, $sql_latest_data);
}
