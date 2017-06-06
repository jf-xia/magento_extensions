<?php

class Wyomind_Datafeedmanager_Block_Adminhtml_Configurations_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();

        $this->_objectId = 'feed_id';
        $this->_blockGroup = 'datafeedmanager';
        $this->_controller = 'adminhtml_configurations';


        if (Mage::registry('datafeedmanager_data')->getFeedId()) {


            $this->_addButton('copy', array(
                'label' => Mage::helper('adminhtml')->__('Duplicate'),
                'onclick' => "$('feed_id').remove(); editForm.submit();",
                'class' => 'add',
            ));
            /* $this->_addButton('export', array(
              'label'   => Mage::helper('adminhtml')->__('Export template'),
              'onclick' => "$('feed_id').export(); editForm.submit();",
              'class'   => 'go',
              )); */
            $this->_addButton('generate', array(
                'label' => Mage::helper('adminhtml')->__('Generate'),
                'onclick' => "$('generate').value=1; editForm.submit();",
                'class' => 'save',
            ));
            $this->_removeButton('save');
            $this->_removeButton('reset');
            $this->_addButton('save', array(
                'label' => Mage::helper('adminhtml')->__('Save'),
                'onclick' => "$('continue').value=1; editForm.submit();",
                'class' => 'save',
            ));
        }
    }

    public function getHeaderText() {
        if (Mage::registry('datafeedmanager_data') && Mage::registry('datafeedmanager_data')->getFeedId()) {
            return Mage::helper('datafeedmanager')->__("Edit data feed  '%s'", $this->htmlEscape(Mage::registry('datafeedmanager_data')->getFeed_name()));
        } else {
            return Mage::helper('datafeedmanager')->__('Add data feed');
        }
    }

}