<?php

require_once('connect.php');

function getDevices($conn)
{
    $sql_devices = "SELECT * FROM device";
    return mysqli_query($conn, $sql_devices);
}