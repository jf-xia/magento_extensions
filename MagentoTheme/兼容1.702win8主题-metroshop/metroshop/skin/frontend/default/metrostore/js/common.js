// JavaScript Document
jQuery(document).ready(function(){
	jQuery("[rel=tooltip]").tooltip();
	jQuery("#back-top").hide();
	// fade in #back-top
	// scroll body to 0px on click
		jQuery('#back-top a').click(function () {
			jQuery('body,html').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
		jQuery('.menuBox').click(function() {
		if (jQuery('#menuInnner').is(":hidden"))
		{
		jQuery('#menuInnner').slideDown("fast");
		} else {
		jQuery('#menuInnner').slideUp("fast");
		}
		return false;
		});
		if(jQuery(window).width() <= 1025){
			jQuery('.nav-container').hide();
			jQuery('.mobMenu').css('display','table');
		}else{
			jQuery('.nav-container').css('display','table');
			jQuery('.mobMenu').hide();
			}
		jQuery('.bottomBox, .ic_caption, .wrapper').contents().filter(function() {
			return this.nodeType === 3;
		}).remove();

	});
	jQuery(window).scroll(function () {
			if (jQuery(this).scrollTop() > 100) {
				jQuery('#back-top').fadeIn();
			} else {
				jQuery('#back-top').fadeOut();
			}
			
			if (jQuery(this).scrollTop() >200) {
				jQuery('.nav-width').addClass('scrollNav');
			} else {
				jQuery('.nav-width').removeClass('scrollNav');
			}
		});
		
	jQuery(function() {
		   jQuery("img.lazy").lazyload();
	});
	
		
	jQuery(window).resize(function(){
		if(jQuery(window).width() <= 1025){
			jQuery('.nav-container').hide();
			jQuery('.mobMenu').css('display','table');
		}else{
			jQuery('.nav-container').css('display','table');
			jQuery('.mobMenu').hide();
			}
		});