<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
	session_start();

	if (SettingsInitialized())
	{
		require_once(dirname(__FILE__).'/../Utilities/QueueManager.php');
		
		$idToClear = $_SESSION['orderId'];
		$queueManager = new QueueManager();
		$queueManager->ClearOrderById($idToClear);
		$_SESSION['orderCleared'] = '1';
	}
	
	function SettingsInitialized()
	{
		return (isset($_POST['deleteSessionOrder'])
			&& isset($_SESSION['orderId']));
	}
?>