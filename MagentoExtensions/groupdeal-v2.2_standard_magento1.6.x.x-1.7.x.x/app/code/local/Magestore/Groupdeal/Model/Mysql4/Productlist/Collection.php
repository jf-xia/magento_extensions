<?php

class Magestore_Groupdeal_Model_Mysql4_Productlist_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('groupdeal/productlist');
    }
}