<?php
class Topbuy_Paymentverify_CreditcardverifyController extends Mage_Core_Controller_Front_Action{
      public function IndexAction() {
		$payment_uuid = $this->getRequest()->getParam('uuid');
		$postData = Mage::app()->getRequest()->getPost(); 
		
		$isValid = false;
	    $this->loadLayout();
		if ($payment_uuid != "")
		{
			$isValid = true;
			$block = $this->getLayout()->getBlock('paymentverify.creditcardverify.custom'); 
    		if (isset($postData))
			{
				$block->setData('postData',$postData); 
			}
			$block->setData('uuid',$payment_uuid); 
			//$block->setData('inumberint',$inumberint); 
			//$block->setData('inumber1',$inumber1); 
			//$block->setData('inumber2',$inumber2); 
		}  
		$this->getLayout()->getBlock("head")->setTitle($this->__("Credit Card Payment Verify Page")); 
       	$this->renderLayout(); 
    }  
}

