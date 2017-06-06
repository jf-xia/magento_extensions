<?php

class Brisign_Lifestyle_IndexController extends Mage_Core_Controller_Front_Action {        

    public function indexAction()
    {

        /*$handles = array('default');
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $handles[] = 'customer_account';
        }
        $this->loadLayout($handles);
        $this->renderLayout();*/

        $this->loadLayout();
        $this->renderLayout();


    }
    

	public function likeAction() {
		$session = Mage::getSingleton("customer/session");
		if(!$session->isLoggedIn()){
    		$messageError = $this->__('You must Login first!');
    		$session->addError($messageError);
    		session_write_close();
    		$this->_redirect('customer/account');
		}
		if($session->isLoggedIn()){
			foreach($this->getRequest()->getParams() as $title=>$url) {
				//$customerLoginURL = Mage::getBaseUrl() . "lifestyle/index/list";
				$customerId = $session->getCustomer()->getId();
				$wpFavorite = Mage::getModel('lifestyle/favorite')->getCollection()->addFieldToFilter('customer_id',$customerId);
				foreach ($wpFavorite->getItems() as $_item){
					if($_item->getTitle()==$title) {
						$this->_redirect('lifestyle/index/list');
						//Mage::app()->getFrontController()->getResponse()->setRedirect($customerLoginURL)->sendResponse();	
        				return;
					}
				}
				$setCustomerId = Mage::getModel('lifestyle/favorite')->setCustomerId($customerId);
				$setTitle = $setCustomerId->setTitle($title);
				$lifestyleFavorite = $setTitle->setUrl($url)->save();
// 	    		$messageSuccess = $this->__('Add Favorite Success!');
// 	    		$session->addSuccess($messageSuccess);
// 	    		session_write_close();
	    		$this->_redirect('lifestyle/index/list');
				//Mage::app()->getFrontController()->getResponse()->setRedirect($customerLoginURL)->sendResponse();	
			}
		} 
  }
	
	
  public function listAction() {
  	/*
		$customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();
		$wpFavorite = Mage::getModel('lifestyle/favorite')->getCollection()->addFieldToFilter('customer_id',$customerId);
		echo '<ol>';
		foreach ($wpFavorite->getItems() as $_item){
			echo '<li>';
				echo $_item->getTitle();
			echo '</li>';
			echo '<li>';
				echo $_item->getUrl();
			echo '</li>';
		}
		echo '</ol>';
		*/
    $this->loadLayout();
    $this->renderLayout();
  }

}
