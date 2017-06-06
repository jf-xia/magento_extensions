<?php
class Magpleasure_Guestbook_Model_Mysql4_Message extends Magpleasure_Common_Model_Resource_Abstract
{
    protected function _construct()
    {
        $this->_init("guestbook/message", "message_id");
        $this->setUseUpdateDatetimeHelper(true);
    }
}
	 