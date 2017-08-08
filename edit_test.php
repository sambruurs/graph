<?php
include("config.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



$connection = mysqli_connect('localhost',$username,$password,$dbname);

if (isset($_POST["action"])) {


	if ($_POST["action"]=="edit") {

		$query = "UPDATE test_pages SET click_cmd=\"" . $_POST["click_cmd"] . "\",search_string=\"" . $_POST["search_string"] . "\" WHERE test_pages_id=". htmlspecialchars($_POST["test_pages_id"]);

		$connection->query($query);

		$save_succes=1;

	} elseif ($_POST["action"]=="add") {

		$query = "INSERT INTO test_pages(click_cmd,search_string,page_type,url,site_id,status) VALUES(\"" . $_POST["click_cmd"] . "\",\"" . $_POST["search_string"] . "\"," . $_POST["page_type"] . ",\"" . $_POST["url"] . "\"," . $_POST["site_id"] . ",1)";

		$connection->query($query);

		$save_succes=1;
	}

}

if ($_GET["action"]=="edit") {

	$test_query = "SELECT * from test_pages,sites WHERE test_pages.site_id=sites.id AND test_pages_id=" . $_GET["test_pages_id"];
	if (!$test_query_result = $connection->query($test_query)) {
	    die ('There was an error running query[' . $connection->error . ']');
	}

	$test_query_result_row = $test_query_result->fetch_assoc();

}

?>

<html>
<head>

<style>
#wrapper {
  margin-left: 600px;
}
#content {
  float: right;
  width: 100%;
  background-color: #CCF;
}
#sidebar {
  float: left;
  width: 600px;
  margin-left: -600px;
  background-color: #FFA;
}
#cleared {
  clear: both;
}
</style>

<!-- Include Twitter Bootstrap and jQuery: -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" type="text/css"/>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script type="text/javascript">
	

$(function(){

    $(".dropdown-menu li a").click(function(){

      $(".btn:first-child").text($(this).text());
      $(".btn:first-child").val($(this).text());
 
   });

});


function run_test() {


	document.getElementById("test_result").innerHTML="Testing...<img src='img/gears.gif'>";
	document.getElementById("displayPage").innerHTML='<object type="text/html" data="load_test.php?test_pages_id=<?php if (isset($test_query_result_row["test_pages_id"])) echo $test_query_result_row["test_pages_id"];?>" style="width:450px;height:400px;"></object>';
}

function ff_test() {

	var cmd_divs = document.getElementsByClassName('div_action_cmd');cmd_divs[1].innerHTML="Te";
}

function save_test() {


var action_cmd_inputs = formNum["action_cmd[]"];



	for (i = 0; i < action_cmd_inputs.length; i++) { 
		if (action_cmd_inputs[i].value) {

			document.getElementById('click_cmd').value = document.getElementById('click_cmd').value + action_cmd_inputs[i].value + "|"
		}
		

	
	}
	    
	document.forms["formNum"].submit();


}




function add_click() {

	          var newdiv = document.createElement('div');
	          newdiv.innerHTML = new_click;
	          document.getElementById("addform").appendChild(newdiv);
}


new_click = "<?php echo "=======<br>";

	    echo "<input type='text' class='form-control' id='action_cmd[]' style='width:300px;'>";
	    ?>";




</script>

</head>
<body>

<?php include("topmenu.php"); ?>


<?php if (isset($save_succes)) echo "<div class='alert alert-success' role='alert'>Test saved</div>";?>

<div id="wrapper">
<div id="sidebar">


	<form name="formNum" id="formNum" action="" method=post>
	<div class="form-group" id="addform">
url:<br>
	  <input type="text" class="form-control" id="url" name="url" value="<?php if (isset($test_query_result_row["url"])) echo $test_query_result_row["url"];?>" style='width:400px;'>


	  <button type='button' class='' data-toggle='collapse' data-target='#demo'><div class='div_action_cmd_title'>new</div></button>
	 <div id='demo' class='collapse'><div class='div_action_cmd'>lklk</div></div>
	 <input type="hidden" id="action" name="action" value="<?php echo $_GET["action"];?>">
	 <input type="hidden" id="page_type" name="page_type" value="<?php echo $_GET["page_type"];?>">
	 <input type="hidden" id="site_id" name="site_id" value="<?php echo $_GET["site_id"];?>">
	  <input type="hidden" id="test_pages_id" name="test_pages_id" value="<?php if (isset($test_query_result_row["test_pages_id"])) echo $test_query_result_row["test_pages_id"];?>">
	  <input type="hidden" id="click_cmd" name="click_cmd">
	  <input type="hidden" id="action_cmd[]">





	    

	    <?php
		if (isset($test_query_result_row["click_cmd"])) {

			$click_cmd = $test_query_result_row["click_cmd"];

		} else {
			$click_cmd = "";

		}

			$cmd_delen_array = explode("|",$click_cmd);

			$count = 0;


			foreach ($cmd_delen_array as $cmd_delen) {


		    	

			   echo "<input type='text' class='form-control' id='action_cmd[]' style='width:600px;' value=\"".$cmd_delen."\">";
			   echo "<button type='button' class='' data-toggle='collapse' data-target='#demo".$count."'><div class='div_action_cmd_title'>new</div></button>";
		 		echo "<div id='demo".$count."' class='collapse'><div class='div_action_cmd'>lklk</div></div>";

		 		$count =$count +1;
			}

		#}


	     ?>

	  
	</div>
	<a href="#" onClick="add_click()">Add</a><br>
	search string:<br>
	<input type="text" class="form-control" id="search_string" name=search_string value="<?php if (isset($test_query_result_row["search_string"])) echo $test_query_result_row["search_string"];?>" style='width:300px;'>
	<a href="#" onClick="save_test()">Save</a><br>
	<a href="#" onClick="run_test()">Test</a><br>
	<a href="#" onClick="ff_test()">ff Test</a><br>

</form>
</div>
<div id="content">

	<div id="test_result">test</div>
	<div id="displayPage">Display the Html Page here</div>
	<div id="errors">No errors</div>
</div>
<div id="cleared"></div>
</div>
</body>
</html>