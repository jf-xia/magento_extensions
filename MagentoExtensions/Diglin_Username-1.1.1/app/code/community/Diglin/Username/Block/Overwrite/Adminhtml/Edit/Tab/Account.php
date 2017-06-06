<?php
/**
 * Diglin
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Diglin
 * @package     Diglin_Username
 * @copyright   Copyright (c) 2011 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Diglin_Username_Block_Overwrite_Adminhtml_Edit_Tab_Account extends Mage_Adminhtml_Block_Customer_Edit_Tab_Account
{
    /**
     * Add the field 'is_active' to the edit tab form
     * 
     * @see Mage_Adminhtml_Block_Widget_Form::_prepareForm()
     */
    protected function _prepareForm()
    {
        $customer = Mage::registry('current_customer');
        $form = $this->getForm();
        $fieldset = $form->getElements()->searchById('base_fieldset');
        $fieldset->addField('is_active', 'select',
	            array(
	                'name'  	=> 'is_active',
	                'label' 	=> Mage::helper('adminhtml')->__('This account is'),
	                'id'    	=> 'is_active',
	                'title' 	=> Mage::helper('adminhtml')->__('Account status'),
	                'class' 	=> 'input-select',
	                'required' 	=> false,
	                'style'		=> 'width: 80px',
	                'value'		=> '1',
	                'values'	=> array(
	                	array(
	                    	'label' => Mage::helper('adminhtml')->__('Active'),
	                    	'value'	=> '1',
	                	),
	                	array(
	                    	'label' => Mage::helper('adminhtml')->__('Inactive'),
	                    	'value' => '0',
	                		),
	                	),
	            	)
	        	);
	        	
	    $form->setValues($customer->getData());
	    $this->setForm($form);
        return parent::_prepareForm();
    }
}
