<?php

class Spletnisistemi_SMSModule_Model_Observer {
    
    protected $_event = null;
    
    public function onPlaceOrder($observer) {
        $this->_event = 'onPlaceOrder';
        
        $this->getDataAndNotifyUserBySms($observer);
    }
    
    public function onOrderCancled($observer) {
        $this->_event = 'onOrderCancled';
        
        $this->getDataAndNotifyUserBySms($observer);
    }
    
    public function onInvoiceSent($observer) {
        $this->_event = 'onInvoiceSent';
        
        if(!Mage::helper('smsmodule')->canSendSmsOnThisEvent($this->_event)) {
            return false;
        }
        
        $addressData = $observer->getData('invoice')->getOrder()->getBillingAddress();
        $telephone = $addressData->getData('telephone');
        $countryId = $addressData->getData('country_id');

        $this->sendSMS($telephone, $countryId);
    }
    
    protected function getDataAndNotifyUserBySms($observer) {
        
        if(!Mage::helper('smsmodule')->canSendSmsOnThisEvent($this->_event)) {
            return false;
        }
                
        $addressData = $observer->getData('order')->getBillingAddress();
        $telephone = $addressData->getData('telephone');
        $countryId = $addressData->getData('country_id');
        
        $this->sendSMS($telephone, $countryId);
    }
    
    protected function sendSMS($telephone, $countryId) {
        $provider = Mage::helper('smsmodule')->getDefaultSMSProvider();
        $provider = 'Spletnisistemi_SMSModule_Model_SMSProvider_' . $provider;
        $smsProvider = new $provider($this->_event, $countryId);
        
        $response = $smsProvider->sendSMS($telephone);
        if(($error = $smsProvider->handleResponseFromSentSms($response)) !== true) {
            $this->sendEmailToAdminIfSMSError($error);
        }
    }
    
    protected function sendEmailToAdminIfSMSError($error) {
        $emailTemplate  = Mage::getModel('core/email_template')->loadDefault('sms_error_email_template');                                
        
        $emailTemplateVariables = array();
        $emailTemplateVariables['error'] = $error;
            
        $email = Mage::helper('smsmodule')->getSMSErrorsMail();
        $emailTemplate->setSenderName('Admin');
        $emailTemplate->setSenderEmail($email);
        $emailTemplate->setTemplateSubject('MSG Module error');
        
        $emailTemplate->send($email,'Admin', $emailTemplateVariables);
    }
}