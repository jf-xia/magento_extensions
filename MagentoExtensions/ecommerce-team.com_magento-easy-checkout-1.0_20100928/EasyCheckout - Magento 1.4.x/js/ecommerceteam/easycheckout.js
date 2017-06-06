/*
 * Magento EsayCheckout Extension
 *
 * @copyright:	EcommerceTeam (http://www.ecommerce-team.com)
 * @version:	1.0
 *
 */
Event.observe(window, 'load',
	function(){
      	
		Event.observe($('billing:country_id'), 'change', billingAddressChanged);
		Event.observe($('billing:city'), 'change', billingAddressChanged);
		Event.observe($('billing:region'), 'change', billingAddressChanged);
		Event.observe($('billing:region_id'), 'change', billingAddressChanged);
		Event.observe($('billing:postcode'), 'change', billingAddressChanged);
      	
      	if($('easycheckout-addressshipping')){ // if enabled diferent shipping address
      	
      	Event.observe($('shipping:country_id'), 'change', shippingAddressChanged);
		Event.observe($('shipping:city'), 'change', shippingAddressChanged);
		Event.observe($('shipping:region'), 'change', shippingAddressChanged);
		Event.observe($('shipping:region_id'), 'change', shippingAddressChanged);
		Event.observe($('shipping:postcode'), 'change', shippingAddressChanged);
		
		if(e = $('billing_use_for_shipping_yes')){
			Event.observe(e, 'click', changeShippingAddressMode);
		}
		
		}
		
		if($('easycheckout-shippingmethod')){
		
		Event.observe($('easycheckout-shippingmethod'), 'click', function(e){
      		
      		if(e.target.nodeName == 'INPUT'){
      			
      			sendMethods();
      			
      		}
      		
      	});
      	
      	}
      	/*
      	Event.observe($('easycheckout-paymentmethod'), 'click', function(e){
      		
      		if(e.target.nodeName == 'INPUT'){
      			
      			sendMethods();
      			
      		}
      		
      	});
      	*/
      	
      	
      	
	}
);

function startLoadingData(only_review_block){
	
	if(only_review_block){
		
		var overlay_height = $('easycheckout-review').offsetHeight+1;
	
		$('load-info-overlay').style.height = overlay_height+'px';
		$('load-info-overlay').style.top = $('easycheckout-review').offsetTop+'px';
		
	}else{
		var overlay_height = $('easycheckout-shipping-payment-step').offsetHeight + $('easycheckout-review').offsetHeight + 11;
	
		$('load-info-overlay').style.height = overlay_height+'px';
		$('load-info-overlay').style.top = $('easycheckout-shipping-payment-step').offsetTop+'px';
	}
	$('load-info-overlay').style.display = 'block';
	
}

function stopLoadingData(){
	
	$('load-info-overlay').style.display = 'none';
	
}


function shippingAddressChanged(){
	
	if(!$('billing_use_for_shipping_yes').checked){
		sendShippingAddress();
	}
}

function billingAddressChanged(){
		sendBillingAddress();
}

function changeShippingAddressMode(){
	
	$flag = this.checked;
		
	if($flag){
		$('shipping-address-form').style.display = 'none';
		sendBillingAddress();
	}else{
		$('shipping-address-form').style.display = 'block';
		sendShippingAddress();
	};
	
}

function buildQueryString(elements){
	
	var q = '';
	
	for(var i = 0;i < elements.length;i++){
		if((elements[i].type == 'checkbox' || elements[i].type == 'radio') && !elements[i].checked){
			continue;
		}
		q += elements[i].name + '=' + elements[i].value;
		
		if(i+1 < elements.length){
			q += '&';
		}
		
	}
	return q;
}

function update_coupon(remove){
	startLoadingData();
	if (remove){
		
		
        $('remove-coupone').value = "1";
		var q = buildQueryString($$('#coupon_code, #remove-coupone'));
	
		return updateFormData(checkoutCouponUrl, q);
	}
	else{
		
        $('remove-coupone').value = "0";
		var q = buildQueryString($$('#coupon_code, #remove-coupone'));
	
		return updateFormData(checkoutCouponUrl, q);
	}
}

function elogin(e, p, url){
	
	$('elogin-loading').style.display = 'block';
	$('elogin-buttons').style.display = 'none';
	
	var request = new Ajax.Request(url,
	  {
	    method:'post',
	    parameters:'username='+e+'&password='+p,
	    onSuccess: function(transport){ var response = eval('('+(transport.responseText || false)+')');
	      
	      if(response.error){
	      	  $('elogin-message').innerHTML = response.message;
	      	  $('elogin-loading').style.display = 'none';
			  $('elogin-buttons').style.display = 'block';
	      }else{
	      	  
	      	  location.reload();
	      	  
	      }
	      
	    },
	    onFailure: function(){ alert('Something went wrong...');stopLoadingData(); }
	  });
}

function updateFormData(url, q){
	
	var request = new Ajax.Request(url,
	  {
	    method:'post',
	    parameters:q,
	    onSuccess: function(transport){ var response = eval('('+(transport.responseText || false)+')');
	      
	      if(response.error){
			  if(response.review){
	      	  	$('easycheckout-review-info').update(response.review);
	      	  }
			  stopLoadingData();
			  alert(response.message);
	      	  //coming soon...
	      }else{
	      	  if(response.shipping_rates){
	      	  	$('easycheckout-shippingmethod-available').update(response.shipping_rates);
	      	  }
	      	  if(response.payments){
	      	  	$('easycheckout-paymentmethod-available').update(response.payments);
	      	  }
	      	  if(response.review){
	      	  	$('easycheckout-review-info').update(response.review);
	      	  }
			  if(response.coupon){
	      	  	$('easycheckout-coupon').update(response.coupon);
	      	  }
			stopLoadingData();	
	      }
	      
	    },
	    onFailure: function(){ alert('Something went wrong...');stopLoadingData(); }
	  });
	
}


function sendBillingAddress(){
	
	startLoadingData();
	
	var q = buildQueryString($$('#easycheckout-addressbilling input, #easycheckout-addressbilling select, #easycheckout-addressbilling textarea, #billing_use_for_shipping_yes'));
	
	if($('billing_use_for_shipping_yes') && $('billing_use_for_shipping_yes').checked){
		return updateFormData(checkoutDefaultUrl, q);
	}
	
	return updateFormData(checkoutBillingUrl, q);
	
	
}

function sendShippingAddress(){
	
	startLoadingData();
	
	var q = buildQueryString($$('#easycheckout-addressshipping input, #easycheckout-addressshipping select, #easycheckout-addressshipping textarea'));
	
	return updateFormData(checkoutShippingUrl, q);
	
}

function sendMethods(){
	
	startLoadingData(true);
	
	var q = '';
	
	q += buildQueryString($$('#easycheckout-shippingmethod input, #easycheckout-shippingmethod select, #easycheckout-shippingmethod textarea'));
	q += '&';
	q += buildQueryString($$('#easycheckout-paymentmethod input, #easycheckout-paymentmethod select, #easycheckout-paymentmethod textarea'));
	
	return updateFormData(checkoutTotalsUrl, q);
	
}






var paymentForm = Class.create();
paymentForm.prototype = {
	beforeInitFunc:$H({}),
    afterInitFunc:$H({}),
    beforeValidateFunc:$H({}),
    afterValidateFunc:$H({}),
    initialize: function(formId){
        this.form = $(this.formId = formId);
    },
    init : function () {
        var elements = Form.getElements(this.form);
        /*if ($(this.form)) {
            $(this.form).observe('submit', function(event){this.save();Event.stop(event);}.bind(this));
        }*/
        var method = null;
        for (var i=0; i<elements.length; i++) {
            if (elements[i].name=='payment[method]') {
                if (elements[i].checked) {
                    method = elements[i].value;
                }
            }
            elements[i].setAttribute('autocomplete','off');
        }
        if (method) this.switchMethod(method);
    },
    
    switchMethod: function(method){
        if (this.currentMethod && $('payment_form_'+this.currentMethod)) {
        	
            var form = $('payment_form_'+this.currentMethod);
            form.style.display = 'none';
            var elements = form.getElementsByTagName('input');
            for (var i=0; i<elements.length; i++) elements[i].disabled = true;
            var elements = form.getElementsByTagName('select');
            for (var i=0; i<elements.length; i++) elements[i].disabled = true;
            

        }
        if ($('payment_form_'+method)){
            var form = $('payment_form_'+method);
            form.style.display = '';
            var elements = form.getElementsByTagName('input');
            for (var i=0; i<elements.length; i++) elements[i].disabled = false;
            var elements = form.getElementsByTagName('select');
            for (var i=0; i<elements.length; i++) elements[i].disabled = false;
            this.currentMethod = method;
        }
    }
}
