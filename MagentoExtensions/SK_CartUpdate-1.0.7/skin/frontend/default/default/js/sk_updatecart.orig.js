document.observe("dom:loaded", function() {
  var pub = {};
  pub.ajaxInProgress = false;
  pub.WindowSize = {width:window.innerWidth || window.document.documentElement.clientWidth || window.document.body.clientWidth, height:window.innerHeight || window.document.documentElement.clientHeight || window.document.body.clientHeight};
  pub.init = function() {
    $$(".checkout-cart-index .cart form")[0].observe("submit", function(event) {
      Event.stop(event);
      pub.ajax(this)
    });
    pub.prepare()
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
      pub.loading(false)
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
    $$(".checkout-cart-index table#shopping-cart-table input.qty").each(function(el) {
      el.insert({before:'<a href="javascript:void(0);" class="qty-less"></a>', after:'<a href="javascript:void(0);" class="qty-more"></a>'}).up().style.whiteSpace = "nowrap"
    });
    $$(".checkout-cart-index table#shopping-cart-table .qty-less").each(function(el) {
      el.observe("click", function(event) {
        clearTimeout(ajaxTimeout);
        var value = parseFloat(el.next().value);
        if(value > 0) {
          el.next().value = value - 1
        }
        ajaxTimeout = setTimeout(function() {
          pub.ajax($$(".checkout-cart-index .cart form")[0])
        }, 1E3)
      })
    });
    $$(".checkout-cart-index table#shopping-cart-table .qty-more").each(function(el) {
      el.observe("click", function(event) {
        clearTimeout(ajaxTimeout);
        var value = parseFloat(el.previous().value);
        el.previous().value = value + 1;
        ajaxTimeout = setTimeout(function() {
          pub.ajax($$(".checkout-cart-index .cart form")[0])
        }, 1E3)
      })
    })
  };
  pub.fake = function(html) {
    var fake = document.createElement("div");
    Element.extend(fake);
    fake.setAttribute("id", "ajaxCartFake");
    fake.innerHTML = html;
    if(!fake.select("table#shopping-cart-table")[0]) {
      window.location = ""
    }
    var table = fake.select("table#shopping-cart-table")[0];
    var total = fake.select("div.totals")[0];
    $$(".checkout-cart-index table#shopping-cart-table")[0].insert({before:$(table)}).remove();
    $$(".checkout-cart-index div.totals")[0].insert({before:$(total)}).remove();
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