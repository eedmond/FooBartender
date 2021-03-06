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

	require_once(dirname(__FILE__).'/../Utilities/Database.php');
	require_once(dirname(__FILE__).'/OrderStringBuilder.php');
	require_once(dirname(__FILE__).'/UpdateDrinkAvailability.php');

	class Order
	{
		private $database;
		private $drinkName = NULL;
		private $orderState = NULL;
		private $amountToPourInMl = 175;
		private $namesToVolumesMap = NULL;
		private $singleComponentsQueryResults = NULL;
		private $resultString = NULL;
		private $wasOrderSuccessful = false;
		private $orderId;
		
		//private static $updateDrinkLock = Mutex::create();
		//private static $grabStationLock = Mutex::create();
		
		const Mixed = "Mixed";
		const Custom = "Custom";
		const Shot = "Shot";
		
		public function __construct($inputDatabase = NULL)
		{
			if (!$inputDatabase)
			{
				$inputDatabase = new Database();
			}
			$this->database = $inputDatabase;
		}
		
		public function Mixed()
		{
			$this->orderState = Order::Mixed;
			return $this;
		}
		
		public function Custom()
		{
			$this->orderState = Order::Custom;
			return $this;
		}
		
		public function Shot()
		{
			$this->orderState = Order::Shot;
			return $this;
		}
		
		public function Name($input)
		{
			$this->drinkName = $input;
			return $this;
		}
		
		public function SetAmountToPour($inputAmount)
		{
			$this->amountToPourInMl = $inputAmount;
			return $this;
		}
		
		public function SetOrderData($inputMap)
		{
			$this->namesToVolumesMap = $inputMap;
			return $this;
		}
		
		public function Place()
		{
			try
			{
				$this->BeginOrderByState();
				$this->SetOrderSize();
				//Mutex::lock($this->updateDrinkLock);
				$this->VerifyDrinkAvailable();
				//Mutex::unlock($this->updateDrinkLock);
				//Mutex::lock($this->grabStationLock);
				$stationOfOrder = $this->FindOpenStation();
				$this->PlaceInQueue($stationOfOrder);
				$this->GetQueueId($stationOfOrder);
				$this->RemoveOrderFromTable();
				//Mutex::unlock($this->grabStationLock);
				$this->resultString = "Your order has been placed on station $stationOfOrder";
				$this->wasOrderSuccessful = true;
			}
			catch (Exception $e)
			{
				$this->resultString = 'Order failed with error: ' . $e->getMessage(). ' Please try again.';
				$this->wasOrderSuccessful = false;
			}

			$updater = new UpdateDrinkAvailability();
			$updater->UpdateAvailableDrinks();
		}
		
		public function GetResultString()
		{
			return $this->resultString;
		}
		
		public function WasSuccessful()
		{
			if ($this->wasOrderSuccessful)
			{
				return '1';
			}
			
			return '0';
		}
		
		public function DrinkOrdered()
		{
			return $this->drinkName;
		}
		
		public function GetOrderId()
		{
			return $this->orderId;
		}
		
		// verify cleanliness... should these 3 order types be different classes?
		private function BeginOrderByState()
		{
			switch ($this->orderState)
			{
				case Order::Mixed:
					$this->StartMixedOrder();
					break;

				case Order::Shot:
					$this->StartShotOrder();
					break;

				case Order::Custom:
					$this->StartCustomOrder();
					break;
					
				default:
					throw new Exception("No order state set.");
			}
		}
		
		private function StartMixedOrder()
		{			
			if (!$this->drinkName)
			{
				throw new Exception("Ordering a mixedDrink requires a name.");
			}
			
			if (strstr($this->drinkName, 'Eric\'s Jamaican Surprise'))
			{
				if (strstr($this->drinkName, 'Non-Alcoholic'))
				{
					$this->drinkName = $this->GetRandomNonAlcoholicDrink();
				}
				else
				{
					$this->drinkName = $this->GetRandomMixedDrink();
				}
			}
			
			$this->GetComponentsOfDrink();
		}
		
		private function StartShotOrder()
		{
			if (!$this->drinkName)
			{
				throw new Exception('Ordering a shot requires a name.');
			}
			
			if (strstr($this->drinkName, 'Eric\'s Jamaican Surprise'))
			{
				$this->drinkName = $this->GetRandomShot();
			}
			
			$this->namesToVolumesMap = [$this->drinkName => 35];
		}
		
		private function StartCustomOrder()
		{
			if (!$this->namesToVolumesMap)
			{
				throw new Exception('Order data was not set for custom order.');
			}
		}
		
		private function GetComponentsOfDrink()
		{			
			//SELECT name, ingredients, volume FROM mixed WHERE name="{name}"
			$queryResult = $this->database->StartQuery()
				->select('ingredients', 'volume')
				->from(Database::MixedTable, 'u')
				->where('name="'.$this->drinkName.'"')
				->execute()
				->fetchAll();
			
			// Verify correct use of empty() to determine no results
			if (empty($queryResult))
			{
				throw new Exception('GetComponentsOfDrink query failed for ' . $this->drinkName . '.');
			}
			
			$this->CreateMapFromQuery($queryResult);
		}
		
		private function CreateMapFromQuery($queryResult)
		{
			$componentList = $queryResult[0]['ingredients'];
			$componentListArray = explode('|', $componentList);
			$componentListArray = array_filter($componentListArray);
			
			$volumeList = $queryResult[0]['volume'];
			$volumeListArray = explode('|', $volumeList);
			$volumeListArray = array_filter($volumeListArray);
			
			$this->namesToVolumesMap = array_combine($componentListArray, $volumeListArray);
		}
		
		private function GetRandomNonAlcoholicDrink()
		{
			return $this->GetRandomMixedDrink('=');
		}
		
		private function GetRandomMixedDrink($proofSymbol = '>')
		{
			//SELECT name, ingredients, volume FROM mixed WHERE isOnTable>0 AND proof=0/1 ORDER BY RANDOM() LIMIT 1
			$queryResult = $this->database->StartQuery()
				->select('name')
				->from(Database::MixedTable, 'u')
				->where('isOnTable > 0')
				->andWhere("proof $proofSymbol 0")
				->orderBy('RANDOM()')
				->setMaxResults(1)
				->execute()
				->fetchColumn();
			
			// Verify correct use of empty() to determine no results
			if (!$queryResult)
			{
				throw new Exception('Query returned no mixed drinks.');
			}

			return $queryResult;
		}
		
		private function GetRandomShot()
		{
			//SELECT name FROM single WHERE station>-1 AND volume>=35 ORDER BY Random() LIMIT 1
			$queryResult = $this->database->StartQuery()
				->select('name')
				->from(Database::SingleTable, 'u')
				->where('station > -1')
				->andWhere('volume >= 35')
				->andWhere('proof > 10')
				->orderBy('RANDOM()')
				->setMaxResults(1)
				->execute()
				->fetchColumn();
			
			// Verify correct use of empty() to determine no results
			if (!$queryResult)
			{
				throw new Exception('Query returned no shots.');
			}
			
			// verify syntax
			return $queryResult;
		}
		
		private function SetOrderSize()
		{
			foreach ($this->namesToVolumesMap as $key => $volume)
			{
				$this->namesToVolumesMap[$key] *= $this->amountToPourInMl / 175;
				$this->namesToVolumesMap[$key] = ceil($this->namesToVolumesMap[$key]);
			}
		}
		
		private function VerifyDrinkAvailable()
		{
			//SELECT name, station, volume FROM single WHERE name="{name}" OR name="{name}"...
			$queryBuilder = $this->database->StartQuery()
				->select('name', 'station', 'volume')
				->from(Database::SingleTable, 'u');
			
			// verify it works to START with an orWhere, could always add where('true') to start
			foreach ($this->namesToVolumesMap as $componentName => $volume)
			{
				$queryBuilder->orWhere("name = \"$componentName\"");
			}
			
			$this->singleComponentsQueryResults = $queryBuilder->execute()
				->fetchAll();
			
			foreach ($this->singleComponentsQueryResults as $row)
			{
				$componentName = $row['name'];
				$volumeToPour = $this->namesToVolumesMap[$componentName];
				$currentVolume = $row['volume'];
				$station = $row['station'];
				
				if ($station < 0 || $currentVolume < $volumeToPour)
				{
					// verify syntax of double backslash to end var name in string
					throw new Exception("Only $currentVolume mL of $componentName remaining. $volumeToPour mL required.");
				}
			}
		}
		
		private function RemoveOrderFromTable()
		{
			//UPDATE single SET volume=volume-{volumeToPour} WHERE station={station}
			foreach ($this->singleComponentsQueryResults as $row)
			{
				$componentName = $row['name'];
				$volumeToPour = $this->namesToVolumesMap[$componentName];
				$station = $row['station'];
				
				$this->database->StartQuery()
					->update(Database::SingleTable)
					->set('volume', "volume - $volumeToPour")
					->where("station = $station")
					->execute();
			}
		}
		
		private function FindOpenStation()
		{
			//SELECT station FROM queue
			$queryResult = $this->database->StartQuery()
				->select('station')
				->from(Database::QueueTable, 'u')
				->execute();

			$stationIndices = array(0, 1, 2, 3, 4, 5, 6, 7);

			foreach ($queryResult as $row)
			{
				$occupiedStation = $row['station'];
				$stationIndices[$occupiedStation] = -1;
			}
			
			function testOccupied($var)
			{
				return $var != -1;
			}
			
			$stationIndices = array_filter($stationIndices, "testOccupied");
			if (empty($stationIndices))
			{
				throw new Exception('Queue is currently full.');
			}	

			$openStation = array_rand($stationIndices);
			
			return $openStation;
		}
		
		private function PlaceInQueue($station)
		{
			//INSERT INTO queue (orderString, station) values("~", "station")
			$orderString = BuildOrderString($this->namesToVolumesMap, $this->database);
			
			$sql = "INSERT INTO queue (orderString, station) values(\"$orderString\", $station)";
			
			$this->database->ExecuteSql($sql);
			
			/*$this->database->StartQuery()
				->insert(Database::QueueTable)
				->setValue('orderString', '?')
				->setValue('station', '?')
				->setParameter(0, $orderString)
				->setParameter(1, $station)
				->execute();*/
		}
		
		private function GetQueueId($stationOfOrder)
		{
			// SELECT ID from queue where station = $stationOfOrder
			$this->orderId = $this->database->StartQuery()
				->select('ID')
				->from(Database::QueueTable, 'u')
				->where("station = $stationOfOrder")
				->execute()
				->fetchColumn();
		}
	}
?>