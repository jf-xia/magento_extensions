<?php

class EM_Megamenupro_Model_Mysql4_Megamenupro_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('megamenupro/megamenupro');
    }
}