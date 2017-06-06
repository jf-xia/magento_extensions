<?php
class Zopim_Livechat_Model_Mysql4_Livechat extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('livechat/livechat', 'livechat_id');
    }
}
