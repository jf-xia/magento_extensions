/*
*Version: 1.1;
*Author: Behaart;
*Option:
	autoPlayState: false / trur (default:false),
	autoPlayTime: seconds (default:4),
	alignIMG: center / top / bottom / left / right / top_left / top_right / bottom_left / bottom_right (default:center),
*/
$(document).ready(function(){
	$.fn.gallerySplash = function(o){
		var getObject = {
				autoPlayState:false,
				autoPlayTime:4,
				alignIMG:"top",
				controlDisplay:false,
				paginationDisplay:false,
				animationSpeed:0.7
			},
			object = $(this),
			imageHolder = $("#imageHolder",object),
	 		image = $("#imageHolder > img",object),
	 		imageSRCLink = $("#inner>ul>li>a"),
	 		discription = $("#galleryDiscription>li"),
	 		imageDeltaX,
			imageDeltaY,
			currImg = 0,
			prevImg = 0,
			allImg = imageSRCLink.length,
			clickButton=1,
			loadComplete = true,
			autoPlayTimer,
			MSIE8 = ($.browser.msie) && ($.browser.version <= 8),
			doc=$(object);
			
		$.extend(getObject, o);	
		init()

		function init(){
			getObject.animationSpeed = getObject.animationSpeed.replace(",", ".");
			
			$("#imgSpinner").stop().animate({opacity:0}, 0, function(){
			     $(this).css({display:'none'});
			});
			object.css({"position":"fixed", width:"100%", height:"100%"});
			imageHolder.css({"position":"fixed", width:"100%", height:"100%", "z-index":0});
			$(window).resize(resizeImageHandler).trigger('resize');
			if(getObject.controlDisplay == "true"){
			     
			    $("#next, #prev").css({opacity:0}).hover(function()
                {
                    $(this).css({opacity:1});				   
                }, function(){
                	$(this).css({opacity:0});					   
                });
                
				$("#next").click(function(){
					if(loadComplete){
						getObject.autoPlayState = "false";
						prevImg = currImg;
						currImg++;
						if(currImg>allImg-1){
							currImg = 0;
						}
						clickButton=1;
						changeImageHandler();
					}
					return false;
				})
				$("#prev").click(function(){
					if(loadComplete){
						getObject.autoPlayState = "false";
						prevImg = currImg;
						currImg--;
						if(currImg<0){
							currImg = allImg-1;
						}
						clickButton=-1;
						changeImageHandler();
					}
					return false;			
				})
			}else{
				$("#next").css({display:"none"});
				$("#prev").css({display:"none"});
			}
			discription.not(0).css({top:'-220px', display:"none"});
			discription.eq(currImg).css({top:'0px', display:"block"});
			/*-----thumbnail----*/
			if(getObject.paginationDisplay == "true"){
				$("#previewHolder ul>li>a").click(
					function(){
						if($(this).parent().index()!=currImg && loadComplete){
							prevImg = currImg;
							currImg=$(this).parent().index();
							clearTimeout(autoPlayTimer);
							getObject.autoPlayState = "false";
							changeImageHandler();
						}
						return false;			
					}
				)
				$("#previewHolder ul>li").eq(currImg).addClass("active");
			}else{
				$("#previewHolder").css({display:"none"});
			}

			autoPlayHandler();
		}	
		function autoPlayHandler(){
			autoPlayTimer = setTimeout(function(){
				if(getObject.autoPlayState == "true"){
					prevImg = currImg;
					currImg++;
					if(currImg>=allImg){
						currImg = 0;
					}
					changeImageHandler();
				}
			}, getObject.autoPlayTime*1000);
			
		}
		function resizeImageHandler(){
			var imageK =image.height()/image.width(),
				holderK =imageHolder.height()/imageHolder.width(),
				imagePercent = (image.height()/image.width())*100;
				
			image = $("#imageHolder > img");
			imageK =image.height()/image.width()
			holderK =doc.height()/doc.width();
			if(holderK>imageK){
				imagePercent = (image.width()/image.height())*100;
				image.css({height:doc.height(), width:(doc.height()*imagePercent)/100});
			}else{
				imagePercent = (image.height()/image.width())*100;
				image.css({width:doc.width(), height:(doc.width()*imagePercent)/100});
			}

			switch(getObject.alignIMG){
				case "top":
					imageDeltaX=-(image.width()-doc.width())/2;
					imageDeltaY=0;
				break;
				case "bottom":
					imageDeltaX=-(image.width()-doc.width())/2;
					imageDeltaY=-(image.height()-doc.height());
				break;
				case "right":
					imageDeltaX=-(image.width()-doc.width());
					imageDeltaY=-(image.height()-doc.height())/2;
				break;
				case "left":
					imageDeltaX=0;
					imageDeltaY=-(image.height()-doc.height())/2;
				break;
				case "top_left":
					imageDeltaX=0;
					imageDeltaY=0;
				break;
				case "top_right":
					imageDeltaX=-(image.width()-doc.width());
					imageDeltaY=0;
				break;
				case "bottom_right":
					imageDeltaX=-(image.width()-doc.width());
					imageDeltaY=-(image.height()-doc.height());
				break;
				case "bottom_left":
					imageDeltaX=0;
					imageDeltaY=-(image.height()-doc.height());
				break;
				default:
					imageDeltaX=-(image.width()-doc.width())/2;
					imageDeltaY=-(image.height()-doc.height())/2;
			}
			image.css({left:imageDeltaX, top:imageDeltaY, position:"relative", background:"#000000"});
		}
		function changeImageHandler(){
			var imgSRC;
			$("#previewHolder ul>li").eq(currImg).addClass("active");
			$("#previewHolder ul>li").eq(prevImg).removeClass("active");
			loadComplete = false;
			image.addClass("topImg").css({"z-index":1});
			imgSRC = imageSRCLink.eq(currImg).attr("href");
			
			imageHolder.append("<img class='bottomImg' src="+imgSRC+" alt=''>");
			$(".bottomImg").css({display:"none", "z-index":0}).bind("load", loadImageHandler);
			$("#imgSpinner").css({display:'block'}).stop().animate({opacity:1}, 500, "easeOutCubic");

			discription.eq(currImg).css({top:'-220px', display:"block"}).animate({top:'0px'}, 1000, "easeOutCubic");
			discription.eq(prevImg).animate({top:'220px'}, 300, "easeInCubic", function(){
				discription.eq(prevImg).css({display:"none"})
			});
		}
		function loadImageHandler(){
			setTimeout(function(){
				resizeImageHandler();
				$(".bottomImg").unbind("load", loadImageHandler).css({display:"block", position:"absolute", top:imageDeltaY});
				$("#imgSpinner").stop().animate({opacity:0}, 1000, "easeOutCubic", function(){
				    $(this).css({display:'none'});
				})
				$(".topImg").stop(true, true).animate({opacity:0}, getObject.animationSpeed*1000, "easeInOutCubic", function(){
					$(".topImg").remove();
					image.removeClass("bottomImg");
					loadComplete = true;
					autoPlayHandler()
				})
			}, 1000)
		}
	}
})