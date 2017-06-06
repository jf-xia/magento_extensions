$j(function(){
	//Search
	$j('ul.searchCat li').click(function(){
		var catID =	$j(this).attr('class');
		$j('#select-cat').attr('value', catID);
	});
});


$j(function(){
	$j('.form-language > ul > li,.block-currency > ul > li,.loginForm ul li.login,.selectCat li').mouseenter(function(){
		$j(this).find('ul').show();
	}).mouseleave(function(){
		$j(this).find('ul').hide();
	});
});


$j(function(){
	$j('.searchCat li').click(function(){
		var catId = $j(this).attr('class');
		$j('#select-cat').val(catId);
		
		var theCat = $j(this).find('a').html();
		$j('.selectCat > li > a').html(theCat);
	});
});

$j(function(){
	$j('.block-cart').mouseenter(function(){
		$j(this).find('.block-title').addClass('block-active')
									 .css('cursor','pointer');
		$j(this).find('.block-content').show();
	}).mouseleave(function(){
		$j(this).find('.block-title').removeClass('block-active');	
		$j(this).find('.block-content').hide();
	});
});

$j(function(){
	$j('#nav').tinyNav({
		active: 'selected',
		header: 'Categories'	
	});
})

$j(function(){
	//Menu
	if(typeof catAmt != 'undefined'){
		var exlude = $j( "ul#nav li.level-top:gt(" + catAmt + ")");
		var more = $j( "ul#nav li.level-top:gt(" + catAmt + ")").remove();
		var moreText = 'More +';
		if(typeof moreTxt != 'undefined'){
			moreText = moreTxt;
		}
		$j("#nav").append('<li class="more parent"><a href="javascript:void(0);">' +  moreText + '</a><ul class="level0"></ul><li>');
		$j('ul#nav li.more ul').append(exlude);
	}
	$j('#nav li').mouseenter(function(){
		$j(this).children('ul').show();
	}).mouseleave(function(){
		$j(this).children('ul').hide();
	});
});

$j(function(){
		$j('.slideContent').cycle({ 
			fx:     slideFx, 
			speed:  'slow', 
			timeout: slide_auto, 
			rev:     reV,
			pauseOnPagerHover: 1,
			pause: 1,
			pager:  '.slideTab', 
			prev: '.slideshow .prev',
			next: '.slideshow .next',
			pagerEvent: 'mouseover',
			pagerAnchorBuilder: function(idx, slide) { 
				return '.slideTab li:eq(' + idx + ')'; 
				$j('.activeSlide').find('.arrowActive').show();	
			} 
		});
});

$j(function(){
	function block(list,row,rowClass,slide,pagi){
		var productList = list;
		for(var i = 0; i < productList.length; i+=row) {
				$j(productList).slice(i, i+row).wrapAll("<div class='row' />");
		}
		$j(rowClass).cycle({ 
			fx:     'scrollHorz', 
			speed:  'slow', 
			timeout: 0, 
			next: $j(slide+' .next'),
			prev: $j(slide+' .prev'),
			pager:  pagi,
			slideResize: 0,
			after: onAfter 
		});
		function onAfter(curr, next, opts, fwd) {
			  var $containerHeight = $j(this).height();
			  $j(this).parent().animate({height: $containerHeight});
			}
	}
	
		block('.tab .featured ul.products li',6,'.tab .featured ul.products','.tab .featured','');
		block('.tab .latest ul.products li',6,'.tab .latest ul.products','.tab .latest','');
		block('.special ul.products li',4,'.special ul.products','.special','');
		block('.bestseller ul.products li',4,'.bestseller ul.products','.bestseller','');
		block('.more-views ul li',3,'.more-views ul','.more-views','.more-views .pager');
});

$j(function(){
	//Tabs	
	$j('.tab').addClass('tabJs');
	$j('.tabJs .block-product h2').hide();
	$j('.tabJs').prepend('<ul class="tabLink"></ul>');
	
	$j('.tabJs .block-product h2').each(function(i) { 
		i++;
		var headingTab = $j(this).html();
		$j('.tabJs ul.tabLink').append('<li><a href="javascript:void(0);" class="tab' + i + '">' + headingTab + '</a></li>');
	});
	$j('.tabLink .tab1').addClass('active');
	
	$j('.tabJs .block-product').each(function(i) { 
		i++;
		$j(this).addClass('tab' + i);
	});
	$j('.tabJs .block-product').hide();
	$j('.tabJs .tab1').show();
	
	$j('.tabLink li a').mouseenter(function(){
		$j('.tabLink li a').removeClass('active');		
		$j('.tabJs .block-product').hide();
		var tabContent = $j(this).attr('class');
		$j('.tabJs div.' + tabContent).show();
		$j(this).addClass('active');
	});
});

$j(function(){
	if($j('.promo-banners ul.bannerSlides')){
		$j('.promo-banners ul.bannerSlides').cycle({ 
				fx:     'scrollHorz', 
				speed:  'slow', 
				timeout: 0, 
				next: $j('.promo-banners .next'),
				prev: $j('.promo-banners .prev'),
		});
	}
});


$j(function(){
	//Increase Decrease QTY
	$j(".qtyBox ul li").click(function(){
		if($j(this).hasClass("plus")){
			var qty = $j("#qty").val();
			qty++;
			$j("#qty").val(qty);
		}else{
			var qty = $j("#qty").val();
			qty--;
			if(qty>0){
				$j("#qty").val(qty);
			}
		}
	});	
});

$j(function(){
	$j('.rating-links a,p.no-rating a,a.learMore').click(function(e){
		$j('.box-collateral').hide();
		$j('ul.product-tab li').removeClass('active');
		if($j(e.target).is('a.learMore')){
			$j('.box-collateral.box-description').show();
			$j('.product-tab li.box-description').addClass('active');
		}else{
			$j('.box-collateral.reviews').show();
			$j('.product-tab li.reviews').addClass('active');		
		}
	});
});

$j(function(){
	$j('.box-collateral').hide().first().show();
	$j('ul.product-tab li').mouseover(function(){
		$j('ul.product-tab li').removeClass('active');
		var tabClass = $j(this).attr('class');
		$j(this).addClass('active');
		$j('.box-collateral').hide();
		$j('div.' + tabClass).show();
	});
});



$j(function(){
	$j('.more-views, .product-img-box .product-image').magnificPopup({
          delegate: 'a.mImage',
          type: 'image',
          tLoading: 'Loading image #%curr%...',
          mainClass: 'mfp-img-mobile',
          gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0,1]
          },
		  zoom: {
            enabled: true,
			duration:300
		  },
          image: {
            tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
          }
        });
		$j(".optionsScroll").nanoScroller({});
});