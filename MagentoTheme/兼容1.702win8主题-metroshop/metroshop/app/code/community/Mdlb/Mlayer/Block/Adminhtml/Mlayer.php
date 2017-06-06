<?php
class Mdlb_Mlayer_Block_Adminhtml_Mlayer extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_mlayer';
    $this->_blockGroup = 'mlayer';
    $this->_headerText = Mage::helper('mlayer')->__('Banner Manager');
    $this->_addButtonLabel = Mage::helper('mlayer')->__('Add Banner');
    parent::__construct();
  }
}