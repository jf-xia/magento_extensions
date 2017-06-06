<?php

class Brisign_Checklist_AddlistController extends Mage_Core_Controller_Front_Action {        

	public function indexAction() {
		$session = Mage::getSingleton("customer/session");
		if($session->isLoggedIn()){
			foreach($this->getRequest()->getParams() as $title=>$checknote) {
				$customerId = $session->getCustomer()->getId();
				$wpChecknote = Mage::getModel('checklist/checknote')->getCollection()->addFieldToFilter('customer_id',$customerId);
				$setCustomerId = Mage::getModel('checklist/checknote')->setCustomerId($customerId);
				$setTitle = $setCustomerId->setTitle(date("Y-m-d H:i:s"));
				$checklistChecknote = $setTitle->setChecknote($checknote)->save();
				$this->_redirect('checklist/addlist/list');	
			}
		}
  }
	
	
  public function listAction() {
        $this->loadLayout();
        $this->renderLayout();
  }

}
