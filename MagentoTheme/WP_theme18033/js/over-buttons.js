$(document).ready(function(){
	$.fn.overButtons = function(){
		$(this).each(function(e){
			var item =$(this),
				srcItem,
				widthItem,
				heightItem,
				imgItem = item.find("img"),
				idPage,
				MSIE = ($.browser.msie) && ($.browser.version <= 8);
				
			imgItem.load(init())	
		 	function init(){
		 		item.css({display:"block"});
				srcItem = imgItem.attr("src");
				widthItem = parseInt(item.css("width"));
				heightItem = parseInt(item.css("height"));
				imgItem.remove();
				item.css({"display": "block", width:widthItem, height:heightItem/2});
				item.append("<div class='outIcon' style='position:absolute; display:block; width:"+widthItem+"px; height:"+(heightItem/2)+"px; background:transparent url("+srcItem+") no-repeat;'></div>");
				item.append("<div class='overIcon' style='position:absolute; display:block; width:"+widthItem+"px; height:"+(heightItem/2)+"px; background:transparent url("+srcItem+") 0 "+(-heightItem/2)+"px no-repeat;'></div>");
				item.hover(overHandler, outHandler);
				
				if(!MSIE){
					item.find(".overIcon").animate({opacity:0}, 0)
				}else{
					item.find(".overIcon").css({"visibility":"hidden"})
				}
				
				function overHandler(){
					if(!MSIE){
						$(this).find(".overIcon").stop().animate({opacity:1}, 300, "easeOutCubic")
						$(this).find(".outIcon").stop().animate({opacity:0}, 300, "easeInCubic")
					}else{
						item.find(".overIcon").css({"visibility":"visible"})
						item.find(".outIcon").css({"visibility":"hidden"})
					}
					
				}
				function outHandler(){
					if(!MSIE){
						$(this).find(".overIcon").stop().animate({opacity:0}, 300, "easeInCubic")
						$(this).find(".outIcon").stop().animate({opacity:1}, 300, "easeOutCubic")
					}else{
						item.find(".overIcon").css({"visibility":"hidden"})
						item.find(".outIcon").css({"visibility":"visible"})
					}
				}
			}
		})
	
	}
})