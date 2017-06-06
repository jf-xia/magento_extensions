
var PS = {
	onload : function(func) {
		if (typeof this._domLoadedFunction == 'undefined')
			PS._domLoadedFunction = new Array();
		PS._domLoadedFunction.push(func);
		document.observe("dom:loaded", PS._onload);
	},
	substr : function(strVal, startIndex, lengthSubstring) {
		strVal = strVal.toString();
		if (typeof lengthSubstring == 'undefined')
			lengthSubstring = false;
		if (typeof strVal.substr == 'function') {
			if (lengthSubstring)
				return strVal.substr(startIndex, lengthSubstring);
			return strVal.substr(startIndex);
		}
		var strLen = strVal.length;
		if (strLen<startIndex)
			return '';
		var buildStr = '';
		for (var i=startIndex;i<strLen;i++) {
			if (i>startIndex+lengthSubstring)
				return buildStr;
			buildStr+= strVal[i];
		}
		return buildStr;
	},
	evalJsOnContent : function(content) {
		if (typeof content == 'string')
			content = document.getElementById(content);
		if (!content) return;
		var scripts = content.getElementsByTagName('script');
		for (var i=0;i<scripts.length;i++) {
			eval(scripts[i].innerHTML);
		}
	},
	overlayElement : function(element) {
		if (typeof element == 'string')
			element = document.getElementById(element);
		if (!element) return;
		element._psoverlay = document.createElement('div');
		element._psoverlay.className = 'pascalsystem-overlay';
		if (element.className && element.className.toString().length>0) {
			element._psoverlay.className+= ' pascalsystem-'+element.className.toString().split(' ').join(' pascalsystem-');
		}
		if (element.id && element.id.toString().length>0) {
			element._psoverlay.id+= 'pascalsystem-'+element.id.toString();
		}
		element._psoverlay.style.display = 'none';
		element._psoverlay.style.position = 'fixed';
		var temps = element.getElementsByTagName('*');
		if (temps.length) {
			element.insertBefore(element._psoverlay, temps[0]);
		} else {
			element.appendChild(element._psoverlay);
		}
		var dimm = $(element).getDimensions();
		element._psoverlay.style.width = dimm.width.toString()+'px';
		element._psoverlay.style.height = dimm.height.toString()+'px';
		element._psoverlay.style.display = 'block';
		return true;
	},
	unOverlayElement : function(element) {
		return;
		if (typeof element == 'string')
			element = document.getElementById(element);
		if (!element) return;
		if (typeof element._psoverlay == 'undefined') return;
		element._psoverlay.style.display = 'none';
		element._psoverlay.parentNode.removeChild(element._psoverlay);
		element._psoverlay = false;
		return true;
	},
	eachFunc : function(selector, funcRef) {
		if (typeof selector == 'string') {
			selector = $$(selector);
		}
		for (var i=0;i<selector.length;i++) {
			if (typeof funcRef == 'string') {
				selector[i].funcRef();
			} else {
				funcRef(selector[i]);
			}
		}
	},
	extendConfig : function(baseConfObj, config) {
		for (var key in config) {
			if (typeof baseConfObj[key] == 'undefined') {
				baseConfObj[key] = config[key];
				continue;
			}
			if ((typeof config[key] == 'object') && (typeof config[key].length == 'undefined')) {
				PS.extendConfig(baseConfObj[key], config[key]);
				continue;
			}
			baseConfObj[key] = config[key];
		}
	},
	removeCurrentElement : function(el) {
		setTimeout(function(){el.parentNode.removeChild(el);},100);
	},
	getCacheKey : function(params) {
		var cacheKey = '';
		var cKType;
		for (var key in params) {
			cKType = typeof params[key];
			cacheKey+=key+':'+cKType+':';
			if (cKType == 'object') {
				cacheKey+= PS.getCacheKey(params[key]);
			} else {
				cacheKey+= params[key].toString();
			}
		}
		return cacheKey;
	},
	_onload : function() {
		if (typeof PS._domLoadedFunction == 'undefined')
			return;
		var funcRef;
		for (var i=0;i<PS._domLoadedFunction.length;i++) {
			funcRef = PS._domLoadedFunction[i];
			if (typeof funcRef == 'function') {
				funcRef();
			}
		}
	}
};
/**
 * PascalSystem Window function
 */
PS.window = {
	getWidth : function() {
		var myWidth = 0;
		if (typeof(window.innerWidth) == 'number') {
			myWidth = window.innerWidth;
		} else if (document.documentElement && document.documentElement.clientWidth) {
			myWidth = document.documentElement.clientWidth;
		} else if (document.body && document.body.clientWidth) {
			myWidth = document.body.clientWidth;
		}
		return myWidth;
	},
	getHeight : function() {
		var myHeight = 0;
		if (typeof(window.innerHeight) == 'number') {
			myHeight = window.innerHeight;
		} else if (document.documentElement && document.documentElement.clientHeight) {
			myHeight = document.documentElement.clientHeight;
		} else if (document.body && document.body.clientHeight) {
			myHeight = document.body.clientHeight;
		}
		return myHeight;
	},
	getScrollX : function() {
		var scrOfX = 0;
		if(typeof(window.pageXOffset)=='number') {
			scrOfX = window.pageXOffset;
		} else if(document.body && document.body.scrollLeft) {
			scrOfX = document.body.scrollLeft;
		} else if(document.documentElement && document.documentElement.scrollLeft) {
			scrOfX = document.documentElement.scrollLeft;
		}
		return scrOfX;
	},
	getScrollY : function() {
		var scrOfY = 0;
		if(typeof(window.pageYOffset)=='number') {
			scrOfY = window.pageYOffset;
		} else if(document.body && document.body.scrollTop) {
			scrOfY = document.body.scrollTop;
		} else if(document.documentElement && document.documentElement.scrollTop) {
			scrOfY = document.documentElement.scrollTop;
		}
		return scrOfY;
	},
	getDocumentWidth : function() {
		if (document.documentElement && document.documentElement.scrollWidth)
			return document.documentElement.scrollWidth;
		else if (document.body && document.body.scrollWidth)
			return document.body.scrollWidth;
		var D = document;
		return Math.max(
			Math.max(D.body.scrollWidth, D.documentElement.scrollWidth),
			Math.max(D.body.offsetWidth, D.documentElement.offsetWidth),
			Math.max(D.body.clientWidth, D.documentElement.clientWidth)
		);
	},
	getDocumentHeight : function() {
		if (document.documentElement && document.documentElement.scrollHeight)
			return document.documentElement.scrollHeight;
		else if (document.body && document.body.scrollHeight)
			return document.body.scrollHeight;
		var D = document;
		return Math.max(
			Math.max(D.body.scrollHeight, D.documentElement.scrollHeight),
			Math.max(D.body.offsetHeight, D.documentElement.offsetHeight),
			Math.max(D.body.clientHeight, D.documentElement.clientHeight)
		);
	}
};
/**
 * PascalSystem Layer function
 */
PS.layer = function(identifier) {
	this._container = document.getElementById('ps-'+identifier);
	if (!this._container) {
		this._container = document.createElement('div');
		this._container.id = 'ps-'+identifier;
		this._container.style.display = 'none';
		this._container.style.position = 'absolute';
		this._container.style.left = '0px';
		this._container.style.top = '0px';
		this._html = document.createElement('div');
		this._html.id = 'pshtml-'+identifier;
		document.body.appendChild(this._container);
		this._container.appendChild(this._html);
	}
}
PS.layer.prototype.setClass = function(className) {
	this._container.className = className;
	return this;
}
PS.layer.prototype.fullScreen = function() {
	this._container.style.width = PS.window.getDocumentWidth().toString()+'px';
	this._container.style.height = PS.window.getDocumentHeight().toString()+'px';
	this._container.style.display = 'block';
}
PS.layer.prototype.center = function() {
	var posX = (PS.window.getScrollX() + Math.round(PS.window.getWidth() / 2));
	var posY = (PS.window.getScrollY() + Math.round(PS.window.getHeight() / 2));
	var elWidth = $(this._container).getWidth();
	var elHeight = $(this._container).getHeight();
	this._container.style.left = (posX-Math.round(elWidth/2)).toString()+'px';
	this._container.style.top = (posY-Math.round(elHeight/2)).toString()+'px';
	this._container.style.display = 'block';
}
PS.layer.prototype.hide = function() {
	this._html.innerHTML = '';
	this._container.style.display = 'none';
}
PS.layer.prototype.isHide = function() {
	return (this._container.style.display == 'none')?true:false;
}
PS.layer.ajax = function(url, blocks, methodForm, postData, specialFunction, cacheOn) {
	if ((typeof window._PSLayerAjax != 'undefined') && window._PSLayerAjax)
		return false;
	window._PSLayerAjax = true;
	
	if (typeof postData == 'undefined') postData = {};
	if (typeof blocks == 'undefined') blocks = {'content':'content'};
	if (typeof methodForm == 'undefined') methodForm = 'GET';
	var params = {};
	for (var key in blocks) {
		params['pascalsystem'+key] = blocks[key];
	}
	var m = PS.layer.manager();
	m.background.fullScreen();
	m.loader.center();
	if (typeof specialFunction != 'function') {
		specialFunction = function(transport) {
			var response = transport.responseText || "";
			var data = eval('('+response+')');
			var m = PS.layer.manager();
			
			for (var key in data) {
				data[key]._html = document.createElement('div');
				data[key]._html.className = 'ps-'+data[key].selector;
				data[key]._html.innerHTML = data[key].html;
				m.content._html.appendChild(data[key]._html);
			}
			m.loader.hide();
			m.content.center();
			var scripts = m.content._html.getElementsByTagName('script');
			for (var i=0;i<scripts.length;i++) {
				eval(scripts[i].innerHTML);
			}
		};
	}
	if (typeof window._PSAjaxCacheData == 'undefined') window._PSAjaxCacheData = {};
	var cacheKey = false;
	if ((typeof cacheOn != 'undefined') && cacheOn) {
		cacheKey = PS.getCacheKey({'url':url,'params':params,'postData':postData});
		if (typeof window._PSAjaxCacheData[cacheKey] != 'undefined') {
			window._PSLayerAjax = false;
			var m = PS.layer.manager();
			if (m.loader.isHide()) return;
			specialFunction(window._PSAjaxCacheData[cacheKey]);
			return;
		}
	}
	if ("https:" == document.location.protocol) {
		url = url.toString().replace('http://','https://');
	}
	var obj = new Ajax.Request(url,{
		method: methodForm,
		requestHeaders: params,
		data: postData,
		onSuccess: function(transport) {
			window._PSLayerAjax = false;
			var m = PS.layer.manager();
			if (m.loader.isHide()) return;
			var el = this;
			window._PSAjaxCacheData[cacheKey] = transport;
			specialFunction(transport, el)
		}
	});
}
PS.layer.manager = function() {
	if (typeof PS.layer.manager.background == 'undefined') {
		PS.layer.manager.background = new PS.layer('overlay');
		PS.layer.manager.background.setClass('pascalsystem-overlay');
		PS.layer.manager.background._container.onclick = function() {
			PS.layer.manager.close();
		}
	}
	if (typeof PS.layer.manager.loader == 'undefined') {
		PS.layer.manager.loader = new PS.layer('loader');
		PS.layer.manager.loader.setClass('pascalsystem-loader');
	}
	if (typeof PS.layer.manager.content == 'undefined') {
		PS.layer.manager.content = new PS.layer('content');
		PS.layer.manager.content.setClass('pascalsystem-content');
	}
	return PS.layer.manager;
}
PS.layer.manager.close = function() {
	if (!PS.layer.manager.loader.isHide()) return;
	if (typeof PS.layer.manager.background != 'undefined') {
		PS.layer.manager.background.hide();
	}
	if (typeof PS.layer.manager.loader != 'undefined') {
		PS.layer.manager.loader.hide();
	}
	if (typeof PS.layer.manager.content != 'undefined') {
		PS.layer.manager.content.hide();
	}
}
/**
 PascalSystem Ajax function
*/
PS.ajax = {
	call : function(url, blocks) {
		var isQuery = ((url.length>0) && (url[0]=='?') || (url.indexOf('?')>0))?true:false;
		var params = {};
		for (var key in blocks) {
			params['pascalsystem'+key] = blocks[key];
		}
		if ("https:" == document.location.protocol) {
			url = url.toString().replace('http://','https://');
		}
		var obj = new Ajax.Request(url,{
			method:'get',
			requestHeaders: params,
			onSuccess: function(transport){
				var response = transport.responseText || "";
				var data = eval('('+response+')');
				var selector;
				var html;
				var destEls;
				var regs;
				var replaceElement = false;
				var destEl; var srcEl;
				var temp;
				for (var blockName in data) {
					selector = data[blockName].selector;
					regs = selector.split('|');
					replaceElement = false;
					if (regs.length>1) {
						selector = regs[0];
						replaceElement = regs[1];
					}
					destEls = $$(selector);
					if (!destEls.length) continue;
					if (replaceElement) {
						destEls = $(destEls[0]).getElementsBySelector(replaceElement);
						if (!destEls.length) continue;
						temp = document.createElement('div');
						temp.innerHTML = data[blockName].html;
						srcEl = $(temp).getElementsBySelector(replaceElement);
						if (!srcEl.length) continue;
						srcEl = srcEl[0];
						destEls[0].style.display = 'none';
						destEls[0].parentNode.insertBefore(srcEl, destEls[0]);
						destEls[0].parentNode.removeChild(destEls[0]);
						temp = null;
						destEl = destEls[0];
					} else {
						destEl = destEls[0];
						//alert(data[blockName].html);
						destEl.innerHTML = data[blockName].html;
					}
					PS.evalJsOnContent(destEl);
				}
				PS._onload();
			}
		});
	}
};
/**
 * PascalSystem Catalog Quick View Image
 */
PS.catalogQuickViewImage = function(selector) {
	if (typeof selector == 'undefined')
		selector = 'a.product-image'
	var els = $$(selector);
	for (var i=0;i<els.length;i++) {
		els[i].onclick = function() {
			var href = this.href;
			PS.layer.ajax(href, {'product.info.media':'media'}, undefined, undefined, function(transport) {
				var response = transport.responseText || "";
				var data = eval('('+response+')');
				var key = 'product.info.media';
				
				if (typeof data[key] == 'undefined') {
					PS.layer.manager.close();
					return;
				}
				var Re = new RegExp('id\=\"image\" src\=\"([^\"]+)\"');
				var res = Re.exec(data[key].html);
				if (!res) {
					PS.layer.manager.close();
					return;
				}
				var srcImg = res[1];
				
				var imgPreload = new Image();
				imgPreload.__onload = function() {
					this.style.visibility = 'hidden';
					this.style.width = 'auto';
					this.style.height = 'auto';
					var m = PS.layer.manager();
					
					var el = this;
					this._width = (this.width)?this.width:$(el).getWidth();
					this._height = (this.height)?this.height:$(el).getHeight();
					
					if (!this._width || !this._height) {
						PS.removeCurrentElement(this);
						PS.layer.manager.close();
						return;
					}
					m.loader.hide();
					
					var borderContainer = document.createElement('div')
					borderContainer.className = 'pascalsystem-content-layerimage';
					borderContainer.appendChild(el);
					borderContainer.onclick = function() {
						PS.layer.manager.close();
					};
					m.content._html.appendChild(borderContainer);
					var winWidth = PS.window.getWidth();
					var winHeight = PS.window.getHeight();
					winWidth-=40;
					winHeight-=40;
					var scale = 1;
					var scaleWidth;
					var scaleHeight;
					scaleWidth = this._width;
					scaleHeight = this._height;
					if (scaleWidth > winWidth) {
						scale = scaleWidth / winWidth;
						scaleHeight = Math.round(scaleHeight/scale);
						scaleWidth = winWidth;
					}
					if (scaleHeight > winHeight) {
						scale = scaleHeight / winHeight;
						scaleWidth = Math.round(scaleWidth/scale);
						scaleHeight = winHeight;
					}
					this.style.width = scaleWidth.toString()+'px';
					this.style.height = scaleHeight.toString()+'px';
					this.style.visibility = 'visible';
					this.style.position = 'static';
					this.style.top = 'auto';
					this.style.left = 'auto';
					m.content.center();
				}
				imgPreload.src = srcImg;
				imgPreload.style.width='1px';
				imgPreload.style.height='1px';
				imgPreload.style.top='0px';
				imgPreload.style.left='0px';
				imgPreload.style.position='absolute';
				imgPreload.onload = setTimeout(function(){
					if ((typeof imgPreload._postAction != 'undefined') && imgPreload._postAction) return;
					imgPreload.__onload();
				},250);
				document.body.appendChild(imgPreload);
				if ($(imgPreload).getWidth() && $(imgPreload).getHeight()) {
					imgPreload._postAction = true;
					setTimeout(function(){
						imgPreload.__onload();
					},250);
				}
			}, true);
			return false;
		}
	}
}
PS.catalogQuickViewImage.init = function(selector) {
	if (typeof window._catalogQuickViewImageLoaded != 'undefined') {
		PS.catalogQuickViewImage(selector);
		return;
	}
	document.observe("dom:loaded", function() {
		PS.catalogQuickViewImage(selector);
		window._catalogQuickViewImageLoaded = true;
	});
}