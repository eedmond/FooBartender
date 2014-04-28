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
				
				/*function ShowRating($drink)
				{
				
				Call UPDATE mixed SET numRatings=numRatings+1,
						rating=rating+[rating] WHERE name=[drinkName]
				
				}*/

				function GenOrderString($custom, $drink)
				{
					if (isset($_GET['drinkAmount'])) {
						$drinkAmount = $_GET['drinkAmount'];
					}
					else
						$drinkAmount = "full";

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
					else {
						$orderString = $orderString . "\"" . $drink . "\"";
						$orderString = $orderString .  " " . $drinkAmount;
					}

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
					//echo '<p>' . $order . '</p>';
					return $order;
				}
				
				// Opened is set when the page is about to reload itself
				if (isset($_SESSION['orderStatus']) && $_SESSION['orderStatus'] != 'free_to_order')
				{
					if ($drink == 'custom' || strstr($drink, 'Eric\'s Jamaican Surprise'))
					{
						if ($_SESSION['orderStatus'] == 'about_to_order') {
							$file = file("CustomDrinkNames.txt");
							$_SESSION['randDrinkName'] = $file[rand(0, count($file) - 1)];
						}
						echo '<header><h1>Thank you for ordering a ', $_SESSION['randDrinkName'], '</h1><br>';
					}
					else
					{
						echo '<header><h1>Thank you for ordering a ', $drink, '</h1><br>';
					}
					
					// Text is set when the function returns
					if ($_SESSION['orderStatus'] == 'about_to_order')
					{
						$_SESSION['orderStatus'] = 'already_ordered';
						$_POST = $_SESSION['post-data'];
						$_SESSION['drinkName'] = $drink;
						$text = exec(GenOrderString($_GET['custom'], $drink));
						$_SESSION['text'] = $text;
						$_SESSION['drinkAmount'] = $drinkAmount;
					}
					else
					{
						while (!isset($_SESSION['text']))
						{
							sleep(2);
						}
						$text = $_SESSION['text'];
					}
					if ($_GET['custom'] == "false" && !strstr($drink, 'Eric\'s Jamaican Surprise'))
					{
						//setup rating thingy
						//ShowRating($drink);
						?>

						<h1>Rate this drink!</h1><br>
						<input type="number" class="number" id="rating" value="0" min="0" max="10">/10
						<input type="button" class="button" id="rate" name="rate" value="Rate" alt="Rate">
						
						<script>
							$('input[id="rate"]').click(function(){
								$.ajax({
									url: 'rateDrink.php',
									type: 'POST',
									data: {
										number: document.getElementById("rating").value
									},
									success: function() {
										alert("It Worked!");
									}
								});
							});
						</script>
						
						<?php
					}
					echo '<p>', $text, '</p><br>';
					//echo '<p>', implode($alltext), '</p></header>';
					echo '<footer><a href="index.php" class="button scrolly">Back to FooBartender</a></footer>';
				
				} else {
					$_SESSION['post-data'] = $_POST;
					$_SESSION['id'] = '1';	
					$_SESSION['orderStatus'] = 'about_to_order';
				?>
					<header>
						<h1>Thank you for ordering a <?php echo $drink ?></h1>
						<p>Please wait while your order is being processed.</p><br>
						<p>Think about it. There's millions of calculations I'm processing.</p>
					</header>
					
					<script language="javascript">
						location.href="<?php echo $_SERVER['SCRIPT_NAME'], '?', $_SERVER['QUERY_STRING'] ?>";
					</script>
				<?php
				}
				?>
				

				<!--<header>
					<h1>Thank you for ordering a <?php echo $drink ?></h1>
					<p>Please place your drink on station [get station number here]</p>
				</header>
				<footer>
					<a href="index.php#order" onclick="javascript:fadeout()" class="button scrolly">Back to FooBartender</a>
				</footer>-->
			</section>
