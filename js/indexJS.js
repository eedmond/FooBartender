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
		{
			return;
		}
        var posFromTop = $(window).scrollTop();

        if(posFromTop+1 > $('#Mixed').offset().top)
		{
			if (!$('#NavTool').is(":visible"))
			{
				$('#NavTool').fadeIn();
			}
        } else
		{
			if ($('#NavTool').is(":visible"))
			{
				$('#NavTool').fadeOut();
			}
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
	if ($("#"+type+"Slider .image.selected").hasClass("selected"))
	{
		return;
	}
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
	xmlhttp.open("GET", "Utilities/session_free.php", false);
	xmlhttp.send();
}

function submitDrink(drinkType)
{
	FreeSession();
	var drinkData = $(".image.selected");
	var mixedName = drinkData.attr('name').substring(17, drinkData.attr('name').search("</h1>"));
	var drinkAmountSelected = document.getElementById('drinkAmount').selectedIndex;
	var drinkAmount = "full";
	if (drinkAmountSelected == 1)
	{
		drinkAmount = "half";
	}
	else if (drinkAmountSelected == 2)
	{
		drinkAmount = "taste";
	}
	var surpriseType = "";
	if (mixedName == "Eric's Jamaican Surprise")
	{
		if (drinkData.parents(".slider").attr('id') == "mixedDrinkSlider")
		{
			surpriseType = "|Mixed Drink";
		}
		else
		{
			surpriseType = "|Non-Alcoholic";
		}
	}
	$("body").fadeOut(1000);
	location.href = "Orders/newOrder.php?orderType=Mixed&drinkName=" + mixedName + surpriseType + "&drinkAmount=" + drinkAmount;
}

function submitShot()
{
	FreeSession();
	var drinkData = $(".image.selected");
	var shotName = drinkData.attr('name').substring(17, drinkData.attr('name').search("</h1>"));
	var surpriseType = "";
	if (shotName == "Eric's Jamaican Surprise")
		surpriseType = "|Shot";
		
	$("body").fadeOut(1000);
	location.href = "Orders/newOrder.php?orderType=Shot&drinkName=" + shotName + surpriseType;
}

// Custom Drink JS
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