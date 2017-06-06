<?php

class Brisign_Lifestyle_IndexController extends Mage_Core_Controller_Front_Action {        

	public function likeAction() {
		$session = Mage::getSingleton("customer/session");
		if($session->isLoggedIn()){
			foreach($this->getRequest()->getParams() as $title=>$url) {
				$customerId = $session->getCustomer()->getId();
				$setCustomerId = Mage::getModel('lifestyle/favorite')->setCustomerId($customerId);
				$setTitle = $setCustomerId->setTitle($title);
				$lifestyleFavorite = $setTitle->setUrl($url)->save();
				$customerLoginURL = Mage::getBaseUrl() . "lifestyle/index/list";
				Mage::app()->getFrontController()->getResponse()->setRedirect($customerLoginURL)->sendResponse();
			}
		}
    }
	
	
    public function listAction() {
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
    }

/**
    public function indexAction() {
        echo 'Hello World!';
    }
	public function byeAction() {
        echo 'bye World!';
    }
	public function paramsAction() {
		echo '<dl>';            
		foreach($this->getRequest()->getParams() as $key=>$value) {
			echo '<dt><strong>Param: </strong>'.$key.'</dt>';
			echo '<dl><strong>Value: </strong>'.$value.'</dl>';
		}
		echo '</dl>';
	}
**/

}
