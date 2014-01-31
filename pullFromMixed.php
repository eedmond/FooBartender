<?php
	$db = new PDO('sqlite:testdb.db');
	$getNames = $db->query("select * from mixed where isOnTable=1");
	$queryResult = $getNames->fetchAll();

	foreach($queryResult as $row) {
		$drinkName = $row['name'];

		echo '<option value="', $drinkName, '">', $row['name'], '</option>';
	}
	echo '<option id="custom" name="custom" value="custom">Custom Drink</option>';
	unset($db);
?>
