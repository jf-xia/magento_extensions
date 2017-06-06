<?php

class Thylak_Artist_Block_Adminhtml_Artist_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('artist_form', array('legend'=>Mage::helper('artist')->__('Artist information')));
     
      $fieldset->addField('firstname', 'text', array(
          'label'     => Mage::helper('artist')->__('FirstName'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'firstname',
      ));

      $fieldset->addField('lastname', 'text', array(
          'label'     => Mage::helper('artist')->__('Lastname'),
          'required'  => false,
          'name'      => 'lastname',
	  ));
	   $fieldset->addField('email', 'text', array(
          'label'     => Mage::helper('artist')->__('Email'),
          'required'  => false,
          'name'      => 'email',
	  ));

       $fieldset->addField('password', 'text', array(
          'label'     => Mage::helper('artist')->__('Password'),
          'required'  => false,
          'name'      => 'password',
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getArtistData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getArtistData());
          Mage::getSingleton('adminhtml/session')->setArtistData(null);
      } elseif ( Mage::registry('artist_data') ) {
          $form->setValues(Mage::registry('artist_data')->getData());
      }
      return parent::_prepareForm();
  }
}