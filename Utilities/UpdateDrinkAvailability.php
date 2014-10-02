<?php
	require_once(dirname(__FILE__).'/Database.php');
	
	class UpdateDrinkAvailability
	{
		private $database;
		private $drinkQuery;
		private $ingredientsSeenList = array();
		
		const VOLUME_NOT_SET == -100;
		
		function __construct()
		{
			$database = new Database();
		}
		
		function __construct(&$inDatabase)
		{
			$database = $inDatabase;
		}
		
		public function UpdateAvailableDrinks()
		{
			$drinkQuery = QueryAvailableDrinks();
			CheckEachDrinkFromQuery($drinkQuery);
		}
		
		public function UpdateAllDrinks()
		{
			$drinkQuery = QueryAllDrinks();
			CheckEachDrinkFromQuery($drinkQuery);
		}
		
		private function QueryAllDrinks()
		{
			$drinkQuery = $database->StartQuery()
				->select('name', 'ingredients', 'volume', 'isOnTable')
				->from(Database::MixedTable)
				->execute()
				->fetchAll();
			
			return $drinkQuery;
		}
		
		private function QueryAvailableDrinks()
		{
			$drinkQuery = $database->StartQuery()
				->select('name', 'ingredients', 'volume', 'isOnTable')
				->from(Database::MixedTable)
				->where('isOnTable > 0')
				->execute()
				->fetchAll();
			
			return $drinkQuery;
		}
		
		private function CheckEachDrinkFromQuery(&$drinkQuery)
		{
			foreach ($drinkQuery as $row)
			{
				$previousAvailability = $row['isOnTable'];
				$actualAvailability = $previousAvailability;
				
				try
				{
					VerifyDrinkAvailable($row);
					$actualAvailability = 1;
				}
				catch (Exception $e)
				{
					$actualAvailability = 0;
				}
				
				if ($actualAvailability != $previousAvailability)
				{
					$name = $row['name'];
					SetAvailability($name, $actualAvailability);
				}
			}
		}
		
		private function VerifyDrinkAvailability(&$row)
		{
			$componentNames = explode('|', $row['ingredients');
			$volumeArray = explode('|', $row['volume']);
			
			$namesToVolumeMap = array_combine($componentNames, $volumeArray);
			
			$queryBuilder = $database->StartQuery()
				->select('name', 'volume')
				->from(Database::SingleTable)
				->where('false');
			
			foreach ($componentNames as $name)
			{
				if (isset($ingredientsSeenList[$name]))
				{
					if ($ingredientsSeenList[$name] < $namesToVolumeMap[$ingredientName])
					{
						throw new Exception();
					}
				}
				else
				{
					$ingredientsSeenList[$name] = self::VOLUME_NOT_SET;
					$queryBuilder->orWhere("name = $name");
				}
			}
			
			$queryResult = $queryBuilder->execute()
				->fetchAll();
			
			foreach ($queryResult as $ingredientRow)
			{
				$ingredientName = $ingredientRow['name'];
				$currentVolume = $ingredientRow['volume'];
				$desiredVolume = $namesToVolumeMap[$ingredientName];
				
				if ($ingredientsSeenList[$name] == self::VOLUME_NOT_SET)
				{
					$ingredientsSeenList[$name] = $currentVolume;
				}
				
				if (currentVolume < desiredVolume)
				{
					throw new Exception();
				}
			}
		}
		
		private function SetAvailability(&$name, &$availability)
		{
			$database->StartQuery()
				->update(Database::MixedTable)
				->set('isOnTable', 0)
				->execute();
		}
	}
?>