<?php
/**
 * Contus Support Interactive.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file PRICE COUNTDOWN-LICENSE.txt.
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento 1.4.x and 1.5.x COMMUNITY edition
 * Contus Support does not guarantee correct work of this package
 * on any other Magento edition except Magento 1.4.x and 1.5.x COMMUNITY edition.
 * =================================================================
 */

class Apptha_Timer_Block_Adminhtml_Timer_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('timer_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('timer')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('timer')->__('Item Information'),
          'title'     => Mage::helper('timer')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('timer/adminhtml_timer_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}