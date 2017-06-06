<?php
class Topbuy_Homepage_StealdayController extends Mage_Core_Controller_Front_Action{
    public function IndexAction() {
      
        $this->loadLayout();   
        $this->getLayout()->getBlock("head")->setTitle($this->__("Steal Of The Day"));
        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
        $breadcrumbs->addCrumb("StealOfTheDay", array(
                "label" => $this->__("StealOfTheDay"),
                "title" => $this->__("StealOfTheDay")
                    ));
        
        $this->renderLayout(); 
    }
}