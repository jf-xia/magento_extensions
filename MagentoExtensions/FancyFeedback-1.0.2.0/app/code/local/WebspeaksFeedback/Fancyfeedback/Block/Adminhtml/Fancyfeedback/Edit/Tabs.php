<?php

class WebspeaksFeedback_Fancyfeedback_Block_Adminhtml_Fancyfeedback_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('fancyfeedback_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('fancyfeedback')->__('Feedback'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('fancyfeedback')->__('Reply'),
          'title'     => Mage::helper('fancyfeedback')->__('Reply'),
          'content'   => $this->getLayout()->createBlock('fancyfeedback/adminhtml_fancyfeedback_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}