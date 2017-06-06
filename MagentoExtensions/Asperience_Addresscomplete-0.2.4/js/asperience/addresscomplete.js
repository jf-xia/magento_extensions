/**
 * ASPerience Javascript
 */

// Version 1.0
var isIE = navigator.appVersion.match(/MSIE/) == "MSIE";

if (!window.Asperience){
    var Asperience = new Object();
}

Asperience.showLoading = function(){
    Element.show('loading-process');
}
Asperience.hideLoading = function(){
    Element.hide('loading-process');
}
Asperience.GlobalHandlers = {
    onCreate: function() {
        Asperience.showLoading();
    },

    onComplete: function() {
        if(Ajax.activeRequestCount == 0) {
            Asperience.hideLoading();
        }
    }
};

Ajax.Responders.register(Asperience.GlobalHandlers);

Asperience.searchZipCode = Class.create();
Asperience.searchZipCode.prototype = {
    initialize : function(form, fPostcode, fCity, fRegion, img, alert, emptyText){
	
        this.form   = $(form);
        this.fPostcode  = $(fPostcode);
        this.fCity  = $(fCity);
        this.fRegion  = $(fRegion);
        this.img  = $(img);
        this.alert  = $(alert);
        this.imgEnable = false;
        
        this.emptyText = emptyText;

        Event.observe(this.form,  'submit', this.submit.bind(this));
        Event.observe(this.fPostcode, 'focus', this.focus.bind(this));
        Event.observe(this.fPostcode, 'blur', this.blur.bind(this));
        Event.observe(this.fPostcode, 'keyup', this.keyup.bind(this));
        this.blur();
    },

    submit : function(event){
    	this.alert.style.display = 'none';
        if (this.fPostcode.value == this.emptyText || isNaN(this.fPostcode.value) == true){
        	this.alert.style.display = 'inline';
            Event.stop(event);
            return false;
        }
        return true;
    },

    focus : function(event){
        if(this.fPostcode.value==this.emptyText){
            this.fPostcode.value='';
        }
    },

    blur : function(event){
    	this.img.style.display='none';
        if(this.fPostcode.value=='' && this.imgEnable){
            this.fPostcode.value=this.emptyText;
        }
    },
    
    keyup : function(event){
    	if (this.fPostcode.value.length==0){
			this.fCity.value ='';
			this.fRegion.selectedIndex=0;
		}
        if(this.imgEnable && this.fPostcode.value.length>=1 && this.fPostcode.value.length<=4){
            this.img.style.display='inline';
        }
        else{
        	this.img.style.display='none';
        }
    },
    
    reInitCountry : function(fCountry, urlCountry){
    	this.fCountry  = $(fCountry);
    	this.fPostcode.value=this.emptyText;
    	this.fCity.value ='';
    	this.fRegion.selectedIndex=0;
    	this.img.disabled=true;
    	new Ajax.Request(urlCountry, {
            method: 'post', 
            postBody: 'country='+this.fCountry.value,
            onComplete: this.processResult.bind(this)
        });
    },
    
    processResult : function(transport){
    	if(transport.responseText){
    		this.imgEnable = true;
		}else{
			this.imgEnable = false;
			this.fPostcode.value='';
		}
    },
    
    initAutocomplete : function(urlPostCode, destinationElement){

        new Ajax.Autocompleter(
            this.fPostcode,
            destinationElement,
            urlPostCode,
            {
                paramName: this.fPostcode.name,
                minChars: 1,
                updateElement: this._selectAutocompleteItem.bind(this),
                onShow : function(element, update) { 
                    if(!update.style.position || update.style.position=='absolute') {
                        update.style.position = 'absolute';
                        Position.clone(element, update, {
                            setHeight: false, 
                            offsetTop: element.offsetHeight
                        });
                    }
                    Effect.Appear(update,{duration:0});
                }
            }
        );
        
    },

    _selectAutocompleteItem : function(element){
        if(element.title){
        	var value_vecteur = element.title.split("||");
            this.fPostcode.value = value_vecteur[0];
            this.fCity.value  = value_vecteur[1];
			this.fRegion.value = value_vecteur[2];
        }
    }
}