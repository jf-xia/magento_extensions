<?php

class Magestore_Groupdeal_Block_Adminhtml_Subscriber_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('subscriber_form', array('legend'=>Mage::helper('groupdeal')->__('Information'), 'class'=>'fieldset-wide'));
	
      $fieldset->addField('email', 'text', array(
          'label'     => Mage::helper('groupdeal')->__('Email'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'email',
	  ));
	  
	  $fieldset->addField('price_from', 'text', array(
          'label'     => Mage::helper('groupdeal')->__('Price From'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'price_from',
	  ));
	  
	  $fieldset->addField('price_to', 'text', array(
          'label'     => Mage::helper('groupdeal')->__('Price To'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'price_to',
	  ));

      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('groupdeal')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('groupdeal')->__('Enabled'),
              ),

              array(
                  'value'     => 0,
                  'label'     => Mage::helper('groupdeal')->__('Disabled'),
              ),
          ),
      ));
     
       
	  if ( Mage::getSingleton('adminhtml/session')->getSubscriberData() )
      {
	  	  $data = Mage::getSingleton('adminhtml/session')->getSubscriberData();
          Mage::getSingleton('adminhtml/session')->setSubscriberData(null);
      } elseif ( Mage::registry('subscriber_data') ) {
	  	  $data = Mage::registry('subscriber_data')->getData();
      }
	  $form->setValues($data);
	  
      return parent::_prepareForm();
  }
}