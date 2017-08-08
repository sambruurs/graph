<?php
include("config.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$connection = mysqli_connect('localhost',$username,$password,$dbname);

function dailyErrors($connection,$page_type,$sites_query_result_row_id) {
    $daily_error_query = "SELECT count(result) as result_count from test_pages,test_results WHERE test_results.result=0 AND test_pages.test_pages_id=test_results.test_id AND test_pages.page_type=".$page_type." AND test_pages.site_id=".$sites_query_result_row_id." AND test_pages.status=1 AND test_results.test_date >= curdate() ORDER BY test_date DESC LIMIT 1";
    $daily_error_query_result = $connection->query($daily_error_query);
    $daily_error_row = $daily_error_query_result->fetch_assoc();

    return $daily_error_row["result_count"];
}




$query = "SELECT * from test_pages,sites WHERE test_pages.site_id=sites.id AND page_type=1";
if (!$sites_result = $connection->query($query)) {
    die ('There was an error running query[' . $connection->error . ']');
}

$pages_types_query = "SELECT * from pages_types";
$pages_types_result = $connection->query($pages_types_query);

?>

<html>
<head>

<!-- Include Twitter Bootstrap and jQuery: -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" type="text/css"/>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</head>
<body>


<?php include("topmenu.php"); ?>

<div class="container">
  <h2>Basic Table</h2>
  <p>The .table class adds basic styling (light padding and only horizontal dividers) to a table:</p>            
  <table class="table">
    <thead>
      <tr>
        <th></th>
        <?php
        while ($pages_types_result_row = $pages_types_result->fetch_assoc()) {
          echo "<th>".$pages_types_result_row["name"]."</th>";
    }
    ?>
      </tr>
    </thead>
    <tbody>

<?php

$average_color[0] = "#FF0000";
$average_color[1] = "#4CAF50";
$average_color[2] = "#f48642";
$average_color[3] = "#874da5";

$sites_query = "SELECT * from sites";
$sites_query_result = $connection->query($sites_query);

while ($sites_query_result_row = $sites_query_result->fetch_assoc()) {

  echo "<tr><td>".$sites_query_result_row["name"]."</td>";

  for ($page_type = 1; $page_type <= 4; $page_type++) {

    $row_alert = 0;

    //$average_query = "SELECT * from test_pages,test_average WHERE test_pages.test_pages_id=test_average.test_pages_id AND test_pages.page_type=".$page_type." AND test_pages.site_id=".$sites_query_result_row["id"]." AND test_pages.status=1 ORDER BY calculated_date DESC,average_hour DESC LIMIT 1";
    
    $average_query = "SELECT test_pages_id,response_time,result,test_date  from test_pages,test_results WHERE test_pages.test_pages_id=test_results.test_id AND test_pages.page_type=".$page_type." AND test_pages.site_id=".$sites_query_result_row["id"]." AND test_pages.status=1 AND test_results.test_date >= DATE_ADD(CURDATE(), INTERVAL -3 DAY) ORDER BY test_date DESC LIMIT 1";
    $average_query_result = $connection->query($average_query);
    $average_row = $average_query_result->fetch_assoc();

    $average_row_number = $average_row["result"];


    $sam = dailyErrors($connection,$page_type,$sites_query_result_row["id"]);

    if ($sam>0) { 
      //$average_row_number = 2; # set row color to orange
      $row_alert = 1;

    }


    if ($average_row["response_time"]) {
      echo "<td style='background-color: ".$average_color[$average_row_number]."'>".$average_row["response_time"] . " " . $average_row["test_date"];
      if ($row_alert==1) echo "<img src='img/emergency-alert-icon.png' width='12px'>";
      echo "<a href='edit_test.php?action=edit&test_pages_id=".$average_row["test_pages_id"]."'>e</a></td>";
    } else {
      $is_new_test = "select * from test_pages WHERE site_id=".$sites_query_result_row["id"]."&page_type=".$page_type;
      
      echo "<td><a href='edit_test.php?action=add&page_type=".$page_type."&site_id=".$sites_query_result_row["id"]."'>New</a></td>";
    }
      
  } 

  echo "</tr>";

}

?>


      

    </tbody>
  </table>
</div>

</body>
</html>
