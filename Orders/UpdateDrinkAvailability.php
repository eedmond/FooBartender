<?php
	require_once(dirname(__FILE__).'/../Utilities/Database.php');
	
	class UpdateDrinkAvailability
	{
		private $database;
		private $drinkQuery;
		private $ingredientsSeenList = array();
		
		function __construct($inDatabase = NULL)
		{
			if (!$inDatabase)
			{
				$inDatabase = new Database();
			}
			
			$this->database = $inDatabase;
		}
		
		public function UpdateAvailableDrinks()
		{
			$this->drinkQuery = $this->QueryAvailableDrinks();
			$this->CheckEachDrinkFromQuery();
		}
		
		public function UpdateAllDrinks()
		{
			$this->drinkQuery = $this->QueryAllDrinks();
			$this->CheckEachDrinkFromQuery();
		}
		
		private function QueryAllDrinks()
		{
			$this->drinkQuery = $this->database->StartQuery()
				->select('name', 'ingredients', 'volume', 'isOnTable')
				->from(Database::MixedTable, 'u')
				->execute()
				->fetchAll();
			
			return $this->drinkQuery;
		}
		
		private function QueryAvailableDrinks()
		{
			$this->drinkQuery = $this->database->StartQuery()
				->select('name', 'ingredients', 'volume', 'isOnTable')
				->from(Database::MixedTable, 'u')
				->where('isOnTable > 0')
				->execute()
				->fetchAll();
			
			return $this->drinkQuery;
		}
		
		private function CheckEachDrinkFromQuery()
		{
			foreach ($this->drinkQuery as $row)
			{
				$previousAvailability = $row['isOnTable'];
				$actualAvailability = $previousAvailability;
				
				if ($this->IsDrinkAvailable($row))
				{
					$actualAvailability = 1;
				}
				else
				{
					$actualAvailability = 0;
				}
				
				if ($actualAvailability != $previousAvailability)
				{
					$name = $row['name'];
					$this->SetAvailability($name, $actualAvailability);
				}
			}
		}
		
		private function IsDrinkAvailable($row)
		{
			$componentNames = explode('|', $row['ingredients']);
			$componentNames = array_filter($componentNames);
			$volumeArray = explode('|', $row['volume']);
			$volumeArray = array_filter($volumeArray);
			
			$namesToVolumeMap = array_combine($componentNames, $volumeArray);
			
			$queryBuilder = $this->database->StartQuery()
				->select('name', 'volume', 'station')
				->from(Database::SingleTable, 'u');
			
			$makeQuery = false;
			foreach ($componentNames as $name)
			{
				if (isset($this->ingredientsSeenList[$name]))
				{
					if ($this->ingredientsSeenList[$name] < $namesToVolumeMap[$name])
					{
						return false;
					}
				}
				else
				{
					$makeQuery = true;
					$queryBuilder->orWhere("name = \"$name\"");
				}
			}
			
			if (!$makeQuery)
			{
				return true;
			}
			
			$queryResult = $queryBuilder->execute()
				->fetchAll();
			
			$drinkAvailable = true;
			foreach ($queryResult as $ingredientRow)
			{
				$ingredientName = $ingredientRow['name'];
				$currentVolume = $ingredientRow['volume'];
				$station = $ingredientRow['station'];
				if ($station < 0)
				{
					$currentVolume = 0;
				}
				$this->ingredientsSeenList[$ingredientName] = $currentVolume;
				
				if (!$drinkAvailable)
				{
					continue;
				}
				
				$desiredVolume = $namesToVolumeMap[$ingredientName];
				
				if ($currentVolume < $desiredVolume)
				{
					$drinkAvailable = false;
				}
			}
			
			return $drinkAvailable;
		}
		
		private function SetAvailability($name, $availability)
		{
			$this->database->StartQuery()
				->update(Database::MixedTable)
				->set('isOnTable', $availability)
				->where("name=\"$name\"")
				->execute();
		}
	}
?>