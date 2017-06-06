<?php

class ZetaPrints_WebToPrint_Model_System_Config_Backend_ZetaPrintsUrl
  extends Mage_Core_Model_Config_Data {

  protected function _beforeSave () {
    $value = $this->getValue();

    //Search for slash at the end of domain name...
    if (($position = strpos($value, '/', 9)) === false)
      //... if no such add one to the end
      $this->setValue($value . '/');
    else
      //... if such exists then trim everything after domain name
      $this->setValue(substr($value, 0, $position + 1));

    return $this;
  }
}

?>
