<?php
class WebspeaksFeedback_Fancyfeedback_Block_Adminhtml_Fancyfeedbacksettings extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_fancyfeedbacksettings';
    $this->_blockGroup = 'fancyfeedback';
    $this->_headerText = Mage::helper('fancyfeedback')->__('Manage Feedback Settings');
    // $this->_addButtonLabel = Mage::helper('fancyfeedback')->__('Add Item');
    parent::__construct();
  }
}