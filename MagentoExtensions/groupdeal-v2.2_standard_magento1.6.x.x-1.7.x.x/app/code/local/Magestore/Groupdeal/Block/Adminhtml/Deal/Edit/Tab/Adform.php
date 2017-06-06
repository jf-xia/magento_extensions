<?php

class Magestore_Groupdeal_Block_Adminhtml_Deal_Edit_Tab_Adform extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('deal_adform', array('legend'=>Mage::helper('groupdeal')->__('Advance information'), 'class'=>'fieldset-wide'));
	  
	  $config = array(	'use_container'                 => true,
						'add_variables'                 => false,
						'add_widgets'                   => false,
						'add_directives' 				=> true,
						'files_browser_window_url'      => Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg_images/index'),
						'directives_url'                => Mage::getSingleton('adminhtml/url')->getUrl('admintml/cms_wysiwyg/directive'),
						);
	  
      $fieldset->addField('short_description', 'editor', array(
          'label'     => Mage::helper('groupdeal')->__('Short Description'),
          'class'     => 'required-entry',
		  'style'     => 'height:100px;',
          'required'  => true,
          'name'      => 'short_description',
		  'wysiwyg'   => false,
      ));
	  
	  $fieldset->addField('full_description', 'editor', array(
          'label'     => Mage::helper('groupdeal')->__('Full Description'),
          'class'     => 'required-entry',
		  'style'     => 'width:98%; height:200px;',
          'required'  => true,
          'name'      => 'full_description',
		  'wysiwyg'   => true,
		  'config' => Mage::getSingleton('cms/wysiwyg_config')->getConfig($config)
      ));
	  
	  $fieldset->addField('the_fine_print', 'editor', array(
          'label'     => Mage::helper('groupdeal')->__('The Fine Print'),
          'class'     => 'required-entry',
		  'style'     => 'width:98%; height:200px;',
          'required'  => true,
          'name'      => 'the_fine_print',
		  'wysiwyg'   => true,
		  'config' => Mage::getSingleton('cms/wysiwyg_config')->getConfig($config)
      ));
      
     $fieldset->addField('highlights', 'editor', array(
          'label'     => Mage::helper('groupdeal')->__('Highlights'),
          'class'     => 'required-entry',
		  'style'     => 'width:98%; height:200px;',
          'required'  => true,
          'name'      => 'highlights',
		  'wysiwyg'   => true,
		  'config' => Mage::getSingleton('cms/wysiwyg_config')->getConfig($config)
      ));
	  
      if ( Mage::getSingleton('adminhtml/session')->getDealData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getDealData());
          Mage::getSingleton('adminhtml/session')->setDealData(null);
      } elseif ( Mage::registry('deal_data') ) {
          $form->setValues(Mage::registry('deal_data')->getData());
      }
      return parent::_prepareForm();
  }
}