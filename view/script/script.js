$(document).ready(function () {
	$('.head').fadeOut(200).fadeIn(500);
	setTimeout(function () {
		$('.container').css('transition', '1s').removeClass('invisible');
	}, 200);
});