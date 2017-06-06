<?php

class Brisign_Checklist_AddlistController extends Mage_Core_Controller_Front_Action {        

	public function indexAction() {
		$session = Mage::getSingleton("customer/session");
		if($session->isLoggedIn()){
			foreach($this->getRequest()->getParams() as $title=>$checknote) {
				$customerLoginURL = Mage::getBaseUrl() . "lifestyle/index/list";
				$customerId = $session->getCustomer()->getId();
				$wpChecknote = Mage::getModel('checklist/checknote')->getCollection()->addFieldToFilter('customer_id',$customerId);
				$setCustomerId = Mage::getModel('checklist/checknote')->setCustomerId($customerId);
				$setTitle = $setCustomerId->setTitle(date("Y-m-d H:i:s"));
				$checklistChecknote = $setTitle->setChecknote($checknote)->save();
				Mage::app()->getFrontController()->getResponse()->setRedirect($customerLoginURL)->sendResponse();	
			}
		}
  }
	
	
  public function listAction() {
		$customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();
		$wpChecknote = Mage::getModel('checklist/checknote')->getCollection()->addFieldToFilter('customer_id',$customerId);
		echo '<ol>';
		foreach ($wpChecknote->getItems() as $_item){
			echo '<li>';
				echo $_item->getTitle();
			echo '</li>';
			echo '<li>';
				echo $_item->getChecknote();
			echo '</li>';
		}
		echo '</ol>';
  }

}
