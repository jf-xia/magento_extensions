<?php
/**
 * @category    Thylak
 * @package     Thylak_Artist
 * 
*/

/**
 * Artist Module - Register Block
 * 
 * @author      Buyan <buyan@talktoaprogrammer.com, bnpart47@yahoo.com>
 */
class Thylak_Artist_Block_Register extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
/**
 * Artist Module - Register Block
 * 
 * @return Register Form Post Action Url
 */
	public function getPostActionUrl()
	{
		return Mage::getUrl('artist/index/postCreate');
	}

}