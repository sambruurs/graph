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

    	echo "<div style='margin: 25px 50px 25px; padding: 25px 50px 25px; background-color:#ddd;'>";
    	echo $sites_result_row["name"]; 

    	$query = "SELECT * from test_pages,pages_types WHERE test_pages.page_type=pages_types.id AND site_id=".$sites_result_row["id"];
    	if (!$tests_result = $connection->query($query)) {
		    die ('There was an error running query[' . $connection->error . ']');
		}

		echo "<table>";
		while ($tests_result_row = $tests_result->fetch_assoc()) {

			$query = "SELECT * from test_results WHERE test_id = ".$tests_result_row["test_pages_id"]." ORDER BY test_date DESC LIMIT 1";
			//echo $query . "<br>";
			$last_test_result = $connection->query($query);

			$last_test_row = $last_test_result->fetch_assoc();

			echo "<tr><td><b>" . $tests_result_row["name"] . "Last test: ".check_date_age($last_test_row["test_date"],$last_test_row["result"])." (".$last_test_row["result"].")</td></tr>";
			echo "<tr><td>Url: " . $tests_result_row["url"] . "<br>Click path: " . $tests_result_row["click_cmd"] . "<br>Zoek string: " . $tests_result_row["search_string"] . "</td></tr>";
		}
		echo "</table>";

    	echo "</div>";
	}

	function check_date_age($check_date,$result) {

		if(strtotime($check_date) < strtotime('-10 minutes') ){
           	return "<span style='color:red;'>".$check_date."</span>";
		 }else{
		 	if ($result==1) {
		    	return "<span style='color:green;'>".$check_date."</span>";
		    }else{
		    	return "<span style='color:orange;'>".$check_date."</span>";
		    }
		}
	}

?>