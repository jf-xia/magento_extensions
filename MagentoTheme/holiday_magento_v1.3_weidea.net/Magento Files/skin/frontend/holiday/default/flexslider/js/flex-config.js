jQuery(function(j$) {

// related slider 

j$('#related-slider').flexslider({
	selector: ".slides > ul",
        animation: "slide",
        animationLoop: false,
        itemWidth: 232,
        itemMargin: 0,
        minItems: 1,
        maxItems: 5,
	//move:1,
	controlNav: false,
	slideshowSpeed: 7000,          
	animationSpeed: 1000,
	slideshow: false,
	start: function(slider){
          j$('div').removeClass('loading');
        }
});

// related slider 

// upsell slider 

j$('#upsell-slider').flexslider({
	selector: ".slides > ul",
        animation: "slide",
        animationLoop: false,
        itemWidth: 232,
        itemMargin: 0,
        minItems: 1,
        maxItems: 5,
	//move:1,
	controlNav: false,
	slideshowSpeed: 7000,          
	animationSpeed: 1000,
	slideshow: false,
	start: function(slider){
          j$('div').removeClass('loading');
        }
});

// upsell slider 

// crosssell slider 

 j$('#crosssell-slider').flexslider({
	selector: ".slides > ul",
        animation: "slide",
        animationLoop: false,
        itemWidth: 232,
        itemMargin: 0,
        minItems: 1,
        maxItems: 5,
	//move:1,
	controlNav: false,
	slideshowSpeed: 7000,          
	animationSpeed: 1000,
	slideshow: false,
	start: function(slider){
          j$('div').removeClass('loading');
        }
});

// crosssell slider

});