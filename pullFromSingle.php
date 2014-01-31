<?php

	$db = new PDO('sqlite:testdb.db');
	$getNames = $db->query("select * from single");
	$queryResult = $getNames->fetchAll();

	for ($count = 0; $count < 16; $count++)
	{
		
		$currentDrink = 0;
		echo '<div id="Drink_Text">Station ', floor($count/2) + 1;
		if($count % 2) echo 'b'; else echo 'a';
		echo '<select name="station', $count, '"><option value=0>Empty</option>';

		foreach($queryResult as $row) {
				$drinkName = $row['name'];

				if ($count == $row['station'])
				{
					echo '<option value="', $drinkName, '" selected>', $row['name'], '</option>';
					$currentDrink = $row['volume'];
				}
				else
					echo '<option value="', $drinkName, '" >', $row['name'], '</option>';
		}
		echo '</select><input name="volume', $count, '" type="number" value=', $currentDrink, ' id="vol0" class="Drink_Field" size="3">ml<br>';
	}
	unset($db);
?>
