Object.extend(Validation, {
    isVisible : function(elm) {
        var container = elm.up('div.swatches-wrapper');
        if (container) {
            return true;
        } else {
             while(elm.tagName != 'BODY') {
                if(!$(elm).visible()) return false;
                elm = elm.parentNode;
            }
            return true;
        }
    },
    insertAdvice : function(elm, advice){
        var container = $(elm).up('.field-row');
        if(container){
            Element.insert(container, {
                after: advice
            });
        } else if (elm.up('td.value')) {
            elm.up('td.value').insert({
                bottom: advice
            });
        } else if (elm.advaiceContainer && $(elm.advaiceContainer)) {
            $(elm.advaiceContainer).update(advice);
        }
        else {
            var container = elm.up('div.swatches-wrapper');
            if (container) {
                Element.insert(container, {
                    after: advice
                });
            } else {
                switch (elm.type.toLowerCase()) {
                    case 'checkbox':
                    case 'radio':
                        var p = elm.parentNode;
                        if(p) {
                            Element.insert(p, {'bottom': advice});
                        } else {
                            Element.insert(elm, {'after': advice});
                        }
                        break;
                    default:
                        Element.insert(elm, {'after': advice});
                }
            }
        }
    }
});

Event.observe(window, 'load', function() {
    $$('li.swatches-customoptions-list-item img').each(function(element) {
        element.observe('click', function(event) {
            var a_el  =  $(this).up('a'), 
            li_el = a_el.up('li.swatches-customoptions-list-item'),
            ul_el = li_el.up('ul.swatches-customoptions-list'),
            rel   = a_el.readAttribute('rel').split('_');
                
            var option_els = $$('#product_addtocart_form [name^=options['+rel[0]+']]'),
                option_el = option_els[0];
           
            if (li_el.hasClassName('selected')) {
                li_el.removeClassName('selected');
                value = "";
            }
            else {
                if (option_el.type == 'select-one' || option_el.type == 'radio') {
                    Selector.findChildElements(ul_el, ['li.selected']).each(function(el) {
                        el.removeClassName('selected');
                    });
                }
                li_el.addClassName('selected');
                value = rel[1];
            }
            
            var state_text = '';
            switch (option_el.type) {
                case 'select-one':
                    option_el.value = value;
                    if (value)
                        state_text += option_el[option_el.selectedIndex].text;
                    option_el.fire('swatches:change');
                    break;
                case 'radio':
                    option_els.each(function(el) {
                        if (el.value == value) {
                            el.checked = true;
                            if (value) {
                                option_el.fire('swatches:change');
                                state_text += el.next().innerText;
                            }
                        }
                    });
                    break;
                case 'checkbox':
                    i = 0;
                    option_els.each(function(el) {
                        if (el.value == rel[1]) {
                            checked = (el.checked) ? false : true;
                            el.checked = checked;
                        }
                        if (el.checked)
                            state_text += el.next().innerText+', ';
                    });
                    state_text = state_text.slice(0, -2);
                    break;
                case 'select-multiple':
                    for (i = 0; i < option_el.options.length; i++) {
                        opt = option_el.options[i];
                        if (opt.value == rel[1]) {
                            selected = (opt.selected) ? false : true;
                            opt.selected = selected;
                        }
                        if (opt.selected)
                            state_text += opt.text+', ';
                    }
                    state_text = state_text.slice(0, -2);
                    break;
            }
            
            if ($('swatches_state_'+rel[0]))
                $('swatches_state_'+rel[0]).update(state_text + '&nbsp;');
            
            opConfig.reloadPrice()
        });
    });
});
