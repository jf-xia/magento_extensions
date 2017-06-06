<?php
class Topbuy_Newsletternotify_IndexController extends Mage_Core_Controller_Front_Action{
    
    
    //http://t1.topbuy.com/index.php/newsletternotify/index/notify?tt=166
    public function NotifyAction() {
        $session = Mage::getSingleton("customer/session");
        if(!$session->isLoggedIn()){
            $session->setBeforeAuthUrl(Mage::helper("core/url")->getCurrentUrl());
            $this->_redirect('customer/account');
        }
        if($session->isLoggedIn()){
            foreach($this->getRequest()->getParams() as $idproduct=>$url) {
                $urldecode=urldecode($url);
                //Mage::log($urldecode);
                $session->setBeforeAuthUrl($urldecode);
                $beforeAuthUrl=$session->getBeforeAuthUrl();	
                $customerId = $session->getCustomer()->getId();
                $product = Mage::getModel('catalog/product')
                    ->setStoreId(Mage::app()->getStore()->getId())
                    ->load($idproduct);

                if ($product->getId()/* && !$product->isSuper()*/) {
                    $notify = Mage::getModel('newsletternotify/notify')->getCollection()->addFieldToFilter('idcustomer',$customerId);
                    foreach ($notify->getItems() as $_item){
                        if($_item->getIdproduct()==$idproduct) {
                            Mage::getSingleton('catalog/session')->addSuccess(
                                $this->__('The product %s already has been added to Notify list.', Mage::helper('core')->escapeHtml($product->getName()))
                            );
                            Mage::app()->getFrontController()->getResponse()->setRedirect($beforeAuthUrl)->sendResponse();
                            return;
                        }
                    }
                    try {
                        Mage::getModel('newsletternotify/notify')
                                ->setIdproduct($idproduct)
                                ->setIdcustomer($customerId)
                                ->setSyncflag("1")
                                ->setEntrydate(strtotime(Mage::getModel('core/date')->date()))
                                ->save();
                    } catch (Mage_Core_Exception $e) {
                        $this->_fault('setData Error Message: ', $e->getMessage());
                        return false;
                    }
                    Mage::getSingleton('catalog/session')->addSuccess(
                        $this->__('The product %s has been added to Notify list. Thank you!', Mage::helper('core')->escapeHtml($product->getName()))
                    );
                }
                Mage::app()->getFrontController()->getResponse()->setRedirect($beforeAuthUrl)->sendResponse();
            }
        }
    }
}