<?php
class Topbuy_Password_Model_Mysql4_Recordpassword extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("password/recordpassword", "rowid");
    }
}