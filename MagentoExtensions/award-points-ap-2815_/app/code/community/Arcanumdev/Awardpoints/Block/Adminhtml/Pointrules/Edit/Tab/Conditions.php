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
 class Arcanumdev_Awardpoints_Block_Adminhtml_Pointrules_Edit_Tab_Conditions extends Mage_Adminhtml_Block_Widget_Form{protected function _prepareForm(){$model=Mage::registry('pointrules_data');$form=new Varien_Data_Form();$form->setHtmlIdPrefix('rule_');$renderer=Mage::getBlockSingleton('adminhtml/widget_form_renderer_fieldset')->setTemplate('promo/fieldset.phtml')->setNewChildUrl($this->getUrl('*/adminhtml_pointrules/newConditionHtml/form/rule_conditions_fieldset'));$fieldset=$form->addFieldset('conditions_fieldset',array('legend'=>Mage::helper('awardpoints')->__('Conditions')))->setRenderer($renderer);$fieldset->addField('conditions','text',array('name'=>'conditions','label'=>Mage::helper('awardpoints')->__('Conditions'),'title'=>Mage::helper('awardpoints')->__('Conditions'),'required'=>true,))->setRule($model)->setRenderer(Mage::getBlockSingleton('rule/conditions'));$form->setValues($model->getData());$this->setForm($form);return parent::_prepareForm();}}