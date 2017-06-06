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
 class Arcanumdev_Awardpoints_Model_Rules extends Mage_Core_Model_Abstract{const TARGET_CART='cart_amount';const TARGET_SKU='sku_value';const ACTIVATED_YES=1;const ACTIVATED_NO=0;const OPERATOR_1='=';const OPERATOR_2='<';const OPERATOR_3='<=';const OPERATOR_4='>';const OPERATOR_5='>=';const OPERATOR_6='between';protected $_targets;protected $_activated;protected $_operator;public function _construct(){parent::_construct();$this->_init('awardpoints/rules ');$this->_targets=array(self::TARGET_CART=>Mage::helper('awardpoints')->__('Cart amount'),self::TARGET_SKU=>Mage::helper('awardpoints')->__('Product sku'),);$this->_activated=array(self::ACTIVATED_YES=>Mage::helper('awardpoints')->__('Active'),self::ACTIVATED_NO=>Mage::helper('awardpoints')->__('Inactive'),);$this->_operator=array(self::OPERATOR_1=>Mage::helper('awardpoints')->__('='),self::OPERATOR_2=>Mage::helper('awardpoints')->__('<'),self::OPERATOR_3=>Mage::helper('awardpoints')->__('<='),self::OPERATOR_4=>Mage::helper('awardpoints')->__('>'),self::OPERATOR_5=>Mage::helper('awardpoints')->__('>='),self::OPERATOR_6=>Mage::helper('awardpoints')->__('Between'),);}public function getPointsByRule(){$websiteId=Mage::app()->getStore(Mage::app()->getStore()->getWebsiteId())->getWebsiteId();$collection=Mage::getResourceModel('awardpoints/rules_collection')->setValidationFilter($websiteId)->load();$arr_rule=array();if ($collection->getSize()){foreach ($collection as $points_rule){$arr_rule[]=array('name'=>$points_rule->getAwardpointsRuleName(),'type'=>$points_rule->getAwardpointsRuleType(),'operator'=>$points_rule->getAwardpointsRuleOperator(),'test_value'=>$points_rule->getAwardpointsRuleTest(),'points'=>$points_rule->getAwardpointsRulePoints());}}return $arr_rule;}public function getOperatorArray(){return $this->_operator;}public function operatorToOptionArray(){return $this->_toOptionArray($this->_operator);}public function getActivatedArray(){return $this->_activated;}public function activatedToOptionArray(){return $this->_toOptionArray($this->_activated);}public function getTargetsArray(){return $this->_targets;}public function targetsToOptionArray(){return $this->_toOptionArray($this->_targets);}protected function _toOptionArray($array){$res=array();foreach ($array as $value=>$label) {	$res[]=array('value'=>$value,'label'=>$label);}return $res;}}