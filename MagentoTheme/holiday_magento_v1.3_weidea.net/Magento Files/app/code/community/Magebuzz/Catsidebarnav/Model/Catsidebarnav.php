<?php

class Magebuzz_Catsidebarnav_Model_Catsidebarnav extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('catsidebarnav/catsidebarnav');
    }
}