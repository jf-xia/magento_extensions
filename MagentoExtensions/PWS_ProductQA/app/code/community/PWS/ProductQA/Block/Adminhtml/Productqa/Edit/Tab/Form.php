<?php
class PWS_ProductQA_Block_Adminhtml_Productqa_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        
        $dateFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
        
        $fieldset_question = $form->addFieldset('productqa_form_question', array(
            'legend'=>Mage::helper('pws_productqa')->__('Question (readonly)')
        ));
        
        $fieldset_question->addField('name', 'text', array(
            'name'      => 'record[name]',
            'label'     => Mage::helper('pws_productqa')->__('Name'),
            'readonly' => true,
        ));

        $fieldset_question->addField('email', 'text', array(
            'name'      => 'record[email]',
            'label'     => Mage::helper('pws_productqa')->__('Email'),
            'readonly' => true,

        ));
        
         $fieldset_question->addField('product_name', 'text', array(
            'name'      => 'record[product_name]',
            'label'     => Mage::helper('pws_productqa')->__('Product'),
            'readonly' => true,

        ));
        
         $fieldset_question->addField('store_name', 'text', array(
            'name'      => 'record[store_name]',
            'label'     => Mage::helper('pws_productqa')->__('Store'),
            'readonly' => true,

        ));
        
        $fieldset_question->addField('created_on', 'date', array(
            'name'      => 'record[created_on]',
            'label'     => Mage::helper('pws_productqa')->__('Posted On'),
            'readonly' => true,
            'input_format' => Varien_Date::DATE_INTERNAL_FORMAT,
            'format'       => $dateFormatIso,
        ));
        
         $fieldset_question->addField('question', 'textarea', array(
            'name'      => 'record[question]',
            'label'     => Mage::helper('pws_productqa')->__('Question'),
            'readonly' => true,
        ));
        
        
       
        
       
        
        $fieldset_question->addField('status', 'select', array(
                'label'     => Mage::helper('pws_productqa')->__('Status'),
                'name'      => 'record[status]',
                'value'		=> 'public',   
                'values'    => array(
                	array('value'=>'public','label'=>'public'),
                	array('value'=>'hidden','label'=>'hidden')
                ),
                'readonly' => true,
            ));
        
        $fieldset_answer = $form->addFieldset('productqa_form_answer', array(
            'legend'=>Mage::helper('pws_productqa')->__('Answer')
        ));    

        $fieldset_answer->addField('answer', 'textarea', array(
            'name'      => 'record[answer]',
            'label'     => Mage::helper('pws_productqa')->__('Answer'),
            'class'     => 'required-entry',
            'required'  => true,
        ));

		if(Mage::getSingleton('adminhtml/session')->getRecordData()){	
		    $record = Mage::getSingleton('adminhtml/session')->getRecordData();		
        	$form->setValues($record['record']);
        	Mage::getSingleton('adminhtml/session')->setRecordData(false);
        } elseif(Mage::registry('productqa')) {
            $form->setValues(Mage::registry('productqa')->getData());
        }
        return parent::_prepareForm();
    }

    

    protected function _toHtml()
    {
        return parent::_toHtml();
    }


}
