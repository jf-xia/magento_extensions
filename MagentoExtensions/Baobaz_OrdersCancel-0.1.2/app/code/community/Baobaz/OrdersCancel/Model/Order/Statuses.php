<?php
class Baobaz_OrdersCancel_Model_Order_Statuses
{
       // set null to enable all possible
    protected $_stateStatuses = array(
        Mage_Sales_Model_Order::STATE_NEW,
        Mage_Sales_Model_Order::STATE_PENDING_PAYMENT,
//        Mage_Sales_Model_Order::STATE_PROCESSING,
//        Mage_Sales_Model_Order::STATE_COMPLETE,
//        Mage_Sales_Model_Order::STATE_CLOSED,
//        Mage_Sales_Model_Order::STATE_CANCELED,
//        Mage_Sales_Model_Order::STATE_HOLDED,
    );

    public function toOptionArray()
    {
        if ($this->_stateStatuses) {
            $statuses = Mage::getSingleton('sales/order_config')->getStateStatuses($this->_stateStatuses);
        }
        else {
            $statuses = Mage::getSingleton('sales/order_config')->getStatuses();
        }
        $options = array();
        $options[] = array(
               'value' => '',
               'label' => Mage::helper('adminhtml')->__('-- Please Select --')
            );
        foreach ($statuses as $code=>$label) {
                $options[] = array(
                   'value' => $code,
                   'label' => $label
                );
        }
        return $options;
    }

}