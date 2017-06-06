<?php

class Magestore_Groupdeal_Block_Adminhtml_Deal_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('deal_form', array('legend'=>Mage::helper('groupdeal')->__('Basic information'), 'class'=>'fieldset-wide'));
      $calendarImage = Mage::getBaseUrl('skin') .'adminhtml/default/default/images/grid-cal.gif';

	  
      $fieldset->addField('deal_title', 'text', array(
          'label'     => Mage::helper('groupdeal')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'deal_title',
      ));
	  
	  $fieldset->addField('url_key', 'text', array(
          'label'     => Mage::helper('groupdeal')->__('URL Key'),
          'required'  => false,
          'name'      => 'url_key',
      ));	
	
      $fieldset->addField('deal_price', 'text', array(
          'label'     => Mage::helper('groupdeal')->__('Price'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'deal_price',
	  ));
	  
	  $fieldset->addField('deal_value', 'text', array(
          'label'     => Mage::helper('groupdeal')->__('Value'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'deal_value',
	  ));
	  
	  $fieldset->addField('minimum_purchase', 'text', array(
          'label'     => Mage::helper('groupdeal')->__('Minimum Purchase (Target)'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'minimum_purchase',
	  ));
	  
	  $fieldset->addField('maximum_purchase', 'text', array(
          'label'     => Mage::helper('groupdeal')->__('Maximum Purchase'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'maximum_purchase',
		  'note'	  => Mage::helper('groupdeal')->__('Zero if unlimited'),
	  ));
	  
	  $fieldset->addField('start_datetime', 'date', array(
          'label'     => Mage::helper('groupdeal')->__('Start Time'),
          'class'     => 'required-entry',
          'required'  => true,
		  'time'	  => true,	
          'name'      => 'start_datetime',
		  'format'    => 'MM/dd/yyyy HH:mm',
		  'image'     => $calendarImage,
	  ));
	  
	  $fieldset->addField('end_datetime', 'date', array(
          'label'     => Mage::helper('groupdeal')->__('End Time'),
          'class'     => 'required-entry',
          'required'  => true,
		  'time'	  => true,
          'name'      => 'end_datetime',
		  'format'    => 'MM/dd/yyyy HH:mm',
		  'image'     => $calendarImage,
	  ));
	  
	 
	 $fieldset->addField('image_url', 'gallery2', array(
		  'label'     => Mage::helper('groupdeal')->__('Image'),
		  'name'      => 'image_url',
		  'required'  => true,
		  'note'	  => '420x250',
		  'values'    => $this->_getImages(),
	  ));
	  
	  $fieldset->addField('featured', 'select', array(
          'label'     => Mage::helper('groupdeal')->__('Is Featured'),
          'name'      => 'featured',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('groupdeal')->__('Yes'),
              ),

              array(
                  'value'     => 0,
                  'label'     => Mage::helper('groupdeal')->__('No'),
              ),
          ),
      ));
	  
      $fieldset->addField('deal_status', 'select', array(
          'label'     => Mage::helper('groupdeal')->__('Status'),
          'name'      => 'deal_status',
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
     
	 $fieldset->addField('option_product', 'select', array(
          'label'     => Mage::helper('groupdeal')->__('Required Option Product'),
          'name'      => 'option_product',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('groupdeal')->__('Yes'),
              ),

              array(
                  'value'     => 0,
                  'label'     => Mage::helper('groupdeal')->__('No'),
              ),
          ),
		  'note'		=> Mage::helper('groupdeal')->__('Use for products have options'),
      ));
       
	  if ( Mage::getSingleton('adminhtml/session')->getDealData() )
      {
	  	  $data = Mage::getSingleton('adminhtml/session')->getDealData();
          Mage::getSingleton('adminhtml/session')->setDealData(null);
      } elseif ( Mage::registry('deal_data') ) {
	  	  $data = Mage::registry('deal_data')->getData();
      }
	  $form->setValues($data);
	  
      return parent::_prepareForm();
  }
  
	protected function _getImages(){
		$dealId = $this->getRequest()->getParam('id');
		$images = Mage::getModel('groupdeal/image')->getCollection()
					->addFieldToFilter('deal_id', $dealId);
		return $images;
	}  
}