<?php

class Magestore_Bannerslider_Model_Bannerslider extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('bannerslider/bannerslider');
    }
}