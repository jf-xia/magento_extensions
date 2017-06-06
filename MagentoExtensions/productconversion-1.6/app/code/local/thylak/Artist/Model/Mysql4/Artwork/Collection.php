<?php
/**
 * @category    Thylak
 * @package     Thylak_Artist
 * 
*/

/**
 * Artwork model - Collection - to get the collections
 * 
 * Here we define the index field of the table
 * 
 * @author      Buyan <buyan@talktoaprogrammer.com, bnpart47@yahoo.com>
 */
class Thylak_Artist_Model_Mysql4_Artwork_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('artist/artwork');
    }
}