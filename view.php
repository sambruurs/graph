<?php
include("config.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



$connection = mysqli_connect('localhost',$username,$password,$dbname);

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

<!-- Include the plugins CSS and JS: -->
<script type="text/javascript" src="js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="css/bootstrap-multiselect.css" type="text/css"/>


<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">

function set_current_date() {
	var dateString = Date.now(); // date string
	var actualDate = new Date(dateString); // convert to actual date
	//var newDate = new Date(actualDate.getFullYear(), actualDate.getMonth(), actualDate.getDate()+1); // create new increased date
	document.getElementById("datum").value = actualDate;
}

function change_day(direction) {
	var dateString = new Date(document.getElementById("datum").value);
	if (direction=="previous") dateString.setDate(dateString.getDate() - 1);
	if (direction=="next") dateString.setDate(dateString.getDate() + 1);
	var actualDate = new Date(dateString);
	document.getElementById("datum").value = actualDate;
}

function reload_graph() {
	var sites = "";
	select1 = document.getElementById("example-getting-started");
	for (var i = 0; i < select1.length; i++) {
        if (select1.options[i].selected) sites = sites + select1.options[i].value + ",";
    }
    sites = sites.substring(0, sites.length - 1);


    var page_types = "";
    select1 = document.getElementById("page_types");
    for (var i = 0; i < select1.length; i++) {
        if (select1.options[i].selected) page_types = page_types + select1.options[i].value + ",";
    }
    page_types = page_types.substring(0, page_types.length - 1);

    var input_date = new Date(document.getElementById("datum").value);


    selected_day = input_date.getFullYear() + "-" + (input_date.getMonth()+1) + "-" + input_date.getDate();

    $('#graph').load('http://ec2-54-217-167-154.eu-west-1.compute.amazonaws.com/graph/gtest.php?view_date=' + selected_day + '&sites=' + sites + '&page_types=' + page_types);
}

</script>
 
</head>

<body onLoad="reload_graph();set_current_date()">

<?php include("topmenu.php"); ?>

    <select id="page_types" multiple="multiple">

    <?php
    while ($pages_types_result_row = $pages_types_result->fetch_assoc()) {
        echo "<option value=".$pages_types_result_row["id"].">".$pages_types_result_row["name"]."</option>";
}
?>
    </select>

    <!-- Build your select: -->
    <select id="example-getting-started" multiple="multiple">

    <?php
    while ($sites_result_row = $sites_result->fetch_assoc()) {
    	echo "<option value=".$sites_result_row["test_pages_id"].">".$sites_result_row["name"]."</option>";
}
?>
    </select>



    <!-- Initialize the plugin: -->
    <script type="text/javascript">
        $(document).ready(function() {
            $('#example-getting-started').multiselect({
            	onDropdownHide: function(event) {
                reload_graph();
            }
            });
        });
    </script>


    <script type="text/javascript">
        $(document).ready(function() {
            $('#page_types').multiselect({
                onDropdownHide: function(event) {
                reload_graph();
            }
            });
        });
    </script>

<a href="#" onClick="change_day('previous');reload_graph();"><</a>

<input type=text name=datum id=datum>

<a href="#" onClick="change_day('next');reload_graph();">></a>

<div id="graph"></div>





<?php

  $query = "SELECT * from sites,test_pages WHERE page_type in (".$page_types.") and test_pages_id in (".$sites.") and sites.id=test_pages.site_id";
  $sites_result = $connection->query($query);



  $tel = 1;

  while ($sites_result_row = $sites_result->fetch_assoc()) {

        $query = "SELECT AVG(average_value) FROM test_average WHERE date(calculated_date) BETWEEN '".$view_date. "' AND DATE_ADD('".$view_date. "', INTERVAL 7 DAY) AND average_type=1 AND test_pages_id=". $sites_result_row["test_pages_id"]; 

        echo $query;
    }

