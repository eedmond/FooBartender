<?php
	$db = new PDO('sqlite:testdb.db');

	if ($_GET['drinkName'] != "" && $_GET['drinkName'] != "null")
		$db->exec("insert into single values('" . $_GET['drinkName'] . "', -1, 0);");
?>