<?php
/**
 * MageWorx
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MageWorx EULA that is bundled with
 * this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.mageworx.com/LICENSE-1.0.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.mageworx.com/ for more information
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @copyright  Copyright (c) 2012 MageWorx (http://www.mageworx.com/)
 * @license    http://www.mageworx.com/LICENSE-1.0.html
 */

/**
 * Customer Credit extension
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @
 */
class MageWorx_Adminhtml_Block_Customercredit_Sales_Order_View_Tab_Invoices extends Mage_Adminhtml_Block_Sales_Order_View_Tab_Invoices
{    
    public function setCollection($collection){
        if (Mage::helper('customercredit')->isEnabledCreditColumnsInGridOrderViewTabs()) $collection->addFieldToSelect('base_customer_credit_amount');
        $this->_collection = $collection;
    }

    protected function _prepareColumns() {
        if (Mage::helper('customercredit')->isEnabledCreditColumnsInGridOrderViewTabs()) {
            $this->addColumnAfter('credit_amount', array(
                'header'    => Mage::helper('customercredit')->__('Credit'),
                'index'     => 'base_customer_credit_amount',
                'type'      => 'currency',
                'currency'  => 'base_currency_code',      
                ), 'state');
        }
        return parent::_prepareColumns();
    }
    
}
