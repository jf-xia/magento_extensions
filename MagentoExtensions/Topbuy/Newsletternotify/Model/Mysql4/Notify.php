<?php
    class Topbuy_Newsletternotify_Model_Mysql4_Notify extends Mage_Core_Model_Mysql4_Abstract
    {
        protected function _construct()
        {
            $this->_init("newsletternotify/notify", "rowid");
        }
    }
	 