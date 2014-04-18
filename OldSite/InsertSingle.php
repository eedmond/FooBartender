<?php
	$db = new PDO('sqlite:../FB.db');

	if ($_GET['drinkName'] != "" && $_GET['drinkName'] != "null")
		$db->exec("insert into single values(\"" . $_GET['drinkName'] . "\", -1, 0, " . $_GET['proof'] . ");");
?>
