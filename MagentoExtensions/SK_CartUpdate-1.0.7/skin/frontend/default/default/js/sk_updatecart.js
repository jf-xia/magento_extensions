document.observe("dom:loaded", function() {
	//return false;
	var pub = {};
	pub.ajaxInProgress = false;
	pub.WindowSize = {width:window.innerWidth || window.document.documentElement.clientWidth || window.document.body.clientWidth, height:window.innerHeight || window.document.documentElement.clientHeight || window.document.body.clientHeight};
	
	pub.init = function() {
		if ($$(".checkout-cart-index .cart")[0]) {
			$$(".checkout-cart-index .cart")[0].down('form').observe("submit", function(event) {
				event.stop();
				Event.stop(event);
				pub.ajax(this);
				return false;
			});
			pub.prepare()
		}
	};

	pub.ajax = function(el) {
		if(pub.ajaxInProgress == true) {
			return false
		}
		pub.ajaxInProgress = true;
		pub.loading(true);
		var form = el;
		var formUrl = form.getAttribute("action");
		var url = formUrl.replace("checkout/cart", "cartupdate/ajax");
		new Ajax.Request(url, {method:"post", evalJS:true, parameters:form.serialize(), onComplete:function(transport) {
			
			pub.fake(transport.responseText);
			pub.prepare();
			pub.ajaxInProgress = false;
			pub.loading(false);

		}, onFailure:function() {
			alert("Something went wrong...")
		}})
	};

	pub.loading = function(action) {
		var loaderId = "ajax-loading";
		var overlayId = "ajax-cart-overlay";
		if(action) {
			var loader = document.createElement("div");
			Element.extend(loader);
			loader.setAttribute("id", loaderId);
			document.body.appendChild(loader);
			pub.center(loaderId);
			var overlay = document.createElement("div");
			Element.extend(overlay);
			overlay.setAttribute("id", overlayId);
			document.body.appendChild(overlay)
		}else {
			$(loaderId).remove();
			$(overlayId).remove()
		}
	};

	pub.center = function(id) {
		var elt = $(id);
		var eltDims = elt.getDimensions();
		var browserDims = pub.WindowSize;
		var y = (browserDims.height - eltDims.height) / 2;
		var x = (browserDims.width - eltDims.width) / 2;
		var styles = {position:"absolute", top:y + "px", left:x + "px"};
		elt.setStyle(styles)
	};

	pub.prepare = function() {
		var ajaxTimeout;

		$$(".checkout-cart-index .cart")[0].down('form').setAttribute('onsubmit', 'return false');
		
		$$(".checkout-cart-index .btn-remove").each(function(el) {
			var url = el.getAttribute('href').replace("checkout/cart", "cartupdate/ajax");
			el.setAttribute('href', url);
		});
		
		$$("#shopping-cart-table input.qty").each(function(el) {
			el.insert({
				before:'<a href="javascript:void(0);" class="qty-less"><span></span></a>', 
				after:'<a href="javascript:void(0);" class="qty-more"><span></span></a>'
			}).up().style.whiteSpace = "nowrap"
		});

		$$("#shopping-cart-table .qty-less").each(function(el) {
			el.observe("click", function(event) {
				clearTimeout(ajaxTimeout);
				var value = parseFloat(el.next().value);
				if(value > 0) {
					el.next().value = value - 1
				}
				ajaxTimeout = setTimeout(function() {
					pub.ajax($$(".checkout-cart-index .cart")[0].down('form'))
				}, 1E3)
			})
		});

		$$("#shopping-cart-table .qty-more").each(function(el) {
			el.observe("click", function(event) {
				clearTimeout(ajaxTimeout);
				var value = parseFloat(el.previous().value);
				el.previous().value = value + 1;
				ajaxTimeout = setTimeout(function() {
					pub.ajax($$(".checkout-cart-index .cart")[0].down('form'))
				}, 1E3)
			})
		})

	};

	pub.fake = function(html) {
		var fake = document.createElement("div");
		Element.extend(fake);
		fake.setAttribute("id", "ajaxCartFake");
		fake.innerHTML = html;
		
		if(!fake.select(".cart-table")) {
			window.location = ""
		}
		
		var table = fake.select(".cart-table")[0];
		var total = fake.select("div.totals")[0];
		
		$("shopping-cart-table").insert({
			before:$(table)
		}).remove();
		
		$$(".checkout-cart-index div.totals")[0].insert({
			before:$(total)
		}).remove();
		
		decorateTable("shopping-cart-table");
		if($$('.messages')[0])
		$$('.messages')[0].remove();
		return true
	};

	pub.init()

});

String.prototype.replace = function(pattern, replacement) {
	return this.split(pattern).join(replacement)
};