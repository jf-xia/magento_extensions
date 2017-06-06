<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/LICENSE-L.txt
 *
 * @category   AW
 * @package    AW_Blog
 * @copyright  Copyright (c) 2009-2010 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/LICENSE-L.txt
 */

class AW_Blog_Block_Manage_Blog_Edit_Tab_Options extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('blog_form', array('legend'=>Mage::helper('blog')->__('Meta Data')));
		
		$fieldset->addField('meta_keywords', 'editor', array(
			'name' => 'meta_keywords',
			'label' => Mage::helper('blog')->__('Keywords'),
			'title' => Mage::helper('blog')->__('Meta Keywords'),
			'style' => 'width: 520px;',
		));
		
		$fieldset->addField('meta_description', 'editor', array(
			'name' => 'meta_description',
			'label' => Mage::helper('blog')->__('Description'),
			'title' => Mage::helper('blog')->__('Meta Description'),
			'style' => 'width: 520px;',
		));
		
		$fieldset = $form->addFieldset('blog_options', array('legend'=>Mage::helper('blog')->__('Advanced Post Options')));
		
		$fieldset->addField('user', 'text', array(
			'label'     => Mage::helper('blog')->__('Poster'),
			'name'      => 'user',
			'style' => 'width: 520px;',
			'after_element_html' => '<span class="hint">(Leave blank to use current user name)</span>',
		));
		
		$fieldset->addField('created_time', 'text', array(
			  'label'     => Mage::helper('blog')->__('Post Date'),
			  'name'      => 'created_time',
			  'style' => 'width: 520px;',
			  'after_element_html' => '<span class="hint">(eg: YYYY-MM-DD HH:MM:SS Leave blank to use current date)</span>',
		));
		
		if ( Mage::getSingleton('adminhtml/session')->getBlogData() )
		{
			$form->setValues(Mage::getSingleton('adminhtml/session')->getBlogData());
			Mage::getSingleton('adminhtml/session')->setBlogData(null);
			} elseif ( Mage::registry('blog_data') ) 
			{
				$form->setValues(Mage::registry('blog_data')->getData());
			}
			return parent::_prepareForm();
		}
	}
