<?php
include("config.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$connection = mysqli_connect('localhost',$username,$password,$dbname);

$query = "SELECT * from sites";

if (!$sites_result = $connection->query($query)) {
    die ('There was an error running query[' . $connection->error . ']');
}

    while ($sites_result_row = $sites_result->fetch_assoc()) {

    	echo gethostbyname("www.score.nl");
    }