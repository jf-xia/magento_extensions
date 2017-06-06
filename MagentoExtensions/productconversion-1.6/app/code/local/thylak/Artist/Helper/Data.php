<?php
/**
 * @category    Thylak
 * @package     Thylak_Artist
 * 
*/

/**
 * Helper Class for the Artist Module
 * 
 * @author      Buyan <buyan@talktoaprogrammer.com, bnpart47@yahoo.com>
 */
class Thylak_Artist_Helper_Data extends Mage_Core_Helper_Abstract
{

/**
 * Get the Artist Url
 *
 * @return  Artist login URL
*/
    public function getArtisturl()
    {
       return Mage::getUrl('artist/index/');
    }

}