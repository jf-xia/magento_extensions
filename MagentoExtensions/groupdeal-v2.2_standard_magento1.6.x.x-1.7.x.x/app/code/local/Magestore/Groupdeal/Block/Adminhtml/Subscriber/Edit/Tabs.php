<?php

class Magestore_Groupdeal_Block_Adminhtml_Subscriber_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('subscriber_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('groupdeal')->__('Subscriber Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('groupdeal')->__('Information'),
          'title'     => Mage::helper('groupdeal')->__('Information'),
          'content'   => $this->getLayout()->createBlock('groupdeal/adminhtml_subscriber_edit_tab_form')->toHtml(),
      ));
	  
	   $this->addTab('category_section', array(
          'label'     => Mage::helper('groupdeal')->__('Categories'),
          'title'     => Mage::helper('groupdeal')->__('Categories'),
          'url'		  => $this->getUrl('*/*/categories',array('_current'=>true,'id'=>$this->getRequest()->getParam('id'))),
		  'class'     => 'ajax',
      ));
      return parent::_beforeToHtml();
  }
}