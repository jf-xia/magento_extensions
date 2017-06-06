<?php
class Mage_Productlist_IndexController extends Mage_Core_Controller_Front_Action {
	public function indexAction(){
		$this->loadLayout();
		$block	=	$this->getLayout()->createBlock('productlist/list_all');
		$this->getLayout()->getBlock('content')->append($block);
		$this->renderLayout();
	}

	public function categoryAction(){
		$this->loadLayout();
		$block	=	$this->getLayout()->createBlock('productlist/list_category');
		$this->getLayout()->getBlock('content')->append($block);
		$this->renderLayout();
	}

}