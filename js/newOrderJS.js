$(document).ready(function() {
	$("body").css("display", "none");
	$("body").fadeIn(500);
	$('input[id="rate"]').click(function(){
		$.ajax({
			url: 'rateDrink.php',
			type: 'post',
			data: {
				rating: $('#rating').val()
			},
			success: function() {},
			
			error: function() {
				alert('Failed to rate drink.');
			}
		});
	
		$("#rateSection").fadeOut(500);
	});
});

function fadeout() {
	$("body").fadeOut(500);
}