<?php
/**
 * @category    Thylak
 * @package     Thylak_Artist
 * 
*/

/**
 * Artist Module - Link Block
 * 
 * @author      Buyan <buyan@talktoaprogrammer.com, bnpart47@yahoo.com>
 */
class Thylak_Artist_Block_Link extends Mage_Core_Block_Template
{
   
/**
 * Artist Module - Link Block
 * 
 * @return Register Form Post Action Url
 */
	public function getArtistinfoUrl()
	{
		return Mage::getUrl('*/*/artist/id/'.$_SESSION['id']);
	}
    public function getArtworkinfoUrl()
    {
        return Mage::getUrl('*/*/artwork/id/'.$_SESSION['id']);
    }    

}