<?php
	$file = file_get_contents('http://ec2-54-154-221-84.eu-west-1.compute.amazonaws.com/www/single_test.php?test_pages_id=' . $_GET["test_pages_id"], false, $context);
	echo $file;