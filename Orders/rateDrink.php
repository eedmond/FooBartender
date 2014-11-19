<?php
	session_start();

	if (SettingsInitialized())
	{
		require_once(dirname(__FILE__).'/../Utilities/Database.php');
		$database = new Database();
		$rating = $_POST['rating'];
		$drinkName = $_SESSION['drinkName'];
		
		$database->StartQuery()
			->update(Database::MixedTable)
			->set('numRatings', 'numRatings + 1')
			->set('rating', "rating + $rating")
			->where("name = \"$drinkName\"")
			->execute();
		
		$_SESSION['drinkRated'] = '1';
	}
	
	function SettingsInitialized()
	{
		return (isset($_POST['rating'])
			&& isset($_SESSION['drinkName']));
	}
?>