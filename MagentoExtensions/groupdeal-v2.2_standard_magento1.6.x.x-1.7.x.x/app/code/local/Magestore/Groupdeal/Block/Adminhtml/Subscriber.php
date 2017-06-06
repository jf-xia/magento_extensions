<?php
class Magestore_Groupdeal_Block_Adminhtml_Subscriber extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_subscriber';
    $this->_blockGroup = 'groupdeal';
    $this->_headerText = Mage::helper('groupdeal')->__('Subscriber Manager');
    parent::__construct();
	$this->_removeButton('add');
  }
}