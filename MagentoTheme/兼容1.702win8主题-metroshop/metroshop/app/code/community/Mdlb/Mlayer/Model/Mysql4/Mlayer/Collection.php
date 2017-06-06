<?php

class Mdlb_Mlayer_Model_Mysql4_Mlayer_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('mlayer/mlayer');
    }
}