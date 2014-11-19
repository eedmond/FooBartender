
$(function() {
	
	$('#fadeBarrier').hide();
	$('#tableSide').hide();
	
	var gridHeight = $('#grid').height();
	$('#tableSide').height(gridHeight);
	
	ResizeSquares();
	
	$(window).resize(function() {
		ResizeSquares();
	});
});

function ResizeSquares()
{
	$('.square').each(function() {
		var w = $(this).width();
		$(this).css({'height': w+'px'});
	});
	
	var wCol = $('div.col.square').width();
	$('div.col.square').css({'height': wCol+'px'});
}

function StationClick(element)
{
	if (!$('#tableSide').is(':visible'))
	{
		var stationNumber = $(element).children('.stationLabel').html();
		$('#tableSide').find('.stationValue').html(stationNumber);
		
		$('#tableSide').toggle('slow', function() {
			$('#fadeBarrier').toggle('fast');
		});
	}
	else
	{
		$('#fadeBarrier').toggle('fast', function() {
			$('#tableSide').toggle('slow');
		});
		
		
	}
}