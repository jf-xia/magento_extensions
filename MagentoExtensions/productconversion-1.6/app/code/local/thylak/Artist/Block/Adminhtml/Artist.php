<?php
class Thylak_Artist_Block_Adminhtml_Artist extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_artist';
    $this->_blockGroup = 'artist';
    $this->_headerText = Mage::helper('artist')->__('Artist Manager');
    $this->_addButtonLabel = Mage::helper('artist')->__('Add Artist');
    parent::__construct();
  }
}