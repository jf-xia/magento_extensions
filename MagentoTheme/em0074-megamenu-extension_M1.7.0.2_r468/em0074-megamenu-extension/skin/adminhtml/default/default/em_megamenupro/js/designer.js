/**
 * EM MegaMenu Core UI javascript
 *
 * @author Giao L. Trinh (giao.trinh@emthemes.com)
 * @copyright 2012 CodeSpot Software JSC., http://www.emthemes.com/
 * @license: commerical license purchased on http://www.emthemes.com/
 */
(function ($) {

var INDENT = 30;

EM_MegaMenu = {
	emWysiwygEditor: null
};


EM_MegaMenu.MenuItem = Class.create({
	$dom: null,
	data: null,
	
	textFieldId: '',
	
	initialize: function(el) {
		this.$dom = $(el);
		this.$dom.data('EM_MegaMenu_Object',  this);
		this.data = {};
				
		if (!this.$dom.hasClass('ui-widget'))
			this.$dom.addClass('ui-widget ui-widget-content ui-helper-clearfix ui-corner-all')
				.find('.menu-item-header').addClass("ui-widget-header ui-corner-all")
					.prepend( "<span class='ui-icon ui-icon-minusthick'></span>");
		
		if ($('input[name=type]', this.$dom).val() == 'text') {
			this.textFieldId = 'em_megamenu_text_' + unique();
			$('textarea[name=text]', this.$dom).attr('id', this.textFieldId);
		}
		
		this.initEvents();
		
		this.save();
	},
	
	initEvents: function() {
		// toggle show/hide content
		$('.menu-item-header .ui-icon', this.$dom).click(this.toggle.bind(this, 'slide'));
		
		// click WYSIWYG Editor button
		$('button.btn-wysiwyg, input.btn-wysiwyg', this.$dom).click(EM_MegaMenu.emWysiwygEditor.open.bind(EM_MegaMenu.emWysiwygEditor, this.textFieldId));

		// click Apply button
		$('button[name=apply], input[name=apply]', this.$dom).click(function() {
			this.save().toggle();
		}.bind(this));
				
		// click Revert button
		$('button[name=revert], input[name=revert]', this.$dom).click(this.revert.bind(this));
		
		// click Delete button
		$('button[name=delete], input[name=delete]', this.$dom).click(this.remove.bind(this));
		
		return this;
	},
	
	toggle: function(effect) {
		if (typeof effect == 'undefined') var effect = 'slide';
		
		$('.menu-item-header .ui-icon', this.$dom).toggleClass( "ui-icon-minusthick" ).toggleClass( "ui-icon-plusthick" );
		if (effect == 'slide')
			$('.menu-item-content', this.$dom).slideToggle();
		else
			$('.menu-item-content', this.$dom).toggle();
			
		return this;
	},
	
		
	save: function() {
		$('input, textarea, select', this.$dom).each(function(item) {
			var type = $(this).attr('type') ? $(this).attr('type').toUpperCase() : '';
			if (type == 'BUTTON' || type == 'SUBMIT' || !this.name) return;
			item.data[this.name] = $(this).val();

			// change menu item's header
			if (this.name == 'label' || this.name == 'title') 
				$('.menu-item-header .title', item.$dom).html($(this).val());
		}.curry(this));
		return this;
	},
	
	revert: function() {
		$('input, textarea, select', this.$dom).each(function(item) {
			var type = $(this).attr('type') ? $(this).attr('type').toUpperCase() : '';
			if (type == 'BUTTON' || type == 'SUBMIT' || !this.name || typeof item.data[this.name] == 'undefined') return
			$(this).val(item.data[this.name]);
		}.curry(this));
		return this;
	},
	
	remove: function() {
		this.$dom.fadeOut(300, this.$dom.remove.bind(this.$dom));
	}
});
var MenuItem = EM_MegaMenu.MenuItem;



EM_MegaMenu.FixedMenuItem = Class.create(MenuItem, {
	initialize: function($super, el, canvas) {
		$super(el);
		
		this.$dom.draggable({
			helper: 'clone',
			connectToSortable: $('.dropzone', canvas.$dom),
			cursor: 'move',
			tolerance: 'pointer',
			revert: 'invalid'
		}).disableSelection();
	}
});
var FixedMenuItem = EM_MegaMenu.FixedMenuItem;



EM_MegaMenu.Canvas = Class.create({
	$dom: null,
	_childrenOfDragging: null,
	
	initialize: function(el) {
		this.$dom = $(el);
		this.$dom.data('EM_MegaMenu_Object',  this);
		
		var canvas = this;
		
		$('.menu-item', this.$dom).each(function() {
			canvas.createMenuItem(this).toggle('none');
		});
		
		
		$('.dropzone', this.$dom).sortable({
			items: '.menu-item',
			cursor: 'move',
			tolerance: 'pointer',
			revert: true,
			
			start: function(event, ui) {
				canvas.prepareTransport(ui.item);
			},
			
			stop: function(event, ui) {
				
				// clone the MenuItem
				if (!$(ui.item).data('EM_MegaMenu_Object') || !($(ui.item).data('EM_MegaMenu_Object') instanceof MenuItem)) {
					$(ui.item).addClass('menu-item-depth-0');
					canvas.createMenuItem(ui.item);
				}
				
				//
				// update depth level
				//
				var depth = canvas.getDepth(ui.placeholder);
				var orgDepth = canvas.getDepth(ui.item);
				
				$(ui.item).removeClass('menu-item-depth-' + orgDepth)
					.addClass('menu-item-depth-' + depth);
				
				
				canvas.restoreTransport(ui.item);
			},

			over: function(event, ui) {
				var mi, mp, parent, depth, parentDepth;
				
				// 
				// Update placeholder's depth level
				//
					
				depth = canvas.getDepth(ui.placeholder);
				
				$(ui.placeholder).removeClass('menu-item-depth-' + depth);
				
				parent = $(ui.placeholder).prevAll('.menu-item:visible').not(ui.item).first();
				if (parent.length > 0) {
					parentDepth = canvas.getDepth(parent);
					if (ui.position.left - ui.originalPosition.left >= INDENT)
						depth = Math.min(depth + 1, parentDepth + 1);
					else if (ui.originalPosition.left - ui.position.left >= INDENT)
						depth = Math.max(parentDepth - Math.round((ui.originalPosition.left - ui.position.left)/INDENT) + 1, 0);
				}
				else depth = 0;
				
				$(ui.placeholder).addClass('menu-item-depth-' + depth);
			}
		});
	},
	
	prepareTransport: function(el) {
		//
		// clone child menu-item to the transport div
		//
		var canvas = this;
		var depth = canvas.getDepth(el);
		var transport = $('.menu-item-transport', el);
		
		canvas._childrenOfDragging = [];
		
		$(el).nextAll('.menu-item').not('.ui-sortable-placeholder')
			.each(function() {
				if (canvas.getDepth(this) <= depth) return false;
				
				var childDepth = canvas.getDepth(this);
				
				$(this).clone().appendTo(transport)
					.add(this)
						.removeClass('menu-item-depth-' + childDepth)
						.addClass('menu-item-depth-' + (childDepth - depth))
							

				canvas._childrenOfDragging.push( $(this).hide() );
			});
	},
	
	restoreTransport: function(el) {
		var canvas = this;
		var parentDepth = canvas.getDepth(el);
		
		$(canvas._childrenOfDragging).each(function() {
			var oldDepth = canvas.getDepth(this);
			var newDepth = parentDepth + oldDepth;
			$(this).removeClass('menu-item-depth-' + oldDepth)
				.addClass('menu-item-depth-' + newDepth)
				.show();
		}).insertAfter(el);
		
		$('.menu-item-transport', el).html('');
	},
	
	createMenuItem: function(el) {
		if ($('.menu-item-transport', el).length == 0)
			$(el).append('<div class="menu-item-transport"></div>');
			
		return new MenuItem(el);
	},
	
	getDepth: function(el) {
		var m = $(el).attr('class').match(/menu-item-depth-([0-9]+)/);
		return m ? parseInt(m[1]) : 0;
	},
	
	updateMenuNest: function() {
		
	},
	
	serializeArray: function() {
		var canvas = this;
		var results = [];
		$('.menu-item', this.$dom).each(function() {
			if ($(this).data('EM_MegaMenu_Object')) {
				var data = $.extend(true, {}, $(this).data('EM_MegaMenu_Object').data);
				data['depth'] = canvas.getDepth(this);
				results.push(data);
			}
		});
		return results;
	},
	
	serialize: function(key) {
		if (!key) key = 'menu';
		
		var result = {};
		result[key] = this.serializeArray();
		return $.param(result);
	}
	
	
});
var Canvas = EM_MegaMenu.Canvas;



EM_MegaMenu.Toolbox = Class.create({
	$dom: null,
	
	initialize: function(el, canvas) {
		this.$dom = $(el);
		this.$dom.data('EM_MegaMenu_Object',  this);
		
		$('.menu-item', this.$dom).each(function() {
			new FixedMenuItem(this, canvas);
		});
	}
});
var Toolbox = EM_MegaMenu.Toolbox;



function unique() {
	time = new Date().getTime();
	var number = Math.floor(Math.random() * 999);
	return time+''+number;
}

})(jQuery);