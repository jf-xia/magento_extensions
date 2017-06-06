<?php

class Magebuzz_Catsidebarnav_Model_Mysql4_Catsidebarnav extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the catsidebarnav_id refers to the key field in your database table.
        $this->_init('catsidebarnav/catsidebarnav', 'catsidebarnav_id');
    }
}