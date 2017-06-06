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
 class Arcanumdev_Awardpoints_Model_Catalogpointrule_Condition_Combine extends Mage_Rule_Model_Condition_Combine{public function __construct(){parent::__construct();$this->setType('awardpoints/catalogpointrule_condition_combine');}public function getNewChildSelectOptions(){$productCondition=Mage::getModel('catalogrule/rule_condition_product');$productAttributes=$productCondition->loadAttributeOptions()->getAttributeOption();$attributes=array();foreach ($productAttributes as $code=>$label){$attributes[]=array('value'=>'catalogrule/rule_condition_product|'.$code, 'label'=>$label);}$conditions=parent::getNewChildSelectOptions();$conditions=array_merge_recursive($conditions, array(array('value'=>'awardpoints/catalogpointrule_condition_combine','label'=>Mage::helper('catalogrule')->__('Conditions Combination')),array('label'=>Mage::helper('awardpoints')->__('Product Attribute'), 'value'=>$attributes),));return $conditions;}public function asHtml(){$html=$this->getTypeElement()->getHtml().Mage::helper('awardpoints')->__("If %s of these order conditions are %s",$this->getAggregatorElement()->getHtml(),$this->getValueElement()->getHtml());if ($this->getId()!='1'){$html.= $this->getRemoveLinkHtml();}return $html;}public function collectValidatedAttributes($productCollection){foreach ($this->getConditions() as $condition){$condition->collectValidatedAttributes($productCollection);}return $this;}}