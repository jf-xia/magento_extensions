$(document).ready(function(){
	var MSIE8 = ($.browser.msie) && ($.browser.version <= 8);
    var aniButtonDuration = 350;
    if(MSIE8){
        aniButtonDuration = 0;
    }
	$.fn.ajaxSwitcher({
		articleTopPosition:0,
        mainPageHash:"#!/home",
		menuInit:function(mainMenu, classMenu, classSubMenu){
		  var itemsWidth = new Array()
          var index = 0;
            $("ul", mainMenu).each(function(){
			    $(this).width('234px');
			})
			$("li", mainMenu).each(function(){
			    itemsWidth[index] = $(this).find('a').width();
                index++;
			})
            index = 0;
            $("li", mainMenu).each(function(){
               $(this).width(itemsWidth[index]);
               index++;
			})  
            
		},
		buttonOver:function(item){
            $(">a", item).css({color:'#e26c27'});
		},
		buttonOut:function(item){
            $(">a", item).css({color:'#fff'});
		},
		subMenuShow:function(item, sClass){
  		    $(">"+sClass, item).stop(true, true).slideDown(400, "easeOutCubic");
		},
		subMenuHide:function(item, sClass){
			$(">"+sClass, item).stop(true, true).slideUp(400, "easeOutCubic");
		},
		pageInit:function (pageList){
			$("article", pageList).css({left:$(document).width(), "display":"none"});
			$(pageList).css({height:0});
            $("#page_layout_cover").stop(true).fadeOut(0);
		},	
		prevPageAnimate:function(page){
			page.stop(true).animate({left:-$(document).width()*2}, 600, "easeInCubic", function(){
				page.css({"display":"none"}).contents().remove();
			});
		},
		currPageAnimate:function(page){
			page.css({left:$(document).width(), "display":"block"}).stop(true).animate({left:0}, 800, "easeInOutCubic")
			$("#controls").stop(true).fadeOut(500);
            $("#page_layout_cover").stop(true).fadeIn(600);
		},
		backToSplash:function(){
			$("#controls").stop(true).delay(500).fadeIn(600);
			$("#page_layout_cover").stop(true).delay(500).fadeOut(600);
		},
		pageLoadComplete:function(){
			$("a[rel^='fancybox']").fancybox({'speedIn'  : 300, 'speedOut'  : 300});
			$(".zoom-icon").stop(true).fadeTo(0, 0);
            $(".thumbnail, .imgList li a").live("mouseover",
		 		function(){
					$(".zoom-icon", this).stop(true).fadeTo(300, 1);
				}
			)
			$(".thumbnail, .imgList li a").live("mouseout",
				function(){
					$(".zoom-icon", this).stop(true).fadeTo(300, 0);
				}
			)
		},
	})
})

$(window).load(function(){
	// Init for audiojs
	audiojs.events.ready(function() {
		var as = audiojs.createAll();
	});
	/*!!!!HACK!!!!!*/
	$("a:[href='#']").live("click", function(){return false})
	/*!!!!end HACK!!!!!*/		
	
	$("#spinnerBG").delay(0).animate({scale:0}, 800, "easeInOutCubic", function(){$("#spinnerBG").remove()});
    
    
    var navigation = $('#menuSlider');
    var nav = $('#menuSlider > nav');
    var menuBtn = $('#menuSlider > span');
    var cover = $('#menuSlider > div');
    var menuH = nav.height() + 55;
    var timeoutId;
    
    nav.css({top: -menuH});
    
    navigation.hover(function()
    {
        clearTimeout(timeoutId);
        showMenu();				   
    }, function(){
    	timeoutId = setTimeout(hideMenu, 1000);				   
    })	
    
    function showMenu(){
        menuBtn.stop().animate({top:-menuBtn.height()}, 200, 'easeInSine',function(){
            menuBtn.css({display:'none'});
        });
        nav.stop().delay(150).animate({top:0}, 350, 'easeOutCubic', function(){
            cover.css({display:'none'});
        });
    }
    function hideMenu(){
        menuBtn.stop().delay(150).animate({top:0}, 350, 'easeOutCubic');
        nav.stop().animate({top:-menuH}, 200, 'easeInSine');
        cover.css({display:'block'});
        menuBtn.css({display:'block'});
    }	
});

