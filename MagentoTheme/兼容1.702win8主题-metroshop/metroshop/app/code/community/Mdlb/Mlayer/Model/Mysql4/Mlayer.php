<?php

class Mdlb_Mlayer_Model_Mysql4_Mlayer extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the Mlayer_id refers to the key field in your database table.
        $this->_init('mlayer/mlayer', 'mlayer_id');
    }
}