<?php
/**
 * @category    Thylak
 * @package     Thylak_Artist
 * 
*/

/**
 * Artist Module - Artist Block
 * 
 * @author      Buyan <buyan@talktoaprogrammer.com, bnpart47@yahoo.com>
 */
class Thylak_Artist_Block_Artist extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
/**
 * Artist Module - Artist Block
 * 
 * @return Artist Form Post Action Url
 */    
	public function getSaveUrl()
	{
		return Mage::getUrl('artist/index/artistsave/');
	}
}