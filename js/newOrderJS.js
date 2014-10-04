$(document).ready(function() {
	$("body").css("display", "none");
	$("body").fadeIn(500);
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
});

function fadeout() {
	$("body").fadeOut(500);
}