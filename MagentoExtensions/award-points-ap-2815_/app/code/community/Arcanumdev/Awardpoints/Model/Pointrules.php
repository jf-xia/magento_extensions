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
 class Arcanumdev_Awardpoints_Model_Pointrules extends Mage_Rule_Model_Rule{const RULE_TYPE_CART =1;const RULE_TYPE_DATAFLOW  =2;const RULE_ACTION_TYPE_ADD=1;const RULE_ACTION_TYPE_DONTPROCESS=2;protected $_types;protected $_action_types;public function _construct(){parent::_construct();$this->_init('awardpoints/pointrules');$this->_types=array(self::RULE_TYPE_CART=>Mage::helper('awardpoints')->__('Cart rule'),self::RULE_TYPE_DATAFLOW=>Mage::helper('awardpoints')->__('Import rule'),);$this->_action_types=array(self::RULE_ACTION_TYPE_ADD=>Mage::helper('awardpoints')->__('Add / remove points'),self::RULE_ACTION_TYPE_DONTPROCESS=>Mage::helper('awardpoints')->__("Don't process points"),);}public function ruletypesToOptionArray(){return $this->_toOptionArray($this->_types);}public function ruletypesToArray(){return $this->_toArray($this->_types);}public function ruleActionTypesToOptionArray(){return $this->_toOptionArray($this->_action_types);}public function ruleActionTypesToArray(){return $this->_toArray($this->_action_types);}protected function _toOptionArray($array){$res=array();foreach ($array as $value=>$label){$res[]=array('value'=>$value, 'label'=>$label);}return $res;}protected function _toArray($array){$res=array();foreach ($array as $value=>$label){$res[$value]=$label;}return $res;}public function getConditionsInstance(){return Mage::getModel('awardpoints/rule_condition_combine');}public function checkRule($to_validate){$storeId=Mage::app()->getStore($request->getStore())->getId();$websiteId=Mage::app()->getStore($storeId)->getWebsiteId();$customerGroupId=Mage::getSingleton('customer/session')->getCustomerGroupId();$rules=Mage::getModel('awardpoints/pointrules')->getCollection()->setValidationFilter($websiteId,$customerGroupId,$couponCode);foreach($rules as $rule){if(!$rule->getStatus()) continue;$rule_validate=Mage::getModel('awardpoints/pointrules')->load($rule->getRuleId());if($rule_validate->validate($to_validate)){Mage::getModel('awardpoints/subscriptions')->updateSegments($to_validate->getEmail(),$rule);} else{Mage::getModel('awardpoints/subscriptions')->unsubscribe($to_validate->getEmail(),$rule);}}}public function getPointrulesByIds($ids){$segmentsids=explode(',',$ids);$segmentstitles=array();foreach ($segmentsids as $segmentid){$collection=$this->getCollection();$collection->getSelect()   ->where('rule_id=?',$segmentid);$row=$collection->getFirstItem();$segmentstitles[]=$row->getTitle();}return implode(',',$segmentstitles);}public function getSegmentsRule(){$segments=array();$collection=$this->getCollection();$collection->getSelect()   ->order('title');$collection->load();foreach ($collection as $key=>$values){$segments[]=array('label'=>$values->getTitle() ,'value'=>$values->getRuleId());}return $segments;}public function getAllRulePointsGathered($cart=null){if($cart == null){$cart=Mage::getSingleton('checkout/cart');}$points=$this->getRulePointsGathered($cart);return $points;}public function getRulePointsGathered($to_validate){$points=0;$storeId=Mage::app()->getStore()->getId();$websiteId=Mage::app()->getStore($storeId)->getWebsiteId();$customerGroupId=Mage::getSingleton('customer/session')->getCustomerGroupId();$rules=Mage::getModel('awardpoints/pointrules')->getCollection()->setValidationFilter($websiteId,$customerGroupId);foreach($rules as $rule){if(!$rule->getStatus()) continue;$rule_validate=Mage::getModel('awardpoints/pointrules')->load($rule->getRuleId());if($rule_validate->validate($to_validate)){if($rule_validate->getActionType() == self::RULE_ACTION_TYPE_DONTPROCESS){return false;}$points+=$rule_validate->getPoints();} else{}}return $points;}public function validateVarienData(Varien_Object $object){if($object->getData('from_date') && $object->getData('to_date')){$dateStartUnixTime=strtotime($object->getData('from_date'));$dateEndUnixTime  =strtotime($object->getData('to_date'));if($dateEndUnixTime < $dateStartUnixTime){return array(Mage::helper('rule')->__("End Date should be greater than Start Date"));}}return true;}}