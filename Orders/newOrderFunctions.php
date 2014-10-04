<?php
	//This function does:
	//	1. Places the order if it hasn't already been done
	//	2. If the order has been placed, it waits for the result
	//	3. Returns the result in both cases
	function GetOrderResult()
	{
		$drinkType = $_GET['orderType'];
		$drinkName = $_GET['drinkName'];
		
		$text = '';
		
		// Text is set when the function returns
		if ($_SESSION['orderStatus'] == 'about_to_order')
		{
			$_SESSION['orderStatus'] = 'already_ordered';
			$_POST = $_SESSION['post-data'];
			$text = PlaceOrder($drinkName, $drinkType, $drinkType);
			$_SESSION['text'] = $text;
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

	function GetDrinkName()
	{
		if (isset($_SESSION['drinkName']))
		{
			return $_SESSION['drinkName'];
		}
		
		$drinkType = $_GET['orderType'];
		$drinkName = $_GET['drinkName'];

		if ($drinkType == 'Custom' || strstr($drinkName, 'Eric\'s Jamaican Surprise'))
		{
			$file = file("CustomDrinkNames.txt");
			$drinkName = $file[rand(0, count($file) - 1)];
		}
		
		$_SESSION['drinkName'] = $drinkName;
		
		return $drinkName;
	}

	function IsFreeToOrder()
	{
		return !isset($_SESSION['orderStatus']) || $_SESSION['orderStatus'] == 'free_to_order';
	}

	function IsRatableDrink($drinkName)
	{
		return $_GET['orderType'] == 'Mixed' && !strstr($drinkName, 'Eric\'s Jamaican Surprise');
	}

	function PlaceOrder($drinkName, $drink, $drinkType)
	{
		$drinkAmount = GetDrinkAmount();

		$order = new Order();

		if ($drinkType == 'Mixed')
		{
			$order->Mixed()
				->SetAmountToPour($drinkAmount)
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
			
			$namesToPartsMap[$drinkName] += $parts;
		}

		foreach($namesToPartsMap as $name => $part)
		{
			$volume = floor(($part * 175 / $totalParts));
			$namesToVolumesMap[$name] = $volume;
		}

		return $namesToVolumesMap;
	}
	
	function GetDrinkAmount()
	{
		$drinkAmount = 175;
		
		if (isset($_GET['drinkAmount']))
		{
			$drinkAmountString = $_GET['drinkAmount'];
		}
		else
		{
			$drinkAmountString = 'full';
		}
		
		switch ($drinkAmountString)
		{
			case ('full'):
				$drinkAmount = 175;
				break;
			case ('half'):
				$drinkAmount = 95;
				break;
			case ('taste'):
				$drinkAmount = 50;
				break;
		}
		
		return $drinkAmount;
	}
?>