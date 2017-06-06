<?php

class Magebuzz_Catsidebarnav_Model_Mysql4_Catsidebarnav_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('catsidebarnav/catsidebarnav');
    }
}