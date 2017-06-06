<?php

class ZetaPrints_Ordercomments_Model_Mysql4_Comment
  extends Mage_Core_Model_Mysql4_Abstract
{

  /**
   * Resource initialization
   */
  protected function _construct()
  {
    $this->_init('ordercomments/customer_comments', 'entity_id');
  }

}
