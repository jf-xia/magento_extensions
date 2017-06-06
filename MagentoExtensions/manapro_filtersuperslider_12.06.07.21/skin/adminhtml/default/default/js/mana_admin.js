/**
 * @category    Mana
 * @package     Mana_Admin
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
// the following function wraps code block that is executed once this javascript file is parsed. Lierally, this 
// notation says: here we define some anonymous function and call it once during file parsing. THis function has
// one parameter which is initialized with global jQuery object. Why use such complex notation: 
// 		a. 	all variables defined inside of the function belong to function's local scope, that is these variables
//			would not interfere with other global variables.
//		b.	we use jQuery $ notation in not conflicting way (along with prototype, ext, etc.)
(function(window, $) {
	window.varienWindowOnload = function() {};
	
	// the following function is executed when DOM ir ready. If not use this wrapper, code inside could fail if
	// executed when referenced DOM elements are still being loaded.
	$(function() {
		$('input,select,textarea').live('change', function() {
			this.setHasChanges(this);
		});
		// UI logic for "use default value" checkboxes
		$('input.m-default').live('click', function() {
			var fieldId = this.id.substring(0, this.id.length - '_default'.length);
			if ($('#'+fieldId).length) {
				if (this.checked) {
					$('#'+fieldId).attr('disabled', true).addClass('disabled');
				}
				else {
					$('#'+fieldId).removeAttr('disabled').removeClass('disabled').focus();
				}
			}
			else {
				//throw 'Field for editing not found!';
			}
		});
		
		// UI logic for standard buttons
		$('button.m-close-action').live('click', function() {
			window.location.href = $.options('button.m-close-action').redirect_to;
		});
		$('button.m-save-action').live('click', function() {
			var request = [];
			if ($.options('edit-form') && $.options('edit-form').subforms) {
				$.each($.options('edit-form').subforms, function(index, formId) {
					$.merge(request, $(formId).serializeArray());
				});
			}
			$(document).trigger('m-before-save', [request]);

			$.mAdminPost($.options('button.m-save-action').action, request, function (response) {
                $.dynamicUpdate(response.update);
                if (response.refresh_redirect) {
                    window.location.href = response.refresh_redirect;
                }
            });
		});
		$('button.m-save-and-close-action').live('click', function() {
			var request = [];
			if ($.options('edit-form') && $.options('edit-form').subforms) {
				$.each($.options('edit-form').subforms, function(index, formId) {
					$.extend(request, $(formId).serializeArray());
				});
			}
            $(document).trigger('m-before-save', [request]);

            $.mAdminPost($.options('button.m-save-and-close-action').action, request, function (response) {
                $.dynamicUpdate(response.update);
                if (!response.error) {
                    window.location.href = $.options('button.m-save-and-close-action').redirect_to;
                }
            });
		});
	});
	$.mAdminPost = function(url, request, callback) {
        $('#loading-mask').show();
        $.post(url, request)
            .done(function(response) {
                try {
                    if (response.isJSON()) {
                        response = $.parseJSON(response);
                        if (response.error && response.message) {
                            alert(response.message);
                        }
                        if (response.ajaxExpired && response.ajaxRedirect) {
                            setLocation(response.ajaxRedirect);
                        }
                        callback(response);
                    }
                    else {
                        callback(response);
                    }
                }
                catch (error) {
                    $.errorUpdate($.options('edit-form').messagesSelector, response || error.message || error);
                }
            })
            .fail(function (error) {
                $.errorUpdate($.options('edit-form').messagesSelector, error);
            })
            .complete(function () {
                $('#loading-mask').hide();
            });
    };
	$.mAdminResponse = function(response, callback) {
        try {
            if (response.isJSON()) {
                if (response.error) {
                    alert(response.message);
                }
                if (response.ajaxExpired && response.ajaxRedirect) {
                    setLocation(response.ajaxRedirect);
                }
            }
            else {
                callback(response);
            }
        }
        catch (error) {
            $.errorUpdate($.options('edit-form').messagesSelector, response || error.message || error);
        }
    };
})(window, jQuery);
