$(document).ready(function() {
	$("body").css("display", "none");
	$("body").fadeIn(500);
	$('#rateButton').click(function(){
		$.ajax({
			url: '/Orders/rateDrink.php',
			type: 'post',
			data: {
				rating: $('#rating').val()
			},
			success: function() {
				$("#RateHeader").html("Thanks for rating, you're the best!");
			},
			
			error: function() {
				alert('Failed to rate drink.');
			}
		});
	
		$("#RateButtons").fadeOut(500);
	});
	
	$('#cancelButton').click(function(){
		$.ajax({
			url: '/Orders/CancelOrder.php',
			type: 'post',
			data: {
				deleteSessionOrder: true
			},
			success: function() {
				$("#CancelOrderHeader").html('"There is nothing new except what has been forgotten."<br>― Marie Antoinette');
			},
			
			error: function() {
				alert('Failed to clear order...');
			}
		});
		
		$("#CancelOrderButton").fadeOut(500);
	});
});

function fadeout() {
	$("body").fadeOut(500);
}