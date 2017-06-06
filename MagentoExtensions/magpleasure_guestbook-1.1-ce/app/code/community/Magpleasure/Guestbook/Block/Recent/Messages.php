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

class Magpleasure_Guestbook_Block_Recent_Messages extends Mage_Core_Block_Template
    implements Mage_Widget_Block_Interface
{
    protected $_limits = array(
        'content' => 400,
        'sidebar' => 100,
    );

    protected $_collection;

    protected function _construct()
    {
        parent::_construct();

    }

    public function getContentLimit()
    {
        return $this->getBlockType() ? $this->_limits[$this->getBlockType()] : 100;
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

    public function hasRecords()
    {
        return $this->getCollection()->getSize();
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
                ->setPageSize($this->getRecordLimit())
                ;

            $this->_collection = $comments;
        }
        return $this->_collection;
    }

    public function getSortType()
    {
        return Varien_Db_Select::SQL_DESC;
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

    protected function _beforeToHtml()
    {
        $blockType = $this->getBlockType();
        $this->setTemplate("guestbook/recent/{$blockType}.phtml");

        return parent::_beforeToHtml();
    }

    public function renderDate($datetime)
    {
        return $this->_helper()->renderDate($datetime);
    }

    public function getShortContent($content)
    {
        $content = $this->_helper()->getCommon()->getStrings()->stripTags($content);
        $shortContent = $this->_helper()->getCommon()->getStrings()->strLimit($content, $this->getContentLimit());

        return $shortContent.(($shortContent == $content) ? "" : "...");
    }

    public function getGuestBookUrl()
    {
        return $this->getUrl('guestbook');
    }
}