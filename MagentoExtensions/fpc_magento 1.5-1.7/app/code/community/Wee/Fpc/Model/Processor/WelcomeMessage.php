<?php

class Wee_Fpc_Model_Processor_WelcomeMessage extends Wee_Fpc_Model_Processor_Abstract
{
    const WELCOME_MESSAGE_KEY = 'welcome_message';

    public function prepareContent($content, array $requestParameter)
    {
        return $this->_replaceContent(self::WELCOME_MESSAGE_KEY, self::_getWelcomeMessageText(), $content);
    }

    public function _getWelcomeMessageText()
    {
        $welcomeMessage = '';
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $welcomeMessage = Mage::helper('core')->__('Welcome, %s!', Mage::getSingleton('customer/session')->getCustomer()->getName());
        } else {
            $welcomeMessage = Mage::getStoreConfig('design/header/welcome');
        }
        return $welcomeMessage;
    }
}