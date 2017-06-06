<?php
class Topbuy_Homepage_NewarrivalsController extends Mage_Core_Controller_Front_Action{
    
    public function IndexAction() {
        $this->loadLayout();   
        $this->getLayout()->getBlock("head")->setTitle($this->__("New Arrivals"));

        $this->renderLayout(); 
    }
    
}