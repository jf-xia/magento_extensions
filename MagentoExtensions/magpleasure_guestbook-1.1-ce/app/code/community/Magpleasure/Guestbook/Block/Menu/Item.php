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
class Magpleasure_Guestbook_Block_Menu_Item extends Mage_Core_Block_Abstract
{
    /**
     * Helper
     * @return Magpleasure_Guestbook_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('guestbook');
    }

    public function addGuestbookLink()
    {
        /** @var Mage_Page_Block_Template_Links $parentBlock  */
        $parentBlock = $this->getParentBlock();
        if ($parentBlock && $this->_helper()->getMenuEnabled()) {
            $parentBlock->addLink(
                $this->_helper()->getMenuLabel(),
                $this->getUrl('guestbook'),
                $this->_helper()->getMenuLabel(),
                false,
                array(),
                $this->_helper()->getMenuPosition(),
                'class="mp-guestbook"',
                'class="top-link-guestbook"'
            );
        }
        return $this;
    }

}