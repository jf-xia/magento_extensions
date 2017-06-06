<?php

/**
 * Deliverynote Model Note
 *
 * @category	Dh
 * @package		Dh_Deliverynote
 * @author		Drew Hunter <drewdhunter@gmail.com>
 * @version		0.1.0
 */
class Dh_Deliverynote_Model_Note extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('deliverynote/note');
    }
}