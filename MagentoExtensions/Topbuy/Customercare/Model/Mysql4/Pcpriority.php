<?php
    class Topbuy_Customercare_Model_Mysql4_Pcpriority extends Mage_Core_Model_Mysql4_Abstract
    {
        protected function _construct()
        {
            $this->_init("customercare/pcpriority", "pcpri_idpri");
            $this->_isPkAutoIncrement = false;
        }
    }
	 