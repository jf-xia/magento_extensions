var inCart = false;

if (window.location.toString().search('/product_compare/') != -1){
	var win = window.opener;
}
else{
	var win = window;
}

if (window.location.toString().search('/checkout/cart/') != -1){
    inCart = true;
}


function setLocation(url){
    if(!inCart && (/*(url.search('/add') != -1 ) || (url.search('/remove') != -1 ) ||*/ url.search('checkout/cart/add') != -1) ){
        sendcart(url, 'url');
    }else{
        window.location.href = url;
    }
}


function sendcart(url, type){
    showLoading();
    if (type == 'form'){
        url = ($('product_addtocart_form').action).replace('checkout', 'mdlajaxcheckout/index/cart');
        //url = ($('product_addtocart_form').action);
        var myAjax = new Ajax.Request(
        url,
        {
            method: 'post',
            postBody: $('product_addtocart_form').serialize(),
            parameters : Form.serialize("product_addtocart_form"),
            onException: function (xhr, e)
            {
                alert('Exception : ' + e);
            },
            onComplete: function (xhr)
            {
                $('mdl-temp-div').innerHTML = xhr.responseText;
                var return_message = $('mdl-temp-div').down('.mdl_ajax_message').innerHTML;
                
                var middle_text = '<div class="mdl-cart-bts">'+$('mdl-temp-div').down('.back-ajax-add').innerHTML+'</div>';

                $('mdl_ajax_confirm').innerHTML = '<div id="mdl_ajax_confirm_wrapper">'+return_message + middle_text + '</div>';

                var link_cart_txt = $('mdl-temp-div').down('.cart_content').innerHTML;

                $$('.top-link-cart').each(function (el){
                    el.innerHTML = link_cart_txt;
                });

                

                var mini_cart_txt = $('mdl-temp-div').down('.cart_side_ajax').innerHTML;

                $$('.mini-cart').each(function (el){
                    el.replace(mini_cart_txt);
                    //new Effect.Opacity(el, { from: 0, to: 1, duration: 1.5 });
                });

                $$('.block-cart').each(function (el){
                    el.replace(mini_cart_txt);
                    //new Effect.Opacity(el, { from: 0, to: 1, duration: 1.5 });
                });

                
                replaceDelUrls();

                if (ajax_cart_show_popup){
                    showConfirm();
                } else {
                    hideMdlOverlay();
                }

            }

        });



    } else if (type == 'url'){

        url = url.replace('checkout', 'mdlajaxcheckout/index/cart');
        //alert(url);
        var myAjax = new Ajax.Request(
        url,
        {
            method: 'post',
            postBody: '',
            onException: function (xhr, e)
            {
                alert('Exception : ' + e);
            },
            onComplete: function (xhr)
            {
                $('mdl-temp-div').innerHTML = xhr.responseText;
                var return_message = $('mdl-temp-div').down('.mdl_ajax_message').innerHTML;
                var middle_text = '<div class="mdl-cart-bts">'+$('mdl-temp-div').down('.back-ajax-add').innerHTML+'</div>';


                var content_ajax = return_message + middle_text;
                
                $('mdl_ajax_confirm').innerHTML = '<div id="mdl_ajax_confirm_wrapper">'+content_ajax + '</div>';

                var link_cart_txt = $('mdl-temp-div').down('.cart_content').innerHTML;

                $$('.top-link-cart').each(function (el){
                    el.innerHTML = link_cart_txt;
                });

                


                var mini_cart_txt = $('mdl-temp-div').down('.cart_side_ajax').innerHTML;

                //alert(mini_cart_txt);

                $$('.mini-cart').each(function (el){
                    el.replace(mini_cart_txt);
                    //new Effect.Opacity(el, { from: 0, to: 1, duration: 1.5 });
                });

                $$('.block-cart').each(function (el){
                    el.replace(mini_cart_txt);
                    //new Effect.Opacity(el, { from: 0, to: 1, duration: 1.5 });
                });


                replaceDelUrls();
                if (ajax_cart_show_popup){
                    showConfirm();
                } else {
                    hideMdlOverlay();
                }
            }

        });

    }

}

function replaceDelUrls(){
    //if (!inCart){
        $$('a').each(function(el){
            if(el.href.search('checkout/cart/delete') != -1 && el.href.search('javascript:cartdelete') == -1){
                el.href = 'javascript:cartdelete(\'' + el.href +'\')';
            }
        });
    //}
}

function replaceAddUrls(){
    $$('a').each(function(link){
        if(link.href.search('checkout/cart/add') != -1){
            link.href = 'javascript:setLocation(\''+link.href+'\'); void(0);';
        }
    });
}

function cartdelete(url){
    
    showLoading();
    url = url.replace('checkout', 'mdlajaxcheckout/index/cartdelete');
    var myAjax = new Ajax.Request(
    url,
    {
        method: 'post',
        postBody: '',
        onException: function (xhr, e)
        {
            alert('Exception : ' + e);
        },
        onComplete: function (xhr)
        {
            $('mdl-temp-div').innerHTML = xhr.responseText;
            //$('mdl-temp-div').insert(xhr.responseText);

            var cart_content = $('mdl-temp-div').down('.cart_content').innerHTML;

            //alert(cart_content);

            $$('.top-link-cart').each(function (el){
                el.innerHTML = cart_content;
            });

            

            var process_reload_cart = false;
            var full_cart_content = $('mdl-temp-div').down('.mdl_full_cart_content').innerHTML;
            $$('.cart').each(function (el){
                el.replace(full_cart_content);
                process_reload_cart = true;
            });

            if (!process_reload_cart){
                $$('.checkout-cart-index .col-main').each(function (el){
                    el.replace(full_cart_content);
                    //new Effect.Opacity(el, { from: 0, to: 1, duration: 1.5 });
                });
            }




            var cart_side = '';
            if ($('mdl-temp-div').down('.cart_side_ajax')){
                cart_side = $('mdl-temp-div').down('.cart_side_ajax').innerHTML;
            }

            
            $$('.mini-cart').each(function (el){
                el.replace(cart_side);
                //new Effect.Opacity(el, { from: 0, to: 1, duration: 1.5 });
            });
            $$('.block-cart').each(function (el){
                el.replace(cart_side);
                //new Effect.Opacity(el, { from: 0, to: 1, duration: 1.5 });
            });

            

            replaceDelUrls();

            //$('mdl_ajax_progress').hide();
            hideMdlOverlay();
        }

    });


}

function showMdlOverlay(){
    new Effect.Appear($('mdl-overlay'), { duration: 0.5,  to: 0.8 });
}

function hideMdlOverlay(){
    $('mdl-overlay').hide();
    $('mdl_ajax_progress').hide();
    $('mdl_ajax_confirm').hide();
}


function mdlCenterWindow(element) {
     if($(element) != null) {

          // retrieve required dimensions
            var el = $(element);
            var elDims = el.getDimensions();
            var browserName=navigator.appName;
            if(browserName==="Microsoft Internet Explorer") {

                if(document.documentElement.clientWidth==0) {
                    //IE8 Quirks
                    //alert('In Quirks Mode!');
                    var y=(document.viewport.getScrollOffsets().top + (document.body.clientHeight - elDims.height) / 2);
                    var x=(document.viewport.getScrollOffsets().left + (document.body.clientWidth - elDims.width) / 2);
                }
                else {
                    var y=(document.viewport.getScrollOffsets().top + (document.documentElement.clientHeight - elDims.height) / 2);
                    var x=(document.viewport.getScrollOffsets().left + (document.documentElement.clientWidth - elDims.width) / 2);
                }
            }
            else {
                // calculate the center of the page using the browser andelement dimensions
                var y = Math.round(document.viewport.getScrollOffsets().top + ((window.innerHeight - $(element).getHeight()))/2);
                var x = Math.round(document.viewport.getScrollOffsets().left + ((window.innerWidth - $(element).getWidth()))/2);
            }
            // set the style of the element so it is centered
            var styles = {
                position: 'absolute',
                top: y + 'px',
                left : x + 'px'
            };
            el.setStyle(styles);




     }
}



function showLoading(){
    showMdlOverlay();
    var progress_box = $('mdl_ajax_progress');
    progress_box.show();
    progress_box.style.width = loadingW + 'px';
    progress_box.style.height = loadingH + 'px';

    
    $('mdl_ajax_progress').innerHTML = $('mdl-loading-data').innerHTML;
    progress_box.style.position = 'absolute';

    mdlCenterWindow(progress_box);
}


function showConfirm(){
    showMdlOverlay();
    $('mdl_ajax_progress').hide();
    var confirm_box = $('mdl_ajax_confirm');
    confirm_box.show();
    confirm_box.style.width = confirmW + 'px';
    confirm_box.style.height = confirmH + 'px';
    //mdl_ajax_confirm_wrapper
    if ($('mdl_ajax_confirm_wrapper') && $('mdl-upsell-product-table')){
        //alert($('mdl_ajax_confirm_wrapper').getHeight());
        confirm_box.style.height = $('mdl_ajax_confirm_wrapper').getHeight() + 'px';
        decorateTable('mdl-upsell-product-table');
    }

    $('mdl_ajax_confirm_wrapper').replace('<div id="mdl_ajax_confirm_wrapper">'+$('mdl_ajax_confirm_wrapper').innerHTML);

    confirm_box.style.position = 'absolute';
    mdlCenterWindow(confirm_box);
}

document.observe("dom:loaded", function() {
    replaceDelUrls();
    replaceAddUrls();
    Event.observe($('mdl-overlay'), 'click', hideMdlOverlay);

    var cartInt = setInterval(function(){
        if (typeof productAddToCartForm  != 'undefined'){
            if ($('mdl-overlay')){
                Event.observe($('mdl-overlay'), 'click', hideMdlOverlay);
            }
            productAddToCartForm.submit = function(url){
                if(this.validator && this.validator.validate()){
                    sendcart('', 'form');
                    clearInterval(cartInt);
                }

                return false;
            }
        } else {
            clearInterval(cartInt);
        }
    },500);
});