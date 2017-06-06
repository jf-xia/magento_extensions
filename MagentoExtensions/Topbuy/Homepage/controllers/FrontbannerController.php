<?php
class Topbuy_Homepage_FrontbannerController extends Mage_Core_Controller_Front_Action{
    public function IndexAction() {
      
	  $this->loadLayout();   
//	  $this->getLayout()->getBlock("head")->setTitle($this->__("frontBanner"));
//	        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
//      $breadcrumbs->addCrumb("home", array(
//                "label" => $this->__("Home Page"),
//                "title" => $this->__("Home Page"),
//                "link"  => Mage::getBaseUrl()
//		   ));
//
//      $breadcrumbs->addCrumb("frontbanner", array(
//                "label" => $this->__("frontBanner"),
//                "title" => $this->__("frontBanner")
//		   ));

      $this->renderLayout(); 
	  
    }
}