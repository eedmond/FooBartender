<?php
        $db = new PDO('sqlite:FB.db');

	$drinkType = $_POST['drinkType'];
	$defaultImage = "mixeddefault";
	$query = "";

	if ($drinkType == "mixedDrink")
	{
		$defaultImage = "mixeddefault";
		$query = "SELECT * FROM mixed WHERE proof > 0 ORDER BY rating / numRatings DESC";
	}
	else if ($drinkType == "nonAlcoholic")
	{
		$defaultImage = "nonalcoholicdefault";
		$query = "SELECT * FROM mixed WHERE proof = 0 ORDER BY rating / numRatings DESC";
	}
	else if ($drinkType == "shot")
	{
		$defaultImage = "shotdefault";
		$query = "SELECT * FROM single WHERE station > -1 AND proof > 25 AND volume > 34";
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
		if ($i == 0 && $row['name'] == "Eric's Jamaican Surprise") {
			echo '<h3>Sorry, no drinks are available right meow</h3>';
			break;
		}

		if ($i < 2 && $row['name'] == "Eric's Jamaican Surprise")
			break;
			
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
		
		$ingredients = "\n";
		$totalProof = 0;
		$index = 0;
		if ($drinkType != "shot")
		{
			foreach ($ingred_list as $item)
			{
				$ingredients = $ingredients . $item . ": " . $durations[$index] . "mL\n";
				if ($drinkType == "mixedDrink" && $row['name'] != "Eric's Jamaican Surprise")
				{
					$thisProof = $db->prepare('SELECT proof FROM single WHERE name="' . $item . '"' );
					$thisProof->execute();
					$totalProof += $thisProof->fetchColumn()*$durations[$index]/175;
				}
				$index++;
			}
		}
		else
		{
			$totalProof = $row['proof'];
			$ingredients = "";
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
		echo '<a class="image full orderIcon"  id="', $drinkType, '" name="', $row['name'], "\n";
		if ($row['name'] == "Eric's Jamaican Surprise")
			echo "\n" . '“The mistake is thinking that there can be an antidote to the uncertainty.”' . "\n" . '― David Levithan, The Lover\'s Dictionary';
		else
			echo $ingredients;
		
		if ($row['name'] != "Eric's Jamaican Surprise")
		{
			if (($drinkType == "mixedDrink" || $drinkType == "shot"))
				echo "\nProof: " . number_format($totalProof, 1) . "\n";
			echo "\nRating: " . number_format($totalRating, 1) . "/10";
		}
		
		echo '" >';
			
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
