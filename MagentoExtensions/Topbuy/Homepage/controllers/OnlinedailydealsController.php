<?php
class Topbuy_Homepage_OnlinedailydealsController extends Mage_Core_Controller_Front_Action{
    public function IndexAction() {
      
        $this->loadLayout();   
        $this->getLayout()->getBlock("head")->setTitle($this->__("Deal of the Day - Online Daily Deals Melbourne Sydney Brisbane Australia - 1 Day Sale"))
                ->setDescription($this->__("Online Daily Deals - Best One Day Sale Deals from Melbourne Sydney Brisbane for Online Shopping featuring pet supplies, computers & laptops, skin care & makeup, fashion apparel and more."))
                ->setKeywords($this->__("Daily Deals, Deal of the Day, Daily Deals Melbourne, Online Deals,  Best Deals, 1 Day Sale, One Day Sale, Daily Deals Sydney, Deals of the Day, One Day Deals, Hot Deals, Cheap Deals, Daily Deal, 1 Day Deals, Daily Deals Brisbane, Dailydeals"));
        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
        $breadcrumbs->addCrumb("Online Daily Deals", array(
                "label" => $this->__("Online Daily Deals"),
                "title" => $this->__("Online Daily Deals")
                    ));
        
        $this->renderLayout(); 
    }
}