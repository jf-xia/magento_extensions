<?php

class EM_Megamenupro_Model_Mysql4_Megamenupro extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the megamenupro_id refers to the key field in your database table.
        $this->_init('megamenupro/megamenupro', 'megamenupro_id');
    }
}