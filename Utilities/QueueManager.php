<?php
require_once(dirname(__FILE__) . "/Database.php");
require_once(dirname(__FILE__) . "/UpdateDrinkAvailability.php");

class QueueManager
{
	function ClearOrderOnStation($station)
	{
		$database = new Database();
		$results = $database->StartQuery()
			->select('orderString')
			->from(Database::QueueTable, 'u')
			->where('station = '. $station)
			->execute()
			->fetchColumn();
		
		var_dump($results);
		
		$volumes = explode('|', $results);
		$this->AddVolumesBackIn($volumes, $database);
		
		//Delete from queue
		$database->ExecuteSql("DELETE from queue where station = ". $station);
		
		$database->StartQuery()
			->update(Database::StationsTable)
			->set('amount', 0)
			->where('station = $station')
			->execute();
			
		$database->StartQuery()
			->update(Database::StationsTable)
			->set('amount', 0)
			->where('station = ' . $station)
			->execute();

	}

	function ClearAllQueue()
	{
		$database = new Database();
		$results = $database->StartQuery()
			->select('station', 'orderString')
			->from(Database::QueueTable, 'u')
			->execute()
			->fetchAll();
		
		$volumes = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
		$count = count($volumes);
		
		foreach ($results as $row)
		{
			$parts = explode('|', $row['orderString']);
			
			if (count($parts) != $count)
				throw new Exception("order string is the wrong size: " . $row['orderString']);
			
			for($i = 0; $i < $count; $i++)
			{
				$volumes[$i] += $parts[$i];
			}
		}
		
		$this->AddVolumesBackIn($volumes, $database);
		
		//Delete from queue
		$database->ExecuteSql("DELETE from queue");
			
		$database->StartQuery()
			->update(Database::StationsTable)
			->set('amount', 0)
			->execute();
	}

	private function AddVolumesBackIn($addVolumesArray, $database = null)
	{	
		if ($database == null)
			$database = new Database();
			
		$index = -1;
		foreach ($addVolumesArray as $newVolume)
		{ 
			$index++;
			
			if ($newVolume == 0)
				continue;
		
			$query = $database->StartQuery()
				->update(Database::SingleTable)
				->set('volume', 'volume + ' . $newVolume)
				->where('station = ' . $index)
				->execute();

		}
		
		$updateMixed = new UpdateDrinkAvailability($database);
		$updateMixed->UpdateAllDrinks();
	}
	
}
?>