<?php

class Kanavan_Searchautocomplete_Model_Searchautocomplete extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('aearchautocomplete/aearchautocomplete');
    }
}
