$(document).ready(function () {
	$('.head').fadeOut(200).fadeIn(300);
});

$('.option-div-list input, #record-count').on('change', function () {
	updateContent(false);
});

$('.option-div-list a').on('click', function (e) {
	updateContentPrevent(e, this);
});

function updateContentPrevent(e, product) {
	e.preventDefault();
	updateContent($(product).attr('href'));
}

function updateContent(product = false) {
	var reviews = $('.col-60');
	var sort = $('.option-div-list input:checked').val();
	var limit = $('#record-count').val();
	var h1 = $('h1').text();
	var href = $('h1').attr('data-href');
	var updateFilterProducts = false;
	if (product) {
		href = product;
		updateFilterProducts = product;
	}
	$.ajax({
		url: '/ajax/requests.php?action=sortPreviews',
		type: 'post',
		data: 'sort=' + sort + '&limit=' + limit + '&h1=' + h1 + '&href=' + href,
		dataType: 'html',
		beforeSend: function () {
			reviews.fadeOut(300);
		},
		success: function (html) {
			if (updateFilterProducts) {
				$('.option-div-list p').each(function () {
					let name = $(this).text();
					let dataName = $(this).attr('data-name');
					if (href !== dataName) {
						$(this).html('<a href="' + dataName + '">' + name + '</a>');
					} else {
						$(this).html(name);
					}
				});
				$('.option-div-list a').on('click', function (e) {
					updateContentPrevent(e, this);
				});
			}
			reviews.replaceWith(html);
			window.history.pushState('', '', href);
		},
		complete: function () {
			$('.col-60').fadeOut(0).fadeIn(500);
		}
	});
}