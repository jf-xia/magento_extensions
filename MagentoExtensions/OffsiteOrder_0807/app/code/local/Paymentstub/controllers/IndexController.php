<?php

class NeedTool_Paymentstub_IndexController extends Mage_Core_Controller_Front_Action
{
	 protected $_order;
	 
    function indexAction()
    {
    	
    	$order_id=-1; 		
		   	if ($this->getRequest()->isPost()) {
					$postData = $this->getRequest()->getPost();
		      $method = 'post';
		
				} else if ($this->getRequest()->isGet()) {
					$postData = $this->getRequest()->getQuery();
					$method = 'get';
		
				} else {
					$model->generateErrorResponse();
					return;
				}
					
			if (!( isset($postData['realorderid']) )) {
				//echo "fail";
				return;
			}
		
			$order_id=$postData['realorderid'];
			$this->preload($order_id);
			$this->_redirect('paymentstub/stub', array('_secure'=>true));
    }
    
    function preload($orderid){
    	$this->getOrder($orderid);
    	//Mage::log($this->_order);
      $session = Mage::getSingleton('checkout/session');
			$session->setLastRealOrderId($orderid);
    	//Mage::log($session);
      $convertOrder = Mage::getModel('sales/convert_order');
    	$quote=Mage::getSingleton('checkout/session')->getQuote();
    	$quote=$convertOrder->toQuote($this->_order,$quote);
			//Mage::log($quote);
    	
    	$this->_order->addStatusToHistory(
						$this->_order->getStatus(),
						Mage::helper('paymentstub')->__('Customer entered NeedTool-PaymentStub')
						);
    	$this->_order->save();
    }
    
    public function getOrder($orderid)
    {
        if ($this->_order == null) {
            $this->_order = Mage::getModel('sales/order');
            $this->_order->loadByIncrementId($orderid);
        }
        return $this->_order;
    }
}