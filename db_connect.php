<?php

$host = "localhost";
$userName = "root";
$passWord = "";
$dbName = "spacetrader";

$conn = mysqli_connect($host, $userName, $passWord, $dbName);

if (!$conn) {
    echo "ERROR";
}