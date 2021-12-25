$(document).ready(function () {
	$('.head').fadeOut(200).fadeIn(500);
});

$('.option-div-list input, #record-count').on('change', function () {
	let sort = $('.option-div-list input:checked').val();
	let limit = $('#record-count').val();
	let h1 = $('h1').text();
	$.ajax({
		url: '/ajax/requests.php?action=sortPreviews',
		type: 'post',
		data: 'sort=' + sort + '&limit=' + limit + '&h1=' + h1,
		dataType: 'html',
		success: function (html) {
			let reviews = $('.col-60');
			reviews.removeAttr('style').fadeOut(100);
			setTimeout(function () {
				reviews.replaceWith(html);
				reviews = $('.col-60');
				reviews.fadeOut(0).fadeIn(200);
			}, 100);
		}
	});
})