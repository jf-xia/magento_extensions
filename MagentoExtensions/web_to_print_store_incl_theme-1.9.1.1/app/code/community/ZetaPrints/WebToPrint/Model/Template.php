<?php

class ZetaPrints_WebToPrint_Model_Template extends Mage_Core_Model_Abstract {
  protected function _construct() {
    $this->_init('webtoprint/template');
    $this->setIdFieldName('guid');
  }

  public function loadById ($template_id) {
    return $this->load($template_id, 'template_id');
  }

  public function load ($id, $field = 'guid') {
    return parent::load($id, $field);
  }

  public function save () {
    $this->setIdFieldName('template_id');
    parent::save();
    $this->setIdFieldName('guid');

    return $this;
  }

  public function delete () {
    $this->setIdFieldName('template_id');
    parent::delete();
    $this->setIdFieldName('guid');

    return $this;
  }
}

?>
