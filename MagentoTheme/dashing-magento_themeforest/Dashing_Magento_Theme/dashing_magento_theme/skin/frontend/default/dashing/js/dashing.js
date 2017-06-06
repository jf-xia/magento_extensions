/*!
 * Dashing Magento Theme
 * Copyright (c) 2011 Worry Free Labs, LLC. (http://worryfreelabs.com/)
 */

jQuery.noConflict();

jQuery(function($) {
	$('#nav > li.parent').each(function(){
		var _width = $(this).find('ul.level0 > li').length * 175;
		$(this).find('ul.level0').width(_width);
	});
	
	$('#nav ul.level0, .mini-cart').before('<span class="space"> </span>');
	
	$('#navigation .searchbtn').hover(function() {
		$('#search').focus();
		$(this).find('.form-search').show();
	}).focusout(function() {
		$(this).find('.form-search').hide();
	});
	
	$('.button').hover(
		function() {
			$(this).animate({opacity: 0.8}, 'fast');
		},
		function() {
			$(this).animate({opacity: 1}, 'fast');
		}
	);
	
	$('#slider > div').easySlider({
		prevId:	'galleryLeft',
		nextId:	'galleryRight'
	});
	
	$('#sticky-1 > div').easySlider({
		prevId:	'stickyLeft1',
		nextId:	'stickyRight1'
	});

	$('#sticky-2 > div').easySlider({
		prevId:	'stickyLeft2',
		nextId:	'stickyRight2'
	});

	$('#sticky-3 > div').easySlider({
		prevId:	'stickyLeft3',
		nextId:	'stickyRight3'
	});

	$('#sticky-4 > div').easySlider({
		prevId:	'stickyLeft4',
		nextId:	'stickyRight4'
	});

	$('.upsell-list > div').easySlider({
		prevId:	'upsellLeft',
		nextId:	'upsellRight'
	});

	$('a.fancybox').fancybox({
		titlePosition: 'inside'
	});
	
	$('a.quickview').fancybox({
		autoScale		: false,
		autoDimensions	: false,
		width			: 650,
		height			: 650,
		onComplete		: function() {
			var $fancyboxInner = $('#fancybox-inner');
			
			$('.upsell-list > div', $fancyboxInner).easySlider({
				prevId:	'upsellLeft',
				nextId:	'upsellRight'
			});
			
			$('.quickview-thumbs a', $fancyboxInner).bind('click', function(event) {
				event.preventDefault();
				$('#quick_image', $fancyboxInner).attr('src', $(this).attr('href'));
			});
		}
	});
});