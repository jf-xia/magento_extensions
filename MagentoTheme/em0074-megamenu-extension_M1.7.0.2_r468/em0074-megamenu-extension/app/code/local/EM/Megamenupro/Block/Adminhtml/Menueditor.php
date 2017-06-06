<?php
class EM_Megamenupro_Block_Adminhtml_Menueditor extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_menueditor';
    $this->_blockGroup = 'megamenupro';
    $this->_headerText = Mage::helper('megamenupro')->__('EMThemes Menu Manager');
    $this->_addButtonLabel = Mage::helper('megamenupro')->__('Add New Menu');
    parent::__construct();
  }
}