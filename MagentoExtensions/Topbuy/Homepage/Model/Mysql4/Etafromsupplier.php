<?php
    class Topbuy_Homepage_Model_Mysql4_Etafromsupplier extends Mage_Core_Model_Mysql4_Abstract
    {
        protected function _construct()
        {
            $this->_init("homepage/etafromsupplier",'supplier');
            $this->_isPkAutoIncrement = false; 
        }
    }
