<?php
	$db = new PDO('sqlite:../FB.db');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); //So it will actually throw warnings!!
	
	$queues = $db->query("select * from queue");
	$numbOrders = count($queues->fetchAll());
	
	if ($numbOrders == 0)
	{
		$db->beginTransaction();
		
		$unsetString = "update single set station=-1, volume=0, proof=0;";
		$db->exec($unsetString);

		for ($i = 0; $i < 16; $i++)
		{
			$drink = $_POST['station' . $i];
			if ($drink == '0')
			{
				continue;
			}

			$vol = $_POST['volume' . $i];
			$prof = $_POST['proof' . $i];
			$queryString = "update single set station=" . $i . ", volume=" . $vol 
				. ", proof=" . $prof . " where name='" . $drink . "';";

			$db->exec($queryString);
		}

		$success = $db->commit();
		exec("functions/updateMixed");
		header("Location: AdminPortal.php?update=" . $success);
		die();
	}
	else
	{
		include "master/master_head.php";
		unset($_POST);
?>
		<p>Could not update database, there are existing orders in the queue.</p>
		<a href="AdminPortal.php">Go Back</a>
<?php
	}
?>
