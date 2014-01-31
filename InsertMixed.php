<?php
	$db = new PDO('sqlite:testdb.db');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

	$query = "INSERT INTO mixed values(\"" . $_POST['drinkName'] . "\", \"";
	$duration = "";
	$drinksAdded = 0;
	$totalVolume = 0;

	for ($count = 0; $count < 5; $count++)
	{
		$drinkName = $_POST["text" . $count];
		$volume = $_POST["vol" . $count];

		if ($drinkName == "0") continue;

		if ($drinksAdded != 0)
			$query = $query . "|";
		$drinksAdded++;

		$query = $query . $drinkName;

		$time = intval($volume*39/150);
		$timestr = (string)$time;
		$timestr = str_pad($timestr, 3, "0", STR_PAD_LEFT);


		$duration = $duration . $timestr;

		echo "duration: " . $duration;

		$totalVolume = $totalVolume + $volume;
	}

	if ($totalVolume <= 190)
	{
		$query = $query . "\", \"" . $duration . "\", 0);";
		$db->exec($query);
	}
?>
