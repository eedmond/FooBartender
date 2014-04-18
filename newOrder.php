	<?php
		session_start();
		/*if (isset($_SESSION('id')))
			echo '<META http-equiv="refresh" content="0;URL=http://192.168.1.111">';*/
	?>
	<head>
		<title>Thank you for ordering!</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keyword" content="" />
		<link href="http://fonts.googleapis.com/css?family=Roboto:100,100italic,300,300italic,400,400italic" rel="stylesheet" type="text/css" />
		<script src="js/jquery.min.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/init.js"></script>

		<noscript>
			<link rel="stylesheet" href="css/skel-noscript.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet href="css/style-wide.css" />
		</noscript>
	</head>

	<?php
		$drink = $_GET['drinkName'];
	?>
	<body>

	<script type="text/javascript">
		$(document).ready(function() {
			$("body").css("display", "none");
			$("body").fadeIn(500);
		});

		function fadeout() {
			$("body").fadeOut(500);
		}
	</script>
		<!-- Main Screen -->
			<section id="header" class="dark">
				<?php
				
				function ShowRating($drink)
				{
				/*
				Call UPDATE mixed SET numRatings=numRatings+1,
						rating=rating+[rating] WHERE name=[drinkName]
				*/
				}

				function GenOrderString($custom, $drink)
				{
					$orderString = "functions/order ";
					if ($custom == "true")
					{
						$orderString = $orderString . "custom ";
						if (isset($_POST['text0'])) // Check if custom order was made
						{
							$orderString = $orderString . GenCustomOrderString();
						}
						else
						{
							$orderString = $orderString . "\"" . $drink . "\" 35";
						}
					}
					else
						$orderString = $orderString . "\"" . $drink . "\"";
				
					return $orderString;
				}
				
				function GenCustomOrderString()
				{
					$order = "\"";
					$duration = "";
					$drinksAdded = 0;
					$totalParts = 0;
					$partsArray = array();

					for ($count = 0; $count < 10; $count++)
					{
						$drinkName = $_POST["text" . $count];
						$parts = $_POST["parts" . $count];

						if ($drinkName == "0" || $parts == 0) continue;

						if ($drinksAdded != 0)
						{
							$order = $order . "|";
						}
						$drinksAdded++;
						$totalParts += $parts;
						$partsArray[$drinkName] = $parts;

						$order = $order . $drinkName;
					}

					foreach($partsArray as $name => $part)
						$duration = $duration . (string)floor(($part * 175 / $totalParts)) . "|";

					$order = $order . "\" \"" . $duration . "\"";
					echo '<p>' . $order . '</p>';
					return $order;
				}
				
				if (isset($_GET['opened'])) {
					$_POST = $_SESSION['post-data'];
					echo '<header><h1>Thank you for ordering a ', $drink, '</h1><br>';
					$text = exec(GenOrderString($_GET['custom'], $drink));
					session_destroy();
					if ($_GET['custom'] == "false")
					{
						//setup rating thingy
						//ShowRating($drink);
						?>
						<h1>Rate this drink!</h1><br>
						<input type="number" id="rating" value="0" min="0" max="10">
						<input type="submit" id="rate" name="rate" value="Rate" onclick="javascript:onRateClick();" alt="Rate">

						<script>
							function onRateClick()
							{
								var drinkName=<?php echo $drink ?>;
								var rating=document.getElementById("rating").value;
							}
						</script>
						<?
					}
					echo '<p>', $text, '</p><br>';
					//echo '<p>', implode($alltext), '</p></header>';
					echo '<footer><a href="index.php/#order" class="button scrolly">Back to FooBartender</a></footer>';
				
				} else {
					$_SESSION['post-data'] = $_POST;
					$_SESSION['id'] = '1';	
				?>
					<header>
						<h1>Thank you for ordering a <?php $drink; ?></h1>
						<p>Please wait while your order is being processed.</p><br>
						<p>Think about it. There's millions of calculations I'm processing.</p>
					</header>
					
					<script language="javascript">
						location.href="<?php echo $_SERVER['SCRIPT_NAME'], '?', $_SERVER['QUERY_STRING'], "&opened=0"?>";
					</script>
				<?php
				}
				?>
				

				<!--<header>
					<h1>Thank you for ordering a <?php echo $drink ?></h1>
					<p>Please place your drink on station [get station number here]</p>
				</header>
				<footer>
					<a href="index.php" onclick="javascript:fadeout()" class="button scrolly">Back to FooBartender</a>
				</footer>-->
			</section>
