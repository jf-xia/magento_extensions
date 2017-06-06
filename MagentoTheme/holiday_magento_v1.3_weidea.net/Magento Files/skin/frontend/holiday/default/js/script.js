 var j$ = jQuery.noConflict();
//jQuery.noConflict();


/* back to top */

j$(document).ready(function(){ 
 
        j$(window).scroll(function(){
            if (j$(this).scrollTop() > 100) {
                j$('.scrollup').fadeIn();
            } else {
                j$('.scrollup').fadeOut();
            }
        }); 
 
        j$('.scrollup').click(function(){
            j$("html, body").animate({ scrollTop: 0 }, 600);
            return false;
        });
 
    });

/* back to top */

/* menu responsive */

var ww = j$(window).width();

j$(document).ready(function() {
  j$("#nav li a").each(function() {
    if (j$(this).next().length > 0) {
    	j$(this).addClass("parent");
		};
	})
	
	j$(".toggleMenu").click(function(e) {
		e.preventDefault();
		j$(this).toggleClass("active");
		j$("#nav").toggle();
	});
	adjustMenu();
})

j$(window).bind('resize orientationchange', function() {
	ww = j$(window).width();
	adjustMenu();
});

var adjustMenu = function() {
	if (ww < 1023) {
    // if "more" link not in DOM, add it
    if (!j$(".more")[0]) {
    j$('<div class="more">&nbsp;</div>').insertBefore(j$('li a.parent')); 
    }
		j$(".toggleMenu").css("display", "inline-block");
		if (!j$(".toggleMenu").hasClass("active")) {
			j$("#nav").hide();
		} else {
			j$("#nav").show();
		}
		j$("#nav li").unbind('mouseenter mouseleave');
		j$("#nav li a.parent").unbind('click');
    j$("#nav li .more").unbind('click').bind('click', function() {
			
			j$(this).parent("li").toggleClass("hover");
		});
	} 
	else if (ww >= 1023) {
    // remove .more link in desktop view
    j$('.more').remove(); 
		j$(".toggleMenu").css("display", "none");
		j$("#nav").show();
		j$("#nav li").removeClass("hover");
		j$("#nav li a").unbind('click');
		j$("#nav li").unbind('mouseenter mouseleave').bind('mouseenter mouseleave', function() {
		 	// must be attached to li so that mouseleave is not triggered when hover over submenu
		 	j$(this).toggleClass('hover');
		});
	}
}

/* menu responsive */

jQuery(function(j$) {

// cart 

j$(".shopping_bg").hover(function () {
   j$('.slideTogglebox').slideToggle(200);
});

// cart 

// header fixed 

var myHeader = j$('.header');
myHeader.data( 'position', myHeader.position() );
j$(window).scroll(function(){
    var hPos = myHeader.data('position'), scroll = getScroll();
    if ( hPos.top < scroll.top ){
        myHeader.addClass('fixed');
    }
    else {
        myHeader.removeClass('fixed');
    }
});

function getScroll () {
    var b = document.body;
    var e = document.documentElement;
    return {
        left: parseFloat( window.pageXOffset || b.scrollLeft || e.scrollLeft ),
        top: parseFloat( window.pageYOffset || b.scrollTop || e.scrollTop )
    };
}

// header fixed 

// language

j$(".header_language").hover(function() {
	j$(this).addClass('active');
	j$(".language_detail").stop(true, true).slideDown(200, "easeInSine");
},  
function() {
	j$(this).removeClass('active');
	j$(".language_detail").stop(true, true).fadeOut(200, "easeInSine");
});

// language

// currency

j$(".header_currency").hover(function() {
	j$(this).addClass('active');
	j$(".currency_detail").stop(true, true).slideDown(200, "easeInSine");
},  
function() {
	j$(this).removeClass('active');
	j$(".currency_detail").stop(true, true).fadeOut(200, "easeInSine");
});

// currency



});
