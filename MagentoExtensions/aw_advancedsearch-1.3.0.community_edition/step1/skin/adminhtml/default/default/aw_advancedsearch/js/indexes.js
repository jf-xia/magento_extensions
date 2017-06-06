/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/LICENSE-M1.txt
 *
 * @category   AW
 * @package    AW_Advancedsearch
 * @copyright  Copyright (c) 2011 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/LICENSE-M1.txt
 */

var AWASIndexes = Class.create(
{
    initialize: function(objName)
    {
        this.global = window;
        this.global[objName] = this;
        this._objectName = objName;
        
        this.selectors = {
            type: 'type',
            attributesFieldset: 'awas_attributes'
        };
        this.urls = {};
        
        document.observe("dom:loaded", this.init.bind(this));
    },

    init: function()
    {
        if(typeof(awas_requesturl) != 'undefined') {
            this.setRequestUrl(awas_requesturl);
        }
        if(typeof(this.selectors.type) != 'undefined' && $(this.selectors.type)) {
            $(this.selectors.type).observe('change', this.global[this._getSelfObjectName()].checkType.bind(this));
            this.checkType();
        }
    },

    _getSelfObjectName: function()
    {
        return this._objectName;
    },

    _showLoader: function() {
        $('loading-mask').show();
    },

    _hideLoader: function() {
        $('loading-mask').hide();
    },

    checkType: function()
    {
        if(typeof(this.selectors.type) != 'undefined' && $(this.selectors.type) && this.urls.request) {
            this._showLoader();
            new Ajax.Request(this.urls.request, {
                parameters: {
                    typeId: $(this.selectors.type).value,
                    id: typeof(this.indexId) != 'undefined' ? this.indexId : null
                },
                onSuccess: function(response) {
                    this._hideLoader();
                    try {
                        var resp = response.responseText.evalJSON();
                        if($(this.selectors.attributesFieldset) && typeof(resp.fieldset) != 'undefined') {
                            $(this.selectors.attributesFieldset).replace(resp.fieldset);
                        }
                    } catch(ex) {}
                }.bind(this),
                onFailure: function() {
                    this._hideLoader();
                }.bind(this)
            });
        }
    },

    setRequestUrl: function(url)
    {
        this.urls.request = this._prepareUrl(url);
    },

    _prepareUrl: function(url)
    {
        url = typeof url != 'undefined' ? url : '';
        return url.replace(/^http[s]{0,1}/, window.location.href.replace(/:[^:].*$/i, ''));
    }
});

var AWASAC = Class.create({
    initialize: function(objName)
    {
        this.global = window;
        this.global[objName] = this;
        this.count = 0;
        this.htmlId = 'attributes_fieldset';
    },

    addItem: function(args)
    {
        data = {
            attribute: 0,
            weight: 1,
            index: this.count
        }
        if(typeof(args) != 'undefined') {
            data.attribute = args.attribute;
            data.weight = args.weight;
        }
        var s = '<tr>'+$(this.htmlId+'_add_template').innerHTML.replace(/__index__/g, '#{index}').replace(/\sdisabled="?no-template"?/g, ' ').replace(/disabled/g, ' ').replace(/="'([^']*)'"/g, '="$1"')+'</tr>';
        var template = new Template(s);
        Element.insert($(this.htmlId+'_container'), {'bottom': template.evaluate(data)});
        $('attributes_row_' + data.index + '_attribute').value = data.attribute;
        $('attributes_row_' + data.index + '_weight').value = data.weight;
        this.count++;
    },

    deleteItem : function(event) {
        var tr = Event.findElement(event, 'tr');
        if (tr) {
            a = tr;
            Element.select(tr, 'input.delete').each(function(elem) {elem.value = 1;});
            Element.select(tr, ['input', 'select']).each(function(elem){elem.hide()});
            Element.hide(tr);
            Element.addClassName(tr, 'no-display template');
        }
    },
    
    setCount: function(count) {
        this.count = count;
    },

    setHtmlId: function(id) {
        this.htmlId = id;
    }
});

new AWASIndexes('awas_indexes');
new AWASAC('attributesControl');
