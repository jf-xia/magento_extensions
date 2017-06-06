<?php

class Mdlb_Mlayer_Model_Mlayer extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('mlayer/mlayer');
    }
}