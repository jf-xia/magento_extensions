<?php
class Topbuy_Customercare_IndexController extends Mage_Core_Controller_Front_Action{
    
    public function tAction(){
        $qemail  = $this->getRequest()->getParam('qemail');
//        if ($qemail){
//            $customer_id=Mage::helper('ajax')->createAccount($qemail,'','','','','');
//        }
        $session = Mage::getSingleton("customer/session");
//        print_r($session->getCustomer()->getId());
       
        if($session->isLoggedIn()){
            $customerId = $session->getCustomer()->getId();
        } else {
            $customer = Mage::getModel('customer/customer');
            $customer->setWebsiteId(1);
            $customerId = $customer->loadByEmail($qemail)->getId();
        }
    }
    public function CreatemsgAction() {
        $qemail  = $this->getRequest()->getParam('qemail');
//        if ($qemail){
//            $customer_id=Mage::helper('ajax')->createAccount($qemail,'','','','','');
//        }
        $session = Mage::getSingleton("customer/session");
//        if(!$session->isLoggedIn()){
//            $session->setBeforeAuthUrl(Mage::helper("core/url")->getCurrentUrl());
//            $this->_redirect('customer/account/login');	
//            return;
//        }
//        if($session->isLoggedIn()){
        $customerId=null;
        $fType=null;
        $priority=null;
        $chosenIdorder=null;
        $description=null;
        $details=null;
        $idstore=99;
        if ($this->getRequest()->getParam('Productid')){
            $productid  = $this->getRequest()->getParam('Productid');
            $title  = $this->getRequest()->getParam('title');
            $fType  = 6;
            $priority=47;
            $description  = $title." (Product SKU: ".Mage::getModel('catalog/product')->load($productid)->getSku().")";
        }
        if($this->getRequest()->getParam('msgtype')=="testimonials"){
            $fType  = 19;
            $priority=41;
            $description  = $this->getRequest()->getParam('Description');
        }
        if($this->getRequest()->getParam('ChosenIdorder')){
            $chosenIdorder  = $this->getRequest()->getParam('ChosenIdorder');
            $fTypep  = $this->getRequest()->getParam('FType');
            $fType = substr($fTypep,0,strrpos($fTypep,'|'));
            $priority=substr($fTypep,strrpos($fTypep,'|')+1);
            $description  = $this->getRequest()->getParam('Description');
        }
        if($this->getRequest()->getParam('FType')){
            $fTypep  = $this->getRequest()->getParam('FType');
            $fType = substr($fTypep,0,strrpos($fTypep,'|'));
            $priority=substr($fTypep,strrpos($fTypep,'|')+1);
            $description  = $this->getRequest()->getParam('Description');
        }
        $details  = $this->getRequest()->getParam('Details');
        if ($fType==null) {
            $message = $this->__('You must select a Question Type!');
            $session->addError($message);
            session_write_close();
            $this->_redirect('customercare');
            return;
        }else if ($description==null) {
            $message = $this->__('Subject cannnot be null!');
            $session->addError($message);
            session_write_close();
            $this->_redirect('customercare');
            return;
        }else if ($details==null) {
            $message = $this->__('Message Details cannnot be null!');
            $session->addError($message);
            session_write_close();
            $this->_redirect('customercare');
            return;
        }
        if(!$customer_id){
            $customerId = $session->getCustomer()->getId();
        } else {
//            $customer = Mage::getModel('customer/customer');
//            $customer->setWebsiteId(1);
//            $customerId = $customer->loadByEmail($qemail)->getId();
            $customerId = $customer_id;
        }
        
        try {
            $pccomments = Mage::getModel('customercare/pccomments');
            $pccomments
                ->setPccommIdparent(0)
                ->setPccommIdmaguser($customerId)
                ->setPccommCreateddate(Mage::getModel('core/date')->date("Y-m-d H:i:s"))
                ->setPccommEditeddate(Mage::getModel('core/date')->date("Y-m-d H:i:s"))
                ->setPccommFtype($fType)
                ->setPccommFstatus(1)
                ->setPccommPriority($priority)
                ->setPccommIdmagorder($chosenIdorder)
                ->setPccommDescription($description)
                ->setPccommDetails($details)
                ->setIdstore($idstore)
                ->save();
        } catch (Mage_Core_Exception $e) {
            $this->_fault('setData Error Message: ', $e->getMessage());
            return false;
        }
        $message = $this->__('Add Message Success!');
        $session->addSuccess($message);
        session_write_close();
//        }
        $this->_redirect('customercare');
        return;	
    }
//    public function rankAction() {
//        $pccommentsUpdate=Mage::getModel('customercare/pccommentsrank')->getCollection()
//                ->addFilter("id_mag_pccomments",336596)
//                ->addFilter("rank_type",1);
//        echo $commentid = $pccommentsUpdate->getLastItem()->getRankPoint();
//        $eee=Mage::getModel('customercare/pccommentsrank')->load($commentid);
//        foreach($eee as $item){
//            echo $item->getRankMagId();
//        }
//        $eee->setRankPoint(6)->save();
//    }
    public function AddrankAction() {
        $session = Mage::getSingleton("customer/session");
        if(!$session->isLoggedIn()){
            $session->setBeforeAuthUrl(Mage::helper("core/url")->getCurrentUrl());
            $this->_redirect('customer/account/login');	
            return;
        }
        if($session->isLoggedIn()){
            $pccommentsId  = $this->getRequest()->getParam('PccommentsId');
            $rankType  = $this->getRequest()->getParam('RankType');
            $rankPoint  = $this->getRequest()->getParam('RankPoint');
            if ($rankType ==""||$rankPoint ==""){
                $isError = "0" ;
                $message = "Please rank them again";
            }
            
            $pccommentsUpdate=Mage::getModel('customercare/pccommentsrank')->getCollection()
                    ->addFilter("id_mag_pccomments",$pccommentsId)
                    ->addFilter("rank_type",$rankType);
            if ($pccommentsUpdate->count()==0){
                $pccomments = Mage::getModel('customercare/pccommentsrank');
            } else {
                $commentid = $pccommentsUpdate->getLastItem()->getRankMagId();
                $pccomments = Mage::getModel('customercare/pccommentsrank')->load($commentid);
            }
            try {
                $pccomments
                    ->setIdMagPccomments($pccommentsId)
                    ->setUpdatedate(Mage::getModel('core/date')->date("Y-m-d H:i:s"))
                    ->setRankType($rankType)
                    ->setRankPoint($rankPoint)
                    ->save();
            } catch (Mage_Core_Exception $e) {
                $this->_fault('setData Error Message: ', $e->getMessage());
                return false;
            }
            $isError = "1" ;
            $message = "Thank for your rank!";
            $rank_arr = array(
                'isError' => $isError,
                'ErrorMsg' => $message
            );
        }	 
        echo json_encode($rank_arr);
        return;
    }
    public function AddAction() {
        $session = Mage::getSingleton("customer/session");
        if(!$session->isLoggedIn()){
            $session->setBeforeAuthUrl(Mage::helper("core/url")->getCurrentUrl());
            $this->_redirect('customer/account/login');	
            return;
        }
        if($session->isLoggedIn()){
            $pccommentsId  = $this->getRequest()->getParam('IDFeedback');
            $comments = $this->getRequest()->getParam('comments');
            $customerId = $session->getCustomer()->getId();
            try {
                $pccomments = Mage::getModel('customercare/pccomments');
                $pccomments
                    ->setPccommIdmagparent($pccommentsId)
                    ->setPccommIdmaguser($customerId)
                    ->setPccommCreateddate(Mage::getModel('core/date')->date("Y-m-d H:i:s"))
                    ->setPccommEditeddate(Mage::getModel('core/date')->date("Y-m-d H:i:s"))
                    ->setPccommFtype(0)
                    ->setPccommFstatus(0)
                    ->setPccommPriority(0)
                    ->setPccommDetails($comments)
                    ->setIdstore(99)
                    ->save();
            } catch (Mage_Core_Exception $e) {
                $this->_fault('setData Error Message: ', $e->getMessage());
                return false;
            }
            $message = $this->__('Add Reply Message Success!');
            $session->addSuccess($message);
            session_write_close();
//            $urlr="customercare/index/detail/idparent/".$pccommentsId;
//            $this->_redirect($urlr);
        }
        $this->_redirect('customercare');
        return;		
    }
    
    public function getTypeOrder(){
        $pcftypeorder=array();
        $pcftypes=Mage::getModel('customercare/pcftypes')->getCollection();
        $pcftypes->getSelect()->order('pcftype_name ASC');
        foreach ($pcftypes as $_item){
            if ($_item->getDisplaytype()==1||$_item->getDisplaytype()==0) $pcftypeorder[$_item->getPcftypeIdtype()]=$_item->getPcftypeName()."|".$_item->getManager();
        }
        return $pcftypeorder;
    }
    
    public function getTypeNOrder(){
        $pcftypeno=array();
        $pcftypes=Mage::getModel('customercare/pcftypes')->getCollection();
        $pcftypes->getSelect()->order('pcftype_name ASC');
        foreach ($pcftypes as $_item){
            if ($_item->getDisplaytype()==2||$_item->getDisplaytype()==0) $pcftypeno[$_item->getPcftypeIdtype()]=$_item->getPcftypeName()."|".$_item->getManager();
        }
        return $pcftypeno;
    }
    public function IndexAction() {
        $session = Mage::getSingleton("customer/session");
        if(!$session->isLoggedIn()){
            $session->setBeforeAuthUrl(Mage::helper("core/url")->getCurrentUrl());
            $this->_redirect('customer/account/login');	
            return;
        }else{
            $this->loadLayout();   
            $this->getLayout()->getBlock("head")->setTitle($this->__("Customer Message"));

            $this->renderLayout();             
        }
    }
    public function DetailAction() {
        $session = Mage::getSingleton("customer/session");
        if(!$session->isLoggedIn()){
            $session->setBeforeAuthUrl(Mage::helper("core/url")->getCurrentUrl());
            $this->_redirect('customer/account/login');	
            return;
        }else{
            $this->loadLayout();   
            $this->getLayout()->getBlock("head")->setTitle($this->__("Customer Message Detail"));

            $this->renderLayout(); 
        }
    }
    public function CreateAction() {
        $session = Mage::getSingleton("customer/session");
        if(!$session->isLoggedIn()){
            $session->setBeforeAuthUrl(Mage::helper("core/url")->getCurrentUrl());
            $this->_redirect('customer/account/login');	
            return;
        }else{
            $this->loadLayout();   
            $this->getLayout()->getBlock("head")->setTitle($this->__("Customer Message Create"));

            $this->renderLayout(); 
        }
    }
    public function CreatenAction() {
        $session = Mage::getSingleton("customer/session");
        if(!$session->isLoggedIn()){
            $session->setBeforeAuthUrl(Mage::helper("core/url")->getCurrentUrl());
            $this->_redirect('customer/account/login');	
            return;
        }else{
            $this->loadLayout();   
            $this->getLayout()->getBlock("head")->setTitle($this->__("Customer Message Create"));

            $this->renderLayout(); 
        }
    }
    public function CreateoAction() {
        $session = Mage::getSingleton("customer/session");
        if(!$session->isLoggedIn()){
            $session->setBeforeAuthUrl(Mage::helper("core/url")->getCurrentUrl());
            $this->_redirect('customer/account/login');	
            return;
        }else{
            $this->loadLayout();   
            $this->getLayout()->getBlock("head")->setTitle($this->__("Customer Message Create"));

            $this->renderLayout(); 
        }
    }
}

//            $pccomments = Mage::getModel('customercare/pccomments');
//            $pccomments
//                ->setPccommIdfeedback()->setPccommIduser()->setPccommIdorder()->setIdproduct()
//                ->setPccommIdmagorder()
//                ->setPccommDescription()
//                ->setIdmagproduct()
//                ->setPccommInternalnotes()
//                ->setNotesreaded()
//                ->setNotedate()
//                ->setPccommIdproduct()
//                ->setPccommProductdes()
//                ->setPccommKeeplive()
//                ->setSourcetype()
//                ->setLastStaffId()
//                ->setPccommIdparent($pccommentsId)
//                ->setPccommIdmaguser($customerId)
//                ->setPccommCreateddate()
//                ->setPccommEditeddate()
//                ->setPccommFtype(0)
//                ->setPccommFstatus(0)
//                ->setPccommPriority(0)
//                ->setPccommDetails($comments)
//                ->setIdstore()
//                ->save();

//                ->setPccommIdfeedback(0)
//                ->setPccommIdorder(0)
//                ->setPccommIdmagparent(0)
//                ->setPccommIduser(0)
//                ->setIdproduct(0)
//                ->setIdmagproduct(0)
//                ->setPccommInternalnotes('')
//                ->setNotesreaded(0)
//                ->setNotedate('')
//                ->setPccommIdproduct('')
//                ->setPccommProductdes('')
//                ->setPccommKeeplive(0)
//                ->setSourcetype(0)
//                ->setLastStaffId(0)