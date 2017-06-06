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
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@mageworx.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.mageworx.com/ for more information
 * or send an email to sales@mageworx.com
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @copyright  Copyright (c) 2010 MageWorx (http://www.mageworx.com/)
 * @license    http://www.mageworx.com/LICENSE-1.0.html
 */
 
/**
 * Customer Credit extension
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @author     MageWorx Dev Team <dev@mageworx.com>
 */
 
class MageWorx_CustomerCredit_Model_Creditmemo_Total_Customercredit extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract
{
    public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo)
    {
        if (!Mage::helper('customercredit')->isEnabled())
        {
            return $this;
        }
        
        $order = $creditmemo->getOrder();
        if (!$order->getBaseCustomerCreditAmount() || $order->getBaseCustomerCreditInvoiced() == 0)
        {
            return $this;
        }
        
        $invoiceBaseRemainder = $order->getBaseCustomerCreditInvoiced() - $order->getBaseCustomerCreditRefunded();
        $invoiceRemainder     = $order->getCustomerCreditInvoiced() - $order->getCustomerCreditRefunded();
        $used = $baseUsed = 0;
        if ($invoiceBaseRemainder < $creditmemo->getBaseGrandTotal())
        {
            $used = $invoiceRemainder;
            $baseUsed = $invoiceBaseRemainder;
            
            $creditmemo->setGrandTotal($creditmemo->getGrandTotal()-$used);
            $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal()-$baseUsed);
        }
        else
        {
            $used = $creditmemo->getGrandTotal();
            $baseUsed = $creditmemo->getBaseGrandTotal();
            
            $creditmemo->setBaseGrandTotal(0);
            $creditmemo->setGrandTotal(0);
            $creditmemo->setAllowZeroGrandTotal(true);
        }
        $creditmemo->setCustomerCreditAmount($used);
        $creditmemo->setBaseCustomerCreditAmount($baseUsed);
        
        return $this;
    }
}