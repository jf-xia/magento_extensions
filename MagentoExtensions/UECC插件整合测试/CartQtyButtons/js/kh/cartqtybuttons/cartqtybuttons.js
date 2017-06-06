if (!window.KH) {
	var KH = {};
}

if (!window.KH.CartQtyButtons) {
	KH.CartQtyButtons = {};
}

KH.CartQtyButtons.Render = Class.create();
KH.CartQtyButtons.Render.prototype = {
    initialize : function(settings, translator)
    {
    	this.settings = settings;
    	this.translator = translator;
    	
        this.render();
    },
    
    render : function()
    {
    	$$(this.settings.selector).each(function(item, index) {
    		var aIncrease = new Element('a')
    								.addClassName('cartQtyButtons')
    								.addClassName('cartQtyButtonsIncrease')
    								.update(this.translator.translate('increase qty'));
    		var aDecrease = new Element('a')
    								.addClassName('cartQtyButtons')
    								.addClassName('cartQtyButtonsDecrease')
    								.update(this.translator.translate('decrease qty'));
    		
    		aIncrease.observe('click', function(event) {
    			this.change(event, this.settings.items[index].increaseQty, index);
    		}.bind(this));
    		
    		aDecrease.observe('click', function(event) {
    			this.change(event, this.settings.items[index].decreaseQty, index);
    		}.bind(this));
    		
    		// set column class for fixed width
    		item.addClassName('cartQtyButtonsNeighbour')
    			.up().addClassName('cartQtyButtonsColumn');
    		
    		switch (this.settings.position) {
    			case 'one_left_one_right':
    				this.renderOneLeftOneRight(item, aIncrease, aDecrease);
    				break;
    				
    			case 'both_left':
    				this.renderBothLeft(item, aIncrease, aDecrease);
    				break;
    				
    			case 'both_right':
    			default:
    				this.renderBothRight(item, aIncrease, aDecrease);
    				break;
    		}
        }.bind(this));
    },
    
    renderBothLeft : function(item, aIncrease, aDecrease)
    {
    	var container = new Element('div');
        container.addClassName('cartQtyButtonsContainer');
		
		// insert buttons
		container.appendChild(aIncrease);
		container.appendChild(aDecrease);

		// set column class for template
		item.up().addClassName('cartQtyButtonsColumnLeft');
		
		// add container with buttons
		item.insert({ before: container });
    },
    
    renderBothRight : function(item, aIncrease, aDecrease)
    {
    	var container = new Element('div');
        container.addClassName('cartQtyButtonsContainer');
		
		// insert buttons
		container.appendChild(aIncrease);
		container.appendChild(aDecrease);

		// set column class for template
		item.up().addClassName('cartQtyButtonsColumnRight');
		
		// add container with buttons
		item.insert({ after: container });
    },
    
    renderOneLeftOneRight : function(item, aIncrease, aDecrease)
    {
		// set column class for template
		item.up().addClassName('cartQtyButtonsColumnLeftRight');
		
		// add container with buttons
		item.insert({ after: aIncrease })
			.insert({ before: aDecrease});
    },
    
    change : function(event, newValue)
    {
    	event.element().stopObserving('click');
    	
    	var form = event.findElement('form');
		var qtyInput = event.findElement('td').childElements().grep(new Selector('input'))[0];
		
		if (! newValue && ! this.settings.nullBehavior) {
			return;
		}
		
		qtyInput.value = newValue;
		form.submit();
    }
};
