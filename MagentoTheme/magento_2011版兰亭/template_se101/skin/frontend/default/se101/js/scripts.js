
// Glider 
Glider = Class.create();
Object.extend(Object.extend(Glider.prototype, Abstract.prototype), {
	initialize: function(wrapper, options){
        this.handStopped = false;
	    this.scrolling  = false;
	    this.wrapper    = $(wrapper);
	    this.scroller   = this.wrapper.down('div.scroller');
	    this.sections   = this.wrapper.getElementsBySelector('div.sectionslide');
	    this.options    = Object.extend({ duration: 1.0, frequency: 3 }, options || {});

	    this.sections.each( function(section, index) {
	      section._index = index;
	    });    

	    this.events = {
	      click: this.click.bind(this),
          mouseover: this.pause.bind(this),
          mouseout: this.resume.bind(this)
	    };

	    this.addObservers();
        if(this.options.initialSection) 
            this.moveTo(this.options.initialSection, this.scroller, { duration:this.options.duration });  // initialSection should be the id of the section you want to show up on load
        if(this.options.autoGlide) 
            this.start();
	  },
	
  addObservers: function() {
    this.wrapper.observe('mouseover', this.events.mouseover);
    this.wrapper.observe('mouseout', this.events.mouseout);
    
    var descriptions = this.wrapper.getElementsBySelector('div.sliderdescription');
    descriptions.invoke('observe', 'mouseover', this.makeActive);
    descriptions.invoke('observe', 'mouseout', this.makeInactive);
    
    var controls = this.wrapper.getElementsBySelector('div.slidercontrol a');
    controls.invoke('observe', 'mouseover', this.events.click);

  },

  click: function(event) {
    var element = Event.findElement(event, 'a');
    
    if (this.scrolling) this.scrolling.cancel();
    this.moveTo(element.href.split("#")[1], this.scroller, { duration:this.options.duration });  
     if (!this.handStopped) {
        clearTimeout(this.timer);
        this.timer = null;
      }
    Event.stop(event);
  },

  moveTo: function(element, container, options) {
    this.current = $(element);
    Position.prepare();
    var containerOffset = Position.cumulativeOffset(container);
    var elementOffset = Position.cumulativeOffset(this.current);

    this.scrolling = new Effect.SmoothScroll(container, {
      duration:options.duration, 
      x:(elementOffset[0]-containerOffset[0]), 
      y:(elementOffset[1]-containerOffset[1])
    });
    
    if (typeof element == 'object')
        element = element.id;
        
    this.toggleControl($$('a[href="#'+element+'"]')[0]);
    
    return false;
  },
        
  next: function(){
    if (this.current) {
      var currentIndex = this.current._index;
      var nextIndex = (this.sections.length - 1 == currentIndex) ? 0 : currentIndex + 1;      
    } else var nextIndex = 1;

    this.moveTo(this.sections[nextIndex], this.scroller, { 
      duration: this.options.duration
    });

  },
	
  previous: function(){
    if (this.current) {
      var currentIndex = this.current._index;
      var prevIndex = (currentIndex == 0) ? this.sections.length - 1 : 
       currentIndex - 1;
    } else var prevIndex = this.sections.length - 1;
    
    this.moveTo(this.sections[prevIndex], this.scroller, { 
      duration: this.options.duration
    });
  },
  
  makeActive: function(event)
  {
    var element = Event.findElement(event, 'div');
    element.addClassName('active');
  },
  
  makeInactive: function(event)
  {
    var element = Event.findElement(event, 'div');
    element.removeClassName('active');
  },
  
  toggleControl: function(el)
  {
    $$('.slidercontrol a').invoke('removeClassName', 'active');
    el.addClassName('active');
  },

	stop: function()
	{
        this.handStopped = true;
		clearTimeout(this.timer);
	},
	
	start: function()
	{
        this.handStopped = false;
		this.periodicallyUpdate();
	},
    
    pause: function()
    {
      if (!this.handStopped) {
        clearTimeout(this.timer);
        this.timer = null;
      }
    },
    
    resume: function()
    {
      if (!this.handStopped)
        this.periodicallyUpdate();
    },
		
	periodicallyUpdate: function()
	{ 
		if (this.timer != null) {
			clearTimeout(this.timer);
			this.next();
		}
		this.timer = setTimeout(this.periodicallyUpdate.bind(this), this.options.frequency*1000);
	}

});

Effect.SmoothScroll = Class.create();
Object.extend(Object.extend(Effect.SmoothScroll.prototype, Effect.Base.prototype), {
  initialize: function(element) {
    this.element = $(element);
    var options = Object.extend({
      x:    0,
      y:    0,
      mode: 'absolute'
    } , arguments[1] || {}  );
    this.start(options);
  },
  setup: function() {
    if (this.options.continuous && !this.element._ext ) {
      this.element.cleanWhitespace();
      this.element._ext=true;
      this.element.appendChild(this.element.firstChild);
    }
   
    this.originalLeft=this.element.scrollLeft;
    this.originalTop=this.element.scrollTop;
   
    if(this.options.mode == 'absolute') {
      this.options.x -= this.originalLeft;
      this.options.y -= this.originalTop;
    } 
  },
  update: function(position) {   
    this.element.scrollLeft = this.options.x * position + this.originalLeft;
    this.element.scrollTop  = this.options.y * position + this.originalTop;
  }
});

// End of Glider


/*
 * Fabtabulous! Simple tabs using Prototype
 * http://tetlaw.id.au/view/blog/fabtabulous-simple-tabs-using-prototype/
 * Andrew Tetlaw
 * version 2 2008-08-10
 * http://creativecommons.org/licenses/by-sa/2.5/
 */
var Fabtabs = Class.create({
	initialize : function(element,options) {
		var parent = this.element = $(element);
		this.options = Object.extend({
		  hover: true,
		  remotehover: false,
		  anchorpolicy: 'allow-initial' // 'protect', 'allow', 'allow initial', 'disable'
		}, options || {});
		this.menu = this.element.select('a');
		this.hrefs = this.menu.map(function(elm){
		  return elm.href.match(/#(\w.+)/) ? RegExp.$1 : null;
		}).compact();
		this.on(this.getInitialTab());
		var onLocal = function(event) {
		  if(this.options.anchorpolicy !== 'allow'){ event.stop(); }
  		var elm = event.findElement("a");
  		this.activate(elm);
  		if(this.options.anchorpolicy === 'protect') { window.location.hash = '.'+this.tabID(elm); }
  	};
  	var onRemote = function(event) {
  	  if(this.options.anchorpolicy !== 'allow'){ event.stop(); }
	    var trig = event.findElement("a");
    	this.activate(this.tabID(trig));
    	if(this.options.anchorpolicy === 'protect') { window.location.hash = '.'+this.tabID(elm); }
	  }
		this.element.observe('click', onLocal.bindAsEventListener(this));
		if(this.options.hover) {
		  this.menu.each(function(elm){elm.observe('mouseover', onLocal.bindAsEventListener(this))}.bind(this));
		}
		var triggers = []; 
		this.hrefs.each(function(id){
		  $$('a[href="#' + id + '"]').reject(function(elm){
		    return elm.descendantOf(parent)
		  }).each(function(trig){
		    triggers.push(trig);
		  });
		})
		triggers.each(function(elm){
		  elm.observe('click', onRemote.bindAsEventListener(this));
		  if(this.options.remotehover) {
  		  elm.observe('mouseover', onRemote.bindAsEventListener(this));
  		}
		}.bind(this));
	},
	activate: function(elm) {
	  if(typeof elm == 'string') {
	    elm = this.element.select('a[href="#'+ elm +'"]')[0];
	  }
	  this.on(elm);
		this.menu.without(elm).each(this.off.bind(this));
	},
	off: function(elm) {
		$(elm).removeClassName('home-active-tab');
		$(this.tabID(elm)).removeClassName('active-tab-body');
	},
	on: function(elm) {
		$(elm).addClassName('home-active-tab');
		$(this.tabID(elm)).addClassName('active-tab-body');
	},
	tabID: function(elm) {
		return elm.href.match(this.re)[1];
	},
	getInitialTab: function() {
		if(this.options.anchorpolicy !== 'disable' && document.location.href.match(this.re)) {
		  var hash = RegExp.$1;
		  if(hash.substring(0,1) == "."){
		    hash = hash.substring(1);
		  }
		  return this.element.select('a[href="#'+ hash +'"]')[0];
		} else {
		  return this.menu.first();
		}
	},
	re: /#(\.?\w.+)/
});

document.observe("dom:loaded", function(){ new Fabtabs('home-tabs'); });


/*  Home Page New Product Slider */
var Slider = Class.create();
Slider.prototype = {
    options: {
        shift: 715
    },
    
    initialize: function(container, controlLeft, controlRight){
        this.animating = false;
        this.containerSize = {
            width: $(container).offsetWidth,
            height: $(container).offsetHeight
        },
        this.content = $(container).down();
        this.controlLeft = $(controlLeft);
        this.controlRight = $(controlRight);
        
        this.initControls();
    },
    
    initControls: function(){
        this.controlLeft.href = this.controlRight.href = 'javascript:void(0)';
        Event.observe(this.controlLeft,  'click', this.shiftLeft.bind(this));
        Event.observe(this.controlRight, 'click', this.shiftRight.bind(this));
        this.updateControls(1, 0);
    },
    
    shiftRight: function(){
        if (this.animating)
            return;
        
        var left = isNaN(parseInt(this.content.style.left)) ? 0 : parseInt(this.content.style.left);
        
        if ((left + this.options.shift) < 0) {
            var shift = this.options.shift;
            this.updateControls(1, 1);
        } else {
            var shift = Math.abs(left);
            this.updateControls(1, 0);
        }
        this.moveTo(shift);
    },
    
    shiftLeft: function(){
        if (this.animating)
            return;
        
        var left = isNaN(parseInt(this.content.style.left)) ? 0 : parseInt(this.content.style.left);
        
        var lastItemLeft = this.content.childElements().last().positionedOffset()[0];
        var lastItemWidth = this.content.childElements().last().getWidth();
        var contentWidth = lastItemLeft + lastItemWidth + 8;
        
        if ((contentWidth + left - this.options.shift) > this.containerSize.width) {
            var shift = this.options.shift;
            this.updateControls(1, 1);
        } else {
            var shift = contentWidth + left - this.containerSize.width;
            this.updateControls(0, 1);
        } 
        this.moveTo(-shift);
    },
    
    moveTo: function(shift){
        var scope = this;
                     
        this.animating = true;
        
        new Effect.Move(this.content, {
            x: shift,
            duration: 0.4,
            delay: 0,
            afterFinish: function(){
                scope.animating = false;
            }
        });
    },
    
    updateControls: function(left, right){
        if (!left)
            this.controlLeft.addClassName('disabled');
        else
            this.controlLeft.removeClassName('disabled');
        
        if (!right)
            this.controlRight.addClassName('disabled');
        else
            this.controlRight.removeClassName('disabled');
    }
}

/*  End of Home Page New Product Slider */

