<?php
    class Topbuy_Customercare_Model_Mysql4_Pccomments extends Mage_Core_Model_Mysql4_Abstract
    {
        protected function _construct()
        {
            $this->_init("customercare/pccomments", "idpccomments");
//            $this->_isPkAutoIncrement = false;
        }
    }
	 