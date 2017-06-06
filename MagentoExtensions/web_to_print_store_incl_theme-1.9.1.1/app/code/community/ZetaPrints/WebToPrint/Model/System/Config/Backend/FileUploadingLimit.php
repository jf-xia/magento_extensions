<?php

class ZetaPrints_WebToPrint_Model_System_Config_Backend_FileUploadingLimit
  extends Mage_Core_Model_Config_Data {

  public function getValue () {
    return ini_get('upload_max_filesize') . 'B';
  }
}

?>
