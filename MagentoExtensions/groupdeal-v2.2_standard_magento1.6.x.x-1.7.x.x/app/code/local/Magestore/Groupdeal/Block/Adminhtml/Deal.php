<?php
class Magestore_Groupdeal_Block_Adminhtml_Deal extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_deal';
    $this->_blockGroup = 'groupdeal';
    $this->_headerText = Mage::helper('groupdeal')->__('Deal Manager');
    $this->_addButtonLabel = Mage::helper('groupdeal')->__('Add Deal');
    parent::__construct();
  }
}