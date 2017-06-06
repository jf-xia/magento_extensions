<?php
class WebspeaksFeedback_Fancyfeedback_Block_Adminhtml_Fancyfeedback extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_fancyfeedback';
    $this->_blockGroup = 'fancyfeedback';
    $this->_headerText = Mage::helper('fancyfeedback')->__('Manage Feedbacks');
    // $this->_addButtonLabel = Mage::helper('fancyfeedback')->__('Add Item');
    parent::__construct();
	$this->_removeButton('add'); 
  }
}