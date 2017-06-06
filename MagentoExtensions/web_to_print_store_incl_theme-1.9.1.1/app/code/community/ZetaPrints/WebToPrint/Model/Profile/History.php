<?php

class ZetaPrints_WebToPrint_Model_Profile_History
  extends Mage_Dataflow_Model_Profile_History {

  protected function _beforeSave () {
    if (!$this->getUserId() && !Mage::getSingleton('admin/session')->hasUser()) {
      $users = Mage::getModel('admin/user')->getCollection();

      if ($users->count() > 0)
        $this->setUserId($users->getFirstItem()->getId());
    }

    return parent::_beforeSave();
  }
}

?>
