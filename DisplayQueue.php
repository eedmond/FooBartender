<?php

 $db = new PDO('sqlite:testdb.db');
        $getNames = $db->query("select * from queue");
        $queryResult = $getNames->fetchAll();
	$numb = count($queryResult);
?>
	<input type="button" onClick="javascript:$('#showQueue').toggle(50);" value="Show Queue" />
<?php
	
	echo '<div style="display:none;" id="showQueue">';
	echo '<p>Number of orders: ', $numb, '</p>';
	echo '<table>';
	if ($numb != 0)
		echo '<tr><th>KEY</th><th>Order String</th><th>Station</th>';

	foreach($queryResult as $row)
	{
		echo '<tr><td>', $row['PRIMARY KEY'], '</td><td>', $row['orderString'], '</td><td>', $row['station'], '</td></tr>'; 
	}
	
	echo '</table></div>';
?>
