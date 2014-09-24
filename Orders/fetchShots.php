<?php
        $db = new PDO('sqlite:FB.db');
        $getNames = $db->query("select * from single WHERE proof>0");
        $queryResult = $getNames->fetchAll();
	
	$i = 0;
	foreach($queryResult as $row)
	{
		if ($i % 4 == 0)
			echo '<div class="8u">';
		if ($i % 2 == 0)
			echo '<div class="row no-collapse">';
		
		$image = strtolower($row['name']);
		$image = preg_replace('/\s+/', '', $image);

		echo '<div class="6u">';
		echo '<a class="image full mixdesc2" name="', $row['name'], '" >';
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
