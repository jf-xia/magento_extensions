<?php

/*
 * overload of app\code\core\Mage\Adminhtml\Block\Permissions\User\Edit\Tabs.php
 * to add a tab
 */
class MDN_AdminLogger_Block_Adminhtml_Permissions_User_Tabs extends Mage_Adminhtml_Block_Permissions_User_Edit_Tabs {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        $this->setId('adminlogger_edit_tabs');
        $this->setDestElementId('edit_form');
    }

    /**
     * Set tabs
     */
    protected function _beforeToHtml() {



        
        
        
        $this->addTab('admin_logger', array(
            'label' => Mage::helper('AdminLogger')->__('Admin Logger'),
            'title' => Mage::helper('AdminLogger')->__('Admin Logger'),
            'content' => $this->getLayout()->createBlock('AdminLogger/Adminhtml_Permissions_User_Tabs_AdminLogger')->toHtml(),
           
        ));
        
        return parent::_beforeToHtml();;
        
    }
    
}
