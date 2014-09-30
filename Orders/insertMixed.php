<?php
	require_once(dirname(__FILE__) . '/../Utilities/Database.php');
	
	$database = new Database();
	
	//$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

	$result = $database->StartQuery()
		->select('*')
		->from(Database::Mixed)
		->where('name = "' . $_POST['drinkName'] .'"')
		->execute();

	if ($result->fetch(PDO::FETCH_NUM) != 0)
	{
		echo "<script type='text/javascript'>";
		echo "alert('" . $_POST['drinkName'] . " already exists and was not added')"; 
		echo "</script>";  
		echo "<script>window.history.go(-1)</script>";
		die();
	}

	$query = "INSERT INTO mixed values(\"" . $_POST['drinkName'] . "\", \"";
	$duration = "";
	$drinksAdded = 0;
	$totalParts = 0;
	$partsArray = array();

	for ($count = 0; $count < 16; $count++)
	{
		$drinkName = $_POST["text" . $count];
		$parts = $_POST["parts" . $count];

		if ($drinkName == "Add Drink" || $parts == 0) continue;

		if ($drinksAdded != 0)
			$query = $query . "|";
		
		$drinksAdded++;
		$totalParts += $parts;
		$partsArray[$drinkName] = $parts;

		$query = $query . $drinkName;
	}

	$alcoholic = 0;

	foreach($partsArray as $name => $part)
	{
		$queryResult = $db->query("SELECT proof FROM single WHERE name=\"" . $name . "\"");
		$proof = $queryResult->fetchAll(PDO::FETCH_COLUMN, 0);
		if ($proof[0] != 0)
			$alcoholic = 1;

		$duration = $duration . (string)floor(($part * 175 / $totalParts)) . "|";
		$queryResult->closeCursor();
	}

	$query = $query . "\", \"" . $duration . "\", 0, " . (string)$alcoholic . ", 0, 0);";
	$db->exec($query);
	unset($db);
	exec("functions/updateMixed");

	echo "<script type='text/javascript'>";
	echo "alert('Successfully added " . $_POST['drinkName'] . "')"; 
	echo "</script>";
	echo "<script>window.history.go(-1)</script>";
	die();
?>
