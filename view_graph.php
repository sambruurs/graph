<?php
	require_once ('jpgraph-4.0.1/src/jpgraph.php');
	require_once ('jpgraph-4.0.1/src/jpgraph_line.php');
	include("config.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$line_colors = array("", "ff0000", "ff8000", "ffff00", "80ff00", "00ffff", "0000ff", "ff00ff", "996633", "000000", "990099");

	$connection = mysqli_connect('localhost',$username,$password,$dbname);


$graph = new Graph(600,500);
$graph->SetScale("textlin");

$theme_class=new UniversalTheme;

$graph->SetTheme($theme_class);
$graph->img->SetAntiAliasing(false);
$graph->title->Set('Filled Y-grid');
$graph->SetBox(false);

$graph->img->SetAntiAliasing();

$graph->yaxis->HideZeroLabel();
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);

$graph->xgrid->Show();
$graph->xgrid->SetLineStyle("solid");
$graph->xgrid->SetColor('#E3E3E3');


$day = $connection->real_escape_string($_GET["day"]);
//$day = 5;
$month = 11;
$year = 2016;


	$query = "SELECT * from sites,test_pages WHERE page_type=1 and sites.id=test_pages.site_id";
	$sites_result = $connection->query($query);

	$tel = 1;

	while ($sites_result_row = $sites_result->fetch_assoc()) {


		$query = "SELECT average_value FROM test_average WHERE average_year = ".$year." AND average_month = ".$month." AND average_day = ".$day. " AND average_hour IS NULL AND average_type=1 AND test_pages_id=". $sites_result_row["test_pages_id"];	
		$test_result = $connection->query($query);

		$row = $test_result->fetch_assoc();

		
		


		//$query = "SELECT * from test_results WHERE test_id=" . $sites_result_row["test_pages_id"] . " AND DATE(test_date) = DATE(now()) ORDER BY test_date DESC";
		$query = "SELECT average_hour,average_value FROM test_average WHERE average_year = ".$year." AND average_month = ".$month." AND average_day = ".$day. " AND average_type=1 AND test_pages_id=". $sites_result_row["test_pages_id"];	
		$test_result = $connection->query($query);

		while ($test_result_row = $test_result->fetch_assoc()) {
			$test_result_array[$test_result_row["average_hour"]] = $test_result_row["average_value"];
		}


		unset($dotted);
		$x = 0;

		while($x < 24) {

				$dotted[] = $row["average_value"];

				if (array_key_exists($x,$test_result_array)) {
					$test = "\$datay".$tel."[".$x."] = \$test_result_array[\"".$x."\"];";
					//echo $test;
					eval($test);				
				} else {
					$test = "\$datay".$tel."[".$x."] = 0;";
					//echo $test;
					eval($test);
				}
			
			$x = $x + 1;
		}

		eval("\$p".$tel." = new LinePlot(\$datay".$tel.");");
		eval("\$graph->Add(\$p".$tel.");");
		eval("\$p".$tel."->SetColor(\"#".$line_colors[$tel]."\");");
		eval("\$p".$tel."->SetLegend('".$sites_result_row["name"]."');");



	

		eval("\$pa".$tel." = new LinePlot(\$dotted);");
		eval("\$graph->Add(\$pa".$tel.");");
		eval("\$pa".$tel."->SetColor(\"#".$line_colors[$tel]."\");");
		eval("\$pa".$tel."->SetStyle(\"dotted\");");
		//eval("\$pa".$tel."->SetLegend('".$sites_result_row["name"]."');");



		$tel = $tel + 1;
		
	}


//print_r ($datay1);


$graph->legend->SetFrameWeight(1);

// Output line
$graph->Stroke();