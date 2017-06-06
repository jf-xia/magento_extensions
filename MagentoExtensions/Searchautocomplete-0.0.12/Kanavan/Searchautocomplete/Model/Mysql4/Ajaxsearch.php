<?php

class Kanavan_Searchautocomplete_Model_Mysql4_Searchautocomplete extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the searchautocomplete_id refers to the key field in your database table.
        $this->_init('searchautocomplete/searchautocomplete', 'searchautocomplete_id');
    }
}
