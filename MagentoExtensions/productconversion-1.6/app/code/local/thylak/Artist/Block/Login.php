<?php
/**
 * @category    Thylak
 * @package     Thylak_Artist
 * 
*/

/**
 * Artist Module - Login Block
 * 
 * @author      Buyan <buyan@talktoaprogrammer.com, bnpart47@yahoo.com>
 */
class Thylak_Artist_Block_Login extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
/**
 * Artist Module - Login Block
 * 
 * @return Artist Details
 */    
     public function getArtist()     
     { 
        if (!$this->hasData('artist')) {
            $this->setData('artist', Mage::registry('artist'));
        }
        return $this->getData('artist');
        
    }
/**
 * Artist Module - Login Block
 * 
 * @return Login Form Post Action Url
 */	
	public function getPostActionUrl()
	{
		return Mage::getUrl('artist/index/loginPost');
	}
/**
 * Artist Module - Login Block
 * 
 * @return Create Account Url - Register Page Url
 */    
	public function getRegisterPostActionUrl()
	{
		return Mage::getUrl('artist/index/create');
	}
}