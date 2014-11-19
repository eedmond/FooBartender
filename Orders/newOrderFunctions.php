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
			$order = PlaceOrder($drinkName, $drinkType);
			$text = $order->GetResultString();
			$_SESSION['text'] = $text;
			$_SESSION['ORDER_SUCCESS'] = $order->WasSuccessful();
			$_SESSION['drinkName'] = $order->DrinkOrdered();
			$_SESSION['orderId'] = $order->GetOrderId();
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
		if (isset($_SESSION['drinkDisplayName']))
		{
			return $_SESSION['drinkDisplayName'];
		}
		
		$drinkType = $_GET['orderType'];
		$drinkDisplayName = $_GET['drinkName'];

		if ($drinkType == 'Custom' || strstr($drinkDisplayName, 'Eric\'s Jamaican Surprise'))
		{
			$file = file("Orders/CustomDrinkNames.txt");
			$drinkDisplayName = $file[rand(0, count($file) - 1)];
		}
		
		$_SESSION['drinkDisplayName'] = $drinkDisplayName;
		
		return $drinkDisplayName;
	}

	function IsFreeToOrder()
	{
		return !isset($_SESSION['orderStatus']) || $_SESSION['orderStatus'] == 'free_to_order';
	}
	
	function IsOrderClearable()
	{
		return !isset($_SESSION['orderCleared']) || $_SESSION['orderCleared'] != '1';
	}

	function IsRatableDrink($drinkName)
	{
		// Disallow ratings for drinks whose orders did not work
		if (isset($_SESSION['ORDER_SUCCESS']) && $_SESSION['ORDER_SUCCESS'] == '0')
		{
			return false;
		}
		
		// Disallow ratings if drink has been rated
		if (isset($_SESSION['drinkRated']) && $_SESSION['drinkRated'] == '1')
		{
			return false;
		}
		return $_GET['orderType'] == 'Mixed';
	}

	function PlaceOrder($drinkName, $drinkType)
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
		
		$order->Place();
		
		return $order;
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