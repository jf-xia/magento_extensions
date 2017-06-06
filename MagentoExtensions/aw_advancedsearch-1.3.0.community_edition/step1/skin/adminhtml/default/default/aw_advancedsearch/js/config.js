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

var AWASConfig = Class.create({
    initialize: function(objName) {
        this.global = window;
        this.global[objName] = this;
        this.selfName = objName;
        
        this.selectors = {
            container: 'awas_config_container',
            loader: 'awas_config_loader',
            error: 'awas_config_error'
        }
        
        document.observe("dom:loaded", this.init.bind(this));
    },

    init: function() {
        if($(this.selectors.container)) {
            this.checkState();
        }
    },

    showLoader: function() {
        this.hideError();
        if($(this.selectors.loader)) {
            if($(this.selectors.container)) {
                $(this.selectors.container).hide();
            }
            $(this.selectors.loader).show();
        }
    },

    hideLoader: function() {
        if($(this.selectors.loader)) {
            if($(this.selectors.container)) {
                $(this.selectors.container).show();
            }
            $(this.selectors.loader).hide();
        }
    },

    showError: function() {
        if($(this.selectors.error)) {
            $(this.selectors.error).show();
        }
    },

    hideError: function() {
        if($(this.selectors.error)) {
            $(this.selectors.error).hide();
        }
    },

    checkState: function() {
        if(typeof(awas_checkStateUrl) != 'undefined') {
            this.showLoader();
            new Ajax.Request(awas_checkStateUrl, {
                onSuccess: function(response) {
                    this.hideLoader();
                    try {
                        var resp = response.responseText.evalJSON();
                        if(typeof(resp.html) != 'undefined') {
                            $(this.selectors.container).innerHTML = resp.html;
                        }
                    } catch(ex) {}
                }.bind(this),
                onFailure: function() {
                    this.hideLoader();
                    this.showError();
                }.bind(this)
            });
        }
    },

    runDaemon: function(stop) {
        if(typeof(stop) == 'undefined') stop = false;
        if(stop) {
            var url = typeof(awas_stopDaemonUrl) != 'undefined' ? awas_stopDaemonUrl : null;
        } else {
            var url = typeof(awas_startDaemonUrl) != 'undefined' ? awas_startDaemonUrl : null;
        }
        if(url) {
            this.showLoader();
            new Ajax.Request(url, {
                onSuccess: function(response) {
                    this.hideLoader();
                    try {
                        var resp = response.responseText.evalJSON();
                        if(typeof(resp.r) != 'undefined' && resp.r) {
                            this.checkState();
                        } else {
                            this.showError();
                        }
                    } catch(ex) {}
                }.bind(this),
                onFailure: function() {
                    this.hideLoader();
                    this.showError();
                }.bind(this)
            });
        } else {
            this.showError();
        }
    }
});

new AWASConfig('awas_config');
