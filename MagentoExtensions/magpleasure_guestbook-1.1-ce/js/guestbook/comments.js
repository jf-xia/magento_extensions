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
 * @package    Magpleasure_Guestbook
 * @version    1.1
 * @copyright  Copyright (c) 2012-2013 Magpleasure Ltd. (http://www.magpleasure.com)
 * @license    http://www.magpleasure.com/LICENSE-CE.txt
 */

var MpGuestbookComments = Class.create();
MpGuestbookComments.prototype = {
    initialize:function (params) {
        for (key in params) {
            this[key] = params[key];
        }
        this.form = false;
    },
    hideForm: function(form_id, callback){
        $(form_id).innerHTML = '';
        new Effect.Fade(form_id, {
            afterFinish: (typeof(callback) != 'undefined' ? callback() : function(){}),
            duration: 1.0
        });
    },
    getForm:function (container, id) {
        var formContainer = $(container);
        if (formContainer && (formContainer.getStyle('display') == 'none')) {

            $$(this.form_selector).each(function (element) {
                if (element.id !== container) {
                    element.innerHTML = '';
                    new Effect.Fade(element.id);
                }
            });

            this.showLoader(container);
            new Effect.Appear(container, {
                duration:1.0,
                afterFinish:(function (e) {
                    new Effect.ScrollTo(formContainer.id);
                    this.loadFormToContainer(container, id);


                }).bind(this)
            });
        } else {
            new Effect.ScrollTo(formContainer.id);
        }
        return false;
    },
    showLoader:function (who) {
        $(who).innerHTML = $(this.loader_container).innerHTML;
    },
    loadFormToContainer:function (container, id) {
        var url = this.form_url.replace(/^http[s]{0,1}/, window.location.href.replace(/:[^:].*$/i, ''));

        new Ajax.Request(
            url.replace('{{post_id}}', this.post_id).replace('{{reply_to}}', id), {
                method:'get',
                onComplete:function (transport) {
                    if (transport && transport.responseText) {
                        try {
                            var response = eval('(' + transport.responseText + ')');
                            if (!response.error) {
                                $(container).innerHTML = response.form;
                                response.form.evalScripts();
                            }
                        } catch (e) {
                            response = {};
                        }
                    }
                }
            });
    },
    submitForm: function(){
        if (this.form && this.form.validator && this.form.validator.validate()){

            var form_id = this.form.form.id;
            var data = {};
            $$('#' + form_id + ' input, #' + form_id + ' textarea').each((function(element){
                if (element.type == 'checkbox'){
                    data[element.name] = element.checked ? 1 : 0;
                } else {
                    data[element.name] = element.value;
                }
            }).bind(this).bind(data));

            var url = this.post_url.replace(/^http[s]{0,1}/, window.location.href.replace(/:[^:].*$/i, ''));

            var container = this.form.form.parentNode.id;
            this.showLoader(container);

            new Ajax.Request(
                url.replace('{{post_id}}', this.post_id).replace('{{reply_to}}', data.reply_to), {
                    method: 'post',
                    parameters: data,
                    onComplete: (function(transport){
                        if (transport && transport.responseText) {
                            try {
                                var response = eval('(' + transport.responseText + ')');
                                if (!response.error) {
                                    this.hideForm(container, (function(){
                                        Element.insert($(container), { before: response.message });
                                        new Effect.Appear('mp-blog-comment-' + response.comment_id, {
                                            duration: 1.0,
                                            afterFinish: (function(){
                                                new Effect.ScrollTo('mp-blog-comment-' + response.comment_id, {
                                                    afterFinish: (function(){
                                                        if ($('no_comments')){
                                                            new Effect.Fade('no_comments');
                                                        }

                                                    }).bind(response).bind(this)
                                                });
                                            }).bind(response).bind(this)
                                        });
                                        response.message.evalScripts();
                                    }).bind(response).bind(this));
                                    if ($(this.comments_counter)){
                                        $(this.comments_counter).innerHTML = response.count_code;
                                    }
                                } else {
                                    $(container).innerHTML = response.form;
                                    response.form.evalScripts();
                                }
                            } catch (e) {
                                response = {};
                            }
                        }
                    }).bind(this).bind(container)
            });





        }
    }
};