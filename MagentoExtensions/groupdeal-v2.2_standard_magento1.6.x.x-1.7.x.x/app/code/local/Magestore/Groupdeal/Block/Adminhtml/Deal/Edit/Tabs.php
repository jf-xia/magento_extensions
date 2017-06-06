<?php

class Magestore_Groupdeal_Block_Adminhtml_Deal_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('deal_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('groupdeal')->__('Deal Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('groupdeal')->__('Basic Information'),
          'title'     => Mage::helper('groupdeal')->__('Basic Information'),
          'content'   => $this->getLayout()->createBlock('groupdeal/adminhtml_deal_edit_tab_form')->toHtml(),
      ));
	  
	   $this->addTab('adform_section', array(
          'label'     => Mage::helper('groupdeal')->__('Advanced Information'),
          'title'     => Mage::helper('groupdeal')->__('Advanced Information'),
          'content'   => $this->getLayout()->createBlock('groupdeal/adminhtml_deal_edit_tab_adform')->toHtml(),
      ));
	  
     
	 $this->addTab('product_section', array(
          'label'     => Mage::helper('groupdeal')->__('Manage Products'),
          'title'     => Mage::helper('groupdeal')->__('Manage Products'),
          'url'		  => $this->getUrl('*/*/products',array('_current'=>true,'id'=>$this->getRequest()->getParam('id'))),
		  'class'     => 'ajax',
      ));
	  
	  $this->addTab('order_section', array(
          'label'     => Mage::helper('groupdeal')->__('View Orders'),
          'title'     => Mage::helper('groupdeal')->__('View Orders'),
          'url'		  => $this->getUrl('*/*/orders',array('_current'=>true,'id'=>$this->getRequest()->getParam('id'))),
		  'class'     => 'ajax',
      ));
      return parent::_beforeToHtml();
  }
}