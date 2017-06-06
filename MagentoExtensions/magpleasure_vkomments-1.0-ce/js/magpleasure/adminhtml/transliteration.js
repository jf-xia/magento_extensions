/**
 * Magpleasure Ltd.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE-CE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magpleasure.com/LICENSE-CE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * Magpleasure does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Magpleasure does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   Magpleasure
 * @package    Magpleasure_Common
 * @version    0.6.11
 * @copyright  Copyright (c) 2012-2013 Magpleasure Ltd. (http://www.magpleasure.com)
 * @license    http://www.magpleasure.com/LICENSE-CE.txt
 */
var MpAdminhtmlTransliteration = Class.create();
MpAdminhtmlTransliteration.prototype = {
    initialize:function (params) {
        this.data = {};
        for (key in params) {
            this[key] = this.data[key] = params[key];
        }
    },
    transliterate: function(title, id){
        if (this.url && $(id)){

            $(id).addClassName('loading');

            /*
             *  Load Dialog Content
             */
            new Ajax.Request(
                this.url.replace('{{title}}', encodeURIComponent(title)).replace(/^http[s]{0,1}/, window.location.href.replace(/:[^:].*$/i, '')), {
                    method: 'get',
                    loaderArea: false,
                    onSuccess: (function(transport){
                        if (transport && transport.responseText) {
                            try {
                                var response = eval('(' + transport.responseText + ')');
                                if (response.slug){
                                    $(id).value = response.slug;
                                }
                            } catch (e) {

                            }
                        }
                    }).bind(this),
                    onFailure: function(){
                        $(id).value = title;
                    },
                    onComplete: function(){
                        $(id).removeClassName('loading');
                    }
                });
        }
    }
};