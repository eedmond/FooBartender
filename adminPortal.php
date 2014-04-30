<?php include "login.php"; ?>
<?php include "login_head.php"; ?>
<!DOCTYPE HTML>
<!--
	Tessellate 1.0 by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>FooBartender: Admin Portal</title>
		<link rel="icon" type="image/png" href="images/nonalcoholic.png"/>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<link href="http://fonts.googleapis.com/css?family=Roboto:100,100italic,300,300italic,400,400italic" rel="stylesheet" type="text/css" />
		<![if lte IE 8]><script src="css/ie/html5shiv.js"></script><![endif]>
		<script src="js/jquery.min.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/init.js"></script>

<!-- bxSlider Javascript file -->
<script src="jquery.bxslider/jquery.bxslider.min.js"></script>
<!-- bxSlider CSS file -->
<link href="jquery.bxslider/jquery.bxslider.css" rel="stylesheet" />

  <!--script src="js/jquery-1.10.2.js"></script-->
		<noscript>
			<link rel="stylesheet" href="css/skel-noscript.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-wide.css" />
		</noscript>
		<!--[if lte IE 8]><link rel="stylesheet" href="css/ie/v8.css" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="css/ie/v9.css" /><![endif]-->
	</head>
				<ul id="NavTool" class="navbar">
					<li><a href="#TableSetup" class="scrolly fa solo"><img src="images/Categories/Tablethumb.png" alt="Mixed Image"><span>Twitter</span></a></li>
					<li><a href="#ManageDrinks" class="scrolly fa solo"><img src="images/Categories/shot.png" alt="Shot Image"><span>Facebook</span></a></li>
					<li><a href="#ManageQueue" class="scrolly fa solo"><img src="images/Categories/nonalcoholic.png" alt="Non-Alcoholic Image"><span>Google+</span></a></li>
					<li><a href="#ManageErrors" class="scrolly fa solo"><img src="images/Categories/customdrink.png" alt="Custom Image"><span>Dribbble</span></a></li>
				</ul>
	<body>

		<!-- Main -->
			<section id="Main" class="main">
				<header>
					<div class="container">
						<h2>Welcome to the FooBartender: Admin Portal</h2>
					</div>
				</header>
				<div class="content dark style1 featured">
					<div class="container">
						<div class="row">
							<div class="6u">
								<section>
									<img src="images/Categories/Tablethumb.png" alt="Mixed Drink Image" width=150 height=150>
									<header>
										<h3><a href="#TableSetup" class="button scrolly">Table Setup</a></h3>
									</header>
								<br><br>
								</section>
							</div>
							<div class="6u">
								<section>
									<img src="images/Categories/nonalcoholic.png" alt="Shot Image" width=150 height=150>
									<header>
										<h3><a href="#ManageDrinks" class="button scrolly">Manage Drinks</a></h3>
									</header>
									<br><br>
								</section>
							</div>
						</div>
						<div class="column">
							<div class="row">
							<div class="6u">
								<section>
									<img src="images/Categories/Queue.png" alt="Non-Alcoholic Image" width=175 height=175>
									<header>
										<h3><a href="#ManageQueue" class="button scrolly">Manage Queue</a></h3>
									</header>
								</section>
							</div><br>
							<div class="6u">
								<section>
									<img src="images/Categories/error.png" alt="Custom Drink" width=150 height=150>
									<header>
										<h3><a href="#ManageErrors" class="button scrolly">Manage Errors</a></h3>
									</header>
								</section>
							</div>
							</div>
						</div>
					</div>
				</div>
			</section>

		<!-- Necessary Functions -->
<script>
$(document).ready(function(){
    $(window).scroll(function(){
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) )
		return;
        var posFromTop = $(window).scrollTop();

        if(posFromTop+1 > $('#Mixed').offset().top){
		if (!$('#NavTool').is(":visible"))
			$('#NavTool').fadeIn();
        } else {
		if ($('#NavTool').is(":visible"))
			$('#NavTool').fadeOut();
        }
    });
});

function FreeSession()
{
    xmlhttp=new XMLHttpRequest();
	xmlhttp.open("GET", "session_free.php", false);
	xmlhttp.send();
}

var customCounter = 0;
function onPlusClick() {
	customCounter = customCounter + 1;
	var textID = "text" + customCounter;
	var partsID = "parts" + customCounter;
	var plusID = "plus" + customCounter;
	var prevPlusID = "plus" + (customCounter - 1);
	var textDOM = document.getElementsByName(textID)[0];
	var partsDOM = document.getElementsByName(partsID)[0];
	var plusDOM = document.getElementsByName(plusID)[0];
	var prevPlusDOM = document.getElementsByName(prevPlusID)[0].style.display="none";
	$(textDOM).fadeTo("slow", 1);
	$(partsDOM).fadeTo("slow", 1);
	$(plusDOM).fadeIn("slow");
}

function onClearClick() {
	while (customCounter > 0) {
		var textID = "text" + customCounter;
		var partsID = "parts" + customCounter;
		var plusID = "plus" + customCounter;
		document.getElementsByName(textID)[0].style.display="none";
		document.getElementsByName(partsID)[0].style.display="none";
		document.getElementsByName(plusID)[0].style.display="none";
		customCounter = customCounter - 1;
	}
	document.getElementsByName("plus0")[0].style.display="inline";
}

</script>
		<!-- Table Setup -->
			<section id="TableSetup" class="main">
				<header>
					<div class="container">
						<h2>Table Setup</h2>
					</div>
				</header>
				<div class="content dark style2">
					<div class="header">
						<div align="center">Setup which drinks are on the table and where</div>
					</div>
					<div class="container">
						<div class="row">
							<ul class="mobilenav" style="padding-left:50%" id="mobilenav">
								<li><a href="#TableSetup" class="scrolly fa solo"><img src="images/Categories/mixeddrink.png" alt="Mixed Image"></a></li>
								<li><a href="#ManageDrinks" class="scrolly fa solo"><img src="images/Categories/shot.png" alt="Shot Image"></a></li>
								<li><a href="#ManageQueue" class="scrolly fa solo"><img src="images/Categories/nonalcoholic.png" alt="Non-Alcoholic Image"></a></li>
								<li><a href="#ManageErrors" class="scrolly fa solo"><img src="images/Categories/customdrink.png" alt="Custom Image"></a></li>
							</ul>
						</div>
					</div>
				</div>
			</section>
			
		<!-- Manage Drinks -->
			<section id="Manage Drinks" class="main">
				<header>
					<div class="container">
						<h2>Manage Drinks</h2>
					</div>
				</header>
				<div class="content dark style2">
					<div class="header">
						<div align="center">Create or delete drinks from the database</div><br>
						<form id="SQLform" name="SQLform" action="SQLCommand.php" method="POST">
							<div align="center"><input type="text" size="40" name="command" value="Custom SQL Command" /><input type="submit" value="Execute Command" style="border:none; background-color:transparent; color:white;"></div><br>
						</form>
					</div>
					<div class="container small">
						<form id="newDrinkForm" method="POST" action="insertMixed.php">
							<div class="row">
								<div class="12u" align="center">
									<ul class="actions">
										<li><input type="text" id="drinkName" class="button" name="drinkName" size="10" value="Drink Name"/></li>
										<li><input type="submit" class="button" value="Submit Drink"/></li>
										<li><input type="reset" class="button alt" value="Clear Order" onclick="javascript:onClearClick();" /></li>
									</ul>
								</div>
							</div>
						<?php
							$db = new PDO('sqlite:FB.db');
							$getNames = $db->query("SELECT * FROM single WHERE station > -1 ORDER BY name ASC");
							$queryResult = $getNames->fetchAll();
	
							for ($count = 0; $count < 16; $count++)
							{
								if ($count == 0) {
									echo '<select id="text', $count, '" name="text', $count, '" style="display: inline">Ingredient<option value=0>Add Drink</option>';
								} else {
									echo '<select id="text', $count, '" name="text', $count, '" style="display: none">Ingredient<option value=0>Add Drink</option>';
								}
								foreach ($queryResult as $row)
								{
									$drinkName = $row['name'];
									echo '<option value="', $drinkName, '">', $row['name'], '</option>';
								}
								echo '</select>';

								if ($count == 0) {
									echo '<input type="number" id="parts', $count, '" name="parts', $count, '" class="number" value=0 min="0" style="display: inline; margin-right: 12px; margin-bottom: 5px;">';
									echo '<input type="button" id="plus', $count, '" name="plus', $count, '" onclick="javascript:onPlusClick();" value="+" alt="New Drink" style="display: inline"></button>';
								} else {
									echo '<input type="number" id="parts', $count, '" name="parts', $count, '" class="number" value=0 min="0" style="display: none; margin-right: 12px; margin-bottom: 5px">';
									echo '<input type="button" id="plus', $count, '" name="plus', $count, '" onclick="javascript:onPlusClick();" value="+" alt="New Drink" style="display: none"></button>';
								}
								if ($count % 2 == 1)
									echo '<br>';
							}
						unset($db);
						?>
						</form>
						<div class="row">
							<ul class="mobilenav" style="padding-left:50%" id="mobilenav">
								<li><a href="#TableSetup" class="scrolly fa solo"><img src="images/Categories/mixeddrink.png" alt="Mixed Image"></a></li>
								<li><a href="#ManageDrinks" class="scrolly fa solo"><img src="images/Categories/shot.png" alt="Shot Image"></a></li>
								<li><a href="#ManageQueue" class="scrolly fa solo"><img src="images/Categories/nonalcoholic.png" alt="Non-Alcoholic Image"></a></li>
								<li><a href="#ManageErrors" class="scrolly fa solo"><img src="images/Categories/customdrink.png" alt="Custom Image"></a></li>
							</ul>
						</div>
					</div>
				</div>
			</section>

		<!-- Manage Queue -->
			<section id="ManageQueue" class="main">
				<header>
					<div class="container">
						<h2>Manage Queue</h2>
					</div>
				</header>
				<div class="content dark style2">
					<div class="header">
						<div align="center">View and erase contents of the queue</div>
					</div>
					<div class="container">
						<div class="row">
							<ul class="mobilenav" style="padding-left:50%" id="mobilenav">
								<li><a href="#TableSetup" class="scrolly fa solo"><img src="images/Categories/mixeddrink.png" alt="Mixed Image"></a></li>
								<li><a href="#ManageDrinks" class="scrolly fa solo"><img src="images/Categories/shot.png" alt="Shot Image"></a></li>
								<li><a href="#ManageQueue" class="scrolly fa solo"><img src="images/Categories/nonalcoholic.png" alt="Non-Alcoholic Image"></a></li>
								<li><a href="#ManageErrors" class="scrolly fa solo"><img src="images/Categories/customdrink.png" alt="Custom Image"></a></li>
							</ul>
						</div>
					</div>
				</div>
			</section>


		<!-- Manage Errors -->
			<section id="ManageErrors" class="main">
				<header>
					<div class="container">
						<h2>Manage Errors</h2>
					</div>
				</header>
				<div class="content dark style2">
					<div class="header">
						<div align="center">Halt all processes</div>
					</div>
					<div class="container small">
						<div class="row">
							<ul class="mobilenav" style="padding-left:50%" id="mobilenav">
								<li><a href="#TableSetup" class="scrolly fa solo"><img src="images/Categories/mixeddrink.png" alt="Mixed Image"></a></li>
								<li><a href="#ManageDrinks" class="scrolly fa solo"><img src="images/Categories/shot.png" alt="Shot Image"></a></li>
								<li><a href="#ManageQueue" class="scrolly fa solo"><img src="images/Categories/nonalcoholic.png" alt="Non-Alcoholic Image"></a></li>
								<li><a href="#ManageErrors" class="scrolly fa solo"><img src="images/Categories/customdrink.png" alt="Custom Image"></a></li>
							</ul>
						</div>	
					</div>
				</div>
			</section>
			
		
		<!-- Footer -->
			<section id="footer">
				<a href="index.php?logout=true" class="button scrolly">Logout</a>
				<div class="copyright">
					<ul class="menu">
						<li>&copy; Triom Productions, All rights reserved.</li>
						<li>Design: <a href="http://html5up.net/">HTML5 UP</a></li>
						<li>Images: Mike Cardoso</li>
						<li>Special thanks to Ian Blaauw</li>
					</ul>
				</div>
			</section>

	</body>
</html>
