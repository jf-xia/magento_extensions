<?php
class Magestore_Groupdeal_MydealController extends Mage_Core_Controller_Front_Action {
	
    public function indexAction(){
		$this->loadLayout();     
		$this->renderLayout();
    }
	
	public function preDispatch(){
        parent::preDispatch();
        if (!Mage::getSingleton('customer/session')->authenticate($this)) {
            $this->setFlag('', 'no-dispatch', true);
        }
    }
}