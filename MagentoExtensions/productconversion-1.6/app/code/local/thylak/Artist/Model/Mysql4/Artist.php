<?php
/**
 * @category    Thylak
 * @package     Thylak_Artist
 * 
*/

/**
 * Artist model
 * 
 * Here we define the index field of the table
 * 
 * @author      Buyan <buyan@talktoaprogrammer.com, bnpart47@yahoo.com>
 */
class Thylak_Artist_Model_Mysql4_Artist extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the artist_id refers to the key field in your database table.
        $this->_init('artist/artist', 'artist_id');
    }
}