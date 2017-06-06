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
 class Arcanumdev_Awardpoints_Block_Adminhtml_Pointrules_Edit extends Mage_Adminhtml_Block_Widget_Form_Container{public function __construct(){$this->_objectId='id';$this->_blockGroup='awardpoints';$this->_controller='adminhtml_pointrules';$this->_formScripts[]=" checkTypes = function(){ if ($('rule_action_type').getValue() == '".Arcanumdev_Awardpoints_Model_Pointrules::RULE_ACTION_TYPE_DONTPROCESS."'){ $('rule_points').value = '1'; $('rule_points').up(1).hide(); } else { $('rule_points').up(1).show(); } }; Event.observe($('rule_action_type'), 'change', function(event) { checkTypes(); }); document.observe('dom:loaded', function() { checkTypes(); $('rule_rule_type').up(1).hide(); }); "; parent::__construct();$this->_updateButton('save','label',Mage::helper('awardpoints')->__('Save Rule')); $this->_updateButton('delete','label',Mage::helper('awardpoints')->__('Delete Rule'));$filter=Mage::registry('pointrules_data');}public function getHeaderText(){$rule=Mage::registry('pointrules_data');if($rule->getRuleId()){return Mage::helper('awardpoints')->__("Edit Rule '%s'",$this->htmlEscape($rule->getTitle()));}else{return Mage::helper('awardpoints')->__('New Rule');}}}