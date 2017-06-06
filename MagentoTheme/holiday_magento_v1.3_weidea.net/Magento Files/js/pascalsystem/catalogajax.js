PS.catalogajax = {
	init : function() {
		PS.catalogajax._initElementsByType('paginator', 'content');
		PS.catalogajax._initElementsByType('mode', 'content');
		PS.catalogajax._initElementsByType('limiter', 'content');
		PS.catalogajax._initElementsByType('sortby', 'content');
		PS.catalogajax._initElementsByType('sortdir', 'content');
		PS.catalogajax._initElementsLayerNav(PS.catalogajax.config.navfilter);
		PS.catalogajax._initElementsLayerNav(PS.catalogajax.config.navclear);
		PS.catalogajax._initElementsLayerNav(PS.catalogajax.config.navremove);
	},
	loadData : function() {
		var url = this.getAttribute('href');
		PS.catalogajax.beforeLoad(PS.catalogajax.config.blocks.content.selector+((typeof PS.catalogajax.config.blocks.content.replace == 'undefined')?'':' '+PS.catalogajax.config.blocks.content.replace));
		PS.ajax.call(url,this._catalogajax);
		return false;
	},
	loadDataBySelect : function(selectElement, url) {
		if (!url) return false;
		PS.catalogajax.beforeLoad(PS.catalogajax.config.blocks.content.selector+((typeof PS.catalogajax.config.blocks.content.replace == 'undefined')?'':' '+PS.catalogajax.config.blocks.content.replace));
		PS.ajax.call(url,selectElement._catalogajax);
		return false;
	},
	loadLayerData : function() {
		var url = this.getAttribute('href');
		PS.catalogajax.beforeLoad(PS.catalogajax.config.blocks.content.selector+((typeof PS.catalogajax.config.blocks.content.replace == 'undefined')?'':' '+PS.catalogajax.config.blocks.content.replace));
		PS.catalogajax.beforeLoad(PS.catalogajax.config.blocks.layer.selector+((typeof PS.catalogajax.config.blocks.layer.replace == 'undefined')?'':' '+PS.catalogajax.config.blocks.layer.replace));
		PS.ajax.call(url,this._catalogajax);
		return false;
	},
	beforeLoad : function(selector) {
		PS.eachFunc(selector, PS.overlayElement);
	},
	_initElementsLayerNav : function(selector) {
		if (typeof selector != 'string') {
			if (typeof selector.length == 'undefined') {
				throw 'bad selector';
			}
			for (var j=0;j<selector.length;j++) {
				PS.catalogajax._initElementsLayerNav(selector[j]);
			}
			return;
		}
		var els = $$(selector);
		for (var i=0;i<els.length;i++) {
			if (typeof els[i]._catalogajax != 'undefined')
				continue;
			els[i]._catalogajax = {};
			els[i]._catalogajax[PS.catalogajax.config.blocks.content.name] = PS.catalogajax.config.blocks.content.selector+((typeof PS.catalogajax.config.blocks.content.replace == 'undefined')?'':'|'+PS.catalogajax.config.blocks.content.replace);
			els[i]._catalogajax[PS.catalogajax.config.blocks.layer.name] = PS.catalogajax.config.blocks.layer.selector+((typeof PS.catalogajax.config.blocks.layer.replace == 'undefined')?'':'|'+PS.catalogajax.config.blocks.layer.replace);
			els[i].onclick = PS.catalogajax.loadLayerData;
		}
	},
	_initElementsByType : function(type, block) {
		var selector = PS.catalogajax.config[type];
		if (typeof selector != 'string') {
			if (typeof selector.length == 'undefined') {
				throw 'bad param config for: `'+type+'`';
			}
			for (var j=0;j<selector.length;j++) {
				PS.catalogajax._initElementsBySelector(selector[j], block);
			}
			return;
		}
		PS.catalogajax._initElementsBySelector(selector, block);
	},
	_initElementsBySelector : function(selector, block) {
		var isSelect = (PS.substr(selector,selector.length-6,6)=='select')?true:false;
		var els = $$(selector);
		var attVal;
		for (var i=0;i<els.length;i++) {
			if (typeof els[i]._catalogajax != 'undefined')
				continue;
			els[i]._catalogajax = {};
			els[i]._catalogajax[PS.catalogajax.config.blocks.content.name] = PS.catalogajax.config.blocks.content.selector+((typeof PS.catalogajax.config.blocks.content.replace == 'undefined')?'':'|'+PS.catalogajax.config.blocks.content.replace);
			if (isSelect) {
				els[i].onchange = function(){
					PS.catalogajax.loadDataBySelect(this, this.value);
				}
			} else {
				els[i].onclick = PS.catalogajax.loadData;
			}
		}
	}
};
PS.onload(function(){PS.catalogajax.init()});

PS.catalogajax.config = {
	'paginator' : '.toolbar .pager .pages a',
	'limiter'	: '.toolbar .pager .limiter select',
	'mode'		: '.toolbar .sorter .view-mode a',
	'sortby'	: '.toolbar .sorter .sort-by select',
	'sortdir'	: '.toolbar .sorter .sort-by a',
	'navfilter'	: '.block-layered-nav .block-content ol li a',
	'navclear'	: '.block-layered-nav .block-content .currently .actions a',
	'navremove'	: '.block-layered-nav .block-content .currently ol li a',
	'blocks'	: {
		'content'	: {
			'name'		: 'content',
			'selector'	: '.page .main .col-main'
		},
		'layer'		: {
			'name'		: 'catalog.leftnav',
			'selector'	: '.page .main .col-left',
			'replace'	: '.block-layered-nav'
		}
	}
};