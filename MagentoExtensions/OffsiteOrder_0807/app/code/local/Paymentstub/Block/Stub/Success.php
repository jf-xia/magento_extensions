<?php

/**
 * One page checkout success page
 *
 * @category   Mage
 * @package    NeedTool_Paymentstub
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class NeedTool_Paymentstub_Block_Stub_Success extends Mage_Core_Block_Template
{
    private $_order;

    /**
     * Retrieve identifier of created order
     *
     * @return string
     */
    public function getOrderId()
    {
        return Mage::getSingleton('checkout/session')->getLastRealOrderId();
    }

    /**
     * Check order print availability
     *
     * @return bool
     */
    public function canPrint()
    {
        return Mage::getSingleton('customer/session')->isLoggedIn() && $this->isOrderVisible();
    }

    /**
     * Get url for order detale print
     *
     * @return string
     */
    public function getPrintUrl()
    {
        /*if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            return $this->getUrl('sales/order/print', array('order_id'=>Mage::getSingleton('checkout/session')->getLastOrderId()));
        }
        return $this->getUrl('sales/guest/printOrder', array('order_id'=>Mage::getSingleton('checkout/session')->getLastOrderId()));*/
        return $this->getUrl('sales/order/print', array('order_id'=>Mage::getSingleton('checkout/session')->getLastOrderId()));
    }

    /**
     * Get url for view order details
     *
     * @return string
     */
    public function getViewOrderUrl()
    {
        return $this->getUrl('sales/order/view/', array('order_id'=>Mage::getSingleton('checkout/session')->getLastOrderId(), '_secure' => true));
    }

    /**
     * See if the order has state, visible on frontend
     *
     * @return bool
     */
    public function isOrderVisible()
    {
        if (!$this->_order) {
            $this->_order = Mage::getModel('sales/order')->load(Mage::getSingleton('checkout/session')->getLastOrderId());
        }
        if (!$this->_order) {
            return false;
        }
        return !in_array($this->_order->getState(), Mage::getSingleton('sales/order_config')->getInvisibleOnFrontStates());
    }

}