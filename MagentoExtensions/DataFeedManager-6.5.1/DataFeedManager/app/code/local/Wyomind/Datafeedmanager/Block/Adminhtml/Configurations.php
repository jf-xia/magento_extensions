<?php

class Wyomind_Datafeedmanager_Block_Adminhtml_Configurations extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = 'adminhtml_configurations';
        $this->_blockGroup = 'datafeedmanager';
        $this->_headerText = Mage::helper('datafeedmanager')->__('Data Feed Manager');
        $this->_addButtonLabel = Mage::helper('datafeedmanager')->__('Create new data feed');
        /* $this->_addButton('copy', array(
          'label' => Mage::helper('datafeedmanager')->__('Import a new data feed'),
          'class' => 'add',
          )); */
        parent::__construct();
    }

}

