<?php

class Mdlb_Mlayer_Block_Adminhtml_Mlayer_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('mlayer_form', array('legend'=>Mage::helper('mlayer')->__('General information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('mlayer')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));
			
			if (!Mage::app()->isSingleStoreMode()) {
				$fieldset->addField('store_id', 'multiselect', array(
							'name'      => 'stores[]',
							'label'     => Mage::helper('cms')->__('Store View'),
							'title'     => Mage::helper('cms')->__('Store View'),
							'required'  => true,
							'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
					));
			}
			else {
					$fieldset->addField('store_id', 'hidden', array(
							'name'      => 'stores[]',
							'value'     => Mage::app()->getStore(true)->getId()
					));
					$model->setStoreId(Mage::app()->getStore(true)->getId());
			}

      $fieldset->addField('filename', 'image', array(
          'label'     => Mage::helper('mlayer')->__('Image File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
	  
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('mlayer')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('mlayer')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('mlayer')->__('Disabled'),
              ),
          ),
      ));
			
			$fieldset->addField('weblink', 'text', array(
          'label'     => Mage::helper('mlayer')->__('Web Url'),
          'required'  => false,
          'name'      => 'weblink',
      ));
			
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('mlayer')->__('Content'),
          'title'     => Mage::helper('mlayer')->__('Content'),
          'style'     => 'width:280px; height:100px;',
          'wysiwyg'   => false,
          'required'  => false,
      ));
			
     
      if ( Mage::getSingleton('adminhtml/session')->getMlayerData() )
      {
          $data = Mage::getSingleton('adminhtml/session')->getMlayerData();
          Mage::getSingleton('adminhtml/session')->setMlayerData(null);
      } elseif ( Mage::registry('mlayer_data') ) {
          $data = Mage::registry('mlayer_data')->getData();
      }
	  $data['store_id'] = explode(',',$data['stores']);
	  $form->setValues($data);
	  
      return parent::_prepareForm();
  }
}