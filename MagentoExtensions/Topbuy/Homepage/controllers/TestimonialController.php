<?php
class Topbuy_Homepage_TestimonialController extends Mage_Core_Controller_Front_Action{
    public function IndexAction() {
      
        $this->loadLayout();   
        $this->getLayout()->getBlock("head")->setTitle($this->__("Testimonial"));
//        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
//        $breadcrumbs->addCrumb("Testimonial", array(
//                "label" => $this->__("Testimonial"),
//                "title" => $this->__("Testimonial")
//                    ));
        
        $this->renderLayout(); 
    }
    public function ViewAction() {
      
        $this->loadLayout();   
        $this->getLayout()->getBlock("head")->setTitle($this->__("Customer Testimonial"));
//        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
//        $breadcrumbs->addCrumb("Testimonial", array(
//                "label" => $this->__("Testimonial"),
//                "title" => $this->__("Testimonial")
//                    ));
        
        $this->renderLayout(); 
    }
    
    public function addAction() {
        
        $session = Mage::getSingleton("customer/session");
        if($session->isLoggedIn()){
            $title  = $this->getRequest()->getParam('title');
            $content = $this->getRequest()->getParam('content');
            $state = $this->getRequest()->getParam('state');
            $customerName = $session->getCustomer()->getName();
            try {
                $testimonialS = Mage::getModel('homepage/testimonialc');
                $testimonialS->setSubject($title)
                    ->setContentbody($content)
                    ->setSenddate(date("Y-m-d H:i:s"))
                    ->setFromname($customerName)
                    ->setStaffid("0")
                    ->setFromstate($state)
                    ->setIdstore("99")
                    ->save();
            } catch (Mage_Core_Exception $e) {
                $this->_fault('setData Error Message: ', $e->getMessage());
                return false;
            }
            $message = $this->__('Add Testimonial Success!');
//            $session->addSuccess($message);
//            session_write_close();
        } else {
            $message = $this->__('You must Login first!');
//            $session->addError($message);
//            session_write_close();
        }
        $this->_redirect('homepage/testimonial');	
    }
}