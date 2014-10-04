<?php
	//This function does:
	//	1. Places the order if it hasn't already been done
	//	2. If the order has been placed, it waits for the result
	//	3. Returns the result in both cases
	function GetOrderResult()
	{
		if ($drinkType == 'Custom' || strstr($drinkName, 'Eric\'s Jamaican Surprise'))
		{
			if ($_SESSION['orderStatus'] == 'about_to_order')
			{
				$file = file("CustomDrinkNames.txt");
				$_SESSION['drinkName'] = $file[rand(0, count($file) - 1)];
			}
		}
		
		// Text is set when the function returns
		if ($_SESSION['orderStatus'] == 'about_to_order')
		{
			$_SESSION['orderStatus'] = 'already_ordered';
			$_POST = $_SESSION['post-data'];
			$_SESSION['text'] = PlaceOrder($drinkName, $drinkType);
		}
		else
		{
			while (!isset($_SESSION['text']))
			{
				sleep(2);
			}
			$text = $_SESSION['text'];
		}
		
		return $text;
	}

	function IsFreeToOrder()
	{
		return !isset($_SESSION['orderStatus'] || $_SESSION['orderStatus'] == 'free_to_order';
	}

	function IsRatableDrink()
	{
		return $_GET['orderType'] == 'Mixed' && !strstr($drinkName, 'Eric\'s Jamaican Surprise');
	}

	function PlaceOrder($drinkName, $drink)
	{
		if (isset($_GET['drinkAmount']))
		{
			$drinkAmount = $_GET['drinkAmount'];
		}
		else
			$drinkAmount = "full";

		$order = new Order();

		if ($drinkType == 'Mixed')
		{
			$order->Mixed()
				->Name($drinkName);
		}
		else if ($drinkType == 'Shot')
		{
			$order->Shot()
				->Name($drinkName);
		}
		else if ($drinkType == 'Custom')
		{
			$namesToVolumesMap = GenerateNamesToVolumesMap();
			$order->Custom()
				->SetOrderData($namesToVolumesMap);
		}
		
		$resultString = $order->Place();
		
		return $resultString;
	}
	
	function GenerateNamesToVolumesMap()
	{
		$drinksAdded = 0;
		$totalParts = 0;
		$namesToPartsMap = array();
		$namesToVolumesMap = array();

		for ($count = 0; $count < 16; $count++)
		{
			$drinkName = $_POST["text" . $count];
			$parts = $_POST["parts" . $count];

			if ($drinkName == "0" || $parts == 0) continue;

			$drinksAdded++;
			$totalParts += $parts;
			
			if (!isset($namesToPartsMap[$drinkName]))
			{
				$namesToPartsMap[$drinkName] = 0;
			}
			
			$namesToPartsMap += $parts;
		}

		foreach($namesToPartsMap as $name => $part)
		{
			$volume = floor(($part * 175 / $totalParts));
			$namesToVolumesMap[$name] = $volume;
		}

		return $namesToVolumesMap;
	}
?>