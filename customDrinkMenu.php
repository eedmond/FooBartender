<?php
	require_once(dirname(__FILE__).'/Utilities/Database.php');

	const NUMB_INGREDIENTS_MAX = 16;
	
	$database = new Database();

	$queryResult = $database->StartQuery()
			->select('name')
			->from(Database::SingleTable, 'u')
			->where('station > -1')
			->orderBy('name','ASC')
			->execute()
			->fetchAll();

	for ($count = 0; $count < NUMB_INGREDIENTS_MAX; $count++)
	{
		if ($count == 0)
		{
			$displayMode = 'inline';
			$valueMargin = '; margin-right: 12px; margin-bottom: 5px;';
		} 
		else
		{
			$displayMode = 'none';
			$valueMargin = '';
		}
		
		echo '<select id="text', $count, '" name="text', $count, '" style="display: ', $displayMode, '">Ingredient<option value=0>Add Ingredient</option>';
		
		foreach ($queryResult as $row)
		{
			$drinkName = $row['name'];
			echo '<option value="', $drinkName, '">', $drinkName, '</option>';
		}
		echo '</select>';
		
		echo '<input type="number" id="parts', $count, '" name="parts', $count, '" class="number" value=0 min="0" name="vol', $count, '" style="display: ', $displayMode, $valueMargin, '">';
		echo '<input type="button" id="plus', $count, '" name="plus', $count, '" onclick="javascript:onPlusClick();" value="+" alt="New Drink" style="display: ', $displayMode, '"></button>';
		
		if ($count % 2 == 1)
		{
			echo '<br>';
		}
	}
	
	unset($database);
	unset($queryResult);
?>
