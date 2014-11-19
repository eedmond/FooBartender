<?php

	require_once(dirname(__FILE__).'/../Utilities/Database.php');
	
	function BuildOrderString($namesToVolumesMap, $database = NULL)
	{
		if (!$database)
		{
			$database = new Database();
		}
		
		$volumesArray = array_fill(0, 16, 0);
		
		$queryBuilder = $database->StartQuery()
			->select('name', 'station')
			->from(Database::SingleTable, 'u');
		
		foreach ($namesToVolumesMap as $name => $volume)
		{
			$queryBuilder->orWhere("name = \"$name\"");
		}
		
		$queryResult = $queryBuilder->execute();
		
		foreach ($queryResult as $row)
		{
			$componentName = $row['name'];
			$station = $row['station'];
			$volumeToPour = $namesToVolumesMap[$componentName];
			
			$volumesArray[$station] = $volumeToPour;
		}
		
		$orderString = implode('|', $volumesArray);
		return $orderString;
	}
?>