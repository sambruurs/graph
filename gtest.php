<?php

  include("config.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$connection = mysqli_connect('localhost',$username,$password,$dbname);


if (!$_GET["view_date"]) {
  $view_date = date("Y-n-j");
} else {
  $view_date = $connection->real_escape_string($_GET["view_date"]);
}

if (!$_GET["page_types"]) {
  $page_types = 1;
} else {
  $page_types = $connection->real_escape_string($_GET["page_types"]);
}

$sites = $connection->real_escape_string($_GET["sites"]);


 

  $query = "SELECT * from sites,test_pages WHERE page_type in (".$page_types.") and test_pages_id in (".$sites.") and sites.id=test_pages.site_id";
  $sites_result = $connection->query($query);



  $tel = 1;

  while ($sites_result_row = $sites_result->fetch_assoc()) {

    $site_name[$tel] = $sites_result_row["name"];

    # ophalen test gemiddelden

    $query = "SELECT average_hour,average_value,calculated_date FROM test_average WHERE date(calculated_date) BETWEEN '".$view_date. "' AND DATE_ADD('".$view_date. "', INTERVAL 7 DAY) AND average_type=1 AND test_pages_id=". $sites_result_row["test_pages_id"]. " ORDER BY calculated_date,average_hour"; 

    $test_result = $connection->query($query);

    $array_tel = 0;

    while ($test_result_row = $test_result->fetch_assoc()) {
      $test_result_array[$array_tel] = $test_result_row["average_value"];
      $test_result_array_datum[$array_tel] = $test_result_row["calculated_date"] . " ". $test_result_row["average_hour"] . ":00";
      //echo $test_result_row["average_hour"];
      $array_tel++;
    }



    # array vullen

    if (count($test_result_array)>0) {

      $x = 1;

      

      while($x < count($test_result_array)) {

          $line[$x][0] = $test_result_array_datum[$x];

          if (array_key_exists($x,$test_result_array)) {
            $line[$x][$tel] = $test_result_array[$x]; 

          } else {
            $line[$x][$tel] = 0;
          }
        
        $x = $x + 1;
      }

    }

    $tel = $tel + 1;
    
  }



?>

<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([




          ['year'
<?php



for ($site_count = 1; $site_count <= count($site_name); $site_count++) {
  echo ",";
  echo "'" . $site_name[$site_count] . "'";
}
echo "],";

$uur_teller = 0;

for ($x = 1; $x < count($line); $x++ ) {
  echo "['" . $test_result_array_datum[$x] . "'";
  $uur_teller ++;
  if ($uur_teller==24) $uur_teller=0;

  for ($y = 1; $y <= count($line[$x])-1; $y++ ) {
      echo "," . $line[$x][$y];
  }

  if ($x==(count($line)-1)) echo "]";
  else echo "],";
}
echo "]);";

  //die();
?>

        var options = {
          title: 'Company Performance',
          hAxis: {format: 'Y,M,d,H', title: 'Hour',  titleTextStyle: {color: '#333'}},
          vAxis: {minValue: 0}
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
  </head>
  <body>

<?php

if (count($line)==0) {
  die ("no data available for ". $view_date);
}

?>
  
    <div id="chart_div" style="width: 100%; height: 500px;"></div>


<?php

  $query = "SELECT * from sites,test_pages WHERE page_type in (".$page_types.") and test_pages_id in (".$sites.") and sites.id=test_pages.site_id";
  $sites_result = $connection->query($query);



  $tel = 1;

  while ($sites_result_row = $sites_result->fetch_assoc()) {

        $query = "SELECT AVG(average_value) FROM test_average WHERE date(calculated_date) BETWEEN '".$view_date. "' AND DATE_ADD('".$view_date. "', INTERVAL 7 DAY) AND average_type=1 AND test_pages_id=". $sites_result_row["test_pages_id"]; 

        echo $query;
    }

    ?>


  </body>
</html>
