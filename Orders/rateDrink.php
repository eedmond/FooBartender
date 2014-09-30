<?php
	session_start();
	
	if (SettingsInitialized())
	{
		require_once(dirname(__FILE__).'/Database.php');
		
		$database = new Database();
		$rating = $_POST['rating'];
		$drinkName = $_SESSION['drinkName'];
		
		$database->StartQuery()
			->update(Database::MixedTable)
			->set('numRatings', 'numRatings + 1')
			->set('rating', 'rating + ' . $rating)
			->where('name = ' . $drinkName)
			->execute();
	}
	
	function SettingsInitialized()
	{
		return (isset($_POST['rating'])
			&& isset($_SESSION['drinkName']);
	}
?>