<?php
?>
<form id="mixedForm" name="mixedForm" action="InsertMixed.php" method="POST">
	<input type="text" name="drinkName" value="Drink Name"><br>
<?php
	$db = new PDO('sqlite:../FB.db');
	$getNames = $db->query("SELECT * FROM single ORDER BY name ASC");
	$queryResult = $getNames->fetchAll();

	for ($count=0; $count < 10; $count++)
	{
		echo 'Ingredient: ';
		echo '<select name="text', $count, '"><option value=0>Empty</option>';
		foreach($queryResult as $row) {
			$drinkName = $row['name'];
			echo '<option value="', $drinkName, '">', $row['name'], '</option>';
		}
		echo '</select>';
		echo 'Parts <input type="number" value=0 name="vol', $count, '"><br>';
	}
	unset($db);
?>

<input type="submit" value="Submit Drink">
</form>
