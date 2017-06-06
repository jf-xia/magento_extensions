<?php

class Magestore_Groupdeal_Model_Mysql4_Orderlist extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the groupdeal_id refers to the key field in your database table.
        $this->_init('groupdeal/orderlist', 'orderlist_id');
    }
}