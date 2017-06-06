<?php
class Topbuy_Homepage_FreeshipController extends Mage_Core_Controller_Front_Action{
    
    public function IndexAction() {
        $this->loadLayout();   
        $this->getLayout()->getBlock("head")->setTitle($this->__("Free Shipping"));

        $this->renderLayout(); 
    }

}