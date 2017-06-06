<?php

class Spletnisistemi_SMSModule_Helper_Data extends Mage_Core_Helper_Abstract  {
    
    const XML_SMS_PROVIDER = 'smsmodule/settings/smsprovider';
    const XML_FROM_NUMBER = 'smsmodule/settings/fromnumber';
    const XML_PROVIDER_USERNAME = 'smsmodule/settings/smsproviderusername';
    const XML_PROVIDER_PASS = 'smsmodule/settings/smsproviderpass';
    const XML_USE_SENDER_ID = 'smsmodule/settings/use_senderid';
    const XML_SMS_ERRORS_MAIL = 'smsmodule/settings/smserrormail';
    
    const XML_ON_PLACE_ORDER = 'smsmodule/settings/send_sms_on_place_order';
    const XML_ON_INVOICE_SENT = 'smsmodule/settings/send_sms_on_invoice_sent';
    const XML_ON_ORDER_CANCLED = 'smsmodule/settings/send_sms_on_order_cancled';
    const XML_ON_PLACE_ORDER_TEXT = 'smsmodule/settings/send_sms_on_place_order_text';
    const XML_ON_INVOICE_SENT_TEXT = 'smsmodule/settings/send_sms_on_invoice_sent_text';
    const XML_ON_ORDER_CANCLED_TEXT = 'smsmodule/settings/send_sms_on_order_cancled_text';
    
    public function getDefaultSMSProvider() {
        return Mage::getStoreConfig(self::XML_SMS_PROVIDER);
    }
    
    public function getFromNumber() {
        return Mage::getStoreConfig(self::XML_FROM_NUMBER);
    } 
    
    public function getUsername() {
        return Mage::getStoreConfig(self::XML_PROVIDER_USERNAME);
    }
    
    public function getPassword() {
        return Mage::getStoreConfig(self::XML_PROVIDER_PASS);
    }
    
    public function getUseSenderId() {
        return Mage::getStoreConfig(self::XML_USE_SENDER_ID);
    }
    
    public function getSMSErrorsMail() {
        return Mage::getStoreConfig(self::XML_SMS_ERRORS_MAIL);
    }
    
    public function canSendSmsOnThisEvent($event) {
        switch($event) {
            case 'onPlaceOrder':
                return Mage::getStoreConfig(self::XML_ON_PLACE_ORDER);
                break;
            
            case 'onInvoiceSent':
                return Mage::getStoreConfig(self::XML_ON_INVOICE_SENT);
                break;
            
            case 'onOrderCancled':
                return Mage::getStoreConfig(self::XML_ON_ORDER_CANCLED);
                break;
            
            default:
                return false;
        }
    }
    
    public function getMsgContent($event) {
        switch($event) {
            case 'onPlaceOrder':
                return Mage::getStoreConfig(self::XML_ON_PLACE_ORDER_TEXT);
                break;
            
            case 'onInvoiceSent':
                return Mage::getStoreConfig(self::XML_ON_INVOICE_SENT_TEXT);
                break;
            
            case 'onOrderCancled':
                return Mage::getStoreConfig(self::XML_ON_ORDER_CANCLED_TEXT);
                break;
            
            default:
                return false;
        }
    }
}