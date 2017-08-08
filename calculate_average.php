<?php

include("config.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$connection = mysqli_connect('localhost',$username,$password,$dbname);

$x = 0;

if (!$_GET["calculate_date"]) {
	$calculate_date = date("Y-n-j"); 
} else {
	$calculate_date = $connection->real_escape_string($_GET["calculate_date"]);
}


$max_date_query = "SELECT max(calculated_date) as max_calculated_date from test_average";
$max_date_result = $connection->query($max_date_query);

$max_date_row = $max_date_result->fetch_assoc();

$max_calculated_date = $max_date_row["max_calculated_date"];

$calculate_date = $max_calculated_date;

while (date('Y-n-j', strtotime($calculate_date . ' -1 day'))!=date("Y-n-j")) {

	echo $calculate_date . "</br>";

	$query = "SELECT * from test_pages";
	$sites_result = $connection->query($query);


	while ($sites_result_row = $sites_result->fetch_assoc()) {

		$x = 0;
		$test_pages_id = $sites_result_row["test_pages_id"];

		while($x < 24) {

		    $query_date = $calculate_date;

			$query = "SELECT AVG(response_time) as average_response_time,test_date from test_results WHERE test_id = ".$test_pages_id." AND hour(test_date) = ".$x." AND date(test_date) = '".$query_date . "'";
			//echo $query . "<br>";
			$average_result = $connection->query($query);

			$row = $average_result->fetch_assoc();

			$update_query ="INSERT INTO test_average(average_type,average_hour,test_pages_id,average_value,calculated_date) VALUES (1," . $x . "," . $test_pages_id . "," . $row["average_response_time"] . ",'".$row["test_date"]."') ON DUPLICATE KEY UPDATE average_value = ".$row["average_response_time"];
			$average_query_result = $connection->query($update_query);

			echo $update_query . "<br>";

		    $x++;
		} 
		$query = "SELECT AVG(response_time) as average_response_time from test_results WHERE test_id = ".$test_pages_id." AND date(test_date) = '".$query_date . "'";
		//echo $query . "<br>";
		$average_result = $connection->query($query);

		$row = $average_result->fetch_assoc();

		$update_query ="INSERT INTO test_average(average_type,test_pages_id,average_value,calculated_date) VALUES (2," . $test_pages_id . "," . $row["average_response_time"] . ") ON DUPLICATE KEY UPDATE average_value = ".$row["average_response_time"];
		$average_query_result = $connection->query($update_query);	

		//echo $update_query . "<br>";
	}



	$calculate_date = date('Y-n-j', strtotime($calculate_date . ' +1 day'));
}

