<?php
	require_once "../Utilities/Database.php";
	
	$database = new Database();
			
	$drinkType = $_POST['drinkType'];
	$defaultImage = "mixeddefault";
	$query = $database->StartQuery();

	if ($drinkType == "mixedDrink")
	{
		$defaultImage = "mixeddefault";
		$query->select('*')
			->from(Database::MixedTable)
			->where('proof > 0')
			->orderBy('rating / numRatings', 'DESC');
	}
	else if ($drinkType == "nonAlcoholic")
	{
		$defaultImage = "nonalcoholicdefault";
		$query->select('*')
			->from(Database::MixedTable)
			->where('proof = 0')
			->orderBy('rating / numRatings', 'DESC');
	}
	else if ($drinkType == "shot")
	{
		$defaultImage = "shotdefault";
		$query->select('*')
			->from(Database::SingleTable)
			->where('proof > 25')
			->andwhere('volume > 34');
	}

	$getNames = $query->execute();
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
		
		$ingredients = "";
		$totalProof = 0;
		$index = 0;
		if ($drinkType != "shot")
		{
			foreach ($ingred_list as $item)
			{
				$ingredients = $ingredients . $item . ": " . $durations[$index] . "mL<br>";
				if ($drinkType == "mixedDrink" && $row['name'] != "Eric's Jamaican Surprise")
				{
					$thisProof = $database->StartQuery()
						->select('proof')
						->from(Database::SingleTable)
						->where('name="' . $item . '"' )
						->execute()
						->fetchColumn();
					$totalProof += $thisProof*$durations[$index]/175;
				}
				$index++;
			}
		}
		else
		{
			$totalProof = $row['proof'];
			$ingredients = "";
		}
		
		$totalRating = $rating / $numRatings;
		
		$image = strtolower($row['name']);
		$image = preg_replace("/\s+/", '', $image);
		$image = preg_replace("/'/", '', $image);

		if (!file_exists("../images/Drinks/" . $image . ".jpg"))
			$image = $defaultImage;

		echo '<div class="6u">';
		#														****** If this name is changed you MUST edit *******
		#														****** index.php submit functions            *******
		echo '<a class="image full orderIcon"  id="', $drinkType, '" name="<h1 align=center>', $row['name'], "</h1>";
		if ($row['name'] == "Eric's Jamaican Surprise")
			echo '“The mistake is thinking that there can be an antidote to the uncertainty.”' . "<br>" . '― David Levithan, The Lover\'s Dictionary';
		else
			echo $ingredients;
		
		if ($row['name'] != "Eric's Jamaican Surprise")
		{
			if (($drinkType == "mixedDrink" || $drinkType == "shot"))
			{
				if ($drinkType == "mixedDrink")
					echo "<br>";
				echo "Proof: " . number_format($totalProof, 0) . "<br>";
			}
			if ($drinkType != "shot")
			{
				if ($numRatings != 0)
					echo "<br>Rating: " . number_format($totalRating, 1) . "/10";
				else
					echo "<br>Be the first to rate it!";
			}
		}
		
		echo '" >';
			
		echo '<img src="../images/Drinks/', $image, '.jpg" alt="FixThis" />';
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
