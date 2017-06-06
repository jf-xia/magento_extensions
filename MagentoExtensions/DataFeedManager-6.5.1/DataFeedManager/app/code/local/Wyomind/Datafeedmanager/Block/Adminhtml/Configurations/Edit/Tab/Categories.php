<?php
class Wyomind_Datafeedmanager_Block_Adminhtml_Configurations_Edit_Tab_Categories extends Mage_Adminhtml_Block_Widget_Form
{
   protected function _prepareForm()
   {
       $form = new Varien_Data_Form();
       $model = Mage::getModel('datafeedmanager/configurations');
			
	   $model ->load($this->getRequest()->getParam('id'));
	  
	   $this->setForm($form);
	   $fieldset = $form->addFieldset('datafeedmanager', array('legend'=>$this->__('Categories')));

  			
	   $this->setTemplate('datafeedmanager/categories.phtml');
	   		

  if ( Mage::registry('datafeedmanager_data') ) $form->setValues(Mage::registry('datafeedmanager_data')->getData());

  return parent::_prepareForm();
 }
}


 