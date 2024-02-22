<?php

$host = "localhost";
$userName = "root";
$passWord = "";
$dbName = "spacetraders";

$conn = mysqli_connect($host, $userName, $passWord, $dbName);

if (!$conn) {
    echo "ERROR";
}