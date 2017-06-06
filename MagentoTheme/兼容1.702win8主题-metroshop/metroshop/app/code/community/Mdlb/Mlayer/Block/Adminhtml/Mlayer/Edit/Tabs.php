<?php

class Mdlb_Mlayer_Block_Adminhtml_Mlayer_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('mlayer_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('mlayer')->__('Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('mlayer')->__('General Information'),
          'title'     => Mage::helper('mlayer')->__('General Information'),
          'content'   => $this->getLayout()->createBlock('mlayer/adminhtml_mlayer_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}