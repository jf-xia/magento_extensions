<?php
    class Topbuy_Ajax_Model_Mysql4_Prddescription extends Mage_Core_Model_Mysql4_Abstract
    {
        protected function _construct()
        {
            $this->_init("ajax/prddescription","rowid");
//            $this->_isPkAutoIncrement = false;
        }
    }
