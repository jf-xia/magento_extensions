/************Js For Ajaxcart************/

if (typeof Element.clone == 'undefined') {
    Element.clone = function (element, deep) {
        if (!(element = $(element))) return;
        var clone = element.cloneNode(deep);
        clone._prototypeUID = void 0;
        if (deep) {
            var descendants = Element.select(clone, '*'),
            i = descendants.length;
            while (i--) {
                descendants[i]._prototypeUID = void 0;
            }
        }
        return Element.extend(clone);
    };
}

var Ajaxcart = Class.create();
Ajaxcart.prototype = {
    allowFinish: true,
    wishlistObj: false,
    initialize: function(ajaxMask,ajaxPopup,popupContent,messageTag,miniCart,links, preLoadAjax){
        this.ajaxMask = ajaxMask;
        this.ajaxPopup = ajaxPopup;
        this.popupContent = popupContent;
		
        this.messageTag = messageTag;
        this.objMessageTag = false;
        this.miniCart = miniCart;
        this.objMiniCart = false;
		
        this.links = links;
        // this.instanceName = instanceName;
        
        this.preLoadAjax = preLoadAjax;
		
        this.jsSource = [];
        this.jsCache = [];
        this.jsCount = 0;		
        this.intervalCache = 0;
		
        this.ajaxOnComplete = this.ajaxOnComplete.bindAsEventListener(this);
        this.addJsSource = this.addJsSource.bindAsEventListener(this);
        this.updateMinicartEvent = this.updateMinicartEvent.bindAsEventListener(this);
    },
    getMessageTag: function(){
        if (!this.objMessageTag){
            if ($$(this.messageTag).first()){
                this.objMessageTag = $$(this.messageTag).first();
            }
        }
        return this.objMessageTag;
    },
    getMiniCart: function(){
        if (!this.objMiniCart){
            if ($$(this.miniCart).first()){
                this.objMiniCart = $$(this.miniCart);
            }
        }
        return this.objMiniCart;
    },
    addToCartHandle: function(requestUrl, params){
        this.url = requestUrl;
        if(window.location.href.match('https://') && !requestUrl.match('https://'))
            requestUrl = requestUrl.replace('http://','https://');
        if(!window.location.href.match('https://') && requestUrl.match('https://'))
            requestUrl = requestUrl.replace('https://','http://');
        if (requestUrl.indexOf('?') != -1)
            requestUrl += '&isajaxcart=true';
        else
            requestUrl += '?isajaxcart=true';
        if (this.getMessageTag())
            requestUrl += '&groupmessage=1';
        if (this.getMiniCart())
            requestUrl += '&minicart=1';
        if (this.links)
            requestUrl += '&ajaxlinks=1';
        // $(this.ajaxMask).show();
        this.responseCache = '';
        this.requestAjax = new Ajax.Request(requestUrl,{
            method: 'post',
            postBody: params,
            parameters: params,
            onException: function (xhr, e){
                $(this.ajaxMask).hide();
                $(this.ajaxPopup).hide();
                window.location.href = this.url;
            },
            onComplete: this.ajaxOnComplete
        });
    },
    cancelRequest: function() {
        if (typeof this.requestAjax == 'object') {
            this.requestAjax.transport.abort();
        }
    },
    ajaxOnComplete: function(xhr){
        if (this.requestAjax.getStatus()) {
            if (xhr.responseText.isJSON()){
                var response = xhr.responseText.evalJSON();
                if (response.hasOptions) {
                    if (response.redirectUrl) this.addToCartHandle(response.redirectUrl,'');
                    else this.popupContentWindow(response);
                } else {
                    if (this.allowFinish) {
                        this.addToCartFinish(response);
                    } else {
                        this.responseCache = response;
                    }
                }
            } else {
                $(this.ajaxMask).hide();
                $(this.ajaxPopup).hide();
                window.location.href = this.url;
            }
        }
    },
    addToCartFinish: function(response){
        if (this.getMessageTag() && response.message){
            this.getMessageTag().update(response.message);
            this.getMessageTag().innerHTML = this.getMessageTag().firstChild.innerHTML;
        }
        if (this.getMiniCart() && response.miniCart){
            this.getMiniCart().each(function(mnc){
                mnc.update(response.miniCart);
                mnc.innerHTML = mnc.firstChild.innerHTML;
            });
            this.updateMinicartEvent();
        }
        if (this.links && response.ajaxlinks){
            this.links.update(response.ajaxlinks);
            this.links.innerHTML = this.links.firstChild.innerHTML;
        }
        if (this.wishlistObj && response.wishlisthtml) {
            $(this.wishlistObj).innerHTML = response.wishlisthtml;
        }
        $(this.ajaxMask).hide();
        ajaxCartHidebyTimout(response);
    },
    popupContentWindow: function(response){
        if (response.optionjs && !this.preLoadAjax){
            for (var i=0;i<response.optionjs.length;i++){
                var pattern = 'script[src="'+response.optionjs[i]+'"]';
                if ($$(pattern).first()) continue;
                this.jsSource[this.jsSource.length] = response.optionjs[i];
            }
        }
        if (response.optionhtml){
            pContent = $(this.popupContent);
            if (pContent.down('form')) {
                pContent.removeChild(pContent.down('form'));
            }
            pContent.innerHTML += response.optionhtml;
            if (typeof ajaxcartTemplateJs != 'undefined') ajaxcartTemplateJs();
            this.jsCache = response.optionhtml.extractScripts();
        }
        if (this.preLoadAjax) {
            this.addJsSource();
        } else {
            this.intervalCache = setInterval(this.addJsSource,500);
            this.addJsSource();
        }
    },
    addJsSource: function(){
        if (this.jsCount == this.jsSource.length){
            this.jsSource = [];
            this.jsCount = 0;
            clearInterval(this.intervalCache);
            this.addJsScript();
        } else {
            var headDoc = $$('head').first();
            var jsElement = new Element('script');
            jsElement.src = this.jsSource[this.jsCount];
            headDoc.appendChild(jsElement);
            this.jsCount++;
        }
    },
    addJsScript: function(){
        if (this.jsCache.length == 0) return false;
        try {
            for (var i=0;i<this.jsCache.length;i++){
                var script = this.jsCache[i];
                var headDoc = $$('head').first();
                var jsElement = new Element('script');
                jsElement.type = 'text/javascript';
                jsElement.text = script;
                headDoc.appendChild(jsElement);
            }
            this.jsCache = [];
            $(this.ajaxMask).hide();
            $(this.ajaxPopup).show();
            var content = $(this.popupContent);
            this.updatePopupBox(content);
            ajaxMoreTemplateJs();
        } catch (e){}
    },
    updateMinicartEvent: function(){
        // var instanceName = this.instanceName;
        $$('a').each(function(el){
            if (el.href.search('/checkout/cart/delete/') != -1) {
                el.onclick = ajaxcartDeleteMini;
            } else if (el.href.search('/checkout/cart/configure/') != -1) {
                el.onclick = ajaxcartUpdateMini;
            }
        // el.href = "javascript:"+instanceName+".addToCartHandle('"+el.href+"','')";
        });
    },
    updatePopupBox: function(content) {
        content.style.removeProperty ? content.style.removeProperty('top') : content.style.removeAttribute('top');
        if (content.offsetHeight + content.offsetTop > document.viewport.getHeight() - 30){
            content.style.position = 'absolute';
            content.style.top = document.viewport.getScrollOffsets()[1]+10+'px';
        }else{
            content.style.position = 'fixed';
        }
        if (content.up('.ajaxcart')) {
            content.up('.ajaxcart').style.width = content.getWidth()+'px';
        }
    }
}

var AjaxcartAnimation = Class.create();
AjaxcartAnimation.prototype = {
    initialize: function(container, target, isStart, objMove, autoScroll, moveCallBack) {
        this.container = $(container);
        this.containerMask = $(this.container.parentNode).down('.ajaxcart-animation-mask');
        this.offsetX = 0;
        this.offsetY = 0;
        var o = this.container.offsetParent;
        while (o) {
            this.offsetX += o.offsetLeft;
            this.offsetY += o.offsetTop;
            o = o.offsetParent;
        }
        if (!target) return false;
        this.target = $(target);
        this.targetX = this.target.offsetLeft;
        this.targetY = this.target.offsetTop;
        o = this.target.offsetParent;
        while (o) {
            this.targetX += o.offsetLeft;
            this.targetY += o.offsetTop;
            o = o.offsetParent;
        }
        this.movingByX = true;
        this.moveStep = 1;
        
        this.autoScroll = autoScroll;
        this.intervalCache = 0;
        
        this.moving = this.moving.bindAsEventListener(this);
        this.movingBlink = 0;
        if (isStart) this.startMove(objMove);
        this.moveCallBack = moveCallBack;
        return true;
    },
    startMove: function(objMove) {
        this.container.innerHTML = '';
        this.container.appendChild(Element.clone(objMove, 1));
        this.calculateXY(objMove);
        // this.updateContainer();
        this.container.style.left = (Math.round(this.x) - this.offsetX) + 'px';
        this.container.style.top = (Math.round(this.y) - this.offsetY) + 'px';
        this.containerMask.show();
        this.target.addClassName('ajaxcart-animation-active');
        this.container.show();
        this.intervalCache = setInterval(this.moving, 45);
    },
    calculateXY: function(obj) {
        this.x = obj.offsetLeft;
        this.y = obj.offsetTop;
        var o = obj.offsetParent;
        while (o) {
            this.x += o.offsetLeft;
            this.y += o.offsetTop;
            if (o.style.position == 'fixed') {
                var offset = document.viewport.getScrollOffsets();
                this.x += offset[0];
                this.y += offset[1];
                o = false;
            } else {
                o = o.offsetParent;
            }
        }
        if (Math.abs(this.y - this.targetY) > Math.abs(this.x - this.targetX)) {
            this.movingByX = false;
            if (this.y > this.targetY) this.moveStep = -1;
            else this.moveStep = 1;
        } else {
            this.movingByX = true;
            if (this.x > this.targetX) {
                this.moveStep = -1;
            } else {
                this.moveStep = 1;
            }
        }
        this.X = this.x;
        this.Y = this.y;
    },
    moving: function() {
        this.movingBlink = this.movingBlink + 1;
        if (this.movingBlink >= 20) {
            this.target.toggleClassName('ajaxcart-blink');
            this.movingBlink = 0;
        }
        if (this.x == this.targetX && this.y == this.targetY) {
            clearInterval(this.intervalCache);
            this.target.removeClassName('ajaxcart-animation-active');
            this.target.removeClassName('ajaxcart-blink');
            this.containerMask.hide();
            this.container.hide();
            if (typeof this.moveCallBack == "function") {
                this.moveCallBack();
            }
            return false;
        }
        if (this.movingByX) {
            this.x += this.getMoveStep();
            if (this.y != this.targetY) {
                this.y = this.Y + (this.targetY - this.Y) * (this.x - this.X) / (this.targetX - this.X);
            }
        } else {
            this.y += this.getMoveStep();
            if (this.x != this.targetX) {
                this.x = this.X + (this.targetX - this.X) * (this.y - this.Y) / (this.targetY - this.Y);
            }
        }
        this.updateContainer();
        return true;
    },
    getMoveStep: function() {
        var x, delta;
        if (this.movingByX) {
            x = (this.x - this.X) / (this.targetX - this.X);
            delta = this.targetX - this.x;
        } else {
            x = (this.y - this.Y) / (this.targetY - this.Y);
            delta = this.targetY - this.y;
        }
        var moveStep = 7 + Math.abs(delta) * (x - x * x);
        if (moveStep > Math.abs(delta)) moveStep = Math.abs(delta);
        return this.moveStep * moveStep;
    },
    updateContainer: function() {
        this.container.style.left = (Math.round(this.x) - this.offsetX) + 'px';
        this.container.style.top = (Math.round(this.y) - this.offsetY) + 'px';
        if (this.autoScroll && this.containerOutOfViewPort()) {
            var pos = Element.cumulativeOffset(this.container);
            var delta = 30; // Math.round(document.viewport.getScrollOffsets()[1]/2);
            window.scrollTo(pos[0], pos[1] - delta);
        }
    },
    containerOutOfViewPort: function() {
        var viewportOffset = this.container.viewportOffset();
        if (viewportOffset.left < 0 || (viewportOffset.top != 0 && viewportOffset.top < 33)) {
            return true;
        }
        var windowWidth = document.viewport.getWidth();
        var windowHeight = document.viewport.getHeight();
        if (viewportOffset.left + this.container.getWidth() > windowWidth
            || viewportOffset.top + this.container.getHeight() > windowHeight
        ) {
            return true;
        }
        return false;
    }
}

var AjaxcartCompare = Class.create();
AjaxcartCompare.prototype = {
    initialize: function(ajaxMask,ajaxPopup,popupContent,messageTag,miniCompare,links,instanceName, preLoadAjax){
        this.ajaxMask = ajaxMask;
        this.ajaxPopup = ajaxPopup;
        this.popupContent = popupContent;
		
        this.messageTag = messageTag;
        this.objMessageTag = false;
        this.miniCompare = miniCompare;
        this.objMiniCompare = false;
		
        this.links = links;
        this.instanceName = instanceName;
        this.preLoadAjax = preLoadAjax;
		
        this.jsSource = [];
        this.jsCache = [];
        this.jsCount = 0;		
        this.intervalCache = 0;
		
        this.ajaxOnComplete = this.ajaxOnComplete.bindAsEventListener(this);
        this.addJsSource = this.addJsSource.bindAsEventListener(this);
        this.updateMiniCompareEvent = this.updateMiniCompareEvent.bindAsEventListener(this);
    },
    getMessageTag: function(){
        if (!this.objMessageTag){
            if ($$(this.messageTag).first()){
                this.objMessageTag = $$(this.messageTag).first();
            }
        }
        return this.objMessageTag;
    },
    getMiniCompare: function(){
        if (!this.objMiniCompare){
            if ($$(this.miniCompare).first()){
                this.objMiniCompare = $$(this.miniCompare);
            }
        }
        return this.objMiniCompare;
    },
    addToCompareHandle: function(requestUrl, params){
       
        this.url = requestUrl;
        if(window.location.href.match('https://') && !requestUrl.match('https://'))
            requestUrl = requestUrl.replace('http://','https://');
        if(!window.location.href.match('https://') && requestUrl.match('https://'))
            requestUrl = requestUrl.replace('https://','http://');
        if (requestUrl.indexOf('?') != -1)
            requestUrl += '&isajaxcart=true';
        else
            requestUrl += '?isajaxcart=true';
        if (this.getMessageTag())
            requestUrl += '&groupmessage=1';
        if (this.getMiniCompare())
            requestUrl += '&minicompare=1';
        if (this.links)
            requestUrl += '&ajaxlinks=1';
		if(params)
            requestUrl += '&wishlist=1';
        this.requestAjax = new Ajax.Request(requestUrl,{
            method: 'post',
            postBody: params,
            parameters: params,
            onException: function (xhr, e){
                $(this.ajaxMask).hide();
                $(this.ajaxPopup).hide();
                window.location.href = this.url;
            },
            onComplete: this.ajaxOnComplete
        });
    },
    cancelRequest: function() {
        if (typeof this.requestAjax == 'object') {
            this.requestAjax.transport.abort();
        }
    },
    ajaxOnComplete: function(xhr){
        if (this.requestAjax.getStatus()) {
            if (xhr.responseText.isJSON()){
                var response = xhr.responseText.evalJSON();
                if(response.redirectUrl){
                    this.addToCompareHandle(response.redirectUrl,'{"wishlist": 1}');
                }else{
                    this.addToCartFinish(response);
                }
            } else {
                $(this.ajaxMask).hide();
                $(this.ajaxPopup).hide();
                window.location.href = this.url;
            }
        }
    },
    addToCartFinish: function(response){
        if (this.getMessageTag() && response.message){
            this.getMessageTag().update(response.message);
            this.getMessageTag().innerHTML = this.getMessageTag().firstChild.innerHTML;
        }
        if (this.getMiniCompare() && response.miniCompare){
            this.getMiniCompare().each(function(mnc){
                mnc.update(response.miniCompare);
                mnc.innerHTML = mnc.firstChild.innerHTML;
            });
            this.updateMiniCompareEvent();
        }
        if (this.links && response.ajaxlinks){
            this.links.update(response.ajaxlinks);
            this.links.innerHTML = this.links.firstChild.innerHTML;
        }
        $(this.ajaxMask).hide();
        var instanceName = this.instanceName;
        if(instanceName == 'compare'){
            ajaxCartHideComparebyTimout(response);
        }else if(instanceName == 'wishlist'){
            $$('div.block-wishlist').each(function(el){
				if(el.childElementCount != 0)
					el.show();
				if(el.childElementCount == 0)
					el.hide();
			});
            ajaxCartHideWishlistbyTimout(response);
        }
    },
    popupContentWindow: function(response){
        if (response.optionjs && !this.preLoadAjax){
            for (var i=0;i<response.optionjs.length;i++){
                var pattern = 'script[src="'+response.optionjs[i]+'"]';
                if ($$(pattern).first()) continue;
                this.jsSource[this.jsSource.length] = response.optionjs[i];
            }
        }
        if (response.optionhtml){
            pContent = $(this.popupContent);
            if (pContent.down('form')) {
                pContent.removeChild(pContent.down('form'));
            }
            pContent.innerHTML += response.optionhtml;
            if (typeof ajaxcartTemplateJs != 'undefined') ajaxcartTemplateJs();
            this.jsCache = response.optionhtml.extractScripts();
        }
        if (this.preLoadAjax) {
            this.addJsSource();
        } else {
            this.intervalCache = setInterval(this.addJsSource,500);
            this.addJsSource();
        }
    },
    addJsSource: function(){
        if (this.jsCount == this.jsSource.length){
            this.jsSource = [];
            this.jsCount = 0;
                clearInterval(this.intervalCache);
            this.addJsScript();
        } else {
            var headDoc = $$('head').first();
            var jsElement = new Element('script');
            jsElement.src = this.jsSource[this.jsCount];
            headDoc.appendChild(jsElement);
            this.jsCount++;
        }
    },
    addJsScript: function(){
        if (this.jsCache.length == 0) return false;
        try {
            for (var i=0;i<this.jsCache.length;i++){
                var script = this.jsCache[i];
                var headDoc = $$('head').first();
                var jsElement = new Element('script');
                jsElement.type = 'text/javascript';
                jsElement.text = script;
                headDoc.appendChild(jsElement);
            }
            this.jsCache = [];
            $(this.ajaxMask).hide();
            $(this.ajaxPopup).show();
            var content = $(this.popupContent);
            this.updatePopupBox(content);
            ajaxMoreTemplateJs();
        } catch (e){}
    },
    updateMiniCompareEvent: function(){
        var instanceName = this.instanceName;
        $$('a').each(function(el){
            if (el.href.search('/catalog/product_compare/remove/') != -1)
                //el.href = "javascript:"+instanceName+".addToCompareHandle('"+el.href+"','')";
                el.onclick = ajaxcartDeleteCompareMini;
            if (el.href.search('/catalog/product_compare/clear/') != -1)
                //  el.href = "javascript:"+instanceName+".addToCompareHandle('"+el.href+"','')";
                el.onclick = ajaxcartClearCompareMini;
        });
        $$('#wishlist-sidebar a').each(function(el){
             if (el.href.search('/wishlist/index/remove/') != -1)
                  el.onclick = ajaxcartDeleteWishlistMini;
         });
    },
    updatePopupBox: function(content) {
        content.style.removeProperty ? content.style.removeProperty('top') : content.style.removeAttribute('top');
        if (content.offsetHeight + content.offsetTop > document.viewport.getHeight() - 30){
            content.style.position = 'absolute';
            content.style.top = document.viewport.getScrollOffsets()[1]+10+'px';
        }else{
            content.style.position = 'fixed';
        }
        if (content.up('.ajaxcart')) {
            content.up('.ajaxcart').style.width = content.getWidth()+'px';
        }
    }
}