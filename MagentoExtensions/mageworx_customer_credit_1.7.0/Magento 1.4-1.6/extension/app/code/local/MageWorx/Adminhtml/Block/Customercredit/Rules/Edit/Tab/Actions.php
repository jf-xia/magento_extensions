<?php
/**
 * MageWorx
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MageWorx EULA that is bundled with
 * this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.mageworx.com/LICENSE-1.0.html
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@mageworx.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.mageworx.com/ for more information
 * or send an email to sales@mageworx.com
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @copyright  Copyright (c) 2010 MageWorx (http://www.mageworx.com/)
 * @license    http://www.mageworx.com/LICENSE-1.0.html
 */
 
/**
 * Customer Credit extension
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @author     MageWorx Dev Team <dev@mageworx.com>
 */
class MageWorx_Adminhtml_Block_Customercredit_Rules_Edit_Tab_Actions extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $model = Mage::registry('current_customercredit_rule');
		
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('rule_');

        $fieldset = $form->addFieldset('action_fieldset', array('legend'=>Mage::helper('salesrule')->__('Update prices using the following information')));
	
        $fieldset->addField('simple_action', 'select', array(
            'label'     => Mage::helper('salesrule')->__('Apply'),
            'name'      => 'simple_action',
            'options'    => array(
                'give_credit' => Mage::helper('customercredit')->__('Give Credit')
            ),
        ));
        
        $fieldset->addField('is_onetime', 'select', array(
            'label'     => Mage::helper('salesrule')->__('One-time'),
            'name'      => 'is_onetime',
            'options'    => array(
                '1' => Mage::helper('customercredit')->__('Yes'),
                '0' => Mage::helper('customercredit')->__('No')                
            ),
        ));
                
        $fieldset->addField('credit', 'text', array(
            'name' => 'credit',
            'required' => true,
            'class' => 'validate-not-negative-number',
            'label' => Mage::helper('customercredit')->__('Credit Amount'),
        ));

        $form->setValues($model->getData());
		
        //$form->setUseContainer(true);

        $this->setForm($form);

        return parent::_prepareForm();
    }

}
