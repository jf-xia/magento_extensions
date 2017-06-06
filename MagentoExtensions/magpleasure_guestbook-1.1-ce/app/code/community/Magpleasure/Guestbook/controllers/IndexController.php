<?php
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

class Magpleasure_Guestbook_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     * Helper
     *
     * @return Magpleasure_Guestbook_Helper_Data
     */
    public function _helper()
    {
        return Mage::helper('guestbook');
    }

    /**
     * Response for Ajax Request
     *
     * @param array $result
     */
    protected function _ajaxResponse($result = array())
    {
        $this->getResponse()->setBody(Zend_Json::encode($result));
    }

    public function indexAction()
    {
        $this->loadLayout()->renderLayout();
    }


    protected function _getMessageBlockHtml()
    {
        return $this->getLayout()->getMessagesBlock()->addMessages($this->_getCustomerSession()->getMessages(true))->toHtml();
    }

    /**
     * Customer Session
     *
     * @return Mage_Customer_Model_Session
     */
    protected function _getCustomerSession()
    {
        return Mage::getSingleton('customer/session');
    }

    public function formAction()
    {
        $result = array();
        $error = false;

        $replyTo = $this->getRequest()->getParam('reply_to');

        if (!is_null($replyTo)){
            $comment = Mage::getModel('guestbook/message')->load($replyTo);
        }

        /** @var Magpleasure_Guestbook_Block_Comments_Form $form  */
        $form = $this->getLayout()->createBlock('guestbook/comments_form');
        if ($form){

            if (isset($comment) && $comment->getId()){
                $form->setReplyTo($comment);
            }

            $form->setSecureCode($this->_helper()->_secure()->getSecureCode($replyTo));
            $result['form'] = $form->toHtml();
        }


        if ($error){
            $result['error'] = 1;
            $result['message'] = $this->_getMessageBlockHtml();
        }
        $this->_ajaxResponse($result);
    }

    public function postFormAction()
    {
        $result = array();
        $error = false;

        $post = new Varien_Object($this->getRequest()->getPost());

        $replyTo = $post->getReplyTo();
        $secureCode = $post->getSecureCode();

        if ($this->_helper()->_secure()->validate($secureCode, $replyTo)){
            $newComment = null;
            if ($replyTo){
                /** @var Magpleasure_Guestbook_Model_Message $comment  */
                $comment = Mage::getModel('guestbook/message')->load($replyTo);
                if ($comment->getId()){
                    $newComment = $comment->reply($post->getData());
                }

            } else {
                $post->unsetData('reply_to');
                /** @var Magpleasure_Guestbook_Model_Message $comment  */
                $comment = Mage::getModel('guestbook/message');
                $newComment = $comment->comment($post->getData());

            }

            if ($newComment){
                /** @var Magpleasure_Guestbook_Block_Comments_Message $message */
                $message = $this->getLayout()->createBlock('guestbook/comments_message');
                if ($message){
                    $message->setMessage($newComment);
                    $message->setIsAjax(true);
                    $result['message'] = $message->toHtml();
                    $result['comment_id'] = $newComment->getId();
                    $result['count_code'] = $message->getCountCode();
                }
            } else {
                $error = 1;
                $this->_getCustomerSession()->addError($this->_helper()->__("Can not create post."));
            }

        } else {
            $error = 1;
            $this->_getCustomerSession()->addError($this->_helper()->__("Your session was expired. Please refresh this page and try again. "));
        }

        if ($error){
            $result['error'] = 1;

            /** @var Magpleasure_Guestbook_Block_Comments_Form $form  */
            $form = $this->getLayout()->createBlock('guestbook/comments_form');
            if ($form){
                if ($replyTo){
                    /** @var Magpleasure_Guestbook_Model_Message $comment  */
                    $replyTo = Mage::getModel('guestbook/message')->load($replyTo);
                    $form->setReplyTo($replyTo);
                }
                $form->setIsAjax(true);
                $form->setFormData($post->getData());
                $form->setSecureCode($this->_helper()->_secure()->getSecureCode($replyTo));
                $result['form'] = $form->toHtml();
            }

        }

        $this->_ajaxResponse($result);
    }

}