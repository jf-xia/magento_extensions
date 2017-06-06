<?php

class Topbuy_Homepage_BusinessController extends Mage_Core_Controller_Front_Action {
    
    public function ResellerinfoAction() {
        $this->loadLayout();   
        $this->getLayout()->getBlock("head")->setTitle($this->__("Reseller Dashboard"));

        $this->renderLayout(); 
    }

    public function ResellerAction() {
        $session = Mage::getSingleton("customer/session");
        if(!$session->isLoggedIn()){
            $session->setBeforeAuthUrl(Mage::helper("core/url")->getCurrentUrl());
            $this->_redirect('customer/account/login');	
        } else {
            $customerid = $session->getCustomer()->getId();
            $company  = $this->getRequest()->getParam('company');
            $abn  = $this->getRequest()->getParam('abn');
            $website  = $this->getRequest()->getParam('website');
            $ebayid  = $this->getRequest()->getParam('eBayid');
            $store  = $this->getRequest()->getParam('store');
            $monthlysales  = $this->getRequest()->getParam('monthlysales');
            $iproducts  = $this->getRequest()->getParam('iproducts');
            $wherehear  = $this->getRequest()->getParam('wherehear');
            try {
                Mage::getModel('homepage/reseller')
                    ->setCustomerid($customerid)
                    ->setCompany($company)
                    ->setAbn($abn)
                    ->setWebsite($website)
                    ->setEbayid($ebayid)
                    ->setStore($store)
                    ->setMonthlysales($monthlysales)
                    ->setIproducts($iproducts)
                    ->setWherehear($wherehear)
                    ->save();
            } catch (Mage_Core_Exception $e) {
                $this->_fault('setData Error Message: ', $e->getMessage());
                return false;
            }
            $message = $this->__('Submit Reseller Application Success!');
            $session->addSuccess($message);
            session_write_close();
            $this->_redirect('customer/account');
        }
    }
    
    public function SupplierAction() {
        $session = Mage::getSingleton("customer/session");
        if(!$session->isLoggedIn()){
            $session->setBeforeAuthUrl(Mage::helper("core/url")->getCurrentUrl());
            $this->_redirect('customer/account/login');	
        } else {
            $customerid = $session->getCustomer()->getId();
            $company  = $this->getRequest()->getParam('company');
            $contactname  = $this->getRequest()->getParam('contactname');
            $email  = $this->getRequest()->getParam('semail');
            $phone  = $this->getRequest()->getParam('phone');
            $fax  = $this->getRequest()->getParam('fax');
            $website  = $this->getRequest()->getParam('website');
            $products  = $this->getRequest()->getParam('products');
            $iscredit  = 0;
            $iselectronicinf  = 0;
            $isdatafeed  = 0;
            $isdropship  = 0;
            $iselectronic  = 0;
            if ($this->getRequest()->getParam('iscredit')!=null) $iscredit  = 1;
            if ($this->getRequest()->getParam('iselectronicinf')!=null) $iselectronicinf  = 1;
            if ($this->getRequest()->getParam('isdatafeed')!=null) $isdatafeed  = 1;
            if ($this->getRequest()->getParam('isdropship')!=null) $isdropship  = 1;
            if ($this->getRequest()->getParam('iselectronic')!=null) $iselectronic  = 1;
            $comments  = $this->getRequest()->getParam('comments');
            $street  = $this->getRequest()->getParam('street');
            $city  = $this->getRequest()->getParam('city');
            $state  = $this->getRequest()->getParam('state');
            $postcode = $this->getRequest()->getParam('postcode');
            $country = $this->getRequest()->getParam('country');
            try {
                Mage::getModel('homepage/supplier')
                    ->setCustomerid($customerid)
                    ->setCompany($company)
                    ->setContactname($contactname)
                    ->setEmail($email)
                    ->setPhone($phone)
                    ->setFax($fax)
                    ->setWebsite($website)
                    ->setProducts($products)
                    ->setIscredit($iscredit)
                    ->setIselectronicinf($iselectronicinf)
                    ->setIsdatafeed($isdatafeed)
                    ->setIsdropship($isdropship)
                    ->setIselectronic($iselectronic)
                    ->setComments($comments)
                    ->setStreet($street)
                    ->setCity($city)
                    ->setState($state)
                    ->setPostcode($postcode)
                    ->setCountry($country)
                    ->save();
            } catch (Mage_Core_Exception $e) {
                $this->_fault('setData Error Message: ', $e->getMessage());
                return false;
            }
            $message = $this->__('Submit Supplier Application Success!');
            $session->addSuccess($message);
            session_write_close();
            $this->_redirect('customer/account');
        }
    }
    
}