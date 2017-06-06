<?php

class WebspeaksFeedback_Fancyfeedback_Block_Adminhtml_Fancyfeedback_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('fancyfeedback_form', array('legend'=>Mage::helper('fancyfeedback')->__('Reply to Feedback')));
     
      $fieldset->addField('name', 'label', array(
          'label'     => Mage::helper('fancyfeedback')->__('Name'),
          'name'      => 'name',
      ));

      $fieldset->addField('email', 'label', array(
          'label'     => Mage::helper('fancyfeedback')->__('Email'),
          'name'      => 'email',
      ));

      $fieldset->addField('comment', 'label', array(
          'label'     => Mage::helper('fancyfeedback')->__('Comment'),
          'name'      => 'comment',
      ));

    /*  $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('fancyfeedback')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('fancyfeedback')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('fancyfeedback')->__('Disabled'),
              ),
          ),
      ));*/
     
      $fieldset->addField('reply', 'editor', array(
          'name'      => 'reply',
          'label'     => Mage::helper('fancyfeedback')->__('Message'),
          'title'     => Mage::helper('fancyfeedback')->__('Message'),
          'style'     => 'width:280px; height:200px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getFancyfeedbackData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getFancyfeedbackData());
          Mage::getSingleton('adminhtml/session')->setFancyfeedbackData(null);
      } elseif ( Mage::registry('fancyfeedback_data') ) {
          $form->setValues(Mage::registry('fancyfeedback_data')->getData());
      }
      return parent::_prepareForm();
  }
}