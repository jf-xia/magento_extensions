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
 class Arcanumdev_Awardpoints_Model_Rule_Condition_Combine extends Mage_Rule_Model_Condition_Combine{public function __construct(){parent::__construct();$this->setType('awardpoints/rule_condition_combine');}public function getNewChildSelectOptions(){$conditions=parent::getNewChildSelectOptions();$conditions=array_merge_recursive($conditions, array(array('value'=>'awardpoints/rule_condition_combine','label'=>Mage::helper('awardpoints')->__('Conditions Combination'))));$c_attributes=array(array('value'=>'awardpoints/rule_condition_customeraddress_params|postcode','label'=>Mage::helper('awardpoints')->__('User post code')),array('value'=>'awardpoints/rule_condition_customeraddress_params|region_id','label'=>Mage::helper('awardpoints')->__('User region')),array('value'=>'awardpoints/rule_condition_customeraddress_params|country_id','label'=>Mage::helper('awardpoints')->__('User country')));$conditions=array_merge_recursive($conditions, array(array('label'=>Mage::helper('awardpoints')->__('User location'), 'value'=>$c_attributes),));$addressCondition=Mage::getModel('salesrule/rule_condition_address');$addressAttributes=$addressCondition->loadAttributeOptions()->getAttributeOption();$cart_attributes=array();foreach ($addressAttributes as $code=>$label) {$cart_attributes[]=array('value'=>'salesrule/rule_condition_address|'.$code, 'label'=>$label);}$conditions=array_merge_recursive($conditions, array(array('label'=>Mage::helper('salesrule')->__('Cart Attributes'), 'value'=>$cart_attributes),));return $conditions;}public function asHtml(){$html=$this->getTypeElement()->getHtml().Mage::helper('awardpoints')->__("If %s of these order conditions are %s",$this->getAggregatorElement()->getHtml(),$this->getValueElement()->getHtml());if($this->getId()!='1') {$html.= $this->getRemoveLinkHtml();}return $html;}}