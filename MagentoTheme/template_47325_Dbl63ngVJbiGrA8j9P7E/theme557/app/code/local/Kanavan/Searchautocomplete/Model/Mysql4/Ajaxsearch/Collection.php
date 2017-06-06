<?php

class Kanavan_Searchautocomplete_Model_Mysql4_Searchautocomplete_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('searchautocomplete/searchautocomplete');
    }
}
