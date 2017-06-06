<?php

/**
 * Deliverynote Block Mysql4
 *
 * @category	Dh
 * @package		Dh_Deliverynote
 * @author		Drew Hunter <drewdhunter@gmail.com>
 * @version		0.1.0
 */
class Dh_Deliverynote_Model_Mysql4_Note extends Mage_Core_Model_Mysql4_Abstract
{    
    protected function _construct()
    {
        $this->_init('deliverynote/note', 'delivery_note_id');
    }
}