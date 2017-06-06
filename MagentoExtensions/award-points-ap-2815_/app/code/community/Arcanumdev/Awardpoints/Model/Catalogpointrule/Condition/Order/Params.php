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
 class Arcanumdev_Awardpoints_Model_Catalogpointrule_Condition_Order_Params extends Mage_Rule_Model_Condition_Abstract{public function __construct(){parent::__construct();$this->setType('awardpoints/rule_condition_order_params')->setValue(null);}public function loadAttributeOptions(){$hlp=Mage::helper('awardpoints');$this->setAttributeOption(array('store'=>$hlp->__('Store'),'category'=>$hlp->__('Category'),'order_status'=>$hlp->__('Order status'),'sku'=>$hlp->__('Contains any of these SKUs'),));return $this;}public function getValueSelectOptions(){$hlp=Mage::helper('awardpoints');if($this->getAttribute()=='store'){$stores=Mage::helper('awardpoints')->getStoresForRule();$stores_options=array();foreach ($stores as $key=>$store)$stores_options[]=array('label'=>$store, 'value'=>$key);$this->setData('value_select_options',$stores);}if($this->getAttribute()=='category'){$categories=Mage::helper('awardpoints')->getCategoriesArray();foreach ($categories as $key=>$category)$categories[$key]['label']=str_replace('&nbsp;','',$category['label']);$this->setData('value_select_options',$categories);}if($this->getAttribute()=='order_status'){$this->setData('value_select_options',Mage::getSingleton('sales/order_config')->getStatuses());}return $this->getData('value_select_options');}public function loadOperatorOptions(){$this->setOperatorOption(array('=='=>Mage::helper('awardpoints')->__('is'),'!='=>Mage::helper('awardpoints')->__('is not')));return $this;}public function asHtml(){if($this->getAttribute()=='sku'){$html=$this->getTypeElement()->getHtml().Mage::helper('awardpoints')->__("%s %s",$this->getAttributeElement()->getHtml(),$this->getValueElement()->getHtml());if($this->getId()!='1') {$html.= $this->getRemoveLinkHtml();}return $html;}return parent::asHtml();}public function getAttributeElement(){$element=parent::getAttributeElement();$element->setShowAsText(true);return $element;}public function getValueElementType(){if($this->getAttribute()=='store'||$this->getAttribute()=='category'||$this->getAttribute()=='order_status') return 'select';return 'text';}public function validate(Varien_Object $object){if($this->getAttribute()=='sku'){$sku=explode(',',$this->getValue());foreach($sku as $skuA){foreach($object->getSku() as $skuB){if($skuA == $skuB) return true;}}return false;}if($this->getAttribute()=='category')return $this->validateAttribute($object->getCategories());return parent::validate($object);}}