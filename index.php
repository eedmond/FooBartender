<?php include "login_head.php"; ?>
<!DOCTYPE HTML>
<!--
	Tessellate 1.0 by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>Welcome to the FooBartender</title>
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
					<li><a href="#Mixed" class="scrolly fa solo"><img src="images/Categories/mixeddrink.png" alt="Mixed Image"><span>Twitter</span></a></li>
					<li><a href="#Shots" class="scrolly fa solo"><img src="images/Categories/shot.png" alt="Shot Image"><span>Facebook</span></a></li>
					<li><a href="#Non_Alcoholic" class="scrolly fa solo"><img src="images/Categories/nonalcoholic.png" alt="Non-Alcoholic Image"><span>Google+</span></a></li>
					<li><a href="#Custom" class="scrolly fa solo"><img src="images/Categories/customdrink.png" alt="Custom Image"><span>Dribbble</span></a></li>
					<li><a href="#Feedback" class="scrolly fa solo"><img src="images/Categories/comments.png" alt="Comments Image"><span>Dribbble</span></a></li>
					
				</ul>
	<body>

		<!-- Header -->
			<section id="header" class="dark">
				<header>
					<h1>Welcome to the FooBartender</h1>
					<p>Designed and built by Michael Cardoso, Eric Edmond, and Jonathan Soifer</p>
				</header>
				<footer>
					<a href="#order" class="button scrolly">Order a Drink</a>
				</footer>
			</section>
			
		<!-- Order -->
			<section id="order" class="main">
				<header>
					<div class="container">
						<h2>Choose the type of drink you would like to order: </h2>
					</div>
				</header>
				<div class="content dark style1 featured">
					<div class="container">
						<div class="row">
							<div class="6u">
								<section>
									<img src="images/Categories/mixeddrink.png" alt="Mixed Drink Image" width=150 height=150>
									<header>
										<h3><a href="#Mixed" class="button scrolly">Mixed Drink</a></h3>
									</header>
									<!--<p>A mix of alcoholic and non-alcoholic drinks. Everything from your standard Rum and Coke to more exotic flavors.</p>-->
								<br><br>
								</section>
							</div>
							<div class="6u">
								<section>
									<img src="images/Categories/shot.png" alt="Shot Image" width=150 height=150>
									<header>
										<h3><a href="#Shots" class="button scrolly">Shot</a></h3>
									</header>
									<!--<p>Just a single shot of alcohol. No chaser necessary.</p>-->
									<br><br>
								</section>
							</div>
						</div>
						<div class="column">
							<div class="row">
							<div class="6u">
								<section>
									<img src="images/Categories/nonalcoholic.png" alt="Non-Alcoholic Image" width=175 height=175>
									<header>
										<h3><a href="#Non_Alcoholic" class="button scrolly">Non-Alcoholic</a></h3>
									</header>
									<!--<p>Juices, carbonated drinks, they're all here.</p>-->
								</section>
							</div><br>
							<div class="6u">
								<section>
									<img src="images/Categories/customdrink.png" alt="Custom Drink" width=150 height=150>
									<header>
										<h3><a href="#Custom" class="button scrolly">Custom</a></h3>
									</header>
									<!--<p>Turn your dreams into reality. Triom Productions is not liable for any damages caused by custom drink orders.</p>-->
								</section>
							</div>
							</div>
						</div>
						<!--<div class="row">
							<div class="12u">
								<img src="images/Categories/comments.png" alt="Comments" width=150 height=150>
								<footer>
									<h3><a href="#Feedback" class="button scrolly">Leave a Comment</a></h3>
								</footer>
							</div>
						</div>-->
					</div>
				</div>
			</section>

		<!-- Mixed -->
<script>
$(document).ready(function(){

$.ajax(
{
    url: 'Orders/fetchDrinks.php',
   type: 'post',
   data: { "drinkType": "mixedDrink"},
  success: function(data) {
  	$('#mixedDrinkSlider').html(data);
  },
  async: false
}
);

$.ajax(
{
    url: 'Orders/fetchDrinks.php',
   type: 'post',
   data: { "drinkType": "shot"},
  success: function(data) {
  	$('#shotSlider').html(data);
  },
  async: false
}
);

$.ajax(
{
    url: 'Orders/fetchDrinks.php',
   type: 'post',
   data: { "drinkType": "nonAlcoholic"},
  success: function(data) {
  	$('#nonAlcoholicSlider').html(data);
  },
  async: false
}
);

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

  $('.slider').bxSlider({
    slideWidth: 1000,
    minSlides: 1,
    maxSlides: 3,
    slideMargin: 10,
	infiniteLoop: false
  });

	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) )
	{
		$('.bx-prev').hide();
		$('.bx-next').hide();
		$('.mobilenav').show();
	}
  
	$(".orderIcon").mouseenter(function(){
		$(this).stop(true, true);
		MouseEnter($(this).attr('id'), $(this));
	});
	$(".orderIcon").mouseleave(function() {
		$(this).stop(true, true);
		MouseLeave($(this).attr('id'), $(this));
	});
	$(".orderIcon").click(function() {
		$(this).stop(true, true);
		ClickFunction($(this).attr('id'), $(this));
	});

});

function MouseEnter(type, element)
{
	var descText = "#MixedDesc";

	if (type == "mixedDrink")
		descText = "#MixedDesc";

	else if (type == "shot")
		descText = "#ShotDesc";

	else if (type == "nonAlcoholic")
		descText = "#NonAlcoholicDesc";
	
	$(descText).stop(true, true);
	if ($("#"+ type + "Slider .image.selected").hasClass("selected")) return;
	element.fadeTo("slow", 1);
	$(descText).html(element.attr('name').replace(/\n/g, "<br />"));
	$(descText).fadeIn("slow");
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) )
		element.trigger("click");
}

function MouseLeave(type, element)
{
	var descText = "#MixedDesc";

	if (type == "mixedDrink")
		descText = "#MixedDesc";

	else if (type == "shot")
		descText = "#ShotDesc";

	else if (type == "nonAlcoholic")
		descText = "#NonAlcoholicDesc";

	$(descText).stop(true, true);
	if ($("#"+type+"Slider .image.selected").hasClass("selected")) return;
	element.fadeTo("slow", 0.5);
            	$(descText).fadeOut(300);
}

function ClickFunction(type, element)
{
	var orderButt = "#OrderMixed";
	var descText = "#MixedDesc";

	if (type == "mixedDrink")
	{
		descText = "#MixedDesc";
		orderButt = "#OrderMixed";
	}
	else if (type == "shot")
	{
		descText = "#ShotDesc";
		orderButt = "#OrderShot";
	}
	else if (type == "nonAlcoholic")
	{
		descText = "#NonAlcoholicDesc";
		orderButt = "#OrderNonAlcoholic";
	}

	if (element.hasClass("selected"))
	{
		element.removeClass("selected");
		$(orderButt).fadeOut("slow");
	}
	else
	{
		var selected = $(".image.selected");
		if (selected.hasClass("selected"))
		{
			selected.fadeTo("slow", 0.5);
			selected.removeClass("selected");

			$(".drinkDesc").fadeOut("slow");
			$(".drinkButton").fadeOut("slow");

		}
		element.fadeTo("slow", 1);
		element.addClass("selected");
		$(descText).stop(true, true);
		$(descText).fadeIn("slow");
		$(descText).html(element.attr('name').replace(/\n/g, "<br />"));
		$(orderButt).stop(true, false);
		$(orderButt).fadeIn("slow");
	}
}

function FreeSession()
{
    xmlhttp=new XMLHttpRequest();
	xmlhttp.open("GET", "session_free.php", false);
	xmlhttp.send();
}

function submitDrink(drinkType)
{
	FreeSession();
	var drinkData = $(".image.selected");
	var mixedName = drinkData.attr('name').substring(17, drinkData.attr('name').search("</h1>"));
	var drinkAmountSelected = document.getElementById('drinkAmount').selectedIndex;
	var drinkAmount = "full";
	if (drinkAmountSelected == 1) {
		drinkAmount = "half";
	}
	else if (drinkAmountSelected == 2) {
		drinkAmount = "taste";
	}
	var orderPostFix = "";
	if (mixedName == "Eric's Jamaican Surprise")
	{
		if (drinkData.parents(".slider").attr('id') == "mixedDrinkSlider")
			orderPostFix = "|Mixed Drink";
		else
			orderPostFix = "|Non-Alcoholic";
	}
	$("body").fadeOut(1000);
	location.href = "newOrder.php?custom=false&drinkName=" + mixedName + orderPostFix + "&drinkAmount=" + drinkAmount;
}

function submitShot()
{
	FreeSession();
	var drinkData = $(".image.selected");
	var mixedName = drinkData.attr('name').substring(17, drinkData.attr('name').search("</h1>"));
	var orderPostFix = "";
	if (mixedName == "Eric's Jamaican Surprise")
		orderPostFix = "|Shot";
		
	$("body").fadeOut(1000);
	location.href = "newOrder.php?custom=true&drinkName=" + mixedName + orderPostFix;
}

</script>
		<!-- Mixed Drinks -->
			<section id="Mixed" class="main">
				<header>
					<div class="container">
						<h2>Mixed Drinks</h2>
					</div>
				</header>
				<div class="content dark style2">
					<div class="container">
						<div class="row">
							<div class="4u">
								<section>
									<h3>A splash of naughty, a shot of nice, these mixed drinks are just for you!</h3>
								</section>
								<div class="hidden drinkDesc" id="MixedDesc">Some test Text</div>
								<section class="hidden drinkButton" id="OrderMixed">
									<footer>
										Drink amount:
										<select id="drinkAmount" name="drinkAmount" value="Full">
											<option value="Full">Full</option>
											<option value="Half">Half</option>
											<option value="Taste">Taste</option>
										</select><br><br>
										<a onclick="javascript:submitDrink('mixed')" class="button">Submit Order</a>
									</footer>
								</section>
							</div>
							<div class="8u">
								<div class="slider" id="mixedDrinkSlider"></div>
							</div>
							<ul class="mobilenav" style="padding-left:50%" id="mobilenav">
								<li><a href="#Mixed" class="scrolly fa solo"><img src="images/Categories/mixeddrink.png" alt="Mixed Image"></a></li>
								<li><a href="#Shots" class="scrolly fa solo"><img src="images/Categories/shot.png" alt="Shot Image"></a></li>
								<li><a href="#Non_Alcoholic" class="scrolly fa solo"><img src="images/Categories/nonalcoholic.png" alt="Non-Alcoholic Image"></a></li>
								<li><a href="#Custom" class="scrolly fa solo"><img src="images/Categories/customdrink.png" alt="Custom Image"></a></li>
							</ul>
						</div>
					</div>
				</div>
			</section>
			
		<!-- Shots -->
			<section id="Shots" class="main">
				<header>
					<div class="container">
						<h2>Shots</h2>
					</div>
				</header>
				<div class="content dark style2">
					<div class="container">
						<div class="row">
							<div class="4u">
								<section>
									<h3>Straight, no chaser.</h3>
								<div class="hidden drinkDesc" id="ShotDesc">Some test Text</div>
								<section class="hidden drinkButton" id="OrderShot">
									<footer>
										<a onclick="javascript:submitShot()" class="button">Submit Order</a>
									</footer>
								</section>
								</section>
							</div>
							<div class="8u">
								<div class="slider" id="shotSlider"></div>
							</div>
							<ul class="mobilenav" style="padding-left:50%" id="mobilenav">
								<li><a href="#Mixed" class="scrolly fa solo"><img src="images/Categories/mixeddrink.png" alt="Mixed Image"></a></li>
								<li><a href="#Shots" class="scrolly fa solo"><img src="images/Categories/shot.png" alt="Shot Image"></a></li>
								<li><a href="#Non_Alcoholic" class="scrolly fa solo"><img src="images/Categories/nonalcoholic.png" alt="Non-Alcoholic Image"></a></li>
								<li><a href="#Custom" class="scrolly fa solo"><img src="images/Categories/customdrink.png" alt="Custom Image"></a></li>
							</ul>
						</div>
					</div>
				</div>
			</section>

		<!-- Basic Elements -->
			<section id="Non_Alcoholic" class="main">
				<header>
					<div class="container">
						<h2>Non-Alcoholic</h2>
					</div>
				</header>
				<div class="content dark style2">
					<div class="container">
						<div class="row">
							<div class="4u">
								<section>
									<h3>For the light of stomach</h3>
								</section>
								<div class="hidden drinkDesc" id="NonAlcoholicDesc">Some Test Text</div>
								<section class="hidden drinkButton" id="OrderNonAlcoholic">
									<footer>
										<a onclick="javascript:submitDrink('nonAlcoholic')" class="button">Submit Order</a>
									</footer>
								</section>
							</div>
							<div class="8u">
								<div class="slider" id="nonAlcoholicSlider">
								</div>
							</div>
								<ul class="mobilenav" style="padding-left:50%" id="mobilenav">
									<li><a href="#Mixed" class="scrolly fa solo"><img src="images/Categories/mixeddrink.png" alt="Mixed Image"></a></li>
									<li><a href="#Shots" class="scrolly fa solo"><img src="images/Categories/shot.png" alt="Shot Image"></a></li>
									<li><a href="#Non_Alcoholic" class="scrolly fa solo"><img src="images/Categories/nonalcoholic.png" alt="Non-Alcoholic Image"></a></li>
									<li><a href="#Custom" class="scrolly fa solo"><img src="images/Categories/customdrink.png" alt="Custom Image"></a></li>
								</ul>
						</div>
					</div>
				</div>
			</section>


		<!-- Custom -->
			<section id="Custom" class="main">
				<header>
					<div class="container">
						<h2>Custom Drink</h2>
					</div>
				</header>
				<div class="content dark style2">
					<div class="header">
						<div align="center">All the power you could ever ask for is right at your fingertips</div><br>
					</div>
					<div class="container small">
						<form id="customForm" method="post" action="newOrder.php?custom=true&drinkName=custom">
							<div class="row">
								<div class="12u" align="center">
									<ul class="actions">
										<li><input type="submit" class="button" value="Order Custom Drink" onclick="javascript:FreeSession();" /></li>
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
									echo '<input type="number" id="parts', $count, '" name="parts', $count, '" class="number" value=0 min="0" name="vol', $count, '" style="display: inline; margin-right: 12px; margin-bottom: 5px;">';
									echo '<input type="button" id="plus', $count, '" name="plus', $count, '" onclick="javascript:onPlusClick();" value="+" alt="New Drink" style="display: inline"></button>';
								} else {
									echo '<input type="number" id="parts', $count, '" name="parts', $count, '" class="number" value=0 min="0" name="vol', $count, '" style="display: none">';
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
								<li><a href="#Mixed" class="scrolly fa solo"><img src="images/Categories/mixeddrink.png" alt="Mixed Image"></a></li>
								<li><a href="#Shots" class="scrolly fa solo"><img src="images/Categories/shot.png" alt="Shot Image"></a></li>
								<li><a href="#Non_Alcoholic" class="scrolly fa solo"><img src="images/Categories/nonalcoholic.png" alt="Non-Alcoholic Image"></a></li>
								<li><a href="#Custom" class="scrolly fa solo"><img src="images/Categories/customdrink.png" alt="Custom Image"></a></li>
							</ul>
						</div>	
					</div>
					<script>
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
				</div>
			</section>
			
		<!-- Feedback -->
			<section id="Feedback" class="main">
				<header>
					<div class="container">
						<h2>How are we doing?</h2>
					</div>
				</header>
				<div class="content dark style2">
					<div class="header">
						<div align="center">Use to form below to give us any feedback or suggestions!</div><br><br>
					</div>
					<div class="container small">
						<form method="post" action="mailCustom.php">
							<div class="row half">
								<div class="6u"><input type="text" class="text" placeholder="Your Name" name="Name" /></div>
								<div class="6u"><input type="text" class="text" placeholder="Your Email" name="Email" /></div>
							</div>
							<div class="row half">
								<div class="12u"><textarea name="message" placeholder="Message" name="Message"></textarea></div>
							</div>
							<div class="row">
								<div class="12u">
									<ul class="actions">
										<li><input type="submit" class="button" value="Send Message" /></li>
										<li><input type="reset" class="button alt" value="Clear Form" /></li>
									</ul>
								</div>
							</div>
						</form>
						<div class="row">
								<ul class="mobilenav" style="padding-left:50%" id="mobilenav">
									<li><a href="#Mixed" class="scrolly fa solo"><img src="images/Categories/mixeddrink.png" alt="Mixed Image"></a></li>
									<li><a href="#Shots" class="scrolly fa solo"><img src="images/Categories/shot.png" alt="Shot Image"></a></li>
									<li><a href="#Non_Alcoholic" class="scrolly fa solo"><img src="images/Categories/nonalcoholic.png" alt="Non-Alcoholic Image"></a></li>
									<li><a href="#Custom" class="scrolly fa solo"><img src="images/Categories/customdrink.png" alt="Custom Image"></a></li>
								</ul>
						</div>
					</div>
				</div>
			</section>
			
		<!-- Footer -->
			<section id="footer">
				<ul class="icons">
					<li><a href="http://twitter.com/justsixguys/" class="fa fa-twitter solo"><span>Twitter</span></a></li>
					<li><a href="https://www.facebook.com/foobartender/" class="fa fa-facebook solo"><span>Facebook</span></a></li>
					<li><a href="About/about.html" class="fa fa-dribbble solo"><span>Dribbble</span></a></li>
					<li><a href="http://html5up.net/" class="fa fa-github solo"><span>GitHub</span></a></li>
					<li><a href="OldSite/AdminPortal.php" class="fa fa-gears"></a></li>
					<li><a href="adminPortal.php" class="fa fa-headphones"></a></li>
				</ul>
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
