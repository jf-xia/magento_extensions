<?php

abstract class Spletnisistemi_SMSModule_Model_SMSProvider_Abstract {
    
    protected $_event = null;
    protected $_user = null;
    protected $_pass = null;
    protected $_from = null;
    protected $_useSenderId = null;
    protected $_countryCode = null;
    protected $_msg = null;
    protected $_availableCountriesCodes = array('SI' => '386');
    
    public function __construct($event, $country) {
        $this->_event = $event;
        $this->_from = Mage::helper('smsmodule')->getFromNumber();
        $this->_user = Mage::helper('smsmodule')->getUsername();
        $this->_pass = Mage::helper('smsmodule')->getPassword();
        $this->_useSenderId = Mage::helper('smsmodule')->getUseSenderId();
        $this->_msg = Mage::helper('smsmodule')->getMsgContent($this->_event);
        $this->setCountryCode($country);
    }
    
    private function setCountryCode($country) {
        if(isset($this->_availableCountriesCodes[$country])) {
            $this->_countryCode = $this->_availableCountriesCodes[$country];
            return $this;
        }
        
        throw new Spletnisistemi_SMSModule_Exception('Country not supported. You cannot send SMS to ' . $country . ' country.');
    }
    
    public function doISendSMSOnThisEvent() {
        return Mage::helper('smsmodule')->canSendSmsOnThisEvent($this->_event);
    }
    
    public function checkIfNumberIsValid($number) {
        if(!is_numeric($number) && strlen($number) != 9) {
            return false;
        }
        
        return true;
    }

    abstract public function sendSms($to);
    
    /**
     * Returns true on success, error description on faile
     * 
     * @param response
     * @return boolean|string 
     */
    abstract public function handleResponseFromSentSms($response);
}