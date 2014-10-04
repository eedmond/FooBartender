<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);

/*Correct usage of file:
	To clear the whole queue: call this file without any query string values
	To clear a particular order: call this file with a query string "?station=xxx"
*/
require_once(dirname(__FILE__) . '/QueueManager.php');

$manager = new QueueManager();
if (isset($_GET['station']))
{
	$station = $_GET['station'];
	
	if ($station < 0 || $station > 15)
		return; //Fail silently for now...
	
	$manager->ClearOrderOnStation($station);
}
else
{
	$manager->ClearAllQueue();
}


?>