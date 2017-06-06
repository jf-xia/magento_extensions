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


class Magpleasure_Guestbook_Block_Adminhtml_Widget_Grid_Column_Renderer_Customer extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * Renders grid column
     *
     * @param   Varien_Object $row
     * @return  string
     */
    public function render(Varien_Object $row)
    {
        $customerId = $this->_getValue($row);
        if ($customerId) {
            $html = "";
            /** @var Mage_Customer_Model_Customer $customer  */
            $customer = Mage::getModel('customer/customer')->load($customerId);
            $name = $customer->getName();
            $url = $this->getUrl('adminhtml/customer/edit', array('id'=>$customerId));
            $html .= "<a href=\"{$url}\" target=\"_blank\">{$name}</a>";
            return $html;
        }
        return parent::render($row);
    }
}