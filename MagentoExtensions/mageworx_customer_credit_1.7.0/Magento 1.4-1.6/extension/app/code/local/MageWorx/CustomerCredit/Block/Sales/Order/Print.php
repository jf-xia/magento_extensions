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
 * @copyright  Copyright (c) 2011 MageWorx (http://www.mageworx.com/)
 * @license    http://www.mageworx.com/LICENSE-1.0.html
 */

/**
 * Customer Credit extension
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @author     MageWorx Dev Team
 */
class MageWorx_CustomerCredit_Block_Sales_Order_Print extends Mage_Sales_Block_Order_Print {

    public function getPaymentInfoHtml() {
        $paymentHtml =  parent::getChildHtml('payment_info');
        $_order = $this->getOrder();
        if($_order->getCustomerCreditAmount()>0 && $_order->getPayment()->getMethod() != 'customercredit') {
            $paymentHtml = Mage::helper('customercredit')->__('Internal Credit') . ' + ' . $paymentHtml;
        }
        return $paymentHtml;
    }

}

