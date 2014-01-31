<br><br>
<form id="mixedForm" name="mixedForm" action="InsertMixed.php" method="POST">
	<input type="text" name="drinkName" value="Drink Name"><br>
<?php
	$db = new PDO('sqlite:testdb.db');
	$getNames = $db->query("select * from single");
	$queryResult = $getNames->fetchAll();

	for ($count=0; $count < 5; $count++)
	{
		echo 'Ingredient: ';
		echo '<select name="text', $count, '"><option value=0>Empty</option>';
		foreach($queryResult as $row) {
			$drinkName = $row['name'];
			echo '<option value="', $drinkName, '">', $row['name'], '</option>';
		}
		echo '</select>';
		echo 'Volume <input type="number" value=0 name="vol', $count, '">ml<br>';
	}
	unset($db);
?>

<input type="submit" value="Submit Drink">
</form>
