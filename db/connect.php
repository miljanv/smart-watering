<?php
session_start();

$servername = "127.0.0.1";
$username = "root";
$password = "";
$database = "smart_watering";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Not connected to db.");
}
