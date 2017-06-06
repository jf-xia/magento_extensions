<?php

class Monk_Blog_Block_Manage_Blog_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('blog_form', array('legend'=>Mage::helper('blog')->__('Post information')));
		
		$fieldset->addField('title', 'text', array(
		  'label'     => Mage::helper('blog')->__('Title'),
		  'class'     => 'required-entry',
		  'required'  => true,
		  'name'      => 'title',
		));
		
		$fieldset->addField('identifier', 'text', array(
		  'label'     => Mage::helper('blog')->__('Identifier'),
		  'class'     => 'required-entry',
		  'required'  => true,
		  'name'      => 'identifier',
		  'class'     => 'validate-identifier',
		  'after_element_html' => '<span class="hint">(eg: domain.com/blog/identifier)</span>',
		));
	  
	  	/**
         * Check is single store mode
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'multiselect', array(
                'name'      => 'stores[]',
                'label'     => Mage::helper('cms')->__('Store View'),
                'title'     => Mage::helper('cms')->__('Store View'),
                'required'  => true,
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            ));
        }
		
		$categories = array();
	  	$collection = Mage::getModel('blog/cat')->getCollection()->setOrder('sort_order', 'asc');
		foreach ($collection as $cat) {
			$categories[] = ( array(
				'label' => (string)$cat->getTitle(),
				'value' => $cat->getCatId()
				));
		}
		
	  	$fieldset->addField('cat_id', 'multiselect', array(
                'name'      => 'cats[]',
                'label'     => Mage::helper('blog')->__('Category'),
                'title'     => Mage::helper('blog')->__('Category'),
                'required'  => true,
                'values'    => $categories,
     	));
		
		$fieldset->addField('status', 'select', array(
		'label'     => Mage::helper('blog')->__('Status'),
		'name'      => 'status',
		'values'    => array(
		  array(
			  'value'     => 1,
			  'label'     => Mage::helper('blog')->__('Enabled'),
		  ),
		
		  array(
			  'value'     => 2,
			  'label'     => Mage::helper('blog')->__('Disabled'),
		  ),
		  
		  array(
			  'value'     => 3,
			  'label'     => Mage::helper('blog')->__('Hidden'),
		  ),
		),
		'after_element_html' => '<span class="hint">(Hidden Pages will not show in the blog but can still be accessed directly)</span>',
		));
	  	
		$fieldset->addField('comments', 'select', array(
		'label'     => Mage::helper('blog')->__('Enable Comments'),
		'name'      => 'comments',
		'values'    => array(
		  array(
			  'value'     => 0,
			  'label'     => Mage::helper('blog')->__('Enabled'),
		  ),
		
		  array(
			  'value'     => 1,
			  'label'     => Mage::helper('blog')->__('Disabled'),
		  ),
		),
		'after_element_html' => '<span class="hint">Disabling will close the post to new comments</span>',
		));
	  			
		$fieldset->addField('post_content', 'editor', array(
            'name'      => 'post_content',
            'label'     => Mage::helper('blog')->__('Content'),
            'title'     => Mage::helper('blog')->__('Content'),
            'style'     => 'width:700px; height:500px;',
            'wysiwyg'   => true,
            
        )); 
		
		if ( Mage::getSingleton('adminhtml/session')->getBlogData() )
		{
		  $form->setValues(Mage::getSingleton('adminhtml/session')->getBlogData());
		  Mage::getSingleton('adminhtml/session')->setBlogData(null);
		} elseif ( Mage::registry('blog_data') ) {
		  $form->setValues(Mage::registry('blog_data')->getData());
		}
		return parent::_prepareForm();
  }
}