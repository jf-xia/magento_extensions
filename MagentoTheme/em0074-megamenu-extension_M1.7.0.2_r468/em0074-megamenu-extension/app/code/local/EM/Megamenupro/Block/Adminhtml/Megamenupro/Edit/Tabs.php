<?php

class EM_Megamenupro_Block_Adminhtml_Megamenupro_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('megamenupro_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('megamenupro')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('megamenupro')->__('Item Information'),
          'title'     => Mage::helper('megamenupro')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('megamenupro/adminhtml_megamenupro_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}