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

class Magpleasure_Guestbook_Block_Comments extends Mage_Core_Block_Template
{
    protected $_collection;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate("guestbook/comments.phtml");
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $head = $this->getLayout()->getBlock("head");
        if ($head){
            $head->setTitle($this->_helper()->getMenuLabel());
        }

        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
        if ($breadcrumbs){

            $breadcrumbs->addCrumb("home", array(
                "label" => $this->__("Home"),
                "title" => $this->__("Home"),
                "link" => Mage::getBaseUrl('web')
            ));

            $breadcrumbs->addCrumb("guestbook", array(
                "label" => $this->_helper()->getMenuLabel(),
                "title" => $this->_helper()->getMenuLabel()
            ));
        }
    }

    /**
     * Customer Session
     *
     * @return Mage_Customer_Model_Session
     */
    public function getCustomerSession()
    {
        return Mage::getSingleton('customer/session');
    }

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
     * Core
     *
     * @return Mage_Core_Helper_Data
     */
    public function _core()
    {
        return Mage::helper('core');
    }

    public function getHeader()
    {
        return $this->_helper()->getMenuLabel();
    }

    protected function _beforeToHtml()
    {
        $this->getToolbar()
            ->setPageVarName('p')
            ->setLimit($this->_helper()->getCommentsPerPage())
            ->setTemplate('guestbook/comments/pager.phtml')
            ->setCollection($this->getCollection())
        ;
        parent::_beforeToHtml();
    }

    public function getCollection()
    {
        if (!$this->_collection){
            /** @var Magpleasure_Guestbook_Model_Mysql4_Message_Collection $comments  */
            $comments = Mage::getModel('guestbook/message')->getCollection();

            if (!Mage::app()->isSingleStoreMode()){
                $comments->addStoreFilter(Mage::app()->getStore()->getId());
            }

            $comments
                ->addActiveFilter($this->_helper()->getCommentsAutoapprove() ? null : $this->getCustomerSession()->getSessionId() )
            ;

            $comments
                ->setDateOrder($this->getSortType())
                ->setNotReplies()
                ;

            $this->_collection = $comments;
        }
        return $this->_collection;
    }

    public function getSortType()
    {
        return Mage::getStoreConfig('guestbook/messages/sort_order');
    }

    public function getMessageHtml(Magpleasure_Guestbook_Model_Message $message)
    {
        $messageBlock = $this->getLayout()->createBlock('guestbook/comments_message');
        if ($messageBlock){
            $messageBlock->setMessage($message);
            return $messageBlock->toHtml();
        }
        return false;
    }

    public function getFormUrl()
    {
        return $this->getUrl('guestbook/index/form', array(
            'reply_to'=>'{{reply_to}}',
        ));
    }

    public function getPostUrl()
    {
        return $this->getUrl('guestbook/index/postForm', array(
            'reply_to'=>'{{reply_to}}',
        ));
    }

    /**
     * @return Mage_Page_Block_Html_Pager
     */
    public function getToolbar()
    {
        return $this->getLayout()->getBlock('guestbook_list_toolbar');
    }

    public function getToolbarHtml()
    {
        return $this->getToolbar()->toHtml();
    }

}