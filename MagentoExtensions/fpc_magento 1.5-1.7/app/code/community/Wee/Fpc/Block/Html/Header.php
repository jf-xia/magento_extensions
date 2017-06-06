<?php

class Wee_Fpc_Block_Html_Header extends Mage_Page_Block_Html_Header 
{
    public function getWelcome()
    {
        $welcomeMessage = parent::getWelcome();
        return Mage::helper('wee_fpc')->getStartPattern('welcome_message').$welcomeMessage.Mage::helper('wee_fpc')->getEndPattern('welcome_message');
    }
}