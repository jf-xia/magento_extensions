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

class Magpleasure_Guestbook_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Common Helper
     *
     * @return Magpleasure_Common_Helper_Data
     */
    public function getCommon()
    {
        return Mage::helper('magpleasure');
    }

    /**
     * Auto Approving
     *
     * @return mixed
     */
    public function getCommentsAutoapprove()
    {
        return Mage::getStoreConfig('guestbook/messages/autoapprove');
    }

    public function getCommentsPerPage()
    {
        return Mage::getStoreConfig('guestbook/messages/record_per_page');
    }

    public function getCommentsAllowGuests()
    {
        return Mage::getStoreConfig('guestbook/messages/allow_guests');
    }

    public function getMenuEnabled()
    {
        return Mage::getStoreConfig('guestbook/menu/enabled');
    }

    public function getMenuPosition()
    {
        return Mage::getStoreConfig('guestbook/menu/position');
    }

    public function getMenuLabel()
    {
        return Mage::getStoreConfig('guestbook/menu/label');
    }

    /**
     * Render
     *
     * @return Magpleasure_Guestbook_Helper_Comment_Render
     */
    public function _render()
    {
        return Mage::helper("guestbook/comment_render");
    }

    /**
     * Comment Secure
     *
     * @return Magpleasure_Guestbook_Helper_Comment_Secure
     */
    public function _secure()
    {
        return Mage::helper("guestbook/comment_secure");
    }

    protected function _getTimezone()
    {
        return Mage::getStoreConfig('general/locale/timezone');
    }

    public function renderTime($datetime)
    {
        $date = new Zend_Date($datetime, Zend_Date::ISO_8601, Mage::app()->getLocale()->getLocaleCode());
        $date->setTimezone($this->_getTimezone());
        return $date->toString(Zend_Date::TIME_SHORT);
    }

    public function renderDate($datetime)
    {
        $date = new Zend_Date($datetime, Zend_Date::ISO_8601, Mage::app()->getLocale()->getLocaleCode());
        $date->setTimezone($this->_getTimezone());
        return $date->toString(Zend_Date::DATE_LONG);
    }

    /**
     * Wrapper for standart strip_tags() function with extra functionality for html entities
     *
     * @param string $data
     * @param string $allowableTags
     * @param bool $escape
     * @return string
     */
    public function stripTags($data, $allowableTags = null, $escape = false)
    {
        $result = strip_tags($data, $allowableTags);
        return $escape ? $this->escapeHtml($result, $allowableTags) : $result;
    }

    /**
     * Notifier
     *
     * @return Magpleasure_Guestbook_Helper_Notifier
     */
    public function getNotifier()
    {
        return Mage::helper('guestbook/notifier');
    }
}
	 