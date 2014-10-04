<?php
	require_once(dirname(__FILE__) . '/../Utilities/Database.php');
	
	$database = new Database();

	//TODO: find a way to set this?
	//$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

	$result = $database->StartQuery()
		->select('*')
		->from(Database::Mixed)
		->where('name = "' . $_POST['drinkName'] .'"')
		->execute();

	if ($result->fetch(PDO::FETCH_NUM) != 0)
	{
?>
		<script type='text/javascript'>
			alert('<? echo $_POST['drinkName'] ?> already exists and was not added')
		</script>  
		<script>window.history.go(-1)</script>
<?php
		die();
	}

	$valuesArray = array();
	$valuesArray['name'] = $_POST['drinkName'];

	$totalParts = 0;
	$partsArray = array();

	for ($count = 0; $count < 16; $count++)
	{
		$drinkName = $_POST["text" . $count];
		$parts = $_POST["parts" . $count];

		if ($drinkName == "Add Drink" || $parts == 0) 
			continue;
		
		$totalParts += $parts;
		$partsArray[$drinkName] = $parts;
	}
	
	$valuesArray['ingredients'] = implode('|', $partsArray);

	$vals = insertMixed_collectVolumes($partsArray);
	$volumeArray = $vals[0];
	$alcoholic = $vals[1];
	
	
	$valuesArray['volume'] = implode('|', $volumeArray);
	$valuesArray['isOnTable'] = 0;
	$valuesArray['proof'] = $alcoholic;
	$valuesArray['numRatings'] = 0;
	$valuesArray['rating'] = 0;
	
	$database->StartQuery()
		->insert('mixed')
		->values($valuesArray)
		->execute();
	
	$drinkUpdater = new UpdateDrinkAvailability($database);
	$drinkUpdater->UpdateAllDrinks();	
?>
	<script type='text/javascript'>
		alert('Successfully added <? echo $_POST['drinkName']; ?>"')
	</script>
	<script>window.history.go(-1)</script>
<?php
	die();
	
	
	function insertMixed_collectVolumes($partsArray)
	{
		$alcoholic = 0;
		$volumeArray = array();
		foreach($partsArray as $name => $part)
		{
			//$queryResult = $db->query("SELECT proof FROM single WHERE name=\"" . $name . "\"");
			$proof = $database->StartQuery()
								->select('proof')
								->from(Database::SingleTable)
								->where('name = "' . $name . '"')
								->execute()
								->fetchAll(); //fetchAll(PDO::FETCH_COLUMN, 0)
								
			if ($proof[0] != 0)
				$alcoholic = 1;
			
			$currentVolume = (string)floor(($part * 175 / $totalParts));
			array_push($volumeArray, $currentVolume);
		}
		
		return array($volumeArray, $alcoholic);
	}
?>
