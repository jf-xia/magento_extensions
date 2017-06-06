<?php

class Belvg_FacebookFree_Model_Resource_FacebookFree extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('facebookfree/facebookfree', 'id');
    }

}