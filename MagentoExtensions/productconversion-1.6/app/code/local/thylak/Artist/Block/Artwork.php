<?php
/**
 * @category    Thylak
 * @package     Thylak_Artist
 * 
*/

/**
 * Artist Module - Artwork Block
 * 
 * @author      Buyan <buyan@talktoaprogrammer.com, bnpart47@yahoo.com>
 */
class Thylak_Artist_Block_Artwork extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
/**
 * Artist Module - Artwork Block
 * 
 * @return Artwork Form Post Action Url
 */    
	public function getSaveUrl()
	{
		return Mage::getUrl('artist/index/artworksave');
	}


}