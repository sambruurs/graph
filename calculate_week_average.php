<?php

include("config.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$connection = mysqli_connect('localhost',$username,$password,$dbname);

function daysInWeek($weekNum)
{
    $result = array();
    $datetime = new DateTime('00:00:00');
    $datetime->setISODate((int)$datetime->format('o'), $weekNum, 1);
    $interval = new DateInterval('P1D');
    $week = new DatePeriod($datetime, $interval, 6);

    foreach($week as $day){
        $result[] = $day->format('Y-m-d');
    }
    return $result;
}



function getStartAndEndDate($week, $year)
{

    $time = strtotime("1 January $year", time());
    $day = date('w', $time);
    $time += ((7*$week)+1-$day)*24*3600;
    $return[0] = date('Y-n-j', $time);
    $time += 6*24*3600;
    $return[1] = date('Y-n-j', $time);
    return $return;
}


for ($week_tel=45;$week_tel<80;$week_tel++) {

    $daysInWeek = getStartAndEndDate($week_tel,2016);

    echo date('Y-n-j');

    if ($daysInWeek[0] < date('Y-n-j')) {

        $current_week = $date=date("W");

        $query = "SELECT * from test_pages";
        $sites_result = $connection->query($query);


        while ($sites_result_row = $sites_result->fetch_assoc()) {

        	$query = "SELECT AVG(average_value) as average_response_time,calculated_date from test_average WHERE test_pages_id = ".$sites_result_row['test_pages_id']." AND date(calculated_date) BETWEEN '".$daysInWeek[0]."' AND '".$daysInWeek[1]."'";

        	$average_result = $connection->query($query);

        	$row = $average_result->fetch_assoc();

        	$update_query ="INSERT INTO test_average(average_type,average_hour,test_pages_id,average_value,calculated_date) VALUES (2,0," . $sites_result_row['test_pages_id'] . "," . $row["average_response_time"] . ",'".$daysInWeek[0]."') ON DUPLICATE KEY UPDATE average_value = ".$row["average_response_time"];
        	$average_query_result = $connection->query($update_query);
        	echo $update_query . "\n";
        }
    }
}