<?php

class WebspeaksFeedback_Fancyfeedback_Block_Adminhtml_Fancyfeedbacksettings_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('fancyfeedbacksettings_form', array('legend'=>Mage::helper('fancyfeedback')->__('Reply to Feedback')));
     
      $fieldset->addField('enabled', 'label', array(
          'label'     => Mage::helper('fancyfeedback')->__('Enabled'),
          'name'      => 'enabled',
      ));

    /*  $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('fancyfeedbacksettings')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('fancyfeedbacksettings')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('fancyfeedbacksettings')->__('Disabled'),
              ),
          ),
      ));*/
     
      if ( Mage::getSingleton('adminhtml/session')->getFancyfeedbacksettingsData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getFancyfeedbacksettingsData());
          Mage::getSingleton('adminhtml/session')->setFancyfeedbacksettingsData(null);
      } elseif ( Mage::registry('fancyfeedbacksettings_data') ) {
          $form->setValues(Mage::registry('fancyfeedbacksettings_data')->getData());
      }
      return parent::_prepareForm();
  }
}