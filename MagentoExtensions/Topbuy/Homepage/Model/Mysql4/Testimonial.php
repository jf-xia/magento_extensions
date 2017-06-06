<?php
    class Topbuy_Homepage_Model_Mysql4_Testimonial extends Mage_Core_Model_Mysql4_Abstract
    {
        protected function _construct()
        {
            $this->_init("homepage/testimonial", "idtestimonial");
            $this->_isPkAutoIncrement = false;
        }
    }
	 