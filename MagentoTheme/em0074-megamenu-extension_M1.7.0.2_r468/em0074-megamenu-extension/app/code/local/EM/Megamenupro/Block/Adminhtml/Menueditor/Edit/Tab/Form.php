<?php
class EM_Megamenupro_Block_Adminhtml_Menueditor_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('megamenupro_form', array('legend'=>Mage::helper('megamenupro')->__('Item information')));
     
      $fieldset->addField('name', 'text', array(
          'label'     => Mage::helper('megamenupro')->__('Name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'name',
      ));
	  
	  $fieldset->addField('type', 'select', array(
          'label'     => Mage::helper('megamenupro')->__('Type'),
          'name'      => 'type',
          'values'    => array(
              array(
                  'value'     => 0,
                  'label'     => Mage::helper('megamenupro')->__('Horizantal'),
              ),

              array(
                  'value'     => 1,
                  'label'     => Mage::helper('megamenupro')->__('Vertical'),
              ),
          ),
      ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('megamenupro')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('megamenupro')->__('Enabled'),
              ),

              array(
                  'value'     => 0,
                  'label'     => Mage::helper('megamenupro')->__('Disabled'),
              ),
          ),
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getMegamenuproData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getMegamenuproData());
          Mage::getSingleton('adminhtml/session')->setMegamenuproData(null);
      } elseif ( Mage::registry('megamenupro_data') ) {
          $form->setValues(Mage::registry('megamenupro_data')->getData());
      }
      return parent::_prepareForm();
  }
}