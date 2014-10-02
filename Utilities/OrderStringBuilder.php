<?php

	require_once(dirname(__FILE__).'/Database.php');

	function BuildOrderString(&$namesToVolumesMap)
	{
		$database = new Database();
		return BuildOrderString($namesToVolumesMap, $database);
	}
	
	function BuildOrderString(&$namesToVolumesMap, &$database)
	{
		$volumesArray = array_fill(0, 16, 0);
		
		$queryBuilder = $database->StartQuery()
			->select('name', 'station')
			->from(Database::SingleTable)
			->where('false');
		
		foreach ($namesToVolumesMap as $name)
		{
			$queryBuilder->orWhere("name = $name");
		}
		
		$queryResult = $queryBuilder->execute();
		
		foreach ($queryResult as $row)
		{
			$componentNames = $row['name'];
			$station = $row['station'];
			$volumeToPour = $namesToVolumesMap[$componentName];
			
			$volumesArray[$station] = $volumeToPour;
		}
		
		$orderString = implode('|', $volumesArray);
		return $orderString;
	}
?>