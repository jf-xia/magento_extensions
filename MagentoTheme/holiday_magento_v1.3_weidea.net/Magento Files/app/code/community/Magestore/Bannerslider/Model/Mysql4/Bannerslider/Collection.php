<?php

class Magestore_Bannerslider_Model_Mysql4_Bannerslider_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('bannerslider/bannerslider');
    }
}