<?php
/**
 * @category    Thylak
 * @package     Thylak_Artist
*/

/**
 * Artist model
 *
 * @author      Buyan <buyan@talktoaprogrammer.com, bnpart47@yahoo.com>
 */
class Thylak_Artist_Model_Artist extends Mage_Core_Model_Abstract
{
/**
 * Constructor to initialize the Model
*/    
    public function _construct()
    {
        parent::_construct();
        $this->_init('artist/artist');
    }
}