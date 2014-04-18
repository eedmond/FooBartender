<?php
	$db = new PDO('sqlite:../FB.db');
	$getNames = $db->query("SELECT * FROM mixed WHERE isOnTable=1 ORDER BY name ASC");
	$queryResult = $getNames->fetchAll();

	foreach($queryResult as $row) {
		$drinkName = $row['name'];

		echo '<option value="', $drinkName, '">', $row['name'], '</option>';
	}
	echo '<option id="custom" name="custom" value="custom">Custom Drink</option>';
	unset($db);
?>
