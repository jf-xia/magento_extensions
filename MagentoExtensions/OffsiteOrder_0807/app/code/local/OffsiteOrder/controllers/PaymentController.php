<?php
class NeedTool_OffsiteOrder_PaymentController extends Mage_Core_Controller_Front_Action
{
    protected $_order;

    public function getOrder()
    {
        if ($this->_order == null) {
            $session = Mage::getSingleton('checkout/session');
            $this->_order = Mage::getModel('sales/order');
            $this->_order->loadByIncrementId($session->getLastRealOrderId());
        }
        return $this->_order;
    }

	public function orderAction()
	{
		
		if (!$order->getId()) {
			$this->norouteAction();
			return;
		}
		
		// TODO:save order id
		
		// save status
		if ($order instanceof Mage_Sales_Model_Order && $order->getId()) {
        $order->addStatusToHistory(
                $order->getStatus(),
                Mage::helper('offsiteorder')->__('Customer returned from offsiteorder.') . $errorMsg. ''
      	);            
        $order->save();
     }
    // redirect 
		$this->getResponse()->setRedirect($this->getExpress()->getApi()->getPaypalUrl());
	}

}
