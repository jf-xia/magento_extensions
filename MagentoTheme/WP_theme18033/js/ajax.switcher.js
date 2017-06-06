/*
*Version: 2.3.1 - last update: 27.07.2012;
*Author: Behaart;
*/
(function($){
	$.fn.ajaxSwitcher=function(o){
		var sO= {
			mainPageHash:"#!/splash-page",
			pageList:"#pageList",
			mainMenu:"#headerMenu>li, #footerMenu>li",
			classMenu:".menu",
			classSubMenu:".sub-menu",
			siteWrapper:"#pageList",
			pageSpinner:"#pageSpinner",
			noChangeLinks:".comment-edit-link, .comment-reply-link, .thumbnailGallery, .file, a[rel*='fancybox'],  a[href*='profile.php'], a[href*='wp-login.php'][class!='logOutPage'], a[href*='wp-admin'], a[href*='feed'], a[href*='.jpg'], a[href*='.png'], a[href*='.gif']",
			articleTopPosition:100,
			mobileResponseSize:768,
			scrollToTop:true,
			
			menuInit:function(mainMenu, classMenu, classSubMenu){},
			buttonOver:function(item){},
			buttonOut:function(item){},
			subMenuButtonOver:function(item){},
			subMenuButtonOut:function(item){},
			subMenuShow:function(item){},
			subMenuHide:function(item){},
			pageInit:function(pageList){},
			currPageAnimate:function(){},
			prevPageAnimate:function(){},
			backToSplash:function(){},
			pageLoadComplete:function(){},
			windowResize:function(){}
		},
		lpC=true,
		currPageHash,
		currPageHolder = "#pageHolder_1",
		prevPageHash,
		prevPageHolder = "#pageHolder_2",
		backToTopButton = "#backToTop",
		MSIE9 = ($.browser.msie) && ($.browser.version == 9),
		MSIE8 = ($.browser.msie) && ($.browser.version == 8),
		siteURL = $("#logo>a").attr("href"),
		sitePath= $("#logo>a").attr("class"),
		hashState,
		oldhashState;
		$.extend(sO, o);
		
		function init(){
			if(MSIE9){
				//$("html").css({"overflow-y":"scroll"});
			}else{
				//$("body").css({"overflow-y":"scroll"});
			}
			hashState =sO.mainPageHash ;
			oldhashState = window.location.hash;
			//$(window).scroll(toucScroll)
			$(window).bind("mousewheel", function(){$("body, html").stop(true, true)}).bind("hashchange", hashC).scroll(scrollPageToTop).resize(resizeWrapper)
			$(backToTopButton).bind("click", clickBackToTop)
			$("a").live("click", noLoadPage);
			$(".comment_link").live("click", function(){sP($(this).attr("href"))});	
			sO.menuInit($(sO.mainMenu), $(sO.classMenu), $(sO.classSubMenu));
			sO.pageInit($(sO.pageList));
			changeLinks("#wrapper, #footer, header");
			scrollPageToTop()
			
			$("#logo>a").attr("href", sO.mainPageHash).removeClass();
			$(sO.classSubMenu).css({display:"none"})
			$.fn.ajaxForms();

			$(sO.mainMenu).hover(
				function(){
					if($(">a", this).attr("href") != currPageHash){
						$(this).addClass("itemActive");
						sO.buttonOver($(this));
					}
					$(this).addClass("itemHover");	
					sO.subMenuShow($(this), sO.classSubMenu);
				},
				function (){
					var item = $(this)
					if($(">a", this).attr("href") != currPageHash){
						$(this).removeClass("itemActive");
						sO.buttonOut($(this));
					}
					$(this).removeClass("itemHover");
					setTimeout(function(){
						if(!item.hasClass("itemHover")){
							sO.subMenuHide(item, sO.classSubMenu)
						}
					}, 1000)
				}
			).trigger("mouseout");	
			$(">li", sO.classSubMenu).hover(
				function(){
					if($(">a", this).attr("href") != currPageHash){
						$(this).addClass("itemActive");
						sO.subMenuButtonOver($(this));
					}
					$(this).addClass("itemHover");
					sO.subMenuShow($(this), sO.classSubMenu);
				},
				function(){
					var item = $(this)
					if($(">a", this).attr("href") != currPageHash){
						$(this).removeClass("itemActive");
						sO.subMenuButtonOut($(this));
					}
					$(this).removeClass("itemHover");
					setTimeout(function(){
						if(!item.hasClass("itemHover")){
							sO.subMenuHide(item, sO.classSubMenu);
						}
					}, 1000)
				}
			).trigger("mouseout");
			hashC();
			//sO.buttonOver($("a:href="+window.location.hash));
		};
		function changeLinks(item){
			$("a:[href^='"+siteURL+"']", item).not(sO.noChangeLinks).each(function(){
				var linkUrl = $(this).attr("href");
				linkUrl = "#!/"+linkUrl.slice(siteURL.length, linkUrl.length);
				if(linkUrl.length==linkUrl.lastIndexOf("/")+1){
					linkUrl= linkUrl.slice(0, -1)
				}
				$(this).attr("href", linkUrl);
			})
		};
		function noLoadPage(){
			if(lpC==false && window.location.hash!=sO.mainPageHash){
				return false;
			}
		}
		function hashC(){
			oldhashState=hashState;
			hashState = window.location.hash;
			if(hashState.lastIndexOf("/#")!=-1){
				hashState = hashState.substr(0, hashState.lastIndexOf("/#"))
			}
			if(hashState!=oldhashState){
				if(window.location.href.indexOf("customize.php")==-1){
				
					lpC=false;
					
					if(currPageHolder == "#pageHolder_2"){
						prevPageHolder = "#pageHolder_2";
						currPageHolder = "#pageHolder_1";
					}else{
						prevPageHolder = "#pageHolder_1";
						currPageHolder = "#pageHolder_2";
					}
					
					if(window.location.href.lastIndexOf(".php")==-1){
						if(hashState.length < 4){
							window.location.hash=sO.mainPageHash;
						}
					}
					prevPageHash = currPageHash;
					currPageHash = window.location.hash.split("?")[0].split("page/")[0];
					if(currPageHash.length==currPageHash.lastIndexOf("/")+1){
						currPageHash= currPageHash.slice(0, -1)
					}	
					if(prevPageHash != currPageHash){
						sO.buttonOut($(">a:[href='"+prevPageHash+"']", sO.mainMenu).parent());
						$(">a:[href='"+prevPageHash+"']", sO.mainMenu).parent().removeClass("itemActive");
						sO.subMenuButtonOut($("a:[href='"+prevPageHash+"']", sO.classSubMenu).parent());
						$("a:[href='"+prevPageHash+"']", sO.classSubMenu).parent().removeClass("itemActive");
						
						if(!$(">a:[href='"+currPageHash+"']", sO.mainMenu).parent().hasClass("itemActive")){
							sO.buttonOver($(">a:[href='"+currPageHash+"']", sO.mainMenu).parent());
							$(">a:[href='"+currPageHash+"']", sO.mainMenu).parent().addClass("itemActive");
						}
						if(!$(">a:[href='"+currPageHash+"']", sO.classSubMenu).parent().hasClass("itemActive")){
							sO.subMenuButtonOver($("a:[href='"+currPageHash+"']", sO.classSubMenu).parent());
							$("a:[href='"+currPageHash+"']", sO.classSubMenu).parent().addClass("itemActive");
						}
					}
					if($(prevPageHolder).height()<100){
						$(prevPageHolder).css({display:"none"})	
					}				
					sO.prevPageAnimate($(prevPageHolder));
						
					if(window.location.hash!=sO.mainPageHash){
						ajaxLP();
					}else{
						backTS()
					}
				}
			}else{
				$($(">a:[href='"+hashState+"']", sO.mainMenu).parent()).addClass("itemActive");
				sO.buttonOver($(">a:[href='"+hashState+"']", sO.mainMenu).parent());
				currPageHash = hashState;
			}
		};
		function ajaxLP(Eror404){
			var pageURL;
			if(Eror404){
				pageURL =sitePath+"/404.php"
			}else{
				pageURL =siteURL+"/"+window.location.hash.substring(3, window.location.hash.length)
			}
			$(sO.pageSpinner).css({display:"block", opacity:0}).delay(400).animate({opacity:1}, 300, "swing");
			
			$.ajax({
				url:pageURL,
				type:"GET",
				data:"ajaxRequest=true",
				cache: false,
	            success:function(data){ajaxLPС(data)},
	            error:function(data){ajaxLPE(data)}
			})	
		};
		function ajaxLPС(data){
			$(sO.pageSpinner).delay(400).animate({opacity:0}, 300, "swing", function(){$(sO.pageSpinner).css({display:"none"})});
			$(currPageHolder).html(data);
			
			sO.pageLoadComplete();	
			changeLinks($(currPageHolder));
			sO.currPageAnimate($(currPageHolder));
			lpC=true;
			setTimeout(function(){sP(window.location.hash)}, 200);
		};
		function ajaxLPE(data){
			ajaxLP(true)
		};
		function backTS(){
			sO.backToSplash()
			$(sO.pageSpinner).delay(400).animate({opacity:0}, 300, "swing", function(){$(sO.pageSpinner).css({display:"none"})});
			setTimeout(function(){sP(window.location.hash)}, 200);
		};
		function sP(getHash){
			var winHash = getHash,
				scrollPosition = 0,
				scrollID,
				strLenght = getHash.length;
				
			resizeWrapper();
			if(winHash.lastIndexOf("/#")!=-1){
				if(winHash.indexOf("?")!=-1){
					strLenght = winHash.indexOf("?");
				}
				scrollID=winHash.slice(winHash.lastIndexOf("/#")+1, strLenght);
				if($(scrollID).length!=0){
					scrollPosition = $(scrollID).offset().top;
				}
			}
			if(sO.scrollToTop){
				$("body, html").delay(450).stop(true).animate({scrollTop:scrollPosition}, 1000, "easeInOutCubic");	
			}
		};
		function resizeWrapper(){
			var contentHeight= $(currPageHolder).outerHeight(true)+sO.articleTopPosition;
			
			if(!MSIE8){
				sO.windowResize();
			}
			if($(currPageHolder).text().length<5){
				contentHeight = "0";
			}
			$(sO.siteWrapper).stop(true, true).animate({height:contentHeight}, 1000, "easeInOutCubic");
			
			if($(document).outerWidth()<sO.mobileResponseSize){
				$(backToTopButton).addClass("backToTop_mobile");
				$(backToTopButton).removeClass("backToTop");
			}else{
				$(backToTopButton).addClass("backToTop");
				$(backToTopButton).removeClass("backToTop_mobile");
			}
		}
		function scrollPageToTop(){
			if($("body").scrollTop()>0 || $("html").scrollTop()>0){
				$(backToTopButton).stop(true, true).fadeIn(400)
			}else{
				$(backToTopButton).stop(true, true).fadeOut(400)
			}
		}
		/*function toucScroll(){	
			if($(document).outerWidth()<sO.mobileResponseSize){
				//$(backToTopButton).css({top:0})
			}
		}*/
		function clickBackToTop(){
			$("body, html").stop(true, true).animate({scrollTop:0}, 800, "easeInOutCubic");
		} 
		init();
	}
})(jQuery)