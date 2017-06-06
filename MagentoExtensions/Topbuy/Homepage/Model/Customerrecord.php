<?php

class Topbuy_Homepage_Model_Customerrecord extends Mage_Core_Model_Abstract
{
    protected function _construct(){

       $this->_init("homepage/customerrecord");

    }
    
    public function isNewCustomer() {
        $session = Mage::getSingleton("customer/session");
        $cookieNew=Mage::getSingleton('core/cookie')->get("topbuy_cookie_newcustomer");
        if(!$session->isLoggedIn()&&$cookieNew!=2){ 
            if ($cookieNew==1) {
                Mage::getSingleton("core/cookie")->set("topbuy_cookie_newcustomer", 2, (3600*24*365));
//            } else if ($cookieNew==2) {
//                Mage::getSingleton("core/cookie")->set("topbuy_cookie_newcustomer", 3, (3600*24*365));
            } else {
                Mage::getSingleton("core/cookie")->set("topbuy_cookie_newcustomer", 1, (3600*24*365));
            }
            return true;
        } else {
            return false;
        }
    }
    
    public function cProVisitHRecord($productId) {
        $session = Mage::getSingleton("customer/session");
        $cookieIdcustomer=Mage::getSingleton('core/cookie')->get("topbuy_cookie_idcustomer");
        $cookieTime=Mage::getSingleton('core/cookie')->get("topbuy_cookie_customert");
        $cookieRecord=Mage::getSingleton('core/cookie')->get("topbuy_cookie_customerr");
        $customerId="false";
        if($session->isLoggedIn()){ 
            $customerId = $session->getCustomer()->getId();
        } else {
            if ($cookieIdcustomer) $customerId=$cookieIdcustomer;
        }
//$ttt=0000;
        if ($cookieTime<strtotime(Mage::getModel('core/date')->date())||$cookieIdcustomer!=$customerId||$cookieRecord!=$productId) {
//$ttt=111111;
            Mage::getSingleton("core/cookie")->set("topbuy_cookie_customert", strtotime(Mage::getModel('core/date')->date())+3600*2, (3600*24*365));
            Mage::getSingleton("core/cookie")->set("topbuy_cookie_customerr", $productId, (3600*24*365));
            $crecord = Mage::getModel('homepage/customerrecord');
            if($session->isLoggedIn()){ 
                if ($cookieIdcustomer>1000000000) {
                        $crecordCollection = $crecord->getCollection()->addFieldToFilter("idcustomer",$cookieIdcustomer);
                        foreach($crecordCollection as $item){
                            $item->setIdcustomer($customerId);
                        }
                        try {
//$ttt=2222;
                            $crecordCollection->save();
                        } catch (Mage_Core_Exception $e) {
                            $this->_fault('setData Error Message: ', $e->getMessage());
                            return false;
                        }
                        Mage::getSingleton("core/cookie")->set("topbuy_cookie_idcustomer", $customerId, (3600*24*365));
                } else {
                    Mage::getSingleton("core/cookie")->set("topbuy_cookie_idcustomer", $customerId, (3600*24*365));
                } 
            } else {
                if (!$cookieIdcustomer) {
                    $tempidcustomer=substr((time()+microtime())*1111,3,10);
                    Mage::getSingleton("core/cookie")->set("topbuy_cookie_idcustomer", $tempidcustomer, (3600*24*365));
                    $customerId = $tempidcustomer;
                } else {
                    $customerId = $cookieIdcustomer;
                } 
            }
            try {
//$ttt=2222;
                $crecord->setIdcustomer($customerId)
                        ->setIdproduct($productId)
                        ->setEntrydate(Mage::getModel('core/date')->date())
                        ->setSourcetype("product")
                        ->setIdstore(99)
                        ->save();
            } catch (Mage_Core_Exception $e) {
                $this->_fault('setData Error Message: ', $e->getMessage());
                return false;
            }
        }
//        return $ttt;
    }
    
    public function cCatVisitHRecord() {
        $session = Mage::getSingleton("customer/session");
        if (Mage::registry('current_category')) {
            $ccate = Mage::registry('current_category')->getId();
            $cookieIdcustomer=Mage::getSingleton('core/cookie')->get("topbuy_cookie_idcustomer");
            $cookieTime=Mage::getSingleton('core/cookie')->get("topbuy_cookie_customert");
            $cookieRecord=Mage::getSingleton('core/cookie')->get("topbuy_cookie_customerr");
            $customerId="false";
            if($session->isLoggedIn()){ 
                $customerId = $session->getCustomer()->getId();
            } else {
                if ($cookieIdcustomer) $customerId=$cookieIdcustomer;
            }
//$ttt=0000;
            if ($cookieTime<strtotime(Mage::getModel('core/date')->date())||$cookieIdcustomer!=$customerId||$cookieRecord!=$ccate) {
//$ttt=111111;
                Mage::getSingleton("core/cookie")->set("topbuy_cookie_customert", strtotime(Mage::getModel('core/date')->date())+3600*2, (3600*24*365));
                Mage::getSingleton("core/cookie")->set("topbuy_cookie_customerr", $ccate, (3600*24*365));
                $crecord = Mage::getModel('homepage/customerrecord');
                if($session->isLoggedIn()){ 
                    if ($cookieIdcustomer>1000000000) {
                            $crecordCollection = $crecord->getCollection()->addFieldToFilter("idcustomer",$cookieIdcustomer);
                            foreach($crecordCollection as $item){
                                $item->setIdcustomer($customerId);
                            }
                            try {
                                $crecordCollection->save();
//$ttt=2222;
                            } catch (Mage_Core_Exception $e) {
                                $this->_fault('setData Error Message: ', $e->getMessage());
                                return false;
                            }
                            Mage::getSingleton("core/cookie")->set("topbuy_cookie_idcustomer", $customerId, (3600*24*365));
                    } else {
                        Mage::getSingleton("core/cookie")->set("topbuy_cookie_idcustomer", $customerId, (3600*24*365));
                    } 
                } else {
                    if (!$cookieIdcustomer) {
                        $tempidcustomer=substr((time()+microtime())*1111,3,10);
                        Mage::getSingleton("core/cookie")->set("topbuy_cookie_idcustomer", $tempidcustomer, (3600*24*365));
                        $customerId = $tempidcustomer;
                    } else {
                        $customerId = $cookieIdcustomer;
                    } 
                }
                try {
//$ttt=2222;
                    $crecord->setIdcustomer($customerId)
                            ->setIdproduct($ccate)
                            ->setEntrydate(date(strtotime(Mage::getModel('core/date')->date())))
                            ->setSourcetype("Category")
                            ->setIdstore(99)
                            ->save();
                } catch (Mage_Core_Exception $e) {
                    $this->_fault('setData Error Message: ', $e->getMessage());
                    return false;
                }
            }
        }
//        return $ttt;
    }

}
	 