// JavaScript Document
jQuery.noConflict();
	function showOptions(id){
		jQuery('#fancybox'+id).delay(1000).trigger('click');
	}
	function setAjaxData(data,iframe){
		if(data.status == 'ERROR'){
			alert(data.message);
		}else{
			if(jQuery('.block-cart')){
	            jQuery('.block-cart').replaceWith(data.sidebar);
	        }
	        if(jQuery('.header .links')){
	            jQuery('.header .links').replaceWith(data.toplink);
	        }
			jQuery('body,html').animate({
				scrollTop: 0
			}, 800);
			jQuery('#minicart').fadeIn();
			setTimeout(function(){jQuery('#minicart').fadeOut();},2000);
	        jQuery.fancybox.close();
		}
	}
	function setLocationAjax(url,id){
		url += 'isAjax/1';
		url = url.replace("checkout/cart","ajax/index");
		jQuery('#ajax_loader'+id).show();
		try {
			jQuery.ajax( {
				url : url,
				dataType : 'json',
				success : function(data) {
					jQuery('#ajax_loader'+id).hide();
         			setAjaxData(data,false);           
				}
			});
		} catch (e) {
		}
	}
	jQuery(document).ready(function(){
	  jQuery('.fancybox').fancybox(

			{

			   hideOnContentClick : true,

			   width: 580,

			   autoDimensions: true,

               type : 'iframe',

			   showTitle: false,

			   scrolling: 'no',

			   onComplete: function(){

				jQuery('#fancybox-frame').load(function() {

					 // wait for frame to load and then gets it's height

					jQuery('#fancybox-content').height(jQuery(this).contents().find('body').height()+0);

					jQuery.fancybox.resize();

				 });



			   }

			}

		);
		}); 
		jQuery(window).scroll(function(){
	  jQuery('.fancybox').fancybox(

			{

			   hideOnContentClick : true,

			   width: 580,

			   autoDimensions: true,

               type : 'iframe',

			   showTitle: false,

			   scrolling: 'no',

			   onComplete: function(){

				jQuery('#fancybox-frame').load(function() {

					 // wait for frame to load and then gets it's height

					jQuery('#fancybox-content').height(jQuery(this).contents().find('body').height()+0);

					jQuery.fancybox.resize();

				 });



			   }

			}

		);
		});     
