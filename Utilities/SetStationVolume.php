<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);

/*Correct usage of file:
	In order to set the volume at a station, call this file with: 
		?station=xxx&volume=xxx
*/
require_once(dirname(__FILE__) . '/QueueManager.php');
require_once(dirname(__FILE__) . "/Database.php");
require_once(dirname(__FILE__) . "/UpdateDrinkAvailability.php");

if (isset($_GET['station']) && isset($_GET['volume']))
{
	$station = $_GET['station'];
	$volume = $_GET['volume'];
	
	$database = new Database();
	$database->StartQuery()
		->update(Database::SingleTable)
		->set("volume", $volume)
		->where("station=" . $station)
		->execute();
	
	$updater = new UpdateDrinkAvailability($database);
	$updater->UpdateAllDrinks();
	
	echo "Success!";
}
else
{
	echo "SetStationVolume failed: invalid parameters.";
}
?>