/**
 * @category    Mana
 * @package     ManaPro_FilterAjax
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */
// the following function wraps code block that is executed once this javascript file is parsed. Lierally, this 
// notation says: here we define some anonymous function and call it once during file parsing. THis function has
// one parameter which is initialized with global jQuery object. Why use such complex notation: 
// 		a. 	all variables defined inside of the function belong to function's local scope, that is these variables
//			would not interfere with other global variables.
//		b.	we use jQuery $ notation in not conflicting way (along with prototype, ext, etc.)
(function($, undefined) {
    var _productsClicked = false;

	if (window.History && window.History.enabled) {
		$(window).bind('statechange',function(){
			var State = window.History.getState();
			applyFilter(State.url);
		});
	}
	function applyFilter(url) {
		var selectors = $.options('#m-filter-ajax').selectors;
		$(document).trigger('m-ajax-before', [selectors]);
		$.get(window.encodeURI(url + (url.indexOf('?') == -1 ? '?' : '&') + 'm-ajax=1'))
			.done(function(response) {
				try {
					response = $.parseJSON(response);
					if (!response) {
						if ($.options('#m-filter-ajax').debug) {
							alert('No response.');
						}
					}
					else if (response.error) {
						if ($.options('#m-filter-ajax').debug) {
							alert(response.error);
						}
					}
					else {
						$.dynamicReplace(response.update, $.options('#m-filter-ajax').debug, true);
						if (response.options) {
							$.options(response.options);
						}
						if (response.script) {
							$.globalEval(response.script);
						}
						if (response.title) {
						    document.title = response.title;
						}
						rebind();
					}
				}
				catch (error) {
					if ($.options('#m-filter-ajax').debug) {
						alert(response && typeof(response)=='string' ? response : error);
					}
				}					
			})
			.fail(function(error) {
				if ($.options('#m-filter-ajax').debug) {
					alert(error.status + (error.responseText ? ': ' + error.responseText : ''));
				}
			})
			.complete(function() {
				$(document).trigger('m-ajax-after', [selectors, _productsClicked]);
                _productsClicked = false;
			});
		return false; // prevent default link click behavior
	}

	function rememberIfProductsClicked(element) {
        if (element !== undefined && $(element).parents().hasClass('category-products')) {
            _productsClicked = true;
        }
    }
	function hrefClick() {
        rememberIfProductsClicked(this);
        var locationUrl = window.decodeURIComponent(this.href);
		if (window.History && window.History.enabled) {
			
			window.History.pushState(null,window.title,locationUrl);
			return false;
		}
		else {
			return applyFilter(locationUrl);
		}
	}
	function rebind() {
		$.each($.options('#m-filter-ajax').exactUrls, function(urlIndex, url) {
			$('*[href="' + url + '"]').each(function() {
				var anchor = this;
				var isException = false;
				$.each($.options('#m-filter-ajax').urlExceptions, function(urlExceptionIndex, urlException) {
					if (!isException && anchor.href.indexOf(urlException) != -1) {
						isException = true;
					}
				});
				if (!isException) {
					$(anchor).unbind('click', hrefClick).bind('click', hrefClick);
				}
			}); 
		});
		$.each($.options('#m-filter-ajax').partialUrls, function(urlIndex, url) {
			$('*[href*="' + url + '"]').each(function() {
				var anchor = this;
				var isException = false;
				$.each($.options('#m-filter-ajax').urlExceptions, function(urlExceptionIndex, urlException) {
					if (!isException && anchor.href.indexOf(urlException) != -1) {
						isException = true;
					}
				});
				if (!isException) {
					$(anchor).unbind('click', hrefClick).bind('click', hrefClick);
				}
			}); 
		});
	}

	var oldSetLocation = null;
	function setLocation(locationUrl, element) {
        var handled = false;
		$.each($.options('#m-filter-ajax').exactUrls, function(urlIndex, url) {
			if (!handled && locationUrl == url) {
				var isException = false;
				$.each($.options('#m-filter-ajax').urlExceptions, function(urlExceptionIndex, urlException) {
					if (!isException && locationUrl.indexOf(urlException) != -1) {
						isException = true;
					}
				});
				if (!isException) {
					handled = true;
                    rememberIfProductsClicked(element);
                    locationUrl = window.decodeURIComponent(locationUrl);
					if (window.History && window.History.enabled) {
						window.History.pushState(null,window.title,locationUrl);
					}
					else {
						applyFilter(locationUrl);
					}
				}
			}
		});
		$.each($.options('#m-filter-ajax').partialUrls, function(urlIndex, url) {
			if (!handled && locationUrl.indexOf(url) != -1) {
				var isException = false;
				$.each($.options('#m-filter-ajax').urlExceptions, function(urlExceptionIndex, urlException) {
					if (!isException && locationUrl.indexOf(urlException) != -1) {
						isException = true;
					}
				});
				if (!isException) {
					handled = true;
                    rememberIfProductsClicked(element);
                    locationUrl = window.decodeURIComponent(locationUrl);
					if (window.History && window.History.enabled) {
						window.History.pushState(null,window.title,locationUrl);
					}
					else {
						applyFilter(locationUrl);
					}
				}
			}
		});
		if (!handled) {
			oldSetLocation(locationUrl);
		}
	}
	// the following function is executed when DOM ir ready. If not use this wrapper, code inside could fail if
	// executed when referenced DOM elements are still being loaded.
	$(function() {
		rebind();
		if (window.setLocation) {
			oldSetLocation = window.setLocation;
			window.setLocation = setLocation;
		}
	});
	
})(jQuery);
