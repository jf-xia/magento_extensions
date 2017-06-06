<?php
class CommerceStack_Recommender_AccountController extends Mage_Adminhtml_Controller_Action
{
	public function getapikeyAction()
    {      
        session_write_close(); // prevent other requests from blocking during update because of locked session file
        
        $dataHelper = Mage::helper('csapiclient/account');
        $json = $dataHelper->getApiKeyAsJson();

        $this->getResponse()->setBody($json);
    }
}