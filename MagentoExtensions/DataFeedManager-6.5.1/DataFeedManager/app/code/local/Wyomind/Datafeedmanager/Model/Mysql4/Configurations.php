<?php

class Wyomind_Datafeedmanager_Model_Mysql4_Configurations extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		// Note that the datafeedmanager_id refers to the key field in your database table.
		$this->_init('datafeedmanager/configurations', 'feed_id');
	}
}