$(document).ready(function(){
	
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

function clearEntireQueue() {
	$.ajax({
		url: 'Utilities/ClearQueue.php',
		type: 'get',
		success: function(data) {
			//We could potentially put debugging statements here for a failed ClearQueue
			$('#clearQueueDebug').html(data);
			location.reload();
		},
		async: true
	});
}

function clearFromQueue(stationIndex)
{
	$.ajax({
		url: 'Utilities/ClearQueue.php',
		type: 'get',
		success: function(data) {
			//We could potentially put debugging statements here for a failed ClearQueue
			$('#clearQueueDebug').html(data);
			location.reload();
		},
		async: true,
		data: { station: stationIndex }
	});
}

function swapIn(button)
{
	var thisButton = $(button);
	var nextButton = thisButton.siblings("a.swapOff");
	var parentDiv = thisButton.parent();
	var volumeDiv = parentDiv.siblings("div.table_c4");
	var input = volumeDiv.children(".swapOff");
	var valueSpan = volumeDiv.children(".swapOn");
	
	var currentValue = valueSpan.html();
	
	
	thisButton.hide();
	nextButton.show();
	valueSpan.hide();
	input.fadeIn("slow");
	input.val(currentValue);
}

function swapOut(button)
{
	var thisButton = $(button);
	var nextButton = thisButton.siblings("a.swapOn");
	var parentDiv = thisButton.parent();
	var volumeDiv = parentDiv.siblings("div.table_c4");
	var input = volumeDiv.children(".swapOff");
	var valueSpan = volumeDiv.children(".swapOn");
	
	var currentValue = valueSpan.html();
	
	
	thisButton.hide();
	nextButton.show();
	valueSpan.fadeIn("slow");
	input.hide();
	input.val(currentValue);
}


function updateStationVolume(button)
{
	var button = $(button);
	var stationIndex = button.parent().siblings().first(".table_c3").html();
	var div = button.parent().siblings("div.table_c4");
	var value = div.children("input").val();
	var prevValue = div.children("span").html();
	
	
	
	
	$.ajax({
		url: 'Utilities/SetStationVolume.php',
		type: 'get',
		success: function(data) {
			//We could potentially put debugging statements here for a failed ClearQueue
			$('#setVolumeDebug').html(data);
			location.reload();
		},
		async: true,
		data: { 
			station: stationIndex,
			volume: value
		}
	});
}