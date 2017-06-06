<?php

class Monk_Blog_Helper_Data extends Mage_Core_Helper_Abstract
{
	const XML_PATH_ENABLED     		= 'blog/blog/enabled';
	const XML_PATH_TITLE       		= 'blog/blog/title';
	const XML_PATH_MENU_LEFT   		= 'blog/blog/menuLeft';
	const XML_PATH_MENU_RIGHT  		= 'blog/blog/menuRoght';
	const XML_PATH_FOOTER_ENABLED   = 'blog/blog/footerEnabled';
	const XML_PATH_LAYOUT      		= 'blog/blog/layout';

    public function isEnabled()
    {
        return Mage::getStoreConfig( self::XML_PATH_ENABLED );
    }
	
	public function isTitle()
    {
        return Mage::getStoreConfig( self::XML_PATH_TITLE );
    }
	
	public function isMenuLeft()
    {
        return Mage::getStoreConfig( self::XML_PATH_MENU_LEFT );
    }
	
	public function isMenuRight()
    {
        return Mage::getStoreConfig( self::XML_PATH_MENU_RIGHT );
    }
	
	public function isFooterEnabled()
    {
        return Mage::getStoreConfig( self::XML_PATH_FOOTER_ENABLED );
    }
	
	public function isLayout()
    {
        return Mage::getStoreConfig( self::XML_PATH_LAYOUT );
    }
	
	public function getUserName()
    {
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        return trim("{$customer->getFirstname()} {$customer->getLastname()}");
    }

    public function getUserEmail()
    {
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        return $customer->getEmail();
    }
}