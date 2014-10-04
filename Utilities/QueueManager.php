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
			->where('station = $station')
			->execute()
			->fetchColumn();
			
		$volumes = explode('|', $results[0]);
		$this->AddVolumesBackIn($volumes, $database);
		
		//Delete from queue
		$database->ExecuteSql("DELETE from queue where station = ". $station);
		
		$database->StartQuery()
			->update(Database::StationsTable)
			->set('amount', 0)
			->where('station = $station')
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
		
		foreach ($results as $row)
		{
			$parts = explode('|', $row['orderString']);
			$volumes += $parts;
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
		echo "Starting to Add Volumes!";
		
		if ($database == null)
			$database = new Database();
			
		$index = 0;
		foreach ($addVolumesArray as $newVolume)
		{ 
			echo "Adding volume: " . $newVolume;
			
			if ($newVolume == 0)
				continue;
		
			$database->StartQuery()
				->update(Database::SingleTable)
				->set('volume', 'volume + ' . $newVolume)
				->where('station = ' . $i)
				->execute();

			$index++;
		}
		
		$updateMixed = new UpdateDrinkAvailability($database);
		$updateMixed->UpdateAllDrinks();
	}
	
}
?>