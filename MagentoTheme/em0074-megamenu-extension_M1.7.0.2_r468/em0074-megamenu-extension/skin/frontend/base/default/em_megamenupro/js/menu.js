/**
 * EM MegaMenuPro
 *
 * @license commercial software
 * @copyright (c) 2012 Codespot Software JSC - EMThemes.com. (http://www.emthemes.com)
 */

(function($) {

var ANIMATION_SPEED = 'normal';


var isMobile = /iPhone|iPod|iPad|Phone|Mobile|Android|hpwos/i.test(navigator.userAgent);
var isPhone = /iPhone|iPod|Phone|Android/i.test(navigator.userAgent);

function isMobileView() {
	return $('body').hasClass('adapt-0');
}

/**
 * Make menu support on mobile
 */
function mobile() {
	
	var skipHashChange = false;
	
	/**
	 * Restore selected menu position when browser Hash change
	 */
	function hashchange() {
		if (!isMobileView()) return;
		
		if (skipHashChange) {
			skipHashChange = false;
			return;
		}
		
		var m;
		if (location.hash && (m = location.hash.match(/^#menu\/(.*)$/))) {
			var hash = m[1].split('/');
			if (hash.length == 0) return;
			
			var $nav = $('.em_nav').eq(hash.shift()).find('.hnav, .vnav');
			var $el = $nav;
			
			if ($el.length > 0) {
				$('.mhover', $nav).removeClass('mhover');
				
				var left = 0;
				while (hash.length > 0) {
					$el = $el.children().eq(hash.shift());
					if ($el.hasClass('menu-item-link') 
					|| $el.get(0).tagName =='LI' && $el.parents('.em-catalog-navigation').length > 0) {
						$el.addClass('mhover');
						left -= $nav.width();
					}
				}
				// $nav.css('margin-left', left + 'px');
				$nav.animate({
					'margin-left': left + 'px'
				}, ANIMATION_SPEED);
			}
		}
		else if (!location.hash) {
			var $nav = $('.em_nav .hnav, .em_nav .vnav');
			$('.mhover', $nav).removeClass('mhover');
			// $nav.css('margin-left', '');
			$nav.animate({
				'margin-left': ''
			}, ANIMATION_SPEED);
		}
	}
	hashchange();
	
	$(window).hashchange(hashchange)

	// when layout changed, update menu to mobile or normal accordingly
	$(window).bind('emadaptchange', function() {
		if (isMobileView()) {
			hashchange();
		}
		else {
			// reset mega menu if not mobile view
			$('.em_nav .hnav, .em_nav .vnav').css('margin-left', '')
				.find('.mhover').removeClass('mhover');
		}
	});
	
	
	$('.em_nav').each(function(i) {
		var $nav = $('.hnav, .vnav', this);
	
		
		// prepend a.arrow into parent LI 
		$('.em-catalog-navigation li.parent, .menu-item-parent', $nav).each(function() {
			$(this).prepend('<a href="#" class="arrow"><span>&gt;</span></a>');
		});
		
		// bind event when click on a.arrow sliding to the sub menu horizontally
		$('a.arrow', $nav).bind(isMobile ? 'click mouseenter' : 'click', function(event) {
			if (!isMobileView()) return;
		
			event.preventDefault();
			event.stopPropagation();
		
			var $li = $(this.parentNode);

		
			if ($li.hasClass('mhover')) return;
			
			// fix bug event called twice cause menu sub menu showed even not clicked
			// don't know why it happens!!!
			if (event.which == 0 || event.screenX == 0 && event.screenY == 0) return;
		
			// add class .mhover to LIs of this branch
			$('.mhover', $nav).not($li.parents('.mhover')).removeClass('mhover');
			$li.addClass('mhover');

			// build hash string
			var idx = [ $li.index() ];
			$li.parentsUntil($nav).each(function() {
				idx.unshift($(this).index());
			});
		
			/* --- use hashchange event instead
			skipHashChange = true; --- */
			location = '#menu/' + i + '/' + idx.join('/');
		
			// show the next child menu
			// $nav.css('margin-left', parseInt($nav.css('margin-left')) - $nav.width() + 'px');
			/* --- use hashchange event instead
			$nav.animate({
				'margin-left': parseInt($nav.css('margin-left')) - $nav.width() + 'px'
			}, ANIMATION_SPEED); --- */
		});
		
		
			// .each(function() {
			// 	$(this).touchwipe({
			// 		wipeLeft: function() {
			// 			$(this).trigger('click');
			// 		}.bind(this),
			// 		wipeRight: function() {
			// 			if (location.hash)	history.back();
			// 		}.bind(this),
			// 		preventDefaultEvents:false
			// 	});
			// });
			

	});

}


/**
 * Fix mega menu drop-down's container overflows the right edge of page.
 *
 * Should be called once when document ready
 */ 
function fixMegaMenuOverflow() {
	function fix($container, $nav) {
		var pad = $nav.offset().left + $nav.outerWidth() - ($container.offset().left + $container.outerWidth());
		var pad2 = $container.offset().left + pad - $nav.offset().left;
		if (pad2 < 0) pad = pad - pad2;
		if (pad < 0) $container.css('left', pad+'px').data('last-left', pad);
	}

	$('.em_nav .menu-item-link > .menu-container').parent().hover(function() {
		var $container = $(this).children('.menu-container');
		var $nav = $(this).parents('.em_nav').first();
		
		if ($container.data('last-left'))
			$container.css('left', $container.data('last-left')+'px');

		$.browser.msie ? fix.delay(0.01, $container, $nav) : fix($container, $nav);
		
	}, function() {
		$(this).children('.menu-container').css('left', '');
	});
}

$(document).ready(function() {
	fixMegaMenuOverflow();
	mobile();
});

})(jQuery);
