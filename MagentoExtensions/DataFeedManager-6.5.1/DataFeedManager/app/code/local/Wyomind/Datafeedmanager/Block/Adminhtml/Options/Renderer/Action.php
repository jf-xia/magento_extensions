<?php

class Wyomind_Datafeedmanager_Block_Adminhtml_Options_Renderer_Action extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action {

    public function render(Varien_Object $row) {
        $this->getColumn()->setActions(
                array(
                    array(
                        'url' => $this->getUrl('*/adminhtml_options/edit', array('id' => $row->getOption_id())),
                        'caption' => Mage::helper('datafeedmanager')->__('Edit'),
                    ),
                    array(
                        'url' => $this->getUrl('*/adminhtml_options/delete', array('id' => $row->getOption_id())),
                        'confirm' => Mage::helper('datafeedmanager')->__('Are you sure you want to delete this option ?'),
                        'caption' => Mage::helper('datafeedmanager')->__('Delete'),
                    ),
                   
                )
        );
        return parent::render($row);
    }

}
