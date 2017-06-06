<?php

class ZetaPrints_Ordercomments_Model_Mysql4_Comment_Collection
        extends Mage_Core_Model_Mysql4_Collection_Abstract
{
  public function _construct()
  {
    $this->_init('ordercomments/comment');
  }
}
