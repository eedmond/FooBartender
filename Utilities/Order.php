<?php
//TODO: make functionality to also get a random NON-alcoholic drink for Jamaican Surprise
// ctrl+f 'verify' to look for places to verify code

/* Standard uses:
  mixed drink:
	$order = new Order();
	$order->Mixed()
		->Name('Rum and Coke')
		->Place();
	
  custom order:
	$order = new Order();
	$order->Custom()
		->SetOrderData({map of component names to volumes}) i.e. {"Coke" => 120, "Rum" => 35};
		->Place();

  shot:
	$order = new Order();
	$order->Shot()
		->Name('Eric\'s Jamaican Surprise')
		->Place();

Place() returns a string of the result,
it also does NOT call updateMixed, but
these can both be changed
*/

	require_once(dirname(__FILE__).'/Database.php');
	require_once(dirname(__FILE__).'/OrderStringBuilder.php');

	class Order
	{
		private $database;
		private $drinkName = NULL;
		private $orderState = NULL;
		private $namesToVolumesMap = NULL;
		private $resultString = NULL;
		
		const Mixed = "Mixed";
		const Custom = "Custom";
		const Shot = "Shot";
		
		public function __construct()
		{
			$database = new Database();
		}
		
		public function __construct($inputDatabase)
		{
			$database = $inputDatabase;
		}
		
		public function Mixed()
		{
			$orderState = Order::Mixed;
		}
		
		public function Custom()
		{
			$oderState = Order::Custom;
		}
		
		public function Shot()
		{
			$orderState = Order::Shot;
		}
		
		public function Name($input)
		{
			$drinkName = $input;
		}
		
		public function SetOrderData($inputMap)
		{
			$namesToVolumesMap = $inputMap;
		}
		
		public function Place()
		{
			try
			{
				BeginOrderByState();
				VerifyDrinkAvailable();
				$station = FindOpenStation();
				PlaceInQueue();
				$resultString = "Your order has been placed on station $station";
			}
			catch (Exception $e)
			{
				$resultString = 'Order failed with error: ' . $e->getMessage(). ' Please try again.';
			}
			
			return $resultString;
		}
		
		// verify cleanliness... should these 3 order types be different classes?
		private function BeginOrderByState()
		{
			switch ($orderState)
			{
				case Order::Mixed:
					StartMixedOrder();
					break;

				case Order::Shot:
					StartShotOrder();
					break;

				case Order::Custom:
					StartCustomOrder();
					break;
					
				default:
					throw new Exception("No order state set.");
			}
		}
		
		private function StartMixedOrder()
		{
			if (!$drinkName)
			{
				throw new Exception("Ordering a mixedDrink requires a name.");
			}
			
			if (strstr($drinkName, 'Eric\'s Jamaican Surprise') == 0)
			{
				$drinkName = GetRandomMixedDrink();
			}
			
			GetComponentsOfDrink();
		}
		
		private function StartShotOrder()
		{
			if (!$drinkName)
			{
				throw new Exception('Ordering a shot requires a name.');
			}
			
			if (strstr($drinkName, 'Eric\'s Jamaican Surprise') == 0)
			{
				GetRandomShot();
			}
			
			$namesToVolumesMap = [$drinkName => 35];
		}
		
		private function StartCustomOrder()
		{
			if (!$namesToVolumesMap)
			{
				throw new Exception('Order data was not set for custom order.');
			}
		}
		
		private function GetComponentsOfDrink()
		{			
			//SELECT name, ingredients, volume FROM mixed WHERE name="{name}"
			$queryResult = $database->StartQuery()
				->select('ingredients', 'volume')
				->from(Database::MixedTable)
				->where("name=$drinkName")
				->execute()
				->fetchAll();
			
			// Verify correct use of empty() to determine no results
			if (empty($queryResult))
			{
				throw new Exception('GetComponentsOfDrink query failed.');
			}
			
			CreateMapFromQuery($queryResult);
		}
		
		private function CreateMapFromQuery($queryResult)
		{
			$componentList = $queryResult[0]['ingredients'];
			$componentListArray = explode('|', $componentList);
			
			$volumeList = $queryResult[0]['volume'];
			$volumeListArray = explode('|', $volumeList);
			
			$namesToVolumesMap = array_combine($componentListArray, $volumeListArray);
		}
		
		private function GetRandomMixedDrink()
		{
			//SELECT name, ingredients, volume FROM mixed WHERE isOnTable>0 AND proof=0/1 ORDER BY RANDOM() LIMIT 1
			$queryResult = $database->StartQuery()
				->select('ingredients', 'volume')
				->from(Database::MixedTable)
				->where('isOnTable > 0')
				->andWhere('proof = 1')
				->orderBy('RANDOM()')
				->setMaxResults(1)
				->execute()
				->fetchAll();
			
			// Verify correct use of empty() to determine no results
			if (empty($queryResult))
			{
				throw new Exception('Query returned no mixed drinks.');
			}
			
			// verify syntax
			$drinkName = $queryResult[0]['name'];
		}
		
		private function GetRandomShot()
		{
			//SELECT name FROM single WHERE station>-1 AND volume>=35 ORDER BY Random() LIMIT 1
			$queryResult = $database->StartQuery()
				->select('name')
				->from(Database::SingleTable)
				->where('station > -1')
				->andWhere('volume >= 35')
				->orderBy('RANDOM()')
				->setMaxResults(1)
				->execute()
				->fetchAll();
			
			// Verify correct use of empty() to determine no results
			if (empty($queryResult))
			{
				throw new Exception('Query returned no shots.');
			}
			
			// verify syntax
			$drinkName = $queryResult[0]['name'];
		}
		
		private function VerifyDrinkAvailable()
		{
			//SELECT name, station, volume FROM single WHERE name="{name}" OR name="{name}"...
			$queryBuilder = $database->StartQuery()
				->select('name', 'station', 'volume')
				->from(Database::SingleTable);
			
			// verify it works to START with an orWhere, could always add where('true') to start
			foreach ($namesToVolumesMap as $componentName => $volume)
			{
				$queryBuilder->orWhere("name = $componentName");
			}
			
			$queryResults = $queryBuilder->execute()
				->fetchAll();
			
			foreach ($queryResults as $row)
			{
				$componentName = $row['name'];
				$volumeToPour = $namesToVolumesMap[$componentName];
				$currentVolume = $row['volume'];
				$station = $row['station'];
				
				if ($station < 0 || $currentVolume < $volumeToPour)
				{
					// verify syntax of double backslash to end var name in string
					throw new Exception("Only $currentVolume\\mL of $componentName remaining. $volumeToPour\\ml required.");
				}
			}
			
			RemoveOrderFromTable($queryResults);
		}
		
		private function RemoveOrderFromTable($queryResults)
		{
			//UPDATE single SET volume=volume-{volumeToPour} WHERE station={station}
			foreach ($queryResults as $row)
			{
				$componentName = $row['name'];
				$volumeToPour = $namesToVolumesMap[$componentName];
				$station = $row['station'];
				
				$database->StartQuery()
					->update(Database::SingleTable)
					->set('volume', "volume - $volumeToPour")
					->where("station = $station")
					->execute();
			}
		}
		
		private function FindOpenStation()
		{
			//SELECT station FROM stations WHERE amount=0 ORDER BY Random() LIMIT 1
			$queryResult = $database->StartQuery()
				->select(Database::StationsTable)
				->where('amount = 0')
				->orderBy('Random()')
				->setMaxResults(1)
				->execute()
				->fetchColumn();
			
			if (!$queryResult)
			{
				throw new Exception('Queue is currently full.');
			}
			
			$openStation = $queryResult;
			
			OccupyStation($openStation);
			return $openStation;
		}
		
		private function OccupyStation($openStation)
		{
			//UPDATE stations SET amount=amount+1 WHERE station={station}
			$database->StartQuery()
				->update(Database::StationsTable)
				->set('amount', 'amount + 1')
				->where('station = ' . $openStation)
				->execute();
		}
		
		private function PlaceInQueue($station)
		{
			//INSERT INTO queue (orderString, station) values("~", "station")
			$orderStringBuilder = new OrderStringBuilder($namesToVolumesMap, $database);
			$orderString = $orderStringBuilder->GetOrderString();
			
			$database->StartQuery()
				->insert(Database::QueueTable)
				->setValue('orderString', '?')
				->setValue('station', '?')
				->setParameter(0, $orderString)
				->setParameter(1, $station)
				->execute();
		}
	}
?>