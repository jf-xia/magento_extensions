



jQuery(function($) {

    $('.ajx-cart').live('click', function () {
        if ( $(window).width() < 769 )  {
            return false;
        }
        var cart = $('.shopping_bg');
        var imgtodrag = $(this).parents('li.item').find('a.product-image img:not(.back_img)').eq(0);
        if (imgtodrag) {
            var imgclone = imgtodrag.clone()
                .offset({ top:imgtodrag.offset().top, left:imgtodrag.offset().left })
                .css({'opacity':'0.7', 'position':'absolute', 'height':'150px', 'width':'150px', 'z-index':'1000'})
                .appendTo($('body'))
                .animate({
                    'top':cart.offset().top + 10,
                    'left':cart.offset().left + 30,
                    'width':55,
                    'height':55
                }, 1000, 'easeInOutExpo');
            imgclone.animate({'width':0, 'height':0}, function(){ $(this).detach() });
        }
        return false;
    });


  

});

jQuery(function($) {
	
	
	$('.fancybox').live('click', function() {
        
        
            $this = $(this);
            $.fancybox({
			   hideOnContentClick : true,
			   width: 630,
			   autoDimensions: true,
                            type : 'iframe',
                            href: $this.attr('href'),
			   showTitle: false,
			   scrolling: 'no',
			   onComplete: function(){
                                $('#fancybox-loading').show();
				$('#fancybox-frame').load(function() { // wait for frame to load and then gets it's height
					$('#fancybox-loading').hide();
					$('#fancybox-content').height($(this).contents().find('body').height()+30);
					$.fancybox.resize();
				 });

			   }
	    });
        return false;
	});
	
	function showOptions(id){
		$('#fancybox'+id).trigger('click');
	}
	
	
});	 


        function setAjaxData(data,iframe){
		if(data.status == 'ERROR'){
			alert(data.message);
		}else{
		    successMessage(data.message);
			if(jQuery('.block-cart')){
	            jQuery('.block-cart').replaceWith(data.sidebar);
	        }
	        if(jQuery('.header .links')){
	            jQuery('.header .links').replaceWith(data.toplink);
	        }
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