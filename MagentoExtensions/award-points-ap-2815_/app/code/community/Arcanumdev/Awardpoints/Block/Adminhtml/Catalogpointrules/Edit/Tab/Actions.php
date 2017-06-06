<?php
 /*
 * Arcanum Dev AwardPoints
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to arcanumdev@wafunotamago.com so we can send you a copy immediately.
 *
 * @category   Magento Sale Extension
 * @package    AwardPoints
 * @copyright  Copyright (c) 2012 Arcanum Dev. Y.K. (http://arcanumdev.wafunotamago.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 class Arcanumdev_Awardpoints_Block_Adminhtml_Catalogpointrules_Edit_Tab_Actions extends Mage_Adminhtml_Block_Widget_Form{protected function _prepareForm(){$model=Mage::registry('catalogpointrules_data');$form=new Varien_Data_Form();$form->setHtmlIdPrefix('rule_');$fieldset=$form->addFieldset('action_fieldset', array('legend'=>Mage::helper('awardpoints')->__('Actions')));$fieldset->addField('action_type', 'select', array('label'=>Mage::helper('awardpoints')->__('Type of action'),'name'=>'action_type','values'=>$model->ruleActionTypesToOptionArray(),'after_element_html'=>'','required'=>true,));$fieldset->addField('points', 'text', array('name'=>'points','class'=>'validate-number','label'=>Mage::helper('awardpoints')->__('Points'),'title'=>Mage::helper('awardpoints')->__('Rule Title'),'required'=>true,));$form->setValues($model->getData());$this->setForm($form);return parent::_prepareForm();}}