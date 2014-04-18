<?php
        $db = new PDO('sqlite:FB.db');

	$drinkType = $_POST['drinkType'];
	$defaultImage = "mixeddefault";
	$query = "";

	if ($drinkType == "mixedDrink")
	{
		$defaultImage = "mixeddefault";
		$query = "SELECT * FROM mixed WHERE proof > 0";
	}
	else if ($drinkType == "nonAlcoholic")
	{
		$defaultImage = "nonalcoholicdefault";
		$query = "SELECT * FROM mixed WHERE proof = 0";
	}
	else if ($drinkType == "shot")
	{
		$defaultImage = "shotdefault";
		$query = "SELECT * FROM single WHERE station > -1 AND proof > 0 AND volume > 34";
	}

	$getNames = $db->query($query);
	$queryResult = $getNames->fetchAll();
	
	$surprise = [
		"name" => "Eric's Jamaican Surprise",
		"isOnTable" => "1",
	];
	
	array_push($queryResult, $surprise);
	
	$i = 0;
	foreach($queryResult as $row)
	{
		if ($drinkType != "shot" && $row['isOnTable'] == 0)
			continue;

		if ($i % 4 == 0)
			echo '<div class="8u">';
		if ($i % 2 == 0)
			echo '<div class="row no-collapse">';
			
		$ingred_string = $row['ingredients'];
		$duration_list = $row['volume'];
		$ingred_list = explode("|", $ingred_string);
		$durations = explode("|", $duration_list);
		$numRatings = $row['numRatings'];
		$rating = $row['rating'];
		
		//Remove empty elements in case durations starts with ||
		//while (strlen($durations[0]) == 0) array_shift($durations);
		$ingredients = "";
		$index = 0;
		foreach ($ingred_list as $item)
		{
			$ingredients = $ingredients . $item . ": " . $durations[$index++] . "mL\t";
		}
		
		if ($numRatings == 0) {
			$totalRating = 0;
		}
		else {
			$totalRating = $rating / $numRatings;
		}
		
		$image = strtolower($row['name']);
		$image = preg_replace("/\s+/", '', $image);
		$image = preg_replace("/'/", '', $image);

		if (!file_exists("images/Drinks/" . $image . ".jpg"))
			$image = $defaultImage;

		echo '<div class="6u">';
		echo '<a class="image full orderIcon"  id="', $drinkType, '" name="', $row['name'] . " Contains:    " . $ingredients . $totalRating . "/10", '" >';
		echo '<img src="images/Drinks/', $image, '.jpg" alt="FixThis" />';
		echo '</a>';
		echo '</div>';
		
		if ($i % 2 == 1)
			echo '</div>';
		if ($i % 4 == 3)
			echo '</div>';
		$i++;
	}	
	if ($i % 2 != 0)  //The last div end did not triggers
		echo '</div>';
	if ($i % 4 != 0)
		echo '</div>'; 
?>
