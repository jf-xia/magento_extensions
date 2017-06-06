<?php

class Wee_Fpc_Model_Processor_LoginLogout extends Wee_Fpc_Model_Processor_Abstract
{
    const LOGIN_LOGOUT_KEY = 'log_in_out_link';
    
    public function prepareContent($content, array $requestParameter)
    {
        $isLoggedIn = Mage::getSingleton('customer/session')->isLoggedIn();
        $replaceText = '';
        if ($isLoggedIn) {
            $text = Mage::helper('core')->__('Log Out');
            $replaceText = sprintf('<a href="%s" title="%s">%s</a>', Mage::helper('customer')->getLogoutUrl(), $text, $text);
        } else {
            $text = Mage::helper('core')->__('Log In');
            $replaceText = sprintf('<a href="%s" title="%s">%s</a>', Mage::helper('customer')->getLoginUrl(), $text, $text);
        }
        return $this->_replaceContent(self::LOGIN_LOGOUT_KEY, $replaceText, $content);
    }
}