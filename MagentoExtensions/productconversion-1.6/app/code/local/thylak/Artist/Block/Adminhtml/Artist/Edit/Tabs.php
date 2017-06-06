<?php

class Thylak_Artist_Block_Adminhtml_Artist_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('artist_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('artist')->__('Artist Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('artist')->__('Artist Info'),
          'title'     => Mage::helper('artist')->__('Artist Info'),
          'content'   => $this->getLayout()->createBlock('artist/adminhtml_artist_edit_tab_form')->toHtml(),
      ));
	  $this->addTab('form_section1', array(
          'label'     => Mage::helper('artist')->__('Artwork Info'),
          'title'     => Mage::helper('artist')->__('Artwork Info'),
          'content'   => $this->getLayout()->createBlock('artist/adminhtml_artwork_grid')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}