<?php

class Inchoo_EmailCommunication_Block_Adminhtml_Log_Container extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $action = Mage::app()->getRequest()->getActionName();
        $this->_blockGroup = 'inchoo_email_communication';
        $this->_controller = 'adminhtml_log';

        parent::__construct();
        $this->_removeButton('add');
        
        $this->_addButton('clear', array(
            'label'     => Mage::helper('inchoo_email_communication')->__('Clear All'),
            'onclick'   => 'setLocation(\'' . $this->getUrl('*/email_communication_log/clear') .'\')',
            'class'     => 'clear',
        ));        
        
        
    }

    public function getHeaderText()
    {
        return Mage::helper('inchoo_email_communication')->__('Inchoo Email Communication Log');
    }
}
