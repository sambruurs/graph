<?php
include("config.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



$connection = mysqli_connect('localhost',$username,$password,$dbname);

$test_query = "SELECT * from test_pages,sites WHERE test_pages.site_id=sites.id AND test_pages_id=22";
if (!$test_query_result = $connection->query($test_query)) {
    die ('There was an error running query[' . $connection->error . ']');
}

$test_query_result_row = $test_query_result->fetch_assoc();

$element_array[0] = "ul";
$element_array[1] = "div";
$element_array[2] = "span";

$identifier_type_array[0] = "type";
$identifier_type_array[1] = "class";

$action_array[0] = "click";
$action_array[1] = "set";

?>

<html>
<head>

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

function build_cmd() {


var element_inputs = formNum["element[]"];
var identifier_type_inputs = formNum["identifier_type[]"];
var identifier_name_inputs = formNum["identifier_name[]"];
var element_action_inputs = formNum["element_action[]"];

if(element_inputs.selectedIndex) {
	alert("niet genoeg velden.");
} else {

	for (i = 0; i < element_inputs.length; i++) { 
		if (element_inputs[i].selectedIndex) {

			cmd = "b." + element_inputs[i].options[element_inputs[i].selectedIndex].text + "(:" + identifier_type_inputs[i].options[identifier_type_inputs[i].selectedIndex].text + " => '" + identifier_name_inputs[i].value + "')." + element_action_inputs[i].options[element_action_inputs[i].selectedIndex].text;

			alert(cmd);
		}
		
	}
}
    



}

function add_click() {

	          var newdiv = document.createElement('div');
	          newdiv.innerHTML = new_click;
	          document.getElementById("addform").appendChild(newdiv);
}


new_click = "<?php echo "=======<br><select class='form-control' id='element[]' style='width:100px;'><option>niks</option>";

	    for ($element_array_count = 0; $element_array_count < count($element_array); $element_array_count++) {echo "<option>".$element_array[$element_array_count]."</option>";}
	    echo "</select>";

		echo "<select class='form-control' id='identifier_type[]' style='width:100px;'><option>niks</option>";

	    for ($identifier_type_count = 0; $identifier_type_count < count($identifier_type_array); $identifier_type_count++) {
	    	echo "<option>".$identifier_type_array[$identifier_type_count]."</option>";

	    }

	    echo "</select>";
	    echo "<input type='text' class='form-control' id='identifier_name[]' style='width:100px;'>";

 		echo "<select class='form-control' id='element_action[]' style='width:100px;'><option>niks</option>";

	    for ($action_count = 0; $action_count < count($action_array); $action_count++) {
	    	echo "<option>".$action_array[$action_count]."</option>";

	    }

	    echo "</select>";

	    ?>";




</script>

</head>
<body>
<a href="#" onClick="build_cmd()">test</a><br>
<form name="formNum" id="formNum" action="" >
<div class="form-group" id="addform">
  <input type="text" class="form-control" id="url" value="<?php echo $test_query_result_row["url"];?>">
  <input type="hidden" id="element[]">
  <input type="hidden" id="identifier_type[]">
  <input type="hidden" id="identifier_name[]">
  <input type="hidden" id="element_action[]">




    

    <?php
	$search_string = $test_query_result_row["click_cmd"];

	$cmd_delen_array = explode("|",$search_string);



	foreach ($cmd_delen_array as $cmd_delen) {


    	$cmd_delen = str_replace("b.","",$cmd_delen);

		$cmd_delen_tmp = explode("(:",$cmd_delen);

		//echo $cmd_delen_tmp[0];

		echo "<select class='form-control' id='element[]' style='width:100px;'><option>niks</option>";

	    for ($element_array_count = 0; $element_array_count < count($element_array); $element_array_count++) {
	    	echo "<option";
	    	if ($element_array[$element_array_count]==$cmd_delen_tmp[0]) echo " selected";
	    	echo ">".$element_array[$element_array_count]."</option>";

	    }

	    echo "</select>";

	    $cmd_delen_tmp1 = explode(" => '",$cmd_delen_tmp[1]);



		echo "<select class='form-control' id='identifier_type[]' style='width:100px;'><option>niks</option>";

	    for ($identifier_type_count = 0; $identifier_type_count < count($identifier_type_array); $identifier_type_count++) {
	    	echo "<option";
	    	if ($identifier_type_array[$identifier_type_count]==$cmd_delen_tmp1[0]) echo " selected";
	    	echo ">".$identifier_type_array[$identifier_type_count]."</option>";

	    }

	    echo "</select>";

	    $cmd_delen_tmp2 = explode("').",$cmd_delen_tmp1[1]);

	   echo "<input type='text' class='form-control' id='identifier_name[]' style='width:100px;' value='".$cmd_delen_tmp2[0]."'>";
 
 		echo "<select class='form-control' id='element_action[]' style='width:100px;'><option>niks</option>";

	    for ($action_count = 0; $action_count < count($action_array); $action_count++) {
	    	echo "<option";
	    	if ($action_array[$action_count]==$cmd_delen_tmp2[1]) echo " selected";
	    	echo ">".$action_array[$action_count]."</option>";

	    }

	    echo "</select>";

	}

	


     ?>

  
</div>
<a href="#" onClick="add_click()">test</a><br>

</body>
</html>