<?php
require_once(dirname(__FILE__) . "/Database.php");
require_once(dirname(__FILE__) . "/../Orders/UpdateDrinkAvailability.php");

class QueueManager
{	
	function ClearOrder($whereStatement)
	{
		$database = new Database();
		$results = $database->StartQuery()
			->select('orderString')
			->from(Database::QueueTable, 'u')
			->where($whereStatement)
			->execute()
			->fetchColumn();
		
		var_dump($results);
		
		$volumes = explode('|', $results);
		$this->AddVolumesBackIn($volumes, $database);
		
		//Delete from queue
		$database->ExecuteSql("DELETE FROM queue WHERE $whereStatement");
	}
	
	function ClearOrderById($idToClear)
	{
		$this->ClearOrder("id = $idToClear");
	}
	
	function ClearOrderOnStation($station)
	{
		$this->ClearOrder("station = $station");
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